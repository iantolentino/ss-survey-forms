# Survey Forms System Summary

This repository contains a PHP survey application for Strata Staff. Each survey has its own directory, form configuration, submission handler, results page, and SQLite database. Shared rendering and validation code lives in `survey/shared/`.

The central `survey/results.php` page lets an administrator select a survey, view its responses, and export that survey's data to CSV. Response deletion is permanent and is scoped to the survey currently selected.

The Personal Equipment and Internet Access Poll Survey is implemented under `survey/personal-equipment-internet-access-poll-survey/`. Its Shift schedule field uses a time picker, and email is required. Its implementation has been developed and checked locally in XAMPP. The user reports uploading the targeted ZIP to cPanel; extraction and live behavior have not been confirmed.
