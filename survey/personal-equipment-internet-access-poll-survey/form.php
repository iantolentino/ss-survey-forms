<?php
/**
 * Personal Equipment and Internet Access Poll Survey configuration.
 * This password protects the standalone results page and the central results hub.
 */
return [
    'slug'           => 'personal-equipment-internet-access-poll-survey',
    'title'          => 'Personal Equipment and Internet Access Poll Survey',
    'assets'         => '../shared',
    'db_path'        => __DIR__ . '/data/survey.sqlite',
    'admin_password' => 'change-me',
    'meta'           => 'Please complete all required fields.',
    'intro'          => [
        'Please complete this survey so we can understand each team member’s work-from-home readiness and setup.',
    ],
    'submit_label' => 'Submit survey',
    'thanks_title' => 'Thank you',
    'thanks_text'  => 'Your response has been recorded. You can close this page.',
    'questions' => [
        ['key' => 'q1', 'type' => 'text', 'label' => 'User Name'],
        ['key' => 'q2', 'type' => 'text', 'label' => 'Which team do you belong to?'],
        ['key' => 'q3', 'type' => 'time', 'label' => 'Shift schedule'],
        [
            'key' => 'q4', 'type' => 'radio',
            'label' => 'Able to work from home?',
            'options' => ['Yes', 'No'],
        ],
        [
            'key' => 'q5', 'type' => 'radio',
            'label' => 'Internet connection at home?',
            'options' => ['Yes', 'No'],
        ],
        [
            'key' => 'q6', 'type' => 'text',
            'label' => 'Internet speed (MBPS down/up)',
            'hint' => 'Enter download/upload speeds, for example 100 Mbps down / 20 Mbps up.',
            'hide_if' => ['q5' => ['No']],
        ],
        [
            'key' => 'q7', 'type' => 'text',
            'label' => 'Service Provider',
            'hide_if' => ['q5' => ['No']],
        ],
        ['key' => 'q8', 'type' => 'text', 'label' => 'Unit'],
        ['key' => 'q9', 'type' => 'textarea', 'label' => 'WFH setup & equipment (portable screens, monitors, headset, UPS etc.)'],
        ['key' => 'q10', 'type' => 'textarea', 'label' => 'Other comments', 'required' => false],
    ],
];
