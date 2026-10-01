<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/upload.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}
csrf_check();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('err', 'ID tidak valid.');
    header('Location: produk.php');
    exit;
}

$stmt = $pdo->prepare("SELECT nama, gambar_utama FROM produk WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    flash_set('err', 'Produk tidak ditemukan.');
    header('Location: produk.php');
    exit;
}

// Hapus semua file gambar (galeri + gambar_utama) dari disk
hapus_semua_gambar_produk($pdo, $id);
hapus_gambar_produk($row['gambar_utama']);

// Hapus baris (galeri & FK ikut cascade)
$pdo->prepare("DELETE FROM produk WHERE id = :id")->execute([':id' => $id]);

admin_log($pdo, 'delete', 'produk', $id, 'Hapus produk: ' . $row['nama']);
flash_set('ok', 'Produk "' . $row['nama'] . '" berhasil dihapus.');

header('Location: produk.php');
exit;