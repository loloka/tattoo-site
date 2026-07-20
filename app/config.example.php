<?php
/**
 * Скопируй этот файл в config.php (в той же папке /app) и заполни своими данными.
 * config.php НЕ должен попадать в открытый доступ и в git — там пароли.
 */

// ==== БАЗА ДАННЫХ (MySQL) ====
// Данные выдаёт хостинг в панели управления при создании базы данных.
define('DB_HOST', 'localhost');
define('DB_NAME', 'имя_базы');
define('DB_USER', 'пользователь_бд');
define('DB_PASS', 'пароль_бд');

// ==== АДМИНКА ====
// Логин обычный текстом. Пароль — НЕ обычный текст, а хэш.
// Хэш можно сгенерировать через /admin/tools/hash.php (см. README) — после
// получения хэша обязательно удали этот файл-инструмент с хостинга.
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$вставь.сюда.хэш.из.hash.php');

// ==== TELEGRAM (для формы "связаться") ====
// 1. Напиши @BotFather в Telegram, команда /newbot — получишь TELEGRAM_BOT_TOKEN.
// 2. Напиши что-нибудь своему новому боту (просто "привет").
// 3. Открой в браузере: https://api.telegram.org/bot<ТВОЙ_ТОКЕН>/getUpdates
//    В ответе будет "chat":{"id": ЦИФРЫ, ...} — это и есть TELEGRAM_CHAT_ID.
define('TELEGRAM_BOT_TOKEN', '000000000:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA');
define('TELEGRAM_CHAT_ID', '000000000');

// ==== ОФОРМЛЕНИЕ И ТЕКСТЫ САЙТА ====
// Сайт двуязычный (EN/DE, по макету mashasite.psd) — био выводится абзацами,
// перенос строки внутри строки PHP пиши как \n (станет отдельным абзацем).
define('SITE_OWNER_FIRST', 'Имя');       // левая часть большого заголовка
define('SITE_OWNER_LAST', 'Фамилия');    // правая часть большого заголовка
define('SITE_SUBTITLE', 'Tattoo Artworks');
define('SITE_SUBTITLE_EYEBROW', 'Personal Art Collection');
define('SITE_TELEGRAM', '@username');           // отображается в шапке/футере
define('SITE_TELEGRAM_URL', 'https://t.me/username');

define('SITE_NAV_HOME', 'Гл. страница');
define('SITE_NAV_GALLERY', 'Галерея');
define('SITE_NAV_CONTACT', 'Контакты');

define('SITE_BIO_LABEL', 'About me');
define('SITE_BIO_LABEL_DE', 'Über mich');
define('SITE_BIO', "Пара предложений о себе.\nВторой абзац био.");
define('SITE_BIO_DE', "Ein paar Sätze über dich (auf Deutsch).\nZweiter Absatz.");

define('SITE_CONTACT_LABEL', 'Contact me: E mail/Telegram/Fill out the form below');
define('SITE_CONTACT_LABEL_DE', 'Kontakt: E Mail/Telegram/Das Formular unten ausfüllen');

define('GALLERY_EYEBROW', 'Selected works: Section 01');
define('GALLERY_EYEBROW_DE', 'Ausgewählte Arbeiten: Abschnitt 01');
define('GALLERY_TITLE', 'Gallery');
define('GALLERY_TITLE_DE', 'Galerie');
define('GALLERY_EMPTY', 'Works will appear here soon.');
define('GALLERY_EMPTY_DE', 'Werke erscheinen hier bald.');

define('CONTACT_FORM_TITLE', 'Get in touch');
define('CONTACT_FORM_TITLE_DE', 'Kontakt');
define('CONTACT_FORM_INTRO', 'Fill out the form — the message will be sent directly to Telegram');
define('CONTACT_FORM_INTRO_DE', 'Das Formular unten ausfüllen — die Nachricht wird direkt an Telegram gesendet');

define('SITE_TG_CHANNEL_NOTE', 'Telegram channel where I share my everyday sketches');
define('SITE_TG_CHANNEL_NOTE_DE', 'Telegram-Kanal, bei dem ich meine alltäglichen Skizzen teile');

// Часовой пояс для дат в админке
date_default_timezone_set('Asia/Novosibirsk');
