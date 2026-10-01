<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/upload.php';

$page_title  = 'Edit Produk';
$page_active = 'produk';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    flash_set('err', 'ID produk tidak valid.');
    header('Location: produk.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch();

if (!$data) {
    flash_set('err', 'Produk tidak ditemukan.');
    header('Location: produk.php');
    exit;
}

$kategori_list = $pdo->query("SELECT id, nama FROM kategori ORDER BY urutan, nama")->fetchAll();
$errors        = [];
$MAKS_GAMBAR   = 5;

// Ambil galeri saat ini
$stmtG = $pdo->prepare("
    SELECT id, path, urutan
    FROM produk_gambar
    WHERE produk_id = :pid
    ORDER BY urutan ASC, id ASC
");
$stmtG->execute([':pid' => $id]);
$galeri = $stmtG->fetchAll();

$jumlah_galeri = count($galeri);
$slot_tersisa  = max(0, $MAKS_GAMBAR - $jumlah_galeri);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    // Ambil input teks
    $fields = ['nama','slug','deskripsi_singkat','deskripsi_lengkap','komposisi',
               'harga','harga_asli','porsi','kalori','lemak','karbohidrat','serat',
               'protein','kategori_id'];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) {
            $data[$f] = is_string($_POST[$f]) ? trim($_POST[$f]) : $_POST[$f];
        }
    }
    $data['is_unggulan'] = !empty($_POST['is_unggulan']) ? 1 : 0;

    if ($data['nama'] === '')  $errors[] = 'Nama produk wajib diisi.';
    if ($data['harga'] === '' || !is_numeric($data['harga'])) $errors[] = 'Harga wajib berupa angka.';

    if ($data['slug'] === '') {
        $data['slug'] = strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string)$data['nama']));
        $data['slug'] = trim($data['slug'], '-');
    }

    if (empty($errors)) {
        $cek = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE slug = :s AND id <> :id");
        $cek->execute([':s' => $data['slug'], ':id' => $id]);
        if ((int)$cek->fetchColumn() > 0) {
            $errors[] = 'Slug sudah dipakai produk lain.';
        }
    }

    // Cek file baru
    $files        = $_FILES['gambar'] ?? [];
    $jumlah_baru  = hitung_file_upload($files);
    if (($jumlah_galeri + $jumlah_baru) > $MAKS_GAMBAR) {
        $errors[] = "Total gambar tidak boleh lebih dari {$MAKS_GAMBAR}. "
                  . "Saat ini ada {$jumlah_galeri}, kamu menambah {$jumlah_baru}.";
    }

    // Upload gambar baru
    $nama_baru_list = [];
    if (empty($errors) && $jumlah_baru > 0) {
        try {
            $nama_baru_list = upload_gambar_produk_multiple($files);
        } catch (RuntimeException $ex) {
            foreach ($nama_baru_list as $nm) hapus_gambar_produk($nm);
            $errors[] = $ex->getMessage();
        }
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            // Update produk
            $upd = $pdo->prepare("
                UPDATE produk SET
                  nama = :nama, slug = :slug,
                  deskripsi_singkat = :ds, deskripsi_lengkap = :dl, komposisi = :komp,
                  harga = :harga, harga_asli = :harga_asli, porsi = :porsi,
                  kalori = :kal, lemak = :lem, karbohidrat = :kar, serat = :ser, protein = :pro,
                  kategori_id = :kid, is_unggulan = :unggulan
                WHERE id = :id
            ");
            $upd->execute([
                ':nama'       => $data['nama'],
                ':slug'       => $data['slug'],
                ':ds'         => $data['deskripsi_singkat'] ?: null,
                ':dl'         => $data['deskripsi_lengkap'] ?: null,
                ':komp'       => $data['komposisi']         ?: null,
                ':harga'      => (float)$data['harga'],
                ':harga_asli' => ($data['harga_asli'] !== '' ? (float)$data['harga_asli'] : null),
                ':porsi'      => $data['porsi'] ?: null,
                ':kal'        => ($data['kalori']      !== '' ? (int)$data['kalori']      : null),
                ':lem'        => ($data['lemak']       !== '' ? (int)$data['lemak']       : null),
                ':kar'        => ($data['karbohidrat'] !== '' ? (int)$data['karbohidrat'] : null),
                ':ser'        => ($data['serat']       !== '' ? (int)$data['serat']       : null),
                ':pro'        => ($data['protein']     !== '' ? (int)$data['protein']     : null),
                ':kid'        => ($data['kategori_id'] !== '' ? (int)$data['kategori_id'] : null),
                ':unggulan'   => $data['is_unggulan'],
                ':id'         => $id,
            ]);

            // Insert gambar baru ke produk_gambar
            if (!empty($nama_baru_list)) {
                // urutan lanjut dari yang terakhir
                $maxUrut = (int)$pdo->query(
                    "SELECT COALESCE(MAX(urutan), 0) FROM produk_gambar WHERE produk_id = {$id}"
                )->fetchColumn();

                $stmtI = $pdo->prepare("
                    INSERT INTO produk_gambar (produk_id, path, urutan)
                    VALUES (:pid, :path, :urutan)
                ");
                foreach ($nama_baru_list as $i => $nm) {
                    $stmtI->execute([
                        ':pid'    => $id,
                        ':path'   => $nm,
                        ':urutan' => $maxUrut + $i + 1,
                    ]);
                }
            }

            // Sinkronkan gambar_utama = gambar pertama di galeri
            $stmtFirst = $pdo->prepare("
                SELECT path FROM produk_gambar
                WHERE produk_id = :pid
                ORDER BY urutan ASC, id ASC
                LIMIT 1
            ");
            $stmtFirst->execute([':pid' => $id]);
            $first = $stmtFirst->fetchColumn();

            $pdo->prepare("UPDATE produk SET gambar_utama = :g WHERE id = :id")
                ->execute([':g' => $first ?: null, ':id' => $id]);

            $pdo->commit();
            admin_log($pdo, 'update', 'produk', $id, 'Update produk: ' . $data['nama']);
            flash_set('ok', 'Produk berhasil diperbarui.');
            header('Location: produk-edit.php?id=' . $id);
            exit;
        } catch (Exception $ex) {
            $pdo->rollBack();
            foreach ($nama_baru_list as $nm) hapus_gambar_produk($nm);
            $errors[] = 'Gagal menyimpan: ' . $ex->getMessage();
        }
    }
}

// Refresh galeri + slot info (kalau ada error, biar tetap akurat)
$stmtG->execute([':pid' => $id]);
$galeri        = $stmtG->fetchAll();
$jumlah_galeri = count($galeri);
$slot_tersisa  = max(0, $MAKS_GAMBAR - $jumlah_galeri);

require __DIR__ . '/inc/header.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">✦ Edit ✦</p>
    <h1>Edit <em><?= e($data['nama']) ?></em></h1>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <a href="produk.php" class="btn btn--ghost">← Daftar Produk</a>
    <a href="../detail-produk.php?slug=<?= urlencode($data['slug']) ?>"
       target="_blank" rel="noopener" class="btn">Lihat di Situs</a>
  </div>
</div>

<?php foreach ($errors as $err): ?>
  <div class="alert alert--err">✕ <?= e($err) ?></div>
<?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>

  <div class="form-grid">
    <div class="full">
      <label class="field">Nama Produk <span class="req">*</span></label>
      <input type="text" name="nama" required value="<?= e($data['nama']) ?>" />
    </div>

    <div class="full">
      <label class="field">Slug (URL)</label>
      <input type="text" name="slug" value="<?= e($data['slug']) ?>" />
    </div>

    <div class="full">
      <label class="field">Deskripsi Singkat</label>
      <input type="text" name="deskripsi_singkat" maxlength="255"
             value="<?= e($data['deskripsi_singkat']) ?>" />
    </div>

    <div class="full">
      <label class="field">Deskripsi Lengkap</label>
      <textarea name="deskripsi_lengkap" rows="4"><?= e($data['deskripsi_lengkap']) ?></textarea>
    </div>

    <div class="full">
      <label class="field">Komposisi</label>
      <textarea name="komposisi" rows="3"><?= e($data['komposisi']) ?></textarea>
    </div>

    <div><label class="field">Harga (Rp) <span class="req">*</span></label>
      <input type="number" name="harga" step="500" min="0" required value="<?= e($data['harga']) ?>"></div>

    <div><label class="field">Harga Asli</label>
      <input type="number" name="harga_asli" step="500" min="0" value="<?= e($data['harga_asli']) ?>"></div>

    <div><label class="field">Porsi</label>
      <input type="text" name="porsi" value="<?= e($data['porsi']) ?>"></div>

    <div>
      <label class="field">Kategori</label>
      <select name="kategori_id">
        <option value="">— Pilih kategori —</option>
        <?php foreach ($kategori_list as $k): ?>
          <option value="<?= (int)$k['id'] ?>"
            <?= (string)$data['kategori_id'] === (string)$k['id'] ? 'selected' : '' ?>>
            <?= e($k['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div><label class="field">Kalori (kcal)</label>
      <input type="number" name="kalori" min="0" value="<?= e($data['kalori']) ?>"></div>
    <div><label class="field">Total Lemak (g)</label>
      <input type="number" name="lemak" min="0" value="<?= e($data['lemak']) ?>"></div>
    <div><label class="field">Karbohidrat (g)</label>
      <input type="number" name="karbohidrat" min="0" value="<?= e($data['karbohidrat']) ?>"></div>
    <div><label class="field">Serat (g)</label>
      <input type="number" name="serat" min="0" value="<?= e($data['serat']) ?>"></div>
    <div><label class="field">Protein (g)</label>
      <input type="number" name="protein" min="0" value="<?= e($data['protein']) ?>"></div>

    <div class="full">
      <label class="field">Gambar Saat Ini (<?= $jumlah_galeri ?>/<?= $MAKS_GAMBAR ?>)</label>
      <?php if (empty($galeri)): ?>
        <p class="help">Belum ada gambar. Upload di bawah.</p>
      <?php else: ?>
        <div class="galeri-grid">
          <?php foreach ($galeri as $i => $g): ?>
            <div class="galeri-item">
              <img src="<?= e(gambar_produk($g['path'])) ?>" alt="" />
              <?php if ($i === 0): ?>
                <span class="badge-utama">Utama</span>
              <?php endif; ?>
              <form method="post" action="produk-hapus-gambar.php"
                    onsubmit="return confirm('Hapus gambar ini?');"
                    style="display:inline;">
                <?= csrf_field() ?>
                <input type="hidden" name="id"     value="<?= (int)$g['id'] ?>">
                <input type="hidden" name="produk" value="<?= $id ?>">
                <button class="hapus" type="submit" aria-label="Hapus gambar">✕</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="full">
      <label class="field">
        Tambah Gambar
        <span class="help" style="margin-left:6px;text-transform:none;">
          (sisa slot: <?= $slot_tersisa ?> dari <?= $MAKS_GAMBAR ?>)
        </span>
      </label>
      <?php if ($slot_tersisa > 0): ?>
        <input type="file" name="gambar[]" accept="image/*" multiple
               onchange="cekJumlahGambar(this, <?= $slot_tersisa ?>)" />
        <p class="help">
          Format: JPG, PNG, WEBP, GIF. Maks 3 MB per file.
          Gambar baru akan ditambahkan di urutan paling akhir.
        </p>
        <div id="previewMulti" class="galeri-grid"></div>
      <?php else: ?>
        <p class="help" style="color:var(--merah);font-weight:700;">
          Slot gambar sudah penuh. Hapus salah satu gambar dulu untuk menambah yang baru.
        </p>
      <?php endif; ?>
    </div>

    <div class="full">
      <label class="field" style="display:flex;align-items:center;gap:10px;text-transform:none;font-size:14px;">
        <input type="checkbox" name="is_unggulan" value="1"
               style="width:auto;box-shadow:none;"
               <?= !empty($data['is_unggulan']) ? 'checked' : '' ?> />
        Tandai sebagai <strong>Produk Unggulan</strong>
      </label>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn--merah">Simpan Perubahan</button>
    <a href="produk.php" class="btn btn--ghost">Batal</a>
  </div>
</form>

<script>
function cekJumlahGambar(input, max) {
  const files = Array.from(input.files || []);
  const preview = document.getElementById('previewMulti');
  preview.innerHTML = '';

  if (files.length > max) {
    alert('Maksimal ' + max + ' gambar baru. Kamu memilih ' + files.length + '.');
    input.value = '';
    return;
  }

  files.forEach(function (f) {
    const url = URL.createObjectURL(f);
    const box = document.createElement('div');
    box.className = 'galeri-item';
    box.innerHTML = '<img src="' + url + '" alt="">';
    preview.appendChild(box);
  });
}
</script>

<?php require __DIR__ . '/inc/footer.php'; ?>