<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Dashboard';
$page_active = 'dashboard';

// Statistik ringkas
$total_produk  = (int)$pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_kategori = (int)$pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
$total_unggulan = (int)$pdo->query("SELECT COUNT(*) FROM produk WHERE is_unggulan = 1")->fetchColumn();

// Aktivitas admin terbaru
$log = $pdo->query("
    SELECT l.aksi, l.tabel, l.record_id, l.keterangan, l.dibuat_pada, a.username
    FROM admin_log l
    LEFT JOIN admin a ON a.id = l.admin_id
    ORDER BY l.dibuat_pada DESC
    LIMIT 8
")->fetchAll();

// Produk terbaru
$terbaru = $pdo->query("
    SELECT p.id, p.nama, p.slug, p.harga, p.gambar_utama, k.nama AS kategori
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
    ORDER BY p.id DESC
    LIMIT 5
")->fetchAll();

require __DIR__ . '/inc/header.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">✦ Ringkasan ✦</p>
    <h1>Selamat datang, <em><?= e($admin['nama_lengkap'] ?: $admin['username']) ?></em></h1>
  </div>
  <a href="produk-tambah.php" class="btn btn--merah">+ Tambah Produk</a>
</div>

<section class="stat-grid">
  <div class="stat">
    <span class="emoji">🍛</span>
    <span class="num"><?= $total_produk ?></span>
    <span class="label">Total Produk</span>
  </div>
  <div class="stat">
    <span class="emoji">📂</span>
    <span class="num"><?= $total_kategori ?></span>
    <span class="label">Kategori</span>
  </div>
  <div class="stat">
    <span class="emoji">⭐</span>
    <span class="num"><?= $total_unggulan ?></span>
    <span class="label">Produk Unggulan</span>
  </div>
  <div class="stat">
    <span class="emoji">👤</span>
    <span class="num"><?= e($admin['role']) ?></span>
    <span class="label">Role Anda</span>
  </div>
</section>

<section class="card">
  <h2>Produk Terbaru</h2>
  <?php if (empty($terbaru)): ?>
    <p class="empty">Belum ada produk.</p>
  <?php else: ?>
    <div class="tabel-wrap" style="margin-top:16px;">
      <table class="tabel">
        <thead>
          <tr>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($terbaru as $p): ?>
            <tr>
              <td><img class="thumb" src="<?= e(gambar_produk($p['gambar_utama'])) ?>" alt="" /></td>
              <td><strong><?= e($p['nama']) ?></strong></td>
              <td><?= e($p['kategori'] ?? '—') ?></td>
              <td><?= rupiah($p['harga']) ?></td>
              <td>
                <a href="produk-edit.php?id=<?= (int)$p['id'] ?>" class="btn btn--sm">Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<section class="card">
  <h2>Aktivitas Terakhir</h2>
  <?php if (empty($log)): ?>
    <p class="empty">Belum ada aktivitas tercatat.</p>
  <?php else: ?>
    <div class="tabel-wrap" style="margin-top:16px;">
      <table class="tabel">
        <thead>
          <tr>
            <th>Waktu</th>
            <th>Admin</th>
            <th>Aksi</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($log as $l): ?>
            <tr>
              <td><?= e(date('d M Y, H:i', strtotime($l['dibuat_pada']))) ?></td>
              <td><?= e($l['username'] ?? '—') ?></td>
              <td><span class="badge badge--hijau"><?= e($l['aksi']) ?></span></td>
              <td><?= e($l['keterangan'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>