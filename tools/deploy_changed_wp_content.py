#!/usr/bin/env python3
"""Deploy changed Git-tracked wp-content files to staging over SFTP.

This is a thin convenience wrapper around tools/wp_content_sftp_sync.py. It is
intended for the normal Codex workflow: commit changes, push to GitHub, then
deploy the same changed wp-content files to staging.
"""

from __future__ import annotations

import argparse
import subprocess
import sys
from pathlib import Path
from typing import List, Tuple


def run_git(repo_root: Path, *args: str, binary: bool = False) -> str | bytes:
    result = subprocess.run(
        ["git", "-C", str(repo_root), *args],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        check=True,
        text=not binary,
    )
    return result.stdout


def changed_wp_content_paths(repo_root: Path, base: str, head: str) -> Tuple[List[str], List[str]]:
    output = run_git(
        repo_root,
        "diff",
        "--name-status",
        "-z",
        base,
        head,
        "--",
        "wp-content",
        binary=True,
    )
    assert isinstance(output, bytes)
    parts = [part.decode("utf-8") for part in output.split(b"\0") if part]

    uploads: List[str] = []
    deletes: List[str] = []
    index = 0
    while index < len(parts):
        status = parts[index]
        index += 1
        if status.startswith(("R", "C")):
            old_path = parts[index]
            new_path = parts[index + 1]
            index += 2
            if old_path.startswith("wp-content/") and new_path.startswith("wp-content/"):
                uploads.append(new_path)
            continue

        path = parts[index]
        index += 1
        if not path.startswith("wp-content/"):
            continue
        if status.startswith("D"):
            deletes.append(path)
        else:
            uploads.append(path)

    return list(dict.fromkeys(uploads)), list(dict.fromkeys(deletes))


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description="Deploy changed wp-content files to EIAP staging.")
    parser.add_argument("--base", default="HEAD~1", help="Base Git revision. Defaults to HEAD~1.")
    parser.add_argument("--head", default="HEAD", help="Head Git revision. Defaults to HEAD.")
    parser.add_argument(
        "--repo-root",
        default=str(Path(__file__).resolve().parents[1]),
        help="Path to the repository root. Defaults to the parent of tools/.",
    )
    parser.add_argument("--dry-run", action="store_true", help="Show SFTP writes without changing staging.")
    parser.add_argument(
        "--verify-upload",
        action="store_true",
        help="Read uploaded files back and compare SHA-256.",
    )
    parser.add_argument(
        "--allow-sync-for-deletes",
        action="store_true",
        help="Deprecated. Deleted wp-content files are removed with targeted SFTP deletes.",
    )
    return parser


def run_sync_command(
    repo_root: Path,
    sync_script: Path,
    command_name: str,
    paths: List[str],
    dry_run: bool = False,
    verify_upload: bool = False,
) -> int:
    if not paths:
        return 0

    command = [sys.executable, str(sync_script)]
    if dry_run:
        command.append("--dry-run")

    command.append(command_name)
    if command_name == "upload" and verify_upload:
        command.append("--verify-upload")
    command.extend(paths)
    return subprocess.call(command, cwd=str(repo_root))


def main(argv: List[str] | None = None) -> int:
    args = build_parser().parse_args(argv)
    repo_root = Path(args.repo_root).resolve()
    sync_script = repo_root / "tools" / "wp_content_sftp_sync.py"

    uploads, deletes = changed_wp_content_paths(repo_root, args.base, args.head)
    if not uploads and not deletes:
        print(f"No wp-content changes found between {args.base} and {args.head}.")
        return 0

    print(
        f"Deploying {len(uploads)} wp-content upload(s) and {len(deletes)} delete(s) "
        f"from {args.base}..{args.head}."
    )

    upload_result = run_sync_command(
        repo_root,
        sync_script,
        "upload",
        uploads,
        dry_run=args.dry_run,
        verify_upload=args.verify_upload,
    )
    if upload_result != 0:
        return upload_result

    return run_sync_command(
        repo_root,
        sync_script,
        "delete",
        deletes,
        dry_run=args.dry_run,
    )


if __name__ == "__main__":
    raise SystemExit(main(sys.argv[1:]))
