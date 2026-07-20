<?php
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/index.php');
    exit;
}

$id          = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$title       = trim((string) ($_POST['title'] ?? ''));
$author      = trim((string) ($_POST['author'] ?? '')) ?: 'Mary Holzer / Мария Гольцер';
$category    = trim((string) ($_POST['category'] ?? '')) ?: 'Рисунок';
$description = trim((string) ($_POST['description'] ?? ''));
$sortOrder   = (int) ($_POST['sort_order'] ?? 0);

$uploadsDir = __DIR__ . '/../uploads/works';
$thumbsDir  = __DIR__ . '/../uploads/thumbs';

if ($title === '') {
    $_SESSION['flash'] = ['type' => 'err', 'text' => 'Название обязательно'];
    header('Location: /admin/form.php' . ($id ? '?id=' . $id : ''));
    exit;
}

try {
    $newImage = save_uploaded_image($_FILES['image'] ?? [], $uploadsDir, $thumbsDir);
} catch (Throwable $e) {
    $_SESSION['flash'] = ['type' => 'err', 'text' => $e->getMessage()];
    header('Location: /admin/form.php' . ($id ? '?id=' . $id : ''));
    exit;
}

if ($id) {
    $stmt = db()->prepare('SELECT image FROM works WHERE id = ?');
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        header('Location: /admin/index.php');
        exit;
    }

    $imageToUse = $newImage ?: $existing['image'];

    $upd = db()->prepare('UPDATE works SET title=?, author=?, category=?, description=?, image=?, sort_order=? WHERE id=?');
    $upd->execute([$title, $author, $category, $description, $imageToUse, $sortOrder, $id]);

    if ($newImage) {
        delete_work_images($existing['image'], $uploadsDir, $thumbsDir);
    }

    $_SESSION['flash'] = ['type' => 'ok', 'text' => 'Работа обновлена'];
} else {
    if (!$newImage) {
        $_SESSION['flash'] = ['type' => 'err', 'text' => 'Нужно загрузить фото'];
        header('Location: /admin/form.php');
        exit;
    }

    $ins = db()->prepare('INSERT INTO works (title, author, category, description, image, sort_order) VALUES (?,?,?,?,?,?)');
    $ins->execute([$title, $author, $category, $description, $newImage, $sortOrder]);

    $_SESSION['flash'] = ['type' => 'ok', 'text' => 'Работа добавлена'];
}

header('Location: /admin/index.php');
exit;
