<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/upload.php';

$page_title  = 'Tambah Produk';
$page_active = 'produk';

$kategori_list = $pdo->query("SELECT id, nama FROM kategori ORDER BY urutan, nama")->fetchAll();

$errors = [];
$data = [
    'nama' => '', 'slug' => '', 'deskripsi_singkat' => '', 'deskripsi_lengkap' => '',
    'komposisi' => '', 'harga' => '', 'harga_asli' => '', 'porsi' => '',
    'kalori' => '', 'lemak' => '', 'karbohidrat' => '', 'serat' => '', 'protein' => '',
    'kategori_id' => '', 'is_unggulan' => 0,
];

$MAKS_GAMBAR = 5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    foreach ($data as $k => $v) {
        if (isset($_POST[$k])) {
            $data[$k] = is_string($_POST[$k]) ? trim($_POST[$k]) : $_POST[$k];
        }
    }
    $data['is_unggulan'] = !empty($_POST['is_unggulan']) ? 1 : 0;

    // Validasi dasar
    if ($data['nama'] === '')  $errors[] = 'Nama produk wajib diisi.';
    if ($data['harga'] === '' || !is_numeric($data['harga'])) $errors[] = 'Harga wajib berupa angka.';

    // Slug otomatis
    if ($data['slug'] === '') {
        $data['slug'] = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $data['nama']));
        $data['slug'] = trim($data['slug'], '-');
    }

    if (empty($errors)) {
        $cek = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE slug = :s");
        $cek->execute([':s' => $data['slug']]);
        if ((int)$cek->fetchColumn() > 0) {
            $errors[] = 'Slug sudah dipakai produk lain.';
        }
    }

    // Cek jumlah gambar
    $files        = $_FILES['gambar'] ?? [];
    $jumlah_upload = hitung_file_upload($files);
    if ($jumlah_upload > $MAKS_GAMBAR) {
        $errors[] = "Maksimal {$MAKS_GAMBAR} gambar. Kamu memilih {$jumlah_upload} gambar.";
    }

    // Upload
    $nama_gambar_list = [];
    if (empty($errors) && $jumlah_upload > 0) {
        try {
            $nama_gambar_list = upload_gambar_produk_multiple($files);
        } catch (RuntimeException $ex) {
            // hapus yang sudah terlanjur tersimpan
            foreach ($nama_gambar_list as $nm) hapus_gambar_produk($nm);
            $errors[] = $ex->getMessage();
        }
    }

    // Simpan
    if (empty($errors)) {
        $gambar_utama = $nama_gambar_list[0] ?? null;

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("
                INSERT INTO produk
                  (nama, slug, deskripsi_singkat, deskripsi_lengkap, komposisi,
                   harga, harga_asli, porsi, kalori, lemak, karbohidrat, serat, protein,
                   kategori_id, gambar_utama, is_unggulan)
                VALUES
                  (:nama, :slug, :ds, :dl, :komp,
                   :harga, :harga_asli, :porsi, :kal, :lem, :kar, :ser, :pro,
                   :kid, :gambar, :unggulan)
            ");
            $stmt->execute([
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
                ':gambar'     => $gambar_utama,
                ':unggulan'   => $data['is_unggulan'],
            ]);
            $id = (int)$pdo->lastInsertId();

            // Insert semua gambar ke produk_gambar (urutan = urutan upload)
            if (!empty($nama_gambar_list)) {
                $stmtG = $pdo->prepare("
                    INSERT INTO produk_gambar (produk_id, path, urutan)
                    VALUES (:pid, :path, :urutan)
                ");
                foreach ($nama_gambar_list as $i => $nm) {
                    $stmtG->execute([
                        ':pid'    => $id,
                        ':path'   => $nm,
                        ':urutan' => $i + 1,
                    ]);
                }
            }

            $pdo->commit();
            admin_log($pdo, 'create', 'produk', $id, 'Tambah produk: ' . $data['nama']);
            flash_set('ok', 'Produk "' . $data['nama'] . '" berhasil ditambahkan. '
                . count($nama_gambar_list) . ' gambar tersimpan.');
            header('Location: produk-edit.php?id=' . $id);
            exit;
        } catch (Exception $ex) {
            $pdo->rollBack();
            foreach ($nama_gambar_list as $nm) hapus_gambar_produk($nm);
            $errors[] = 'Gagal menyimpan produk: ' . $ex->getMessage();
        }
    }
}

require __DIR__ . '/inc/header.php';
?>

<div class="page-head">
  <div>
    <p class="eyebrow">✦ Produk Baru ✦</p>
    <h1>Tambah <em>Produk</em></h1>
  </div>
  <a href="produk.php" class="btn btn--ghost">← Kembali</a>
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
      <input type="text" name="slug" value="<?= e($data['slug']) ?>" placeholder="otomatis dari nama" />
      <p class="help">Kosongkan untuk otomatis. Huruf kecil, tanpa spasi.</p>
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
    <div><label class="field">Harga Asli (opsional)</label>
      <input type="number" name="harga_asli" step="500" min="0" value="<?= e($data['harga_asli']) ?>"></div>

    <div><label class="field">Porsi</label>
      <input type="text" name="porsi" placeholder="1 porsi / 300g" value="<?= e($data['porsi']) ?>"></div>

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
      <label class="field">Gambar (maks <?= $MAKS_GAMBAR ?>)</label>
      <input type="file" name="gambar[]" accept="image/*" multiple
             onchange="cekJumlahGambar(this)" />
      <p class="help">
        Format: JPG, PNG, WEBP, GIF. Maks 3 MB per file. Maks <?= $MAKS_GAMBAR ?> gambar.
        <br>Gambar <strong>pertama</strong> otomatis jadi gambar utama produk.
      </p>
      <div id="previewMulti" class="galeri-grid"></div>
    </div>

    <div class="full">
      <label class="field" style="display:flex;align-items:center;gap:10px;text-transform:none;font-size:14px;">
        <input type="checkbox" name="is_unggulan" value="1"
               style="width:auto;box-shadow:none;"
               <?= !empty($data['is_unggulan']) ? 'checked' : '' ?> />
        Tandai sebagai <strong>Produk Unggulan</strong> (tampil di beranda)
      </label>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn--merah">Simpan Produk</button>
    <a href="produk.php" class="btn btn--ghost">Batal</a>
  </div>
</form>

<script>
function cekJumlahGambar(input) {
  const max = <?= $MAKS_GAMBAR ?>;
  const files = Array.from(input.files || []);
  const preview = document.getElementById('previewMulti');
  preview.innerHTML = '';

  if (files.length > max) {
    alert('Maksimal ' + max + ' gambar. Kamu memilih ' + files.length + '.');
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