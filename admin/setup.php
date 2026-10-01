<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/helpers.php';

// Cek apakah sudah ada admin
$jumlah = (int)$pdo->query("SELECT COUNT(*) FROM admin")->fetchColumn();

$selesai = false;
$pesan   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($jumlah > 0) {
        $pesan = 'Sudah ada akun admin. Setup hanya boleh dijalankan sekali.';
    } else {
        $user = trim((string)($_POST['username'] ?? 'admin'));
        $mail = trim((string)($_POST['email']    ?? 'admin@masakanpadang.test'));
        $pass = (string)($_POST['password'] ?? '');

        if ($user === '' || $mail === '' || strlen($pass) < 6) {
            $pesan = 'Isi semua kolom. Password minimal 6 karakter.';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO admin (username, email, password_hash, nama_lengkap, role)
                VALUES (:u, :e, :h, :n, 'superadmin')
            ");
            $stmt->execute([
                ':u' => $user,
                ':e' => $mail,
                ':h' => $hash,
                ':n' => 'Administrator Utama',
            ]);
            $selesai = true;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Setup Admin — Masakan Padang</title>
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
    <h1>Setup Admin</h1>
    <p class="sub">Buat akun admin pertama. Halaman ini hanya bisa dipakai sekali.</p>

    <?php if ($selesai): ?>
      <div class="alert alert--ok">✓ Akun admin berhasil dibuat.</div>
      <a href="login.php" class="btn btn--merah" style="width:100%;justify-content:center;">Masuk Sekarang</a>
    <?php elseif ($jumlah > 0): ?>
      <div class="alert alert--err">✕ Sudah ada akun admin. Hapus file <code>setup.php</code> demi keamanan.</div>
      <a href="login.php" class="btn" style="width:100%;justify-content:center;">Ke Halaman Login</a>
    <?php else: ?>
      <?php if ($pesan): ?>
        <div class="alert alert--err"><?= e($pesan) ?></div>
      <?php endif; ?>
      <form method="post" autocomplete="off">
        <div class="field">
          <label class="field">Username <span class="req">*</span></label>
          <input type="text" name="username" value="admin" required />
        </div>
        <div class="field">
          <label class="field">Email <span class="req">*</span></label>
          <input type="email" name="email" value="admin@masakanpadang.test" required />
        </div>
        <div class="field">
          <label class="field">Password <span class="req">*</span></label>
          <input type="password" name="password" minlength="6" required />
          <p class="help">Minimal 6 karakter. Ganti setelah login pertama.</p>
        </div>
        <button type="submit" class="btn btn--merah" style="width:100%;justify-content:center;">
          Buat Akun Admin
        </button>
      </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>