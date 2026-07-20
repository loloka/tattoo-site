<?php
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/functions.php';

require_login();

$works = db()->query('SELECT * FROM works ORDER BY sort_order ASC, created_at DESC')->fetchAll();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Админка — работы</title>
<link rel="stylesheet" href="/assets/css/style.css">
<style>
  .admin-wrap { padding: 30px 20px; max-width: 1000px; margin: 0 auto; }
  .admin-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
  table { width:100%; border-collapse: collapse; }
  th, td { text-align:left; padding:8px; border-bottom:1px solid #000; vertical-align: middle; }
  td img { width:60px; height:60px; object-fit:cover; display:block; }
  .row-actions a { margin-right: 10px; }
  .btn-add { font-weight:700; }
</style>
</head>
<body>
<div class="admin-wrap">
  <div class="admin-top">
    <h1>Работы в галерее</h1>
    <div>
      <a class="link btn-add" href="/admin/form.php">+ Добавить работу</a>
      &nbsp;·&nbsp;
      <a class="link" href="/">Смотреть сайт</a>
      &nbsp;·&nbsp;
      <a class="link" href="/admin/logout.php">Выйти</a>
    </div>
  </div>

  <?php if ($flash): ?>
    <div class="form-msg <?= e($flash['type']) ?>"><?= e($flash['text']) ?></div>
  <?php endif; ?>

  <?php if (!$works): ?>
    <p>Пока нет ни одной работы. Нажмите «Добавить работу».</p>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>Фото</th><th>Название</th><th>Тип</th><th>Автор</th><th>Порядок</th><th>Действия</th></tr>
    </thead>
    <tbody>
      <?php foreach ($works as $w): ?>
        <tr>
          <td><img src="/uploads/thumbs/<?= e($w['image']) ?>" alt=""></td>
          <td><?= e($w['title']) ?></td>
          <td><?= e($w['category'] ?? '') ?></td>
          <td><?= e($w['author']) ?></td>
          <td><?= (int) $w['sort_order'] ?></td>
          <td class="row-actions">
            <a class="link" href="/admin/form.php?id=<?= (int) $w['id'] ?>">Редактировать</a>
            <a class="link" href="/admin/delete.php?id=<?= (int) $w['id'] ?>" onclick="return confirm('Удалить эту работу?');">Удалить</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
</body>
</html>
