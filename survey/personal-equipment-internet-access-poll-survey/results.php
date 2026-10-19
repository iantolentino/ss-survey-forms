<?php
declare(strict_types=1);
session_start();
$form = require __DIR__ . '/form.php';
require __DIR__ . '/../shared/lib.php';

$sid = 'survey_admin_' . md5(__DIR__);
$configured = $form['admin_password'] !== '' && $form['admin_password'] !== 'change-me';

if (isset($_GET['logout'])) {
    unset($_SESSION[$sid]);
    header('Location: results.php');
    exit;
}

$loginError = false;
if ($configured && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['password'])) {
    if (hash_equals($form['admin_password'], (string) $_POST['password'])) {
        session_regenerate_id(true);
        $_SESSION[$sid] = true;
        header('Location: results.php');
        exit;
    }
    $loginError = true;
}

if (empty($_SESSION[$sid])) {
    survey_page_head($form['title'] . ' results login', $form['assets']);
    ?>
    <main class="login">
      <h1><?= e($form['title']) ?> results</h1>
      <?php if (!$configured): ?>
        <p>Set <code>admin_password</code> in <code>form.php</code> to a new value, then reload this page.</p>
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

/* ---------- Load data ---------- */
$pdo  = survey_db($form);
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
    header('Location: results.php?deleted=' . ($delete->rowCount() ? '1' : '0'));
    exit;
}
$rows = $pdo->query('SELECT id, created_at, answers FROM responses ORDER BY id DESC')->fetchAll();
$responses = [];
foreach ($rows as $r) {
    $responses[] = ['id' => $r['id'], 'at' => $r['created_at'], 'a' => json_decode($r['answers'], true) ?: []];
}
$total = count($responses);

/* ---------- CSV export ---------- */
function csv_safe($v): string
{
    $s = is_array($v) ? implode('; ', $v) : (string) $v;
    return ($s !== '' && strpos("=+-@\t\r", $s[0]) !== false) ? "'" . $s : $s;
}

if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $form['slug'] . '-responses.csv"');
    $out = fopen('php://output', 'w');
    fwrite($out, "\xEF\xBB\xBF"); // so Excel reads UTF-8
    $nameQuestion = $form['questions'][0];
    $head = ['Submitted (Manila time)', $nameQuestion['label'], 'Email address'];
    foreach (array_slice($form['questions'], 1, null, true) as $i => $q) {
        $head[] = 'Q' . ($i + 1) . ' ' . $q['label'];
        if (!empty($q['other'])) {
            $head[] = 'Q' . ($i + 1) . ' other';
        }
    }
    fputcsv($out, $head, ',', '"', '\\');
    foreach (array_reverse($responses) as $r) {
        $line = [survey_manila($r['at']), csv_safe($r['a'][$nameQuestion['key']] ?? ''), csv_safe($r['a']['email'] ?? '')];
        foreach (array_slice($form['questions'], 1, null, true) as $q) {
            $line[] = csv_safe($r['a'][$q['key']] ?? '');
            if (!empty($q['other'])) {
                $line[] = csv_safe($r['a'][$q['key'] . '_other'] ?? '');
            }
        }
        fputcsv($out, $line, ',', '"', '\\');
    }
    fclose($out);
    exit;
}

/* ---------- Page ---------- */
survey_page_head($form['title'] . ' responses', $form['assets']);
?>
<main class="wrap results">
  <div class="topbar">
    <h1><?= e($form['title']) ?> responses</h1>
    <a href="results.php?export=csv">Download CSV</a>
    <a href="results.php?logout=1">Log out</a>
  </div>
  <?php if (($_GET['deleted'] ?? '') === '1'): ?><p class="delete-notice" role="status">Response removed. It will no longer appear in results or future CSV exports.</p><?php elseif (($_GET['deleted'] ?? '') === '0'): ?><p class="delete-notice" role="status">That response was already removed or could not be found.</p><?php endif; ?>
  <p class="count"><?= $total ?> response<?= $total === 1 ? '' : 's' ?></p>
  <?php if ($total === 0): ?>
    <section class="r-block"><p>No responses yet. Share the survey link with the team.</p></section>
  <?php endif; ?>
  <div class="individual-responses">
    <?php foreach ($responses as $position => $r): ?>
      <details class="response-card"<?= $position === 0 ? ' open' : '' ?> data-response-id="<?= e($r['id']) ?>">
        <summary class="response-summary">
          <span class="response-identity">
            <span class="response-email"><?= e(($r['a'][$form['questions'][0]['key']] ?? '') !== '' ? $r['a'][$form['questions'][0]['key']] : 'Name not provided') ?></span>
            <span class="response-meta"><?= e($r['a']['email'] ?? 'Email not collected') ?> &middot; Response #<?= e($r['id']) ?> &middot; <?= e(survey_manila($r['at'])) ?> (Manila time)</span>
          </span>
          <span class="response-toggle" aria-hidden="true"></span>
        </summary>
        <dl class="response-answers">
          <?php foreach ($form['questions'] as $i => $q):
              $answer = $r['a'][$q['key']] ?? null;
              $other = $r['a'][$q['key'] . '_other'] ?? '';
              $empty = $answer === null || $answer === '' || $answer === [];
          ?>
            <div class="response-answer">
              <dt><span class="answer-number"><?= $i + 1 ?>.</span> <?= e($q['label']) ?></dt>
              <dd>
                <?php if ($empty): ?>
                  <span class="answer-empty"><?= survey_is_hidden($q, $r['a']) ? 'Skipped' : 'Not answered' ?></span>
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
        <form class="response-actions" method="post" onsubmit="return confirm('Permanently remove this response? It will also be removed from future CSV exports.');">
          <input type="hidden" name="csrf_token" value="<?= e(survey_csrf_token()) ?>">
          <input type="hidden" name="response_id" value="<?= e($r['id']) ?>">
          <button class="delete-response" type="submit" name="delete_response" value="1">Delete response</button>
        </form>
      </details>
    <?php endforeach; ?>
  </div>
</main>
<?php survey_page_foot(); ?>
