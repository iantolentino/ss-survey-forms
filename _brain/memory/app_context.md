# Application Context

## Project name

Strata Staff Survey Forms

## Project type and domain

PHP web application for internal staff surveys and response management.

## Target users

Staff complete surveys. Authorized administrators review individual responses and export them for analysis.

## Core workflow

1. A respondent opens a survey-specific URL and completes its questions.
2. The server validates the required fields and saves the response to that survey's SQLite database.
3. An administrator signs in to the results page, selects a survey, reviews responses, exports CSV, or deletes a response.

## Current features

- Separate form configuration and SQLite database for each survey.
- Required email on the Personal Equipment and Internet Access Poll Survey.
- Password-protected standalone results and a central survey selector.
- CSV export and confirmed, permanent response deletion.
- Local XAMPP development and a cPanel package builder.

## Technology

| Layer | Technology |
| --- | --- |
| Language | PHP |
| UI | Server-rendered HTML and shared CSS/JavaScript |
| Database | SQLite through PDO |
| Results access | Password-protected PHP sessions |
| Local environment | XAMPP |
| Intended hosting | cPanel; user reports uploading the targeted survey ZIP on 2026-10-16, but extraction and live behavior have not been confirmed |

## Constraints

- Preserve existing survey databases during code packaging and upload.
- Keep each survey's responses isolated in its configured database.
- Do not put credentials in brain notes or deployment archives.
- Deletion from results is permanent for the selected survey.
