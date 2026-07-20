<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

$works = db()->query('SELECT * FROM works ORDER BY sort_order ASC, created_at DESC')->fetchAll();
$pageTitle = SITE_OWNER_FIRST . ' ' . SITE_OWNER_LAST . ' — ' . SITE_SUBTITLE;

// категории среди реально загруженных работ — для строки-сводки над галереей
$categories = array_values(array_unique(array_filter(array_map(
    static fn ($w) => $w['category'] ?? '',
    $works
))));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e(SITE_BIO . ' ' . SITE_BIO_DE) ?>">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="site-header">
  <div class="wrap site-header__inner">
    <div class="site-header__logo"><a href="/"><?= e(SITE_NAV_HOME) ?></a></div>
    <nav class="site-header__nav">
      <a href="/#gallery"><?= e(SITE_NAV_GALLERY) ?></a>
      <a href="/#contact"><?= e(SITE_NAV_CONTACT) ?></a>
    </nav>
    <div class="site-header__contact"><a class="link" href="<?= e(SITE_TELEGRAM_URL) ?>" target="_blank" rel="noopener">TG <?= e(SITE_TELEGRAM) ?></a></div>
  </div>
</header>

<section class="hero wrap">
  <h1 class="hero__title fade-in">
    <span class="hero__title-first"><?= e(SITE_OWNER_FIRST) ?></span>
    <span class="hero__title-last"><?= e(SITE_OWNER_LAST) ?></span>
  </h1>
  <?php if (SITE_SUBTITLE_EYEBROW !== ''): ?>
    <div class="hero__subtitle-eyebrow fade-in"><?= e(SITE_SUBTITLE_EYEBROW) ?></div>
  <?php endif; ?>
  <div class="hero__subtitle fade-in"><?= e(SITE_SUBTITLE) ?></div>

  <div class="hero__body">
    <div class="hero__image fade-in" style="background-image:url('/assets/img/hero.jpg')"></div>
    <div>
      <div class="hero__text-block fade-in">
        <div class="hero__label"><?= e(SITE_BIO_LABEL) ?><span class="colon">:</span></div>
        <div class="hero__bio">
          <?php foreach (array_filter(array_map('trim', explode("\n", SITE_BIO))) as $para): ?>
            <p><?= e($para) ?></p>
          <?php endforeach; ?>
        </div>
        <?php if (SITE_BIO_DE !== ''): ?>
        <div class="hero__label"><?= e(SITE_BIO_LABEL_DE) ?><span class="colon">:</span></div>
        <div class="hero__bio">
          <?php foreach (array_filter(array_map('trim', explode("\n", SITE_BIO_DE))) as $para): ?>
            <p><?= e($para) ?></p>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="hero__text-block fade-in">
        <div class="hero__label"><?= e(SITE_CONTACT_LABEL) ?><span class="colon">:</span></div>
        <div class="hero__label"><?= e(SITE_CONTACT_LABEL_DE) ?><span class="colon">:</span></div>
      </div>
    </div>
  </div>
</section>

<section class="wrap" id="gallery">
  <div class="gallery-head fade-in">
    <div>
      <div class="gallery-head__eyebrow"><?= e(GALLERY_EYEBROW) ?> / <?= e(GALLERY_EYEBROW_DE) ?></div>
      <h2 class="gallery-head__title"><?= e(GALLERY_TITLE) ?> / <?= e(GALLERY_TITLE_DE) ?></h2>
    </div>
    <?php if ($works): ?>
      <div class="gallery-head__meta">
        <?= e(works_count_bi(count($works))) ?><br>
        <?= e(implode(' / ', array_map('category_bi', $categories))) ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if (!$works): ?>
    <div class="gallery__empty"><?= e(GALLERY_EMPTY) ?> / <?= e(GALLERY_EMPTY_DE) ?></div>
  <?php else: ?>
    <div class="gallery">
      <?php foreach ($works as $w): ?>
        <div class="gallery__item fade-in"
             data-open-lightbox
             data-full="/uploads/works/<?= e($w['image']) ?>"
             data-title="<?= e($w['title']) ?>"
             data-author="<?= e($w['author']) ?>"
             data-desc="<?= e($w['description']) ?>">
          <img class="gallery__img" src="/uploads/thumbs/<?= e($w['image']) ?>" alt="<?= e($w['title']) ?>" loading="lazy">
          <div class="gallery__overlay">
            <div class="gallery__title"><?= e($w['title']) ?></div>
            <div class="gallery__rule"></div>
            <div class="gallery__author">
              <?= e($w['author']) ?><?php if ($w['category']): ?> · <?= e(category_bi($w['category'])) ?><?php endif; ?> · <?= (int) date('Y', strtotime($w['created_at'])) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="gallery-foot fade-in">
      <span>01 — <?= str_pad((string) count($works), 2, '0', STR_PAD_LEFT) ?></span>
      <span><?= e(implode(' / ', array_map('category_bi', $categories))) ?></span>
    </div>
  <?php endif; ?>
</section>

<section class="wrap contact" id="contact">
  <div>
    <h2 class="contact__title"><?= e(CONTACT_FORM_TITLE) ?> / <?= e(CONTACT_FORM_TITLE_DE) ?><span class="colon">:</span></h2>
    <p class="contact__intro"><?= e(CONTACT_FORM_INTRO) ?><br><?= e(CONTACT_FORM_INTRO_DE) ?></p>
  </div>
  <form id="contact-form">
    <div class="field">
      <label for="f-name"><?= e(FORM_LABEL_NAME) ?></label>
      <input id="f-name" name="name" type="text" required>
    </div>
    <div class="field">
      <label for="f-topic"><?= e(FORM_LABEL_TOPIC) ?></label>
      <select id="f-topic" name="topic" required>
        <option value=""><?= e(FORM_TOPIC_PLACEHOLDER) ?></option>
        <?php foreach (array_filter([FORM_TOPIC_1, FORM_TOPIC_2, FORM_TOPIC_3, FORM_TOPIC_4]) as $opt): ?>
          <option><?= e($opt) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="f-comment"><?= e(FORM_LABEL_COMMENT) ?></label>
      <textarea id="f-comment" name="comment"></textarea>
    </div>
    <div class="field">
      <label for="f-contact"><?= e(FORM_LABEL_CONTACT) ?></label>
      <input id="f-contact" name="contact" type="text" required>
    </div>
    <div class="consent"><?= e(FORM_CONSENT) ?><br><?= e(FORM_CONSENT_DE) ?></div>
    <button class="submit-btn" type="submit"><span class="submit-btn__ru"><?= e(FORM_SUBMIT) ?></span><span class="submit-btn__de"><?= e(FORM_SUBMIT_DE) ?></span></button>
    <div class="form-msg" data-form-msg></div>
  </form>
</section>

<footer class="site-footer">
  <div class="wrap">
    <div class="site-footer__note">
      <?= e(SITE_TG_CHANNEL_NOTE) ?> / <?= e(SITE_TG_CHANNEL_NOTE_DE) ?>:
      <a href="<?= e(SITE_TELEGRAM_URL) ?>" target="_blank" rel="noopener"><?= e(SITE_TELEGRAM) ?></a>
    </div>
    <div class="site-footer__copy">© <?= e(SITE_OWNER_FIRST . ' ' . SITE_OWNER_LAST) ?>, <?= date('Y') ?></div>
  </div>
</footer>

<div class="lightbox" id="lightbox">
  <div class="lightbox__close" data-lightbox-close><?= e(LIGHTBOX_CLOSE) ?></div>
  <div class="lightbox__inner">
    <img data-lightbox-img src="" alt="">
    <div class="lightbox__meta">
      <div class="gallery__title" data-lightbox-title></div>
      <div class="gallery__author" data-lightbox-author></div>
      <p data-lightbox-desc style="text-transform:none;"></p>
    </div>
  </div>
</div>

<script src="/assets/js/main.js"></script>
</body>
</html>
