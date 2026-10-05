# survey

```
survey/
  shared/            reused by every survey (CSS, PHP helpers and embedded scripts)
  ai-use-survey/     one survey
    form.php         questions, intro text, results password  <- the only file you edit
    index.php        the form people fill in
    submit.php       saves the answer (htmx posts here)
    results.php      password-protected individual responses + CSV download
    data/            SQLite file is created here on first page visit
```

## Run locally
```
cd survey
php -S localhost:8000
```
Open http://localhost:8000/ai-use-survey/ (form) and /ai-use-survey/results.php (results).
Needs PHP 8+ with pdo_sqlite and mbstring enabled for this domain.

## Before sharing
1. Open `ai-use-survey/form.php` and change `admin_password` (results stay locked while it is `change-me`).
2. Upload the whole `survey` folder (keep `shared` next to `ai-use-survey`).
3. Make sure `ai-use-survey/data/` is writable by PHP. It gets its own `.htaccess` that blocks web access to the database.

## Make a new survey
1. Copy `ai-use-survey` to `survey/<new-name>`.
2. Delete the `.sqlite` file inside the copy's `data/`.
3. Edit `form.php`: change `slug`, `title`, `db_path` if needed, the password, and the questions.
   Types: `text`, `textarea`, `radio`, `checkbox`, `multiselect`. Add `'other' => true` for an "Other" box
   and `'hide_if' => ['q2' => ['No']]` for skip logic.

Email is required and saved with each response. Responses are not anonymous; no IP or user agent is stored. The same person can submit more than once.

## Upload package
JavaScript is embedded by shared/lib.php. No separate .js files are required.

## Updated UI and database setup
- Green #0F5C4E, blue #1E5AA8, black text and white cards/buttons as appropriate.
- CSS and JavaScript are included directly in the PHP page, so a blocked stylesheet URL does not break the UI.
- Opening ai-use-survey/index.php creates data/survey.sqlite and the responses table automatically.
- Existing databases are preserved; this ZIP deliberately contains no .sqlite file to avoid overwriting responses.
- If the page says temporarily unavailable, check cPanel > Errors / the PHP error log for "survey initialization failed".
- Enable pdo_sqlite (and sqlite3 if your PHP selector requires it) and mbstring in cPanel > Select PHP Version for stratastaff.com. On managed hosting, ask the host to enable these if no extension selector is available.
- The data directory and an existing database must be writable by the PHP account. Typical permissions are 755 for folders and 644 for files when owned by that account.
- This is an update package for the already installed survey. It omits form.php so your existing questions, database path, and results password remain intact.
- Extract into the same domain document root as the current survey and overwrite the application files.
- Do not delete or replace an existing data/survey.sqlite. Open the survey once after extraction, then refresh File Manager to see it.

## Email and sharing
The required email is validated on the server and saved in the response JSON, so existing database tables do not need migration. Emails appear in the password protected results and CSV export. Share the same survey URL with everyone; submissions are independent. Progress remains visible when scrolling on desktop and mobile.

## Individual results
Open results.php and log in to see each respondent separately. Expand a record to see that person's answers. The CSV/Excel export remains unchanged. Older responses without email stay visible.
