<?php
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/functions.php';

require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id) {
    $stmt = db()->prepare('SELECT image FROM works WHERE id = ?');
    $stmt->execute([$id]);
    $work = $stmt->fetch();

    if ($work) {
        db()->prepare('DELETE FROM works WHERE id = ?')->execute([$id]);
        delete_work_images(
            $work['image'],
            __DIR__ . '/../uploads/works',
            __DIR__ . '/../uploads/thumbs'
        );
        $_SESSION['flash'] = ['type' => 'ok', 'text' => 'Работа удалена'];
    }
}

header('Location: /admin/index.php');
exit;
