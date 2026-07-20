<?php
require_once __DIR__ . '/../../app/functions.php';
ensure_session();
$_SESSION = [];
session_destroy();
header('Location: /admin/login.php');
exit;
