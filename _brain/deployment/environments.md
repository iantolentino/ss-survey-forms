# Environments

| Environment | Purpose | Current state |
| --- | --- | --- |
| Local XAMPP | Development and feature checks | Current poll and result-management changes checked locally |
| cPanel hosting | Hosted survey application | User reports uploading the targeted poll ZIP and then the updated shared PHP file; the survey/results flow was tested successfully by the user. Existing AI Use Survey remains hosted. |

## Environment rules

- Keep local survey databases separate from hosted response databases.
- Do not include SQLite response files in upload packages.
- Preserve the existing AI Use Survey configuration and database when deploying a targeted poll update.
- Use credentials configured for the intended environment; do not record their values in `_brain/`.
