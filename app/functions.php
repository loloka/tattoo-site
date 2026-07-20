<?php
require_once __DIR__ . '/config.php';

/** Экранирование для вывода в HTML */
function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

/** Немецкий перевод категории работы (для двуязычной подписи RU/DE) */
function category_de(string $category): string
{
    static $map = [
        'Татуировка' => 'Tätowierung',
        'Рисунок'    => 'Zeichnung',
    ];
    return $map[$category] ?? $category;
}

/** "категория / Kategorie" — двуязычная подпись категории одной строкой */
function category_bi(string $category): string
{
    if ($category === '') {
        return '';
    }
    return $category . ' / ' . category_de($category);
}

/** "N работ / N Werke" — двуязычное склонение количества работ */
function works_count_bi(int $count): string
{
    $ru = $count === 1 ? 'работа' : 'работ';
    $de = $count === 1 ? 'Werk' : 'Werke';
    return $count . ' ' . $ru . ' / ' . $count . ' ' . $de;
}

/** Запускает сессию, если ещё не запущена */
function ensure_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/** true, если в админку залогинены */
function is_logged_in(): bool
{
    ensure_session();
    return !empty($_SESSION['admin_logged_in']);
}

/** Редиректит на логин, если не залогинены. Вызывать в начале защищённых admin-страниц */
function require_login(): void
{
    ensure_session();
    if (empty($_SESSION['admin_logged_in'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

/**
 * Сохраняет загруженное изображение (из $_FILES['image']) в /uploads/works,
 * делает уменьшенную копию в /uploads/thumbs, возвращает имя файла (без пути)
 * либо null, если файл не пришёл / не картинка.
 */
function save_uploaded_image(array $file, string $uploadsDir, string $thumbsDir, int $thumbMaxSide = 900): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Ошибка загрузки файла (код ' . $file['error'] . ')');
    }

    $allowed = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_GIF  => 'gif',
    ];

    $info = @getimagesize($file['tmp_name']);
    if ($info === false || !isset($allowed[$info[2]])) {
        throw new RuntimeException('Файл не похож на изображение (нужен JPG, PNG, WEBP или GIF)');
    }

    $ext      = $allowed[$info[2]];
    $filename = bin2hex(random_bytes(8)) . '.' . $ext;

    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }
    if (!is_dir($thumbsDir)) {
        mkdir($thumbsDir, 0755, true);
    }

    $destFull = rtrim($uploadsDir, '/') . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destFull)) {
        throw new RuntimeException('Не получилось сохранить файл на сервере');
    }

    make_thumbnail($destFull, rtrim($thumbsDir, '/') . '/' . $filename, $info[2], $thumbMaxSide);

    return $filename;
}

/** Создаёт уменьшенную копию картинки (по длинной стороне) */
function make_thumbnail(string $srcPath, string $destPath, int $imageType, int $maxSide): void
{
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($srcPath);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($srcPath);
            break;
        case IMAGETYPE_WEBP:
            $src = imagecreatefromwebp($srcPath);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($srcPath);
            break;
        default:
            return;
    }
    if (!$src) {
        return;
    }

    $w = imagesx($src);
    $h = imagesy($src);
    $ratio = min(1, $maxSide / max($w, $h));
    $newW = max(1, (int) round($w * $ratio));
    $newH = max(1, (int) round($h * $ratio));

    $dst = imagecreatetruecolor($newW, $newH);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

    switch ($imageType) {
        case IMAGETYPE_PNG:
            imagepng($dst, $destPath, 8);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($dst, $destPath, 85);
            break;
        case IMAGETYPE_GIF:
            imagegif($dst, $destPath);
            break;
        default:
            imagejpeg($dst, $destPath, 85);
    }

    imagedestroy($src);
    imagedestroy($dst);
}

/** Удаляет файл картинки и её превью по имени файла (без пути) */
function delete_work_images(string $filename, string $uploadsDir, string $thumbsDir): void
{
    $full = rtrim($uploadsDir, '/') . '/' . $filename;
    $thumb = rtrim($thumbsDir, '/') . '/' . $filename;
    if (is_file($full)) {
        @unlink($full);
    }
    if (is_file($thumb)) {
        @unlink($thumb);
    }
}

/** Отправляет сообщение в Telegram-бота (для формы "связаться") */
function send_telegram_message(string $text): bool
{
    if (TELEGRAM_BOT_TOKEN === '' || TELEGRAM_CHAT_ID === '') {
        return false;
    }
    $url = 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/sendMessage';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'chat_id'    => TELEGRAM_CHAT_ID,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);
    $result = curl_exec($ch);
    $ok = $result !== false && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
    curl_close($ch);
    return $ok;
}
