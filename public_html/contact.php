<?php
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';

header('Content-Type: application/json; charset=utf-8');

function respond(bool $ok, string $message): void
{
    echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, 'Метод не поддерживается');
}

$name    = trim((string) ($_POST['name'] ?? ''));
$topic   = trim((string) ($_POST['topic'] ?? ''));
$comment = trim((string) ($_POST['comment'] ?? ''));
$contact = trim((string) ($_POST['contact'] ?? ''));

if ($name === '' || $topic === '' || $contact === '') {
    respond(false, 'Заполните обязательные поля: имя, тема, контакт.');
}

// простая защита от накрутки — режем слишком длинные значения
$name    = mb_substr($name, 0, 200);
$topic   = mb_substr($topic, 0, 200);
$comment = mb_substr($comment, 0, 2000);
$contact = mb_substr($contact, 0, 200);

$text = "<b>Новая заявка с сайта</b>\n"
    . "Имя: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "\n"
    . "Тема: " . htmlspecialchars($topic, ENT_QUOTES, 'UTF-8') . "\n"
    . "Контакт: " . htmlspecialchars($contact, ENT_QUOTES, 'UTF-8')
    . ($comment !== '' ? "\nКомментарий: " . htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') : '');

$sent = send_telegram_message($text);

if (!$sent) {
    // не роняем форму для пользователя, если телеграм недоступен — просто лог
    error_log('Telegram notify failed for contact form submission from ' . $contact);
}

respond(true, 'Спасибо! Сообщение отправлено.');
