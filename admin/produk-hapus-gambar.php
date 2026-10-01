<?php
declare(strict_types=1);

require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}
csrf_check();

$gid    = (int)($_POST['id']     ?? 0);
$pid    = (int)($_POST['produk'] ?? 0);

if ($gid <= 0 || $pid <= 0) {
    flash_set('err', 'Data tidak valid.');
    header('Location: produk.php');
    exit;
}

// Ambil row
$stmt = $pdo->prepare("
    SELECT id, path FROM produk_gambar
    WHERE id = :id AND produk_id = :pid
    LIMIT 1
");
$stmt->execute([':id' => $gid, ':pid' => $pid]);
$row = $stmt->fetch();

if (!$row) {
    flash_set('err', 'Gambar tidak ditemukan.');
    header('Location: produk-edit.php?id=' . $pid);
    exit;
}

$pdo->beginTransaction();
try {
    // Hapus file + row
    hapus_gambar_produk($row['path']);
    $pdo->prepare("DELETE FROM produk_gambar WHERE id = :id")
        ->execute([':id' => $gid]);

    // Sync gambar_utama = gambar pertama tersisa
    $stmtF = $pdo->prepare("
        SELECT path FROM produk_gambar
        WHERE produk_id = :pid
        ORDER BY urutan ASC, id ASC
        LIMIT 1
    ");
    $stmtF->execute([':pid' => $pid]);
    $first = $stmtF->fetchColumn();

    $pdo->prepare("UPDATE produk SET gambar_utama = :g WHERE id = :id")
        ->execute([':g' => $first ?: null, ':id' => $pid]);

    $pdo->commit();
    admin_log($pdo, 'update', 'produk', $pid, 'Hapus 1 gambar galeri');
    flash_set('ok', 'Gambar berhasil dihapus.');
} catch (Exception $ex) {
    $pdo->rollBack();
    flash_set('err', 'Gagal menghapus: ' . $ex->getMessage());
}

header('Location: produk-edit.php?id=' . $pid);
exit;