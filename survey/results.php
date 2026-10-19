<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/shared/lib.php';

/* Discover each survey from its own form.php. */
$forms = [];
foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
    if (!is_file($dir . '/form.php')) {
        continue;
    }
    $candidate = require $dir . '/form.php';
    if (is_array($candidate) && isset($candidate['slug'], $candidate['title'], $candidate['db_path'], $candidate['questions'])) {
        $forms[$candidate['slug']] = $candidate;
    }
}

$adminSlug = 'personal-equipment-internet-access-poll-survey';
$adminPassword = $forms[$adminSlug]['admin_password'] ?? '';
$sid = 'survey_admin_hub_' . md5(__DIR__);
$configured = $adminPassword !== '' && $adminPassword !== 'change-me';

if (isset($_GET['logout'])) {
    unset($_SESSION[$sid]);
    header('Location: results.php');
    exit;
}

$loginError = false;
if ($configured && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['password'])) {
    if (hash_equals($adminPassword, (string) $_POST['password'])) {
        session_regenerate_id(true);
        $_SESSION[$sid] = true;
        header('Location: results.php');
        exit;
    }
    $loginError = true;
}

if (empty($_SESSION[$sid])) {
    survey_page_head('Survey results login', 'shared');
    ?>
    <main class="login">
      <h1>Survey results</h1>
      <?php if (!$configured): ?>
        <p>Set the results password in the personal equipment survey's <code>form.php</code>, then reload this page.</p>
      <?php else: ?>
        <form method="post">
          <label for="pw">Password</label>
          <input id="pw" type="password" name="password" required autofocus>
          <?php if ($loginError): ?><p class="q-error" style="display:block">Wrong password.</p><?php endif; ?>
          <button class="primary" type="submit">View results</button>
        </form>
      <?php endif; ?>
    </main>
    <?php
    survey_page_foot();
    exit;
}

if (!$forms) {
    http_response_code(503);
    exit('No surveys are configured.');
}
$selectedSlug = (string) ($_POST['survey'] ?? $_GET['survey'] ?? 'ai-use-survey');
if (!isset($forms[$selectedSlug])) {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_response'])) {
        http_response_code(400);
        exit('Unknown survey.');
    }
    $selectedSlug = (string) array_key_first($forms);
}
$form = $forms[$selectedSlug];
$pdo = survey_db($form);
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_response'])) {
    if (!survey_valid_csrf($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Invalid request token. Refresh the page and try again.');
    }
    $responseId = filter_var($_POST['response_id'] ?? null, FILTER_VALIDATE_INT);
    if ($responseId === false || $responseId < 1) {
        http_response_code(400);
        exit('Invalid response ID.');
    }
    $delete = $pdo->prepare('DELETE FROM responses WHERE id = :id');
    $delete->execute([':id' => $responseId]);
    header('Location: results.php?survey=' . rawurlencode($selectedSlug) . '&deleted=' . ($delete->rowCount() ? '1' : '0'));
    exit;
}
$rows = $pdo->query('SELECT id, created_at, answers FROM responses ORDER BY id DESC')->fetchAll();
$responses = [];
foreach ($rows as $row) {
    $responses[] = ['id' => $row['id'], 'at' => $row['created_at'], 'a' => json_decode($row['answers'], true) ?: []];
}
$total = count($responses);

function csv_safe($value): string
{
    $text = is_array($value) ? implode('; ', $value) : (string) $value;
    return ($text !== '' && strpos("=+-@\t\r", $text[0]) !== false) ? "'" . $text : $text;
}

if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $form['slug'] . '-responses.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF");
    $head = ['Submitted (Manila time)', 'Email address'];
    foreach ($form['questions'] as $i => $question) {
        $head[] = 'Q' . ($i + 1) . ' ' . $question['label'];
        if (!empty($question['other'])) {
            $head[] = 'Q' . ($i + 1) . ' other';
        }
    }
    fputcsv($out, $head, ',', '"', '\\');
    foreach (array_reverse($responses) as $response) {
        $line = [survey_manila($response['at']), csv_safe($response['a']['email'] ?? '')];
        foreach ($form['questions'] as $question) {
            $line[] = csv_safe($response['a'][$question['key']] ?? '');
            if (!empty($question['other'])) {
                $line[] = csv_safe($response['a'][$question['key'] . '_other'] ?? '');
            }
        }
        fputcsv($out, $line, ',', '"', '\\');
    }
    fclose($out);
    exit;
}

survey_page_head('Survey results', 'shared');
?>
<main class="wrap results">
  <div class="topbar">
    <h1>Survey results</h1>
    <a href="?survey=<?= e($selectedSlug) ?>&amp;export=csv">Download CSV</a>
    <a href="results.php?logout=1">Log out</a>
  </div>
  <form class="survey-picker" method="get">
    <label for="survey">Choose a survey form
      <select id="survey" name="survey">
        <?php foreach ($forms as $slug => $survey): ?>
          <option value="<?= e($slug) ?>"<?= $slug === $selectedSlug ? ' selected' : '' ?>><?= e($survey['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="primary" type="submit">View responses</button>
  </form>
  <?php if (($_GET['deleted'] ?? '') === '1'): ?><p class="delete-notice" role="status">Response removed. It will no longer appear in results or future CSV exports.</p><?php elseif (($_GET['deleted'] ?? '') === '0'): ?><p class="delete-notice" role="status">That response was already removed or could not be found.</p><?php endif; ?>
  <h2><?= e($form['title']) ?></h2>
  <p class="count"><?= $total ?> response<?= $total === 1 ? '' : 's' ?></p>
  <?php if ($total === 0): ?>
    <section class="r-block"><p>No responses for this survey yet.</p></section>
  <?php endif; ?>
  <div class="individual-responses">
    <?php foreach ($responses as $position => $response): ?>
      <details class="response-card"<?= $position === 0 ? ' open' : '' ?> data-response-id="<?= e($response['id']) ?>">
        <summary class="response-summary">
          <span class="response-identity">
            <span class="response-email"><?= e(($response['a']['email'] ?? '') !== '' ? $response['a']['email'] : 'Email not collected') ?></span>
            <span class="response-meta">Response #<?= e($response['id']) ?> &middot; <?= e(survey_manila($response['at'])) ?> (Manila time)</span>
          </span>
          <span class="response-toggle" aria-hidden="true"></span>
        </summary>
        <dl class="response-answers">
          <?php foreach ($form['questions'] as $i => $question):
              $answer = $response['a'][$question['key']] ?? null;
              $other = $response['a'][$question['key'] . '_other'] ?? '';
              $empty = $answer === null || $answer === '' || $answer === [];
          ?>
            <div class="response-answer">
              <dt><span class="answer-number"><?= $i + 1 ?>.</span> <?= e($question['label']) ?></dt>
              <dd>
                <?php if ($empty): ?>
                  <span class="answer-empty"><?= survey_is_hidden($question, $response['a']) ? 'Skipped' : 'Not answered' ?></span>
                <?php elseif (is_array($answer)): ?>
                  <ul class="answer-options">
                    <?php foreach ($answer as $value): ?><li><?= e($value) ?></li><?php endforeach; ?>
                  </ul>
                <?php else: ?>
                  <div class="answer-text"><?= nl2br(e($answer)) ?></div>
                <?php endif; ?>
                <?php if ($other !== ''): ?><p class="answer-other"><strong>Other:</strong> <?= e($other) ?></p><?php endif; ?>
              </dd>
            </div>
          <?php endforeach; ?>
        </dl>
        <form class="response-actions" method="post" onsubmit="return confirm('Permanently remove this response from the selected survey? It will also be removed from future CSV exports.');">
          <input type="hidden" name="survey" value="<?= e($selectedSlug) ?>">
          <input type="hidden" name="csrf_token" value="<?= e(survey_csrf_token()) ?>">
          <input type="hidden" name="response_id" value="<?= e($response['id']) ?>">
          <button class="delete-response" type="submit" name="delete_response" value="1">Delete response</button>
        </form>
      </details>
    <?php endforeach; ?>
  </div>
</main>
<?php survey_page_foot(); ?>
