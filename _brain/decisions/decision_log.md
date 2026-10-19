# Decision Log

## Decisions

### Separate SQLite database for each survey

- **Type:** ARCH
- **Impact:** High
- **Decision:** Keep each survey's responses in its own SQLite database, selected by that survey's `form.php`.
- **Reason:** Prevent response datasets from being mixed and allow targeted deployment without bundling existing data.
- **Date:** 2026-10-16

### Local-first implementation

- **Type:** SCOPE
- **Impact:** Medium
- **Decision:** Develop and check requested survey changes in local XAMPP before any cPanel deployment.
- **Reason:** Review the behavior locally before changing the hosted application.
- **Date:** 2026-10-16

### Deletion is scoped to the selected survey

- **Type:** SECURITY
- **Impact:** High
- **Decision:** Results deletion targets only the response ID in the currently selected survey and requires an authenticated POST with a CSRF token.
- **Reason:** Make cleanup available while limiting accidental or cross-site deletion.
- **Date:** 2026-10-16
