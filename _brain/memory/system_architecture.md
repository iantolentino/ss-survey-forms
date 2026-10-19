# System Architecture

## Architecture pattern

Small PHP monolith with shared rendering and validation helpers and one directory per survey.

## Layer map

| Layer | Technology | Responsibility |
| --- | --- | --- |
| Form pages | PHP, HTML, CSS, JavaScript | Render survey questions and browser validation |
| Submission | PHP and PDO | Validate submitted fields and insert one response |
| Results | PHP sessions and SQLite | Authenticate administrators, list responses, delete selected responses, and export CSV |
| Persistence | One SQLite file per survey | Keep datasets separate |
| Packaging | Python ZIP builder | Include selected survey and shared runtime without response databases |

## Request and data flow

Respondent browser → survey `index.php` → `submit.php` → shared validation/database helper → that survey's SQLite `responses` table.

Administrator browser → standalone `results.php` or `survey/results.php` → selected survey configuration/database → response display or CSV export. Delete actions use authenticated POST requests with a CSRF token and target only the selected survey database.

## Key contracts

- `form.php` defines the survey slug, title, password, SQLite path, and question definitions.
- `survey/shared/lib.php` provides rendering, validation, and database helpers.
- `tools/build-package.py --survey <slug>` packages only the selected survey, shared runtime, and central results hub; it excludes SQLite databases.

## Risks

- Response deletion is permanent; make a separate database backup before important data maintenance.
- Shared helper or CSS changes are used by multiple surveys and should be reviewed before upload.
- Survey result credentials live in survey configuration; keep them out of public source and notes.
