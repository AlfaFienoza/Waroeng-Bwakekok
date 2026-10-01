<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ambil (buat jika belum ada) CSRF token untuk session ini.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Hidden input siap pakai untuk form POST.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

/**
 * Verifikasi token dari POST. Hentikan eksekusi kalau tidak cocok.
 */
function csrf_check(): void
{
    $kirim = (string)($_POST['_csrf'] ?? '');
    $simpan = (string)($_SESSION['csrf'] ?? '');
    if ($kirim === '' || $simpan === '' || !hash_equals($simpan, $kirim)) {
        http_response_code(419);
        exit('CSRF token tidak valid. Muat ulang halaman.');
    }
}

/* ---------- flash message ---------- */
function flash_set(string $key, string $pesan): void
{
    $_SESSION['flash'][$key] = $pesan;
}

function flash_get(string $key): ?string
{
    if (!empty($_SESSION['flash'][$key])) {
        $pesan = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $pesan;
    }
    return null;
}