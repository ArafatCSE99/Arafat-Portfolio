<?php
require_once __DIR__ . '/../includes/functions.php';
$meta = seo_meta('Word Counter', 'Count words, characters, sentences, and estimate reading time.');
$activePage = 'tools';
$base = rtrim(SITE_URL, '/');
require __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb"><a href="<?= e($base) ?>/"><?= e(t('common.back_home')) ?></a> / <a href="<?= e($base) ?>/tools/index.php"><?= e(t('nav.tools')) ?></a> / <span><?= e(t('tool.word.title')) ?></span></div>
    <span class="eyebrow"><?= icon('edit') ?> <?= e(t('tools.tag')) ?></span>
    <h1 class="section-title"><?= e(t('tool.word.title')) ?></h1>
    <p class="section-desc"><?= e(t('tool.word.sub_desc')) ?></p>
  </div>
</div>

<section class="section tool-panel" style="padding-top:0;max-width:760px;">
  <div class="container">
    <div class="card">
      <div class="form-group">
        <label for="wcText"><?= e(t('tool.word.your_text')) ?></label>
        <textarea id="wcText" class="form-control" style="min-height:260px;" placeholder="<?= e(t('tool.word.placeholder')) ?>"></textarea>
      </div>
      <div class="grid grid-4" style="text-align:center;">
        <div><div style="font-size:1.5rem;font-weight:800;" id="wcWords">0</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.word.words')) ?></div></div>
        <div><div style="font-size:1.5rem;font-weight:800;" id="wcChars">0</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.word.characters')) ?></div></div>
        <div><div style="font-size:1.5rem;font-weight:800;" id="wcSentences">0</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.word.sentences')) ?></div></div>
        <div><div style="font-size:1.5rem;font-weight:800;" id="wcTime">0 min</div><div style="color:var(--text-muted);font-size:.8rem;"><?= e(t('tool.word.reading_time')) ?></div></div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<script src="<?= e(asset_url('assets/js/tools/word-counter.js')) ?>"></script>
