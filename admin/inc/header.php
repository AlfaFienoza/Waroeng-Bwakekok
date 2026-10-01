<?php
declare(strict_types=1);
// Variabel yang diharapkan: $page_title (string), $page_active (string)
$page_title  = $page_title  ?? 'Admin';
$page_active = $page_active ?? '';
$admin_nama  = $admin['nama_lengkap'] ?? $admin['username'] ?? 'Admin';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($page_title) ?> — Admin Masakan Padang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body>

<header class="topbar">
  <div class="topbar-dalam">
    <a class="brand" href="dashboard.php">
      <svg viewBox="0 0 48 36" fill="currentColor" aria-hidden="true">
        <path d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z" />
        <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
        <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
        <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
      </svg>
      <span>
        Masakan<b>Padang</b>
        <small>Panel Admin</small>
      </span>
    </a>

    <nav class="menu-admin">
      <a href="dashboard.php" class="<?= $page_active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
      <a href="produk.php"    class="<?= $page_active === 'produk'    ? 'active' : '' ?>">Produk</a>
      <a href="../beranda.php" target="_blank" rel="noopener">Lihat Situs</a>
      <span class="user-chip" title="<?= e($admin['email'] ?? '') ?>">
        👤 <?= e($admin_nama) ?>
      </span>
      <a href="logout.php" class="btn btn--sm btn--ghost">Keluar</a>
    </nav>
  </div>
</header>

<main class="wrap">
<?php
$flash_ok  = flash_get('ok');
$flash_err = flash_get('err');
$flash_inf = flash_get('info');
if ($flash_ok)  echo '<div class="alert alert--ok">✓ '  . e($flash_ok)  . '</div>';
if ($flash_err) echo '<div class="alert alert--err">✕ ' . e($flash_err) . '</div>';
if ($flash_inf) echo '<div class="alert alert--info">ℹ ' . e($flash_inf) . '</div>';
?>