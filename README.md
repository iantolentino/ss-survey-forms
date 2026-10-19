# SS Survey Forms

PHP survey application for Strata Staff. Uses SQLite to save each submission independently.

## Features
- Required email with browser and server validation.
- Sticky progress on desktop and mobile, conditional questions, and Other answers.
- Individual response records with email, submission time, and every answer.
- Password protected results with response deletion and CSV export compatible with Excel.
- Green #0F5C4E, blue #1E5AA8, embedded CSS/JavaScript, and an F favicon.

## Run locally
Requires PHP 8+, pdo_sqlite, and mbstring.

```sh
php -S 127.0.0.1:8000 -t survey
```

Open http://127.0.0.1:8000/ai-use-survey/ or http://127.0.0.1:8000/personal-equipment-internet-access-poll-survey/.
The survey results hub is at http://127.0.0.1:8000/results.php. SQLite is initialized on first visit.
Set each survey's `admin_password` in its `form.php` before sharing its results page.
The AI survey still uses the placeholder `change-me` in source. The requested password for the new poll is stored in its
`form.php`; pushing that file to a public repository would disclose the credential, so rotate it before publishing source.

## Project progress notes

The `_brain/` folder tracks current state, decisions, daily progress, and handoffs. The root `AGENTS.md` points Codex to its controller.

## Build and upload
```sh
python tools/build-package.py --output dist/survey-results-update.zip
```

Upload the update ZIP to the domain document root and extract it there, overwriting application files. It excludes form.php and all response databases, preserving deployed questions, password, and responses.

For a new installation, add `--full` to include the question configuration. After extraction, set the results password, open the survey, and verify one test submission appears on the results page.

To add only the personal equipment survey to an existing installation without replacing the AI survey's settings or responses:
```sh
python tools/build-package.py --survey personal-equipment-internet-access-poll-survey --output dist/personal-equipment-internet-access-poll-survey.zip
```
Upload and extract that ZIP at the domain document root. It includes the selected survey's configured password and the
central results hub at `survey/results.php`.

The data folder and database must be writable by PHP. Its .htaccess blocks direct web access on Apache/LiteSpeed.

## Backup scope
GitHub backs up application source, deployment instructions, and the package builder. Response databases and the deployed results password are excluded. Back up those separately in cPanel if you need to restore collected responses after a hosting failure.
