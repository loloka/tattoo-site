<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/functions.php';

ensure_session();

if (is_logged_in()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (hash_equals(ADMIN_USERNAME, $username) && password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: /admin/index.php');
        exit;
    }
    $error = 'Неверный логин или пароль';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Вход в админку</title>
<link rel="stylesheet" href="/assets/css/style.css">
<style>
  .login-box { max-width: 320px; margin: 100px auto; padding: 0 20px; }
  .login-box h1 { font-size: 18px; margin-bottom: 20px; }
</style>
</head>
<body>
<div class="login-box">
  <h1>Вход в админку</h1>
  <?php if ($error): ?><div class="form-msg err"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <div class="field">
      <label for="username">Логин</label>
      <input id="username" name="username" type="text" autofocus required>
    </div>
    <div class="field">
      <label for="password">Пароль</label>
      <input id="password" name="password" type="password" required>
    </div>
    <button class="submit-btn" type="submit">Войти →</button>
  </form>
</div>
</body>
</html>
