<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/csrf.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin = admin_saat_ini($pdo);
if (!$admin) {
    session_destroy();
    header('Location: login.php');
    exit;
}