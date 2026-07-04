#!/usr/bin/env python3
"""
Git-aware SFTP sync for EIAP staging wp-content files.

The tool uploads only files that are tracked by Git under wp-content/. It keeps
an EIAP-specific state file on the remote server so later syncs can upload only
changed files and delete files that were removed from the repository.
"""

from __future__ import annotations

import argparse
import errno
import fnmatch
import hashlib
import json
import os
import posixpath
import subprocess
import sys
import time
from dataclasses import dataclass
from pathlib import Path
from stat import S_ISDIR
from typing import Dict, Iterable, List, Optional

try:
    import paramiko
except ImportError:  # pragma: no cover - checked at runtime
    paramiko = None


REPO_NAME = "eiap"
STATE_FILENAME = ".eiap-wp-content-sync-state.json"
IGNORE_FILENAME = ".wp-content-syncignore"
DEFAULT_IGNORE_PATTERNS = [
    "wp-content/**/.DS_Store",
    "wp-content/**/__MACOSX/**",
    f"wp-content/{STATE_FILENAME}",
    f"wp-content/{STATE_FILENAME}.tmp",
]
DEFAULT_REMOTE_SCAN_TARGETS = [
    "wp-content/themes",
]


@dataclass(frozen=True)
class SyncConfig:
    host: str
    port: int
    username: str
    password: str
    remote_dir: str
    repo_root: Path
    dry_run: bool
    verbose: bool
    strict_host_key: bool

    @classmethod
    def from_env(cls, repo_root: Path, dry_run: bool, verbose: bool, strict_host_key: bool) -> "SyncConfig":
        host = os.environ.get("EIAP_SFTP_HOST", "").strip()
        username = os.environ.get("EIAP_SFTP_USERNAME", "").strip()
        password = os.environ.get("EIAP_SFTP_PASSWORD", "")
        remote_dir = os.environ.get("EIAP_SFTP_REMOTE_DIR", "htdocs").strip() or "htdocs"

        try:
            port = int(os.environ.get("EIAP_SFTP_PORT", "22"))
        except ValueError as exc:
            raise SystemExit("EIAP_SFTP_PORT must be an integer.") from exc

        missing = [
            name
            for name, value in [
                ("EIAP_SFTP_HOST", host),
                ("EIAP_SFTP_USERNAME", username),
                ("EIAP_SFTP_PASSWORD", password),
            ]
            if not value
        ]
        if missing:
            raise SystemExit(f"Missing required environment variable(s): {', '.join(missing)}")

        if port < 1 or port > 65535:
            raise SystemExit("EIAP_SFTP_PORT must be between 1 and 65535.")

        return cls(
            host=host,
            port=port,
            username=username,
            password=password,
            remote_dir=remote_dir,
            repo_root=repo_root,
            dry_run=dry_run,
            verbose=verbose,
            strict_host_key=strict_host_key,
        )


@dataclass(frozen=True)
class FileEntry:
    sha256: str
    size: int
    mtime: int


@dataclass(frozen=True)
class SyncPlan:
    uploads: List[str]
    deletes: List[str]
    local_manifest: Dict[str, FileEntry]
    remote_manifest: Dict[str, FileEntry]
    local_head: str


class GitRepo:
    def __init__(self, root: Path) -> None:
        self.root = root

    def run(self, *args: str, binary: bool = False) -> str | bytes:
        result = subprocess.run(
            ["git", "-C", str(self.root), *args],
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            check=True,
            text=not binary,
        )
        return result.stdout

    def ensure_valid(self) -> None:
        try:
            top_level = str(self.run("rev-parse", "--show-toplevel")).strip()
        except subprocess.CalledProcessError as exc:
            raise SystemExit(f"{self.root} is not a Git repository.") from exc

        if Path(top_level).resolve() != self.root.resolve():
            raise SystemExit(f"Repo root mismatch. Expected {self.root}, Git reports {top_level}.")

    def head(self) -> str:
        return str(self.run("rev-parse", "HEAD")).strip()

    def tracked_wp_content_paths(self) -> List[str]:
        output = self.run("ls-files", "-z", "--cached", "--full-name", "--", "wp-content", binary=True)
        assert isinstance(output, bytes)
        return [item.decode("utf-8") for item in output.split(b"\0") if item]

    def wp_content_is_dirty(self) -> bool:
        output = str(self.run("status", "--porcelain", "--", "wp-content")).strip()
        return bool(output)


class PathFilter:
    def __init__(self, patterns: Iterable[str]) -> None:
        self.patterns = []
        for raw_pattern in patterns:
            pattern = raw_pattern.strip()
            if not pattern or pattern.startswith("#"):
                continue
            if pattern.endswith("/"):
                pattern = f"{pattern}**"
            self.patterns.append(pattern.replace("\\", "/"))

    @classmethod
    def from_repo(cls, repo_root: Path) -> "PathFilter":
        patterns = list(DEFAULT_IGNORE_PATTERNS)
        ignore_file = repo_root / IGNORE_FILENAME
        if ignore_file.exists():
            patterns.extend(ignore_file.read_text(encoding="utf-8").splitlines())
        return cls(patterns)

    def matches(self, relative_path: str) -> bool:
        path = relative_path.replace("\\", "/")
        return any(fnmatch.fnmatch(path, pattern) for pattern in self.patterns)


class RemoteSyncState:
    def __init__(self, head: Optional[str], files: Dict[str, FileEntry]) -> None:
        self.head = head
        self.files = files

    @classmethod
    def empty(cls) -> "RemoteSyncState":
        return cls(head=None, files={})

    @classmethod
    def from_json(cls, payload: dict) -> "RemoteSyncState":
        files = {}
        for path, metadata in payload.get("files", {}).items():
            files[path] = FileEntry(
                sha256=str(metadata["sha256"]),
                size=int(metadata["size"]),
                mtime=int(metadata["mtime"]),
            )
        return cls(head=payload.get("head"), files=files)

    def to_json(self) -> dict:
        return {
            "repo": REPO_NAME,
            "head": self.head,
            "generated_at": int(time.time()),
            "files": {
                path: {"sha256": entry.sha256, "size": entry.size, "mtime": entry.mtime}
                for path, entry in sorted(self.files.items())
            },
        }


class SftpWpContentSync:
    def __init__(self, config: SyncConfig) -> None:
        if paramiko is None:
            raise SystemExit(
                "The 'paramiko' package is required. Install it with:\n"
                "python3 -m pip install -r tools/requirements-sftp-sync.txt"
            )

        self.config = config
        self.repo = GitRepo(config.repo_root)
        self.repo.ensure_valid()
        self.path_filter = PathFilter.from_repo(config.repo_root)
        self.ssh_client: Optional[paramiko.SSHClient] = None
        self.sftp: Optional[paramiko.SFTPClient] = None

    def log(self, message: str) -> None:
        print(message)

    def debug(self, message: str) -> None:
        if self.config.verbose:
            self.log(message)

    def connect(self) -> None:
        self.ssh_client = paramiko.SSHClient()
        if self.config.strict_host_key:
            self.ssh_client.load_system_host_keys()
            self.ssh_client.set_missing_host_key_policy(paramiko.RejectPolicy())
        else:
            self.ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

        self.ssh_client.connect(
            hostname=self.config.host,
            port=self.config.port,
            username=self.config.username,
            password=self.config.password,
            look_for_keys=False,
            allow_agent=False,
            timeout=30,
        )
        self.sftp = self.ssh_client.open_sftp()

    def close(self) -> None:
        if self.sftp is not None:
            self.sftp.close()
            self.sftp = None
        if self.ssh_client is not None:
            self.ssh_client.close()
            self.ssh_client = None

    @property
    def remote_wp_content(self) -> str:
        base = normalize_remote_dir(self.config.remote_dir)
        if base in ("", "."):
            return "wp-content"
        if base.rstrip("/") == "wp-content":
            return "wp-content"
        return posixpath.normpath(posixpath.join(base, "wp-content"))

    @property
    def remote_state_path(self) -> str:
        return posixpath.join(self.remote_wp_content, STATE_FILENAME)

    def load_remote_state(self) -> RemoteSyncState:
        assert self.sftp is not None
        try:
            with self.sftp.file(self.remote_state_path, "rb") as handle:
                payload = json.loads(handle.read().decode("utf-8"))
            if payload.get("repo") not in (None, REPO_NAME):
                self.log(f"Warning: remote state file repo value is {payload.get('repo')!r}.")
            self.debug(f"Loaded remote sync state from {self.remote_state_path}")
            return RemoteSyncState.from_json(payload)
        except OSError as exc:
            if is_missing_remote_file(exc):
                self.debug("No remote sync state found.")
                return RemoteSyncState.empty()
            raise
        except json.JSONDecodeError as exc:
            raise SystemExit(f"Remote state file is not valid JSON: {self.remote_state_path}") from exc

    def write_remote_state(self, state: RemoteSyncState) -> None:
        assert self.sftp is not None
        payload = json.dumps(state.to_json(), indent=2, sort_keys=True).encode("utf-8")
        tmp_path = f"{self.remote_state_path}.tmp"
        self.ensure_remote_dir(posixpath.dirname(self.remote_state_path))

        if self.config.dry_run:
            self.log(f"[dry-run] Would write remote state file: {self.remote_state_path}")
            return

        with self.sftp.file(tmp_path, "wb") as handle:
            handle.write(payload)

        try:
            self.sftp.rename(tmp_path, self.remote_state_path)
        except OSError:
            # Some managed SFTP services reject replacing an existing file via rename.
            with self.sftp.file(self.remote_state_path, "wb") as handle:
                handle.write(payload)
            try:
                self.sftp.remove(tmp_path)
            except OSError as exc:
                if not is_missing_remote_file(exc):
                    raise
        self.debug(f"Updated remote sync state: {self.remote_state_path}")

    def ensure_remote_dir(self, remote_dir: str) -> None:
        assert self.sftp is not None
        normalized = posixpath.normpath(remote_dir)
        if normalized in ("", ".", "/"):
            return

        segments = normalized.strip("/").split("/")
        current = "/" if normalized.startswith("/") else ""
        for segment in segments:
            current = posixpath.join(current, segment) if current else segment
            try:
                attrs = self.sftp.stat(current)
                if not S_ISDIR(attrs.st_mode):
                    raise RuntimeError(f"Remote path exists but is not a directory: {current}")
            except OSError as exc:
                if not is_missing_remote_file(exc):
                    raise
                if self.config.dry_run:
                    self.log(f"[dry-run] Would create remote directory: {current}")
                else:
                    self.sftp.mkdir(current)
                    self.debug(f"Created remote directory: {current}")

    def remove_remote_file(self, remote_path: str) -> None:
        assert self.sftp is not None
        if self.config.dry_run:
            self.log(f"[dry-run] Would delete: {remote_path}")
            return
        try:
            self.sftp.remove(remote_path)
            self.debug(f"Deleted remote file: {remote_path}")
        except OSError as exc:
            if not is_missing_remote_file(exc):
                raise
            self.debug(f"Remote file already absent: {remote_path}")

    def upload_file(self, relative_path: str) -> None:
        assert self.sftp is not None
        local_path = self.config.repo_root / relative_path
        remote_path = self.to_remote_path(relative_path)
        self.ensure_remote_dir(posixpath.dirname(remote_path))

        if self.config.dry_run:
            self.log(f"[dry-run] Would upload: {relative_path} -> {remote_path}")
            return

        self.sftp.put(str(local_path), remote_path, confirm=True)
        stat = local_path.stat()
        self.sftp.utime(remote_path, (int(stat.st_atime), int(stat.st_mtime)))
        self.debug(f"Uploaded {relative_path} -> {remote_path}")

    def verify_remote_file(self, relative_path: str) -> None:
        local_entry = build_entry(self.config.repo_root / relative_path)
        remote_path = self.to_remote_path(relative_path)
        remote_sha = self.remote_sha256(remote_path)
        if local_entry.sha256 != remote_sha:
            raise SystemExit(f"Upload verification failed for {relative_path}.")
        self.log(f"Verified: {relative_path}")

    def remote_sha256(self, remote_path: str) -> str:
        assert self.sftp is not None
        hasher = hashlib.sha256()
        with self.sftp.file(remote_path, "rb") as handle:
            while True:
                chunk = handle.read(1024 * 1024)
                if not chunk:
                    break
                hasher.update(chunk)
        return hasher.hexdigest()

    def normalize_target_paths(self, raw_paths: Iterable[str]) -> List[str]:
        tracked_paths = set(self.repo.tracked_wp_content_paths())
        normalized_paths = []

        for raw_path in raw_paths:
            candidate = Path(raw_path)
            if candidate.is_absolute():
                try:
                    relative = candidate.resolve().relative_to(self.config.repo_root)
                except ValueError as exc:
                    raise SystemExit(f"Path is outside the repository: {raw_path}") from exc
            else:
                relative = candidate

            relative_path = relative.as_posix()
            while relative_path.startswith("./"):
                relative_path = relative_path[2:]

            if not relative_path.startswith("wp-content/"):
                raise SystemExit(f"Targeted uploads must be under wp-content/: {raw_path}")
            if self.path_filter.matches(relative_path):
                raise SystemExit(f"Path is ignored by {IGNORE_FILENAME}: {relative_path}")
            if relative_path not in tracked_paths:
                raise SystemExit(f"Path is not tracked by Git: {relative_path}")
            if not (self.config.repo_root / relative_path).is_file():
                raise SystemExit(f"Path is not a local file: {relative_path}")

            normalized_paths.append(relative_path)

        return list(dict.fromkeys(normalized_paths))

    def upload_targeted_files(self, raw_paths: Iterable[str], verify_upload: bool = False) -> None:
        paths = self.normalize_target_paths(raw_paths)
        for relative_path in paths:
            self.upload_file(relative_path)
            if not self.config.dry_run:
                self.log(f"Uploaded: {relative_path}")
                if verify_upload:
                    self.verify_remote_file(relative_path)

        mode = "dry-run targeted upload" if self.config.dry_run else "targeted upload"
        self.log(f"Completed {mode}: {len(paths)} file(s). Remote state was not read or updated.")

    def delete_targeted_files(self, raw_paths: Iterable[str]) -> None:
        paths = self.normalize_remote_targets(raw_paths)
        for relative_path in paths:
            if relative_path == "wp-content":
                raise SystemExit("Targeted deletes require file paths under wp-content/, not wp-content itself.")
            self.remove_remote_file(self.to_remote_path(relative_path))
            if not self.config.dry_run:
                self.log(f"Deleted: {relative_path}")

        mode = "dry-run targeted delete" if self.config.dry_run else "targeted delete"
        self.log(f"Completed {mode}: {len(paths)} file(s). Remote state was not read or updated.")

    def normalize_remote_targets(self, raw_paths: Iterable[str], theme_only: bool = False) -> List[str]:
        normalized_paths = []
        for raw_path in raw_paths:
            candidate = Path(raw_path)
            if candidate.is_absolute():
                try:
                    relative = candidate.resolve().relative_to(self.config.repo_root)
                except ValueError as exc:
                    raise SystemExit(f"Path is outside the repository: {raw_path}") from exc
            else:
                relative = candidate

            relative_path = relative.as_posix().rstrip("/")
            while relative_path.startswith("./"):
                relative_path = relative_path[2:]

            if relative_path != "wp-content" and not relative_path.startswith("wp-content/"):
                raise SystemExit(f"Remote targets must be under wp-content/: {raw_path}")
            if self.path_filter.matches(relative_path):
                raise SystemExit(f"Path is ignored by {IGNORE_FILENAME}: {relative_path}")
            if theme_only and relative_path != "wp-content/themes" and not relative_path.startswith("wp-content/themes/"):
                raise SystemExit(
                    "Reverse SFTP scan/pull is limited to theme files under wp-content/themes/. "
                    f"Rejected: {relative_path}"
                )

            normalized_paths.append(relative_path)

        return list(dict.fromkeys(normalized_paths))

    def to_remote_path_any(self, relative_path: str) -> str:
        suffix = relative_path.replace("\\", "/").rstrip("/")
        if suffix == "wp-content":
            return self.remote_wp_content
        return self.to_remote_path(suffix)

    def collect_remote_files(
        self,
        raw_paths: Iterable[str],
        theme_only: bool = False,
    ) -> tuple[Dict[str, object], List[str]]:
        assert self.sftp is not None
        targets = self.normalize_remote_targets(raw_paths, theme_only=theme_only)
        files: Dict[str, object] = {}
        directory_roots: List[str] = []

        for target in targets:
            remote_path = self.to_remote_path_any(target)
            try:
                attrs = self.sftp.stat(remote_path)
            except OSError as exc:
                if is_missing_remote_file(exc):
                    raise SystemExit(f"Remote path not found: {target}") from exc
                raise

            if S_ISDIR(attrs.st_mode):
                directory_roots.append(target)
                for relative_path, file_attrs in self.walk_remote_dir(remote_path, target):
                    files[relative_path] = file_attrs
            else:
                files[target] = attrs

        return files, directory_roots

    def walk_remote_dir(self, remote_dir: str, relative_dir: str) -> Iterable[tuple[str, object]]:
        assert self.sftp is not None
        try:
            entries = self.sftp.listdir_attr(remote_dir)
        except OSError as exc:
            if is_missing_remote_file(exc):
                return
            raise

        for attrs in entries:
            filename = attrs.filename
            if filename in (".", ".."):
                continue

            remote_child = posixpath.join(remote_dir, filename)
            relative_child = f"{relative_dir.rstrip('/')}/{filename}"
            if self.path_filter.matches(relative_child):
                self.debug(f"Skipping ignored remote path: {relative_child}")
                continue

            if S_ISDIR(attrs.st_mode):
                yield from self.walk_remote_dir(remote_child, relative_child)
            else:
                yield relative_child, attrs

    def scan_remote_changes(
        self,
        raw_paths: Iterable[str],
        include_mtime: bool = False,
        mtime_slop: int = 2,
        preview_limit: int = 50,
    ) -> None:
        targets = list(raw_paths) or DEFAULT_REMOTE_SCAN_TARGETS
        remote_files, _ = self.collect_remote_files(targets, theme_only=True)
        normalized_targets = self.normalize_remote_targets(targets, theme_only=True)
        tracked_paths = set(self.repo.tracked_wp_content_paths())

        remote_only = []
        size_changed = []
        mtime_changed = []
        for relative_path, attrs in sorted(remote_files.items()):
            if self.path_filter.matches(relative_path):
                continue
            local_path = self.config.repo_root / relative_path
            remote_size = int(attrs.st_size)
            remote_mtime = int(attrs.st_mtime)
            if not local_path.exists():
                remote_only.append(relative_path)
                continue

            local_stat = local_path.stat()
            if local_stat.st_size != remote_size:
                size_changed.append((relative_path, local_stat.st_size, remote_size))
                continue
            if include_mtime and abs(int(local_stat.st_mtime) - remote_mtime) > mtime_slop:
                mtime_changed.append((relative_path, int(local_stat.st_mtime), remote_mtime))

        remote_file_paths = set(remote_files)
        local_only = sorted(
            path
            for path in tracked_paths
            if self.path_is_under_targets(path, normalized_targets)
            and not self.path_filter.matches(path)
            and path not in remote_file_paths
        )

        self.log(f"Remote scan target(s): {', '.join(normalized_targets)}")
        self.log(
            f"Scan: {len(remote_files)} remote file(s), {len(remote_only)} remote-only, "
            f"{len(size_changed)} size-changed, {len(local_only)} local-only"
            + (f", {len(mtime_changed)} mtime-changed" if include_mtime else "")
            + "."
        )
        self.print_preview("Remote-only", remote_only, preview_limit)
        self.print_preview(
            "Size-changed",
            [f"{path} local={local_size} remote={remote_size}" for path, local_size, remote_size in size_changed],
            preview_limit,
        )
        self.print_preview("Local-only", local_only, preview_limit)
        if include_mtime:
            self.print_preview(
                "Mtime-changed",
                [f"{path} local={local_mtime} remote={remote_mtime}" for path, local_mtime, remote_mtime in mtime_changed],
                preview_limit,
            )

    def pull_remote_paths(
        self,
        raw_paths: Iterable[str],
        delete_local_missing: bool = False,
    ) -> None:
        remote_files, directory_roots = self.collect_remote_files(raw_paths, theme_only=True)
        remote_file_paths = set(remote_files)

        for relative_path, attrs in sorted(remote_files.items()):
            self.download_file(relative_path, attrs)

        if delete_local_missing:
            self.delete_local_files_missing_from_remote(directory_roots, remote_file_paths)

        mode = "dry-run pull" if self.config.dry_run else "pull"
        self.log(
            f"Completed {mode}: {len(remote_files)} file(s)"
            + (" with local missing-file cleanup." if delete_local_missing else ".")
        )

    def download_file(self, relative_path: str, attrs: object) -> None:
        assert self.sftp is not None
        remote_path = self.to_remote_path_any(relative_path)
        local_path = self.config.repo_root / relative_path

        if self.config.dry_run:
            self.log(f"[dry-run] Would download: {remote_path} -> {relative_path}")
            return

        local_path.parent.mkdir(parents=True, exist_ok=True)
        self.sftp.get(remote_path, str(local_path))
        os.utime(local_path, (int(time.time()), int(attrs.st_mtime)))
        self.log(f"Downloaded: {relative_path}")

    def delete_local_files_missing_from_remote(self, directory_roots: Iterable[str], remote_file_paths: set[str]) -> None:
        for root in directory_roots:
            local_root = self.config.repo_root / root
            if not local_root.exists() or not local_root.is_dir():
                continue

            for local_path in sorted(path for path in local_root.rglob("*") if path.is_file()):
                relative_path = local_path.relative_to(self.config.repo_root).as_posix()
                if self.path_filter.matches(relative_path) or relative_path in remote_file_paths:
                    continue

                if self.config.dry_run:
                    self.log(f"[dry-run] Would delete local file missing on remote: {relative_path}")
                else:
                    local_path.unlink()
                    self.log(f"Deleted local file missing on remote: {relative_path}")

    def path_is_under_targets(self, relative_path: str, targets: Iterable[str]) -> bool:
        for target in targets:
            if relative_path == target or relative_path.startswith(f"{target.rstrip('/')}/"):
                return True
        return False

    def print_preview(self, label: str, items: List[str], preview_limit: int) -> None:
        if not items:
            return
        self.log(f"{label}:")
        for item in items[:preview_limit]:
            self.log(f"  - {item}")
        if len(items) > preview_limit:
            self.log(f"  ... and {len(items) - preview_limit} more")

    def to_remote_path(self, relative_path: str) -> str:
        suffix = relative_path.replace("\\", "/")
        prefix = "wp-content/"
        if not suffix.startswith(prefix):
            raise ValueError(f"Unsupported path outside wp-content: {relative_path}")
        return posixpath.join(self.remote_wp_content, suffix[len(prefix) :])

    def build_local_manifest(self) -> Dict[str, FileEntry]:
        manifest: Dict[str, FileEntry] = {}
        for relative_path in self.repo.tracked_wp_content_paths():
            if self.path_filter.matches(relative_path):
                self.debug(f"Skipping ignored path: {relative_path}")
                continue

            local_path = self.config.repo_root / relative_path
            if not local_path.is_file():
                continue
            manifest[relative_path] = build_entry(local_path)
        return manifest

    def plan_sync(self, assume_remote_current: bool = False, force_full: bool = False) -> SyncPlan:
        local_manifest = self.build_local_manifest()
        remote_state = self.load_remote_state()
        local_head = self.repo.head()

        if force_full:
            uploads = sorted(local_manifest)
            deletes: List[str] = []
        elif remote_state.files:
            uploads = sorted(
                path
                for path, entry in local_manifest.items()
                if path not in remote_state.files or remote_state.files[path].sha256 != entry.sha256
            )
            deletes = sorted(path for path in remote_state.files if path not in local_manifest)
        elif assume_remote_current:
            uploads = []
            deletes = []
        else:
            uploads = sorted(local_manifest)
            deletes = []

        return SyncPlan(
            uploads=uploads,
            deletes=deletes,
            local_manifest=local_manifest,
            remote_manifest=remote_state.files,
            local_head=local_head,
        )

    def print_plan(self, plan: SyncPlan, assume_remote_current: bool, force_full: bool) -> None:
        self.log(f"Remote target: {self.remote_wp_content}/")
        if plan.remote_manifest:
            self.log(f"Remote state found at {self.remote_state_path}")
        elif force_full:
            self.log("No remote state found; forcing a full tracked wp-content upload.")
        elif assume_remote_current:
            self.log("No remote state found; assuming staging already matches local wp-content.")
        else:
            self.log("No remote state found; first sync would upload all tracked wp-content files.")

        bytes_to_upload = sum(plan.local_manifest[path].size for path in plan.uploads)
        self.log(
            f"Plan: {len(plan.uploads)} upload(s), {len(plan.deletes)} delete(s), "
            f"{len(plan.local_manifest)} tracked wp-content file(s), {bytes_to_upload} byte(s) to upload."
        )

        preview_limit = 25
        if plan.uploads:
            self.log("Uploads:")
            for path in plan.uploads[:preview_limit]:
                self.log(f"  - {path}")
            if len(plan.uploads) > preview_limit:
                self.log(f"  ... and {len(plan.uploads) - preview_limit} more")

        if plan.deletes:
            self.log("Deletes:")
            for path in plan.deletes[:preview_limit]:
                self.log(f"  - {path}")
            if len(plan.deletes) > preview_limit:
                self.log(f"  ... and {len(plan.deletes) - preview_limit} more")

    def sync(self, assume_remote_current: bool = False, force_full: bool = False) -> None:
        plan = self.plan_sync(assume_remote_current=assume_remote_current, force_full=force_full)
        self.print_plan(plan, assume_remote_current=assume_remote_current, force_full=force_full)

        for relative_path in plan.uploads:
            self.upload_file(relative_path)
        for relative_path in plan.deletes:
            self.remove_remote_file(self.to_remote_path(relative_path))

        self.write_remote_state(RemoteSyncState(head=plan.local_head, files=plan.local_manifest))
        mode = "dry-run sync" if self.config.dry_run else "sync"
        self.log(f"Completed {mode}. Remote head is now {plan.local_head}.")

    def seed_remote_state(self) -> None:
        if self.repo.wp_content_is_dirty():
            self.log("Warning: wp-content has uncommitted local changes. Seeding state from the working tree.")

        manifest = self.build_local_manifest()
        local_head = self.repo.head()
        self.log(f"Seeding remote state with {len(manifest)} tracked wp-content file(s) from {local_head}.")
        self.write_remote_state(RemoteSyncState(head=local_head, files=manifest))
        mode = "dry-run seed" if self.config.dry_run else "seed"
        self.log(f"Completed {mode}.")


def normalize_remote_dir(value: str) -> str:
    raw = (value or "").strip()
    if raw in ("", ".", "./", "~", "~/", "htdocs", "/htdocs", "~/htdocs"):
        return "."
    if raw.startswith("~/"):
        return raw[2:].strip("/")
    if raw.startswith("/"):
        return raw.rstrip("/") or "/"
    return raw.strip("/")


def is_missing_remote_file(exc: OSError) -> bool:
    return isinstance(exc, FileNotFoundError) or getattr(exc, "errno", None) == errno.ENOENT


def build_entry(path: Path) -> FileEntry:
    hasher = hashlib.sha256()
    with path.open("rb") as handle:
        for chunk in iter(lambda: handle.read(1024 * 1024), b""):
            hasher.update(chunk)

    stat = path.stat()
    return FileEntry(sha256=hasher.hexdigest(), size=stat.st_size, mtime=int(stat.st_mtime))


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Sync git-tracked EIAP wp-content files to staging over SFTP.")
    parser.add_argument(
        "command",
        choices=["plan", "sync", "seed", "upload", "delete", "scan-remote", "pull"],
        help=(
            "plan shows the delta, sync uploads/deletes files, seed writes remote state, "
            "upload pushes explicit files, delete removes explicit remote files, "
            "scan-remote checks remote metadata, pull downloads remote files"
        ),
    )
    parser.add_argument(
        "paths",
        nargs="*",
        help="For upload/delete/pull/scan-remote: one or more wp-content files or directories.",
    )
    parser.add_argument(
        "--repo-root",
        default=str(Path(__file__).resolve().parents[1]),
        help="Path to the EIAP repository root. Defaults to the parent of tools/.",
    )
    parser.add_argument("--dry-run", action="store_true", help="Show remote writes without changing the server.")
    parser.add_argument(
        "--assume-remote-current",
        action="store_true",
        help="If no state exists, assume staging already matches local wp-content and write state only during sync.",
    )
    parser.add_argument(
        "--force-full",
        action="store_true",
        help="Upload every tracked wp-content file even if a remote state file exists.",
    )
    parser.add_argument(
        "--strict-host-key",
        action="store_true",
        help="Require the SFTP host key to already exist in your known_hosts file.",
    )
    parser.add_argument(
        "--verify-upload",
        action="store_true",
        help="For upload only: read each remote file back and compare SHA-256.",
    )
    parser.add_argument(
        "--include-mtime",
        action="store_true",
        help="For scan-remote only: include mtime differences after size checks.",
    )
    parser.add_argument(
        "--mtime-slop",
        type=int,
        default=2,
        help="For scan-remote --include-mtime: ignore mtime differences within this many seconds.",
    )
    parser.add_argument(
        "--preview-limit",
        type=int,
        default=50,
        help="For scan-remote: maximum number of paths to print per change category.",
    )
    parser.add_argument(
        "--delete-local-missing",
        action="store_true",
        help="For pull only: delete local files inside pulled remote directories when missing on remote.",
    )
    parser.add_argument("--verbose", action="store_true", help="Print per-file and diagnostic output.")
    return parser


def main(argv: Optional[List[str]] = None) -> int:
    parser = build_parser()
    args = parser.parse_args(argv)
    repo_root = Path(args.repo_root).resolve()

    commands_with_paths = {"upload", "delete", "scan-remote", "pull"}
    if args.command in {"upload", "delete", "pull"} and not args.paths:
        parser.error(f"{args.command} requires at least one wp-content file or directory path.")
    if args.command not in commands_with_paths and args.paths:
        parser.error(f"{args.command} does not accept file paths.")
    if args.command != "upload" and args.verify_upload:
        parser.error("--verify-upload can only be used with upload.")
    if args.command != "scan-remote" and (args.include_mtime or args.mtime_slop != 2 or args.preview_limit != 50):
        parser.error("--include-mtime, --mtime-slop, and --preview-limit can only be used with scan-remote.")
    if args.command != "pull" and args.delete_local_missing:
        parser.error("--delete-local-missing can only be used with pull.")

    config = SyncConfig.from_env(
        repo_root=repo_root,
        dry_run=args.dry_run,
        verbose=args.verbose,
        strict_host_key=args.strict_host_key,
    )
    service = SftpWpContentSync(config)

    service.connect()
    try:
        if args.command == "plan":
            plan = service.plan_sync(
                assume_remote_current=args.assume_remote_current,
                force_full=args.force_full,
            )
            service.print_plan(
                plan,
                assume_remote_current=args.assume_remote_current,
                force_full=args.force_full,
            )
        elif args.command == "upload":
            service.upload_targeted_files(args.paths, verify_upload=args.verify_upload)
        elif args.command == "delete":
            service.delete_targeted_files(args.paths)
        elif args.command == "scan-remote":
            service.scan_remote_changes(
                args.paths,
                include_mtime=args.include_mtime,
                mtime_slop=args.mtime_slop,
                preview_limit=args.preview_limit,
            )
        elif args.command == "pull":
            service.pull_remote_paths(args.paths, delete_local_missing=args.delete_local_missing)
        elif args.command == "seed":
            service.seed_remote_state()
        else:
            service.sync(
                assume_remote_current=args.assume_remote_current,
                force_full=args.force_full,
            )
    finally:
        service.close()

    return 0


if __name__ == "__main__":
    raise SystemExit(main(sys.argv[1:]))
