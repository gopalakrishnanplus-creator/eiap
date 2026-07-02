# EIAP Staging SFTP Sync

This repository contains local tooling for moving `wp-content/` changes between the EIAP repository and the EIAP staging WordPress filesystem over SFTP.

The workflow is intended for Codex-assisted code changes:

1. Make and review repository changes locally.
2. Commit and push those changes to GitHub.
3. Sync the same tracked `wp-content/` files to the EIAP staging site over SFTP.
4. Verify staging manually.
5. Promote from staging to live through WordPress controls outside this repository.

It also supports a theme-only reverse path when WordPress updates theme files directly on staging:

1. Scan likely remote file differences.
2. Pull the changed theme file or theme directory back into the repository.
3. Review `git status` and `git diff`.
4. Commit and push the pulled filesystem changes.

## Files

| File | Purpose |
| --- | --- |
| `.github/workflows/deploy-staging.yml` | GitHub Actions workflow that syncs staging after pushes to `main` that touch deployable files. |
| `tools/wp_content_sftp_sync.py` | Git-aware SFTP sync command. |
| `tools/deploy_changed_wp_content.py` | Convenience wrapper that deploys changed Git-tracked `wp-content/` files from a Git commit range. |
| `tools/requirements-sftp-sync.txt` | Python dependency list for the sync command. |
| `tools/sftp-sync.env.example` | Environment-variable template for staging credentials. |
| `.wp-content-syncignore` | Extra ignore patterns for the sync manifest. |

## What Gets Synced

Only files returned by this command are eligible:

```bash
git ls-files --cached --full-name -- wp-content
```

The tool does not sync:

- WordPress core (`wp-admin/`, `wp-includes/`).
- `wp-config.php`.
- Database records, plugin settings, forms, pages, menus, users, or theme options.
- `wp-content/uploads/`, unless a media file is intentionally tracked by Git.
- Root files such as `README.md` or `meta.json`.
- Credentials or local environment files.

Removed Git-tracked files are deleted from staging only when they were present in the previously saved remote state.

Reverse pulls are intentionally limited to `wp-content/themes/`. Review `git status` after every pull so added files are intentionally staged or ignored.

## Install

Use Python 3.10 or newer where possible.

```bash
python3 -m venv .venv
source .venv/bin/activate
python3 -m pip install -r tools/requirements-sftp-sync.txt
```

## Configure Credentials

The script reads staging credentials from environment variables:

| Variable | Required | Description |
| --- | --- | --- |
| `EIAP_SFTP_HOST` | Yes | Staging SFTP hostname. |
| `EIAP_SFTP_PORT` | Yes | SFTP port, usually `22`. |
| `EIAP_SFTP_USERNAME` | Yes | Staging SFTP username. |
| `EIAP_SFTP_PASSWORD` | Yes | Staging SFTP password. |
| `EIAP_SFTP_REMOTE_DIR` | No | Remote directory containing `wp-content`. Defaults to `htdocs`, which maps to the SFTP home `wp-content` target for WordPress.com-style hosts. |

Create a local credential file from the template, fill it in, then load it for your shell session:

```bash
cp tools/sftp-sync.env.example tools/sftp-sync.env
# Edit tools/sftp-sync.env with real staging values.
set -a
source tools/sftp-sync.env
set +a
```

Replace the placeholder values before running any command that connects to staging. Do not commit real credentials.

## GitHub Actions Auto-Deploy

The repository includes `.github/workflows/deploy-staging.yml`. After it is pushed to `main`, GitHub Actions can automatically deploy changed Git-tracked `wp-content/` files whenever a push changes:

- `wp-content/**`
- `.wp-content-syncignore`
- `tools/wp_content_sftp_sync.py`
- `tools/requirements-sftp-sync.txt`
- `.github/workflows/deploy-staging.yml`

Configure these repository secrets in GitHub before relying on the workflow:

| Secret | Required | Description |
| --- | --- | --- |
| `EIAP_SFTP_HOST` | Yes | Staging SFTP hostname. |
| `EIAP_SFTP_USERNAME` | Yes | Staging SFTP username. |
| `EIAP_SFTP_PASSWORD` | Yes | Staging SFTP password. |
| `EIAP_SFTP_PORT` | No | SFTP port. Defaults to `22` when omitted. |
| `EIAP_SFTP_REMOTE_DIR` | No | Directory containing `wp-content`. Defaults to `htdocs`. |

Manual workflow dispatch supports four commands:

| Command | Effect |
| --- | --- |
| `plan` | Connects to staging and prints the current remote delta without writing files. |
| `seed` | Writes the remote state file when staging already matches this repo's `wp-content/`. |
| `sync` | Uploads changed tracked files and deletes tracked files removed from the repo, based on remote state. |
| `force-full-sync` | Uploads every tracked `wp-content/` file and refreshes remote state. |

On normal pushes to `main`, the workflow runs:

```bash
python tools/deploy_changed_wp_content.py --base <previous-main-sha> --head <pushed-main-sha>
```

That uploads added, copied, modified, and renamed tracked `wp-content/` files from the push range. It does not require an existing remote state file and does not perform a broad first-time upload. If the push deletes tracked `wp-content/` files, the wrapper stops so a human can decide whether to run a full manual `sync`.

## Commands

Fast targeted upload for routine code changes:

```bash
python3 tools/wp_content_sftp_sync.py upload wp-content/themes/Impreza-child/functions.php
```

Upload multiple known changed files:

```bash
python3 tools/wp_content_sftp_sync.py upload \
  wp-content/themes/Impreza-child/functions.php \
  wp-content/themes/Impreza-child/style.css
```

The `upload` command is the preferred day-to-day staging path. It uploads only the explicit Git-tracked `wp-content/` files passed to it, skips the full remote-state read, and skips the full manifest rewrite. Add `--verify-upload` only when you specifically want to read the remote file back and compare SHA-256.

Deploy every changed Git-tracked `wp-content/` file from the latest pushed commit:

```bash
python3 tools/deploy_changed_wp_content.py --base HEAD~1 --head HEAD
```

If the commit deletes tracked `wp-content/` files, the wrapper stops by default because targeted uploads cannot remove remote files. Re-run with `--allow-sync-for-deletes` only after confirming a full sync is intended:

```bash
python3 tools/deploy_changed_wp_content.py --base HEAD~1 --head HEAD --allow-sync-for-deletes
```

Fast remote scan for likely WordPress-side theme changes:

```bash
python3 tools/wp_content_sftp_sync.py scan-remote
```

Scan a narrower target, such as the child theme:

```bash
python3 tools/wp_content_sftp_sync.py scan-remote wp-content/themes/Impreza-child
```

`scan-remote` recursively lists remote theme directories and compares file existence and size against the local repo. It does not read remote file contents and does not read the large remote state file. Use `--include-mtime` when you want noisier timestamp comparison as well.

Pull an exact file or directory from staging into the repo:

```bash
python3 tools/wp_content_sftp_sync.py pull wp-content/themes/Impreza-child/functions.php
python3 tools/wp_content_sftp_sync.py pull wp-content/themes/Impreza-child
```

Use `--delete-local-missing` only when you want a pulled remote directory to remove local files that no longer exist on staging:

```bash
python3 tools/wp_content_sftp_sync.py pull --delete-local-missing wp-content/themes/Impreza-child
```

After every pull, inspect and commit the repository changes:

```bash
git status --short
git diff -- wp-content/themes/Impreza-child
git add wp-content/themes/Impreza-child
git commit -m "Sync child theme update from staging"
git push origin main
```

Preview the remote delta:

```bash
python3 tools/wp_content_sftp_sync.py plan
```

Preview without changing staging, useful before the first real sync:

```bash
python3 tools/wp_content_sftp_sync.py sync --dry-run
```

Seed remote state without uploading files. Use this once only when staging already matches the repository's `wp-content/`:

```bash
python3 tools/wp_content_sftp_sync.py seed
```

Run a normal delta sync:

```bash
python3 tools/wp_content_sftp_sync.py sync
```

Force a full upload of every tracked `wp-content/` file:

```bash
python3 tools/wp_content_sftp_sync.py sync --force-full
```

Use strict SSH host key checking when the host key is already present in `~/.ssh/known_hosts`:

```bash
python3 tools/wp_content_sftp_sync.py plan --strict-host-key
```

## First-Time Staging Setup

Choose one path:

| Situation | Command |
| --- | --- |
| Staging already matches this repo's `wp-content/` | Run manual GitHub workflow command `seed`, or run `python3 tools/wp_content_sftp_sync.py seed` locally. |
| Staging does not match this repo or is empty | Run manual GitHub workflow command `force-full-sync`, or run `python3 tools/wp_content_sftp_sync.py sync --force-full` locally. |
| You are unsure | Run `python3 tools/wp_content_sftp_sync.py sync --dry-run` and inspect the upload/delete plan first. |

After the first setup, use normal `sync` for later changes.

## Remote State File

The tool writes this marker on staging:

```text
wp-content/.eiap-wp-content-sync-state.json
```

The state file stores the last synced Git commit, file sizes, mtimes, and SHA-256 hashes. It is ignored locally by `.wp-content-syncignore`.

For speed, targeted `upload` does not read or update this state file. That means a later full `plan` or `sync` may still report files changed since the last `seed`. Use full `plan`/`sync` only for broad changes or when remote deletes matter.

## Recommended Codex-To-Staging Workflow

```bash
git status --short
git add wp-content tools .wp-content-syncignore .gitignore .github
git commit -m "Your change summary"
git push origin main
```

If GitHub repository secrets are configured, the `Deploy wp-content to staging` workflow deploys eligible `wp-content/` changes automatically after the push.

For local/manual deployment from Codex, load credentials and deploy the changed files from the latest commit:

```bash
set -a
source tools/sftp-sync.env
set +a

python3 tools/deploy_changed_wp_content.py --base HEAD~1 --head HEAD
```

Use `tools/wp_content_sftp_sync.py upload <path>` only when you intentionally want to deploy a manually chosen file list instead of every changed `wp-content/` file from the commit.

For documentation-only changes, push GitHub only. No SFTP step is needed because the tool intentionally deploys WordPress runtime files under `wp-content/` only.

## Recommended Staging-To-Repo Workflow

When WordPress changes theme files directly on staging:

```bash
set -a
source tools/sftp-sync.env
set +a

python3 tools/wp_content_sftp_sync.py scan-remote wp-content/themes/Impreza-child
python3 tools/wp_content_sftp_sync.py pull wp-content/themes/Impreza-child/functions.php
git status --short
git diff -- wp-content/themes/Impreza-child
git add wp-content/themes/Impreza-child
git commit -m "Sync child theme update from staging"
git push origin main
```

For a quick broad check, run `scan-remote` with no paths. The default scan target is `wp-content/themes`; plugins and uploads are intentionally not part of reverse sync.

## Safety Notes

- Target staging credentials only. Do not use this script directly against live production.
- Keep real SFTP credentials out of Git and out of shared chat logs.
- Use targeted `upload` for known file edits.
- Use `scan-remote` and targeted `pull` for theme files changed directly in WordPress/staging.
- Run full `plan` or `sync` only when working on broad changes, deletions, or first-time setup.
- Commit repository changes before syncing so GitHub remains the source of truth.
- Database-backed WordPress changes still need a database/admin migration path; this script only handles filesystem code under `wp-content/`.
