<?php
declare(strict_types=1);
$form = require __DIR__ . '/form.php';
require __DIR__ . '/../shared/lib.php';

header('Content-Type: text/html; charset=UTF-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Bots fill the hidden field; pretend success and store nothing.
if (!empty($_POST['website'])) {
    echo survey_render_thanks($form);
    exit;
}

function show_errors(array $messages): void
{
    header('HX-Retarget: #form-error');
    header('HX-Reswap: innerHTML');
    echo '<ul>';
    foreach ($messages as $m) {
        echo '<li>' . e($m) . '</li>';
    }
    echo '</ul>';
    exit;
}

[$answers, $errors] = survey_collect($form, $_POST);
if ($errors) {
    show_errors($errors);
}

try {
    $pdo  = survey_db($form);
    $stmt = $pdo->prepare('INSERT INTO responses (answers) VALUES (:a)');
    $stmt->execute([':a' => json_encode($answers, JSON_UNESCAPED_UNICODE)]);
} catch (Throwable $ex) {
    error_log('survey save failed: ' . $ex->getMessage());
    show_errors(['Your response could not be saved. Please try again, or let IT know if it keeps happening.']);
}

echo survey_render_thanks($form);
