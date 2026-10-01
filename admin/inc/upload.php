<?php
declare(strict_types=1);

/**
 * Proses upload 1 file gambar.
 * @return string|null  Nama file tersimpan, atau null kalau tidak ada file.
 * @throws RuntimeException kalau file tidak valid.
 */
function upload_gambar_produk(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Gagal mengunggah file (kode: ' . $file['error'] . ').');
    }
    if ($file['size'] > 3 * 1024 * 1024) {
        throw new RuntimeException('Ukuran gambar maksimal 3 MB.');
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        throw new RuntimeException('File bukan gambar yang valid.');
    }
    $ext_map = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_GIF  => 'gif',
    ];
    if (!isset($ext_map[$info[2]])) {
        throw new RuntimeException('Format gambar tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.');
    }
    $ext = $ext_map[$info[2]];

    if (!is_dir(UPLOAD_PATH)) {
        if (!@mkdir(UPLOAD_PATH, 0775, true) && !is_dir(UPLOAD_PATH)) {
            throw new RuntimeException('Folder upload tidak bisa dibuat.');
        }
    }

    $nama   = 'p-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $tujuan = UPLOAD_PATH . '/' . $nama;

    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        throw new RuntimeException('Gagal menyimpan file ke folder upload.');
    }

    return $nama;
}

/**
 * Proses upload BANYAK file dari $_FILES['gambar'] (name="gambar[]").
 * @return string[]  Daftar nama file yang tersimpan.
 * @throws RuntimeException kalau ada file tidak valid.
 */
function upload_gambar_produk_multiple(array $files): array
{
    $hasil = [];

    if (!isset($files['name']) || !is_array($files['name'])) {
        return $hasil;
    }

    foreach ($files['name'] as $i => $nama_asli) {
        if ($nama_asli === '' || $nama_asli === null) {
            continue;
        }
        $single = [
            'name'     => $files['name'][$i],
            'type'     => $files['type'][$i]     ?? '',
            'tmp_name' => $files['tmp_name'][$i] ?? '',
            'error'    => $files['error'][$i]    ?? UPLOAD_ERR_NO_FILE,
            'size'     => $files['size'][$i]     ?? 0,
        ];
        $tersimpan = upload_gambar_produk($single);
        if ($tersimpan !== null) {
            $hasil[] = $tersimpan;
        }
    }

    return $hasil;
}

/**
 * Hitung jumlah file yang benar-benar di-upload (yang namanya tidak kosong).
 */
function hitung_file_upload(array $files): int
{
    if (empty($files['name']) || !is_array($files['name'])) {
        return 0;
    }
    $n = 0;
    foreach ($files['name'] as $nama) {
        if ($nama !== '' && $nama !== null) {
            $n++;
        }
    }
    return $n;
}

/**
 * Hapus 1 file gambar dari folder upload (kalau file lokal).
 */
function hapus_gambar_produk(?string $nama): void
{
    if (empty($nama)) return;
    if (preg_match('~^https?://~i', $nama)) return;
    $path = UPLOAD_PATH . '/' . basename($nama);
    if (is_file($path)) {
        @unlink($path);
    }
}

/**
 * Hapus seluruh file galeri milik satu produk dari disk.
 */
function hapus_semua_gambar_produk(PDO $pdo, int $produk_id): void
{
    $stmt = $pdo->prepare("SELECT path FROM produk_gambar WHERE produk_id = :pid");
    $stmt->execute([':pid' => $produk_id]);
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
        hapus_gambar_produk($path);
    }
}