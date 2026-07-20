<?php
/**
 * ВАЖНО: этот файл нужен только один раз — чтобы сгенерировать хэш пароля
 * для config.php. После того как скопируете хэш к себе в config.php,
 * ОБЯЗАТЕЛЬНО удалите этот файл с хостинга (или всю папку /admin/tools) —
 * иначе им может воспользоваться кто угодно.
 */

$hash = null;
if (isset($_POST['password']) && $_POST['password'] !== '') {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Генератор хэша пароля</title>
<style>
  body { font-family: monospace; max-width: 600px; margin: 60px auto; padding: 0 20px; }
  input { width: 100%; padding: 8px; margin: 10px 0; }
  .hash { word-break: break-all; background: #f1f1f1; padding: 10px; margin-top: 10px; }
  .warn { color: #b00020; font-weight: bold; }
</style>
</head>
<body>
  <p class="warn">Удалите этот файл после использования!</p>
  <h1>Генератор хэша пароля</h1>
  <form method="post">
    <label>Придумайте пароль для админки:</label>
    <input type="text" name="password" autofocus>
    <button type="submit">Получить хэш</button>
  </form>
  <?php if ($hash): ?>
    <p>Скопируйте эту строку в <code>app/config.php</code>, в константу <code>ADMIN_PASSWORD_HASH</code>:</p>
    <div class="hash"><?= htmlspecialchars($hash, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
</body>
</html>
