# Session Handoff

## Objective

Back up the user-tested Personal Equipment and Internet Access Poll Survey changes to GitHub, including the `_brain` project notes.

## Completed

- Copied the reusable `_brain` framework from `iantolentino/ai-nexus`.
- Added a root `AGENTS.md` adapter so Codex can find the controller.
- Replaced project-state notes with survey-app information and removed unrelated Performance Evaluation System history.
- Recorded the original survey implementation, local checks, and user-reported ZIP upload in the project notes.
- Updated the shared survey renderer to offer two common shifts and custom start/end time pickers.
- Added server-side validation and human-readable stored shift ranges without changing the database schema.
- Pushed the survey implementation and `_brain` notes to the public GitHub `main` branch.

## Current State

The user reports uploading the latest `survey/shared/lib.php` directly to cPanel and successfully testing the survey/results flow. Codex did not independently verify cPanel. The GitHub backup includes the survey code and `_brain` notes; results credentials remain placeholders.

## Decisions

- Keep each survey's responses in a separate SQLite database.
- Preserve the public repo's placeholder-password convention; configure deployed credentials outside the backup.
- Results deletion applies only to the survey currently selected in the hub.
- Keep deployed credentials and response databases outside the public backup.

## Blockers

- None known.

## Relevant Files

- `survey/personal-equipment-internet-access-poll-survey/`
- `survey/results.php`
- `survey/shared/lib.php`
- `tools/build-package.py`
- `_brain/CURRENT_STATE.md`

## Next Action

Continue from the backed-up state for the next requested survey change.

## Warnings

- Response deletion is permanent. Confirm the selected survey before deleting.
- Preserve existing response databases. The targeted ZIP excludes database files but updates shared runtime files.
- Live deployment and successful survey/results testing are user-reported; Codex has not independently checked cPanel.
- Do not put password values in progress notes.

## Git Baseline

- Base commit: 2296cd66e18efbd4531dc8c19f032ab46c5b7c39
- Branch: main
- Working tree at handoff: has uncommitted changes
- Verification: the user reports successful live testing of the survey/results flow; Codex did not independently access cPanel. Public backup excludes credentials, SQLite response databases, and generated context manifests.
