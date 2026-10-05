<?php
/**
 * Survey definition. To make a new survey: copy this whole folder,
 * rename it, then edit only this file (and delete the copied data/*.sqlite).
 *
 * Question types: text | textarea | radio | checkbox | multiselect
 *   'other' => true   adds an "Other (please specify)" option with a text box
 *   'required'        defaults to true
 *   'hide_if'         skip logic, e.g. ['q2' => ['No']] hides the question when q2 = "No"
 */
return [
    'slug'           => 'ai-use-survey',
    'title'          => 'AI Tools and Client Data Survey',
    'assets'         => '../shared',
    'db_path'        => __DIR__ . '/data/survey.sqlite',

    // Password for results.php. Change it before sharing the survey link.
    'admin_password' => 'change-me',

    'meta'  => 'Takes about 2 minutes.',
    'intro' => [
        'Hi team! We are getting a clear picture of how AI tools (ChatGPT, Copilot, Gemini, Claude, DeepSeek, etc.) are used in our work with client data. This is not about personal use.',
        'This survey is for understanding, not discipline. There are no wrong answers, so please be honest. Do not type any actual client information in your answers.',
    ],
    'submit_label' => 'Submit survey',
    'thanks_title' => 'Thank you',
    'thanks_text'  => 'Your response has been recorded. You can close this page.',

    'questions' => [
        [
            'key' => 'q1', 'type' => 'text',
            'label' => 'What is your role?',
        ],
        [
            'key' => 'q2', 'type' => 'radio',
            'label' => 'Do you use any AI tool for work tasks that involve client information or data?',
            'hint' => 'For example: emails, documents, invoices, calendars, reports, spreadsheets.',
            'options' => ['Yes, regularly', 'Yes, occasionally', 'No', 'Not sure'],
        ],
        [
            'key' => 'q3', 'type' => 'multiselect',
            'label' => 'Which AI tools do you use for client work?',
            'placeholder' => 'Select all that apply',
            'options' => ['ChatGPT', 'Microsoft Copilot', 'Google Gemini', 'Claude', 'DeepSeek', 'Grammarly or other AI writing tools'],
            'other' => true,
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q4', 'type' => 'radio',
            'label' => 'Are you using a company-provided account or your personal account?',
            'options' => ['Company-provided', 'Personal account', 'Both'],
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q5', 'type' => 'checkbox',
            'label' => 'What do you use AI for?',
            'hint' => 'Select all that apply.',
            'options' => [
                'Writing or replying to emails',
                'Summarizing documents or meeting notes',
                'Drafting reports or presentations',
                'Spreadsheet formulas or data analysis',
                'Bookkeeping or accounting tasks (e.g. reconciling, categorizing, checking figures)',
                'Calendar, travel, or scheduling help',
                'Translating or proofreading',
            ],
            'other' => true,
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q6', 'type' => 'checkbox',
            'label' => 'What type of client information do you enter into AI tools?',
            'hint' => 'Select all that apply.',
            'options' => [
                'Client or company names',
                "Names or details of the client's customers or contacts",
                'Financial data (invoices, bank details, payroll, tax information)',
                'Emails or messages',
                'Contracts or internal documents',
                'Login credentials or system access details',
                "I don't enter any client information",
            ],
            'other' => true,
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q7', 'type' => 'radio',
            'label' => 'How often do you use AI tools for client work?',
            'options' => ['Daily', 'A few times a week', 'A few times a month', 'Rarely'],
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q8', 'type' => 'checkbox',
            'label' => 'Why do you use AI for client tasks?',
            'hint' => 'Select all that apply.',
            'options' => [
                'Saves time',
                'Improves quality of my work',
                'Client expects fast turnaround',
                'No other tool is available',
                'Learning or upskilling',
            ],
            'other' => true,
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q9', 'type' => 'radio',
            'label' => 'Do you remove or hide client details before entering information into an AI tool?',
            'options' => ['Always', 'Sometimes', 'Never', 'Not applicable'],
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q10', 'type' => 'radio',
            'label' => 'Are you aware of any company or client rules about using AI tools with client data?',
            'options' => ["Yes, I know the rules", "I've heard something, but I'm not sure", "No, I'm not aware of any"],
            'hide_if' => ['q2' => ['No']],
        ],
        [
            'key' => 'q11', 'type' => 'radio',
            'label' => 'Would you use a company-approved AI tool if one were provided?',
            'options' => ['Yes', 'Maybe', 'No'],
        ],
        [
            'key' => 'q12', 'type' => 'textarea',
            'label' => "Is there anything you'd like management to know about using AI at work?",
            'required' => false,
        ],
    ],
];
