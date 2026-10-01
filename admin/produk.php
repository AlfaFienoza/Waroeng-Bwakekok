<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/auth.php';

$page_title  = 'Produk';
$page_active = 'produk';

$q = trim((string)($_GET['q'] ?? ''));

$sql = "
    SELECT p.id, p.nama, p.slug, p.harga, p.harga_asli, p.gambar_utama, p.is_unggulan,
           k.nama AS kategori
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
";
$params = [];
if ($q !== '') {
    $sql .= " WHERE p.nama LIKE :q OR p.slug LIKE :q ";
    $params[':q'] = '%' . $q . '%';
}
$sql .= " ORDER BY p.id DESC ";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftar = $stmt->fetchAll();

require __DIR__ . '/inc/header.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">✦ Katalog ✦</p>
    <h1>Kelola <em>Produk</em></h1>
  </div>
  <a href="produk-tambah.php" class="btn btn--merah">+ Tambah Produk</a>
</div>

<form class="toolbar" method="get">
  <div class="search">
    <input type="text" name="q" placeholder="Cari nama produk…" value="<?= e($q) ?>" />
    <button class="btn btn--sm">Cari</button>
    <?php if ($q !== ''): ?>
      <a href="produk.php" class="btn btn--sm btn--ghost">Reset</a>
    <?php endif; ?>
  </div>
  <p class="help"><?= count($daftar) ?> produk ditemukan.</p>
</form>

<section class="card">
  <?php if (empty($daftar)): ?>
    <p class="empty">Tidak ada produk<?= $q !== '' ? ' untuk pencarian "' . e($q) . '"' : '' ?>.</p>
  <?php else: ?>
    <div class="tabel-wrap">
      <table class="tabel">
        <thead>
          <tr>
            <th>#</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($daftar as $i => $p): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><img class="thumb" src="<?= e(gambar_produk($p['gambar_utama'])) ?>" alt="" /></td>
              <td>
                <strong><?= e($p['nama']) ?></strong><br />
                <small style="color: rgba(43,27,16,.55);"><?= e($p['slug']) ?></small>
              </td>
              <td><?= e($p['kategori'] ?? '—') ?></td>
              <td>
                <?= rupiah($p['harga']) ?>
                <?php if (!empty($p['harga_asli']) && $p['harga_asli'] > $p['harga']): ?>
                  <br><s style="color: rgba(43,27,16,.4); font-size:12px;"><?= rupiah($p['harga_asli']) ?></s>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($p['is_unggulan']): ?>
                  <span class="badge badge--kuning">Unggulan</span>
                <?php else: ?>
                  <span class="badge badge--hijau">Aktif</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="aksi">
                  <a href="../detail-produk.php?slug=<?= urlencode($p['slug']) ?>"
                     target="_blank" rel="noopener"
                     class="btn btn--sm btn--ghost" title="Lihat di situs">Lihat</a>
                  <a href="produk-edit.php?id=<?= (int)$p['id'] ?>"
                     class="btn btn--sm btn--kuning">Edit</a>
                  <form method="post" action="produk-hapus.php" style="display:inline;"
                        onsubmit="return confirm('Hapus produk \'<?= e(addslashes($p['nama'])) ?>\'? Tindakan ini tidak bisa dibatalkan.');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn--sm btn--merah" type="submit">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>