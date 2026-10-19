# Database Backup Policy

## Data locations

Each survey stores responses in the SQLite path defined by its `form.php`, normally under that survey's `data/` directory.

## Backup rules

- Back up the relevant survey database before any production maintenance that can change response data.
- Store backups outside the public web root and do not commit database files to Git.
- Targeted code packages exclude SQLite databases and must not be used as database backups.
- Response deletion is permanent; export or back up the survey database first if records may be needed later.

## Restore procedure

1. Stop or pause writes to the affected survey.
2. Preserve a copy of the current database, then restore the intended backup to that survey's configured path.
3. Confirm the database is readable and the expected response count is present before reopening submissions.
