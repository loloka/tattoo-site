<?php
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/functions.php';

require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$work = [
    'id' => 0,
    'title' => '',
    'author' => 'Mary Holzer / Мария Гольцер',
    'category' => 'Рисунок',
    'description' => '',
    'image' => '',
    'sort_order' => 0,
];

if ($id) {
    $stmt = db()->prepare('SELECT * FROM works WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if (!$found) {
        header('Location: /admin/index.php');
        exit;
    }
    $work = $found;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Редактировать работу' : 'Добавить работу' ?></title>
<link rel="stylesheet" href="/assets/css/style.css">
<style>
  .admin-wrap { padding: 30px 20px; max-width: 600px; margin: 0 auto; }
  .current-img { width:160px; height:160px; object-fit:cover; margin-bottom:10px; display:block; }
</style>
</head>
<body>
<div class="admin-wrap">
  <h1><?= $id ? 'Редактировать работу' : 'Добавить работу' ?></h1>
  <p><a class="link" href="/admin/index.php">← Назад к списку</a></p>

  <form method="post" action="/admin/save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= (int) $work['id'] ?>">

    <?php if ($work['image']): ?>
      <img class="current-img" src="/uploads/thumbs/<?= e($work['image']) ?>" alt="">
    <?php endif; ?>

    <div class="field">
      <label for="image">Фото работы <?= $id ? '(оставьте пустым, чтобы не менять)' : '*' ?></label>
      <input id="image" name="image" type="file" accept="image/*" <?= $id ? '' : 'required' ?>>
    </div>

    <div class="field">
      <label for="title">Название работы *</label>
      <input id="title" name="title" type="text" value="<?= e($work['title']) ?>" required>
    </div>

    <div class="field">
      <label for="author">Автор</label>
      <input id="author" name="author" type="text" value="<?= e($work['author']) ?>">
    </div>

    <div class="field">
      <label for="category">Тип работы</label>
      <select id="category" name="category">
        <?php foreach (['Татуировка', 'Рисунок'] as $cat): ?>
          <option value="<?= e($cat) ?>" <?= $work['category'] === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="field">
      <label for="description">Описание (не обязательно)</label>
      <textarea id="description" name="description"><?= e($work['description']) ?></textarea>
    </div>

    <div class="field">
      <label for="sort_order">Порядок показа (меньше = раньше)</label>
      <input id="sort_order" name="sort_order" type="number" value="<?= (int) $work['sort_order'] ?>">
    </div>

    <button class="submit-btn" type="submit">Сохранить →</button>
  </form>
</div>
</body>
</html>
