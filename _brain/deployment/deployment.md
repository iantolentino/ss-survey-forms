# Deployment Plan

## Current environment

The current survey work was developed and checked in local XAMPP. The user reports uploading the targeted ZIP to cPanel on 2026-10-16. Extraction, activation, and live behavior are not independently confirmed, and Codex did not access cPanel.

The newer shift-picker improvement is in `survey/shared/lib.php` and was not included in that ZIP. The user requested a direct PHP-file upload for this change. Replace the existing `/survey/shared/lib.php` file with the updated local file; this does not require a database migration.

## Target platform

Existing cPanel hosting for the survey application. For the latest shift-picker change, update one PHP file directly; do not use the older ZIP.

## Current direct-file update

- The user asked to receive only the PHP file and upload it directly.
- Replace `/survey/shared/lib.php` with the updated local `survey/shared/lib.php`.
- The user-reported ZIP upload predates the shift-picker change and does not contain it.
- Back up the current shared PHP file before replacing it. This change does not alter SQLite schemas or response database files.
- The time-question renderer is shared, but the Personal Equipment and Internet Access Poll Survey is currently the only survey using a `time` question.

## Upload and recovery notes

- Upload destination is the existing survey folder's `shared/lib.php` (`/survey/shared/lib.php` from the site root).
- After upload, check both presets and a custom shift. Existing saved responses and other survey data do not need a migration.
