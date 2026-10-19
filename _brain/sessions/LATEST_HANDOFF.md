# Session Handoff

## Objective

Make the Personal Equipment and Internet Access Poll Survey shift schedule easier to answer, and prepare the single PHP file for direct upload.

## Completed

- Copied the reusable `_brain` framework from `iantolentino/ai-nexus`.
- Added a root `AGENTS.md` adapter so Codex can find the controller.
- Replaced project-state notes with survey-app information and removed unrelated Performance Evaluation System history.
- Recorded the original survey implementation, local checks, and user-reported ZIP upload in the project notes.
- Updated the shared survey renderer to offer two common shifts and custom start/end time pickers.
- Added server-side validation and human-readable stored shift ranges without changing the database schema.

## Current State

The user reports uploading the latest `survey/shared/lib.php` directly to cPanel and successfully testing the survey/results flow. Codex did not independently verify cPanel. The GitHub backup is being updated; keep results credentials as placeholders in the public repository.

## Decisions

- Keep each survey's responses in a separate SQLite database.
- Preserve the public repo's placeholder-password convention; configure deployed credentials outside the backup.
- Results deletion applies only to the survey currently selected in the hub.
- Upload only the updated PHP file at the matching `/survey/shared/lib.php` path to get the latest shift-picker change; no ZIP was built.

## Blockers

- None known.

## Relevant Files

- `survey/personal-equipment-internet-access-poll-survey/`
- `survey/results.php`
- `survey/shared/lib.php`
- `tools/build-package.py`
- `_brain/CURRENT_STATE.md`

## Next Action

Commit and push the reviewed application and brain notes to the GitHub backup. Exclude all response databases and credentials.

## Warnings

- Response deletion is permanent. Confirm the selected survey before deleting.
- Preserve existing response databases. The targeted ZIP excludes database files but updates shared runtime files.
- Live testing is user-reported; Codex has not independently checked cPanel.
- Do not put password values in progress notes.

## Git Baseline

- Base commit: c3b14ef40fc4e2accc2686835249ac43649575bb
- Branch: main
- Working tree at handoff: has uncommitted changes
- Verification: workflow gate REVIEW passed and changed renderer/validation sections were reviewed in bounded slices. No syntax or browser checks were run for this latest change; earlier local checks apply to the previous version. cPanel extraction/live behavior unverified.
