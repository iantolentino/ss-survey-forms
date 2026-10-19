# Survey Forms System Summary

This repository contains a PHP survey application for Strata Staff. Each survey has its own directory, form configuration, submission handler, results page, and SQLite database. Shared rendering and validation code lives in `survey/shared/`.

The central `survey/results.php` page lets an administrator select a survey, view its responses, and export that survey's data to CSV. Response deletion is permanent and is scoped to the survey currently selected.

The Personal Equipment and Internet Access Poll Survey is implemented under `survey/personal-equipment-internet-access-poll-survey/`. Email is required. Shift schedule offers two preset ranges and custom start/end time pickers. The user reports uploading the targeted ZIP and later the updated shared PHP file, then successfully testing the survey/results flow; Codex did not independently verify the live site.
