<?php
declare(strict_types=1);
$form = require __DIR__ . '/form.php';
require __DIR__ . '/../shared/lib.php';
$ready = true;
try {
    // Create an empty database on first visit, and preserve existing responses.
    survey_db($form);
} catch (Throwable $ex) {
    error_log('survey initialization failed: ' . $ex->getMessage());
    $ready = false;
    http_response_code(503);
}
survey_page_head($form['title'], $form['assets']);
?>
<main class="wrap" id="survey">
  <div class="form-heading">
    <h1><?= e($form['title']) ?></h1>
    <?php if (!empty($form['meta'])): ?><p><?= e($form['meta']) ?></p><?php endif; ?>
  </div>
  <?php if (!empty($form['intro'])): ?>
    <section class="notice">
      <?php foreach ($form['intro'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
    </section>
  <?php endif; ?>
  <?php if (!$ready): ?>
    <section class="unavailable" role="alert">
      <h2>The survey is temporarily unavailable</h2>
      <p>We cannot save responses right now. Please let IT know and come back once the issue has been resolved.</p>
    </section>
  <?php else: ?>
  <div class="survey-layout">
    <aside class="survey-side" aria-label="Survey progress">
      <div class="side-card">
        <div class="progress-heading"><span>Your progress</span><span id="progress-percent">0%</span></div>
        <progress class="progress-track" id="survey-progress" value="0" max="100" aria-label="Required questions answered"></progress>
        <p class="progress-detail" id="progress-detail">Complete the required questions below.</p>
      </div>
    </aside>
    <div class="survey-content">
      <form id="survey-form" method="post" action="submit.php"
            hx-post="submit.php" hx-target="#survey" hx-swap="innerHTML"
            hx-disabled-elt="#submit-btn" hx-on::after-swap="window.scrollTo(0, 0)">
        <?= survey_render_question($form['questions'][0], 1) ?>
        <fieldset class="q" data-required="1">
          <legend><label for="respondent-email">Email address <span class="optional">(required)</span></label></legend>
          <input id="respondent-email" type="email" name="email" maxlength="254" autocomplete="email" required>
          <p class="email-note">Your email will be saved with your response.</p>
        </fieldset>
        <?php foreach (array_slice($form['questions'], 1, null, true) as $i => $q): ?>
          <?= survey_render_question($q, $i + 1) ?>
        <?php endforeach; ?>
        <div class="hp" aria-hidden="true">
          <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>
        <div class="actions">
          <div class="submit-row">
            <button type="submit" id="submit-btn" class="primary"><?= e($form['submit_label']) ?><span aria-hidden="true">&rarr;</span></button>
          </div>
          <div id="form-error" class="form-error" role="alert" aria-live="polite"></div>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>
</main>
<?php survey_page_foot(); ?>
