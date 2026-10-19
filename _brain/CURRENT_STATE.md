# Current State

## Current objective

Maintain the Strata Staff Survey Forms application and its separate surveys. The Personal Equipment and Internet Access Poll Survey is deployed. The user reports uploading the latest shared PHP file and successfully testing the survey/results flow; Codex has not independently verified the live site.

## Active work

- Feature/project: Personal Equipment and Internet Access Poll Survey shift schedule
- Implementation state: shift presets and custom time fields uploaded; user reports successful testing
- Relevant file: `survey/shared/lib.php`

## Important facts

- Each survey defines its own SQLite database in its `form.php`; the AI Use Survey and personal equipment survey have separate databases.
  Source: each survey's `form.php`
- Email is required on the personal equipment survey. Shift schedule offers 6:00 AM–3:00 PM and 7:00 AM–4:00 PM presets plus manual start/end time inputs. The server validates the selection and records a readable time range.
  Source: `survey/personal-equipment-internet-access-poll-survey/form.php`, `survey/shared/lib.php`
- The results hub selects one survey at a time for viewing and CSV export. Deletion is permanent and applies only to the selected survey.
  Source: `survey/results.php`
- The targeted upload package excludes all response databases and the AI Use Survey's form configuration. Extract it at the document root above `survey/`.
  Source: `tools/build-package.py`, `README.md`
- Results credentials are configured in survey form files. Do not copy credential values into progress notes.
- The user reported uploading the targeted ZIP, then directly uploading the updated `survey/shared/lib.php` and successfully testing the survey/results flow.
- Codex has not accessed cPanel or independently verified the live site. The direct PHP change does not alter SQLite schemas or response database files.

## Blockers and bugs

- None known.

## Immediate next action

Keep the GitHub backup in sync. Preserve the deployed results password outside the public repository.

## Last significant decision

Keep responses isolated by survey database and use the targeted package for changes to the new survey so the existing AI survey data stays out of the upload.

Last updated: 2026-10-16
