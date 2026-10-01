<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/inc/csrf.php';

// Sudah login? Lempar ke dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $user = trim((string)($_POST['username'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');

    if ($user === '' || $pass === '') {
    $err = 'Username dan password wajib diisi.';
} else {
    $stmt = $pdo->prepare("
        SELECT id, username, password_hash, nama_lengkap, role, is_aktif
        FROM admin
        WHERE username = :u1 OR email = :u2
        LIMIT 1
    ");
    $stmt->execute([
        ':u1' => $user,
        ':u2' => $user,
    ]);
    $row = $stmt->fetch();

    if (!$row || !$row['is_aktif']) {
        $err = 'Username atau password salah.';
    } elseif (!password_verify($pass, $row['password_hash'])) {
        $err = 'Username atau password salah.';
    } else {
            // Login sukses
            session_regenerate_id(true);
            $_SESSION['admin_id']       = (int)$row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['admin_nama']     = $row['nama_lengkap'] ?: $row['username'];
            $_SESSION['admin_role']     = $row['role'];

            $pdo->prepare("UPDATE admin SET last_login = NOW() WHERE id = :id")
                ->execute([':id' => $row['id']]);

            admin_log($pdo, 'login', 'admin', (int)$row['id'], 'Login berhasil');
            flash_set('ok', 'Selamat datang, ' . ($row['nama_lengkap'] ?: $row['username']) . '!');
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin — Masakan Padang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-logo">
      <svg viewBox="0 0 48 36" fill="currentColor" aria-hidden="true">
        <path d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z" />
        <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
        <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
        <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
      </svg>
    </div>
    <h1>Panel <em>Admin</em></h1>
    <p class="sub">Masuk untuk mengelola menu Masakan Padang.</p>

    <?php if ($err): ?>
      <div class="alert alert--err">✕ <?= e($err) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off" novalidate>
      <?= csrf_field() ?>
      <div class="field">
        <label class="field">Username / Email</label>
        <input type="text" name="username" required autofocus
               value="<?= e($_POST['username'] ?? '') ?>" />
      </div>
      <div class="field">
        <label class="field">Password</label>
        <input type="password" name="password" required />
      </div>
      <button type="submit" class="btn btn--merah" style="width:100%;justify-content:center;margin-top:10px;">
        Masuk
      </button>
    </form>

    <p class="help" style="text-align:center;margin-top:22px;">
      Belum ada akun? <a href="setup.php" style="color:var(--merah);font-weight:700;">Jalankan setup</a>
    </p>
  </div>
</div>
</body>
</html>