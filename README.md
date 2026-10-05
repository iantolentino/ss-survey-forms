# SS Survey Forms

PHP survey application for Strata Staff. Uses SQLite to save each submission independently.

## Features
- Required email with browser and server validation.
- Sticky progress on desktop and mobile, conditional questions, and Other answers.
- Individual response records with email, submission time, and every answer.
- Password protected results and CSV export compatible with Excel.
- Green #0F5C4E, blue #1E5AA8, embedded CSS/JavaScript, and an F favicon.

## Run locally
Requires PHP 8+, pdo_sqlite, and mbstring.

```sh
php -S 127.0.0.1:8000 -t survey
```

Open http://127.0.0.1:8000/ai-use-survey/. SQLite is initialized on the first page visit.
Set `admin_password` in `survey/ai-use-survey/form.php` before opening `results.php`.
The repository stores the placeholder `change-me`, never the deployed password.

## Build and upload
```sh
python tools/build-package.py --output dist/survey-results-update.zip
```

Upload the update ZIP to the domain document root and extract it there, overwriting application files. It excludes form.php and all response databases, preserving deployed questions, password, and responses.

For a new installation, add `--full` to include the question configuration. After extraction, set the results password, open the survey, and verify one test submission appears on the results page.

The data folder and database must be writable by PHP. Its .htaccess blocks direct web access on Apache/LiteSpeed.

## Backup scope
GitHub backs up application source, deployment instructions, and the package builder. Response databases and the deployed results password are excluded. Back up those separately in cPanel if you need to restore collected responses after a hosting failure.
