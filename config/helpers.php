<?php
declare(strict_types=1);

/**
 * Escape output HTML.
 */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Format harga ke Rupiah.
 */
function rupiah($angka): string
{
    return 'Rp' . number_format((float)$angka, 0, ',', '.');
}

/**
 * Resolve path gambar produk.
 * - Kalau sudah berupa URL (http/https), pakai langsung.
 * - Kalau bukan, anggap file lokal di assets/img/produk/.
 */
function gambar_produk(?string $path): string
{
    $path = trim((string)$path);
    if ($path === '') {
        return 'assets/img/placeholder.jpg';
    }
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }
    return 'assets/img/produk/' . ltrim($path, '/');
}

/**
 * URL halaman detail produk.
 */
function url_detail(string $slug): string
{
    return 'detail-produk.php?slug=' . urlencode($slug);
}

/* =========================================================
   FUNGSI ADMIN
   ========================================================= */

/**
 * Cek apakah user sedang login sebagai admin.
 */
function admin_login(): bool
{
    return !empty($_SESSION['admin_id']);
}

/**
 * Ambil data admin yang sedang login (null kalau belum login).
 */
function admin_saat_ini(PDO $pdo): ?array
{
    if (!admin_login()) {
        return null;
    }

    $stmt = $pdo->prepare("
        SELECT id, username, email, nama_lengkap, role
        FROM admin
        WHERE id = :id AND is_aktif = 1
        LIMIT 1
    ");
    $stmt->execute([':id' => (int)$_SESSION['admin_id']]);
    $row = $stmt->fetch();

    return $row ?: null;
}

/**
 * Catat aktivitas admin ke tabel admin_log.
 */
function admin_log(
    PDO $pdo,
    string $aksi,
    ?string $tabel = null,
    ?int $record_id = null,
    ?string $ket = null
): void {
    if (!admin_login()) {
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO admin_log (admin_id, aksi, tabel, record_id, keterangan, ip_address)
        VALUES (:aid, :aksi, :tabel, :rid, :ket, :ip)
    ");
    $stmt->execute([
        ':aid'   => (int)$_SESSION['admin_id'],
        ':aksi'  => $aksi,
        ':tabel' => $tabel,
        ':rid'   => $record_id,
        ':ket'   => $ket,
        ':ip'    => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
}

/**
 * Format tanggal jadi teks relatif ("3 hari lalu", "2 minggu lalu", dst).
 */
function tanggal_relatif(?string $datetime): string
{
    if (!$datetime) return '';

    $ts   = strtotime($datetime);
    $now  = time();
    $diff = $now - $ts;

    if ($diff < 60)         return 'Baru saja';
    if ($diff < 3600)       return floor($diff / 60)     . ' menit lalu';
    if ($diff < 86400)      return floor($diff / 3600)   . ' jam lalu';
    if ($diff < 2592000)    return floor($diff / 86400)  . ' hari lalu';
    if ($diff < 31536000)   return floor($diff / 2592000) . ' bulan lalu';
    return floor($diff / 31536000) . ' tahun lalu';
}