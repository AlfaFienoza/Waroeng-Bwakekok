<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/helpers.php';

// =========================================================
// Konfigurasi pagination
// =========================================================
$per_page = 8;

$page            = max(1, (int)($_GET['page'] ?? 1));
$kategori_filter = trim((string)($_GET['kategori'] ?? 'all'));
if ($kategori_filter === '') $kategori_filter = 'all';

// Ambil daftar kategori (untuk chip filter)
$kategori_list = $pdo->query("
    SELECT slug, nama FROM kategori ORDER BY urutan ASC, nama ASC
")->fetchAll();

// =========================================================
// Query produk + filter kategori + pagination
// =========================================================
$where  = '';
$params = [];

if ($kategori_filter !== 'all') {
    $where = " WHERE k.slug = :kat ";
    $params[':kat'] = $kategori_filter;
}

// Hitung total produk
$stmtCount = $pdo->prepare("
    SELECT COUNT(*)
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
    $where
");
$stmtCount->execute($params);
$total_produk = (int)$stmtCount->fetchColumn();

// Hitung total halaman
$total_pages = max(1, (int)ceil($total_produk / $per_page));

// Pastikan $page tidak melebihi total halaman
if ($page > $total_pages) $page = $total_pages;

$offset = ($page - 1) * $per_page;

// Ambil produk untuk halaman ini
$stmt = $pdo->prepare("
    SELECT p.id, p.nama, p.slug, p.deskripsi_singkat, p.harga, p.harga_asli,
           p.gambar_utama, k.slug AS kategori_slug, k.nama AS kategori_nama
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
    $where
    ORDER BY p.is_unggulan DESC, p.id ASC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$produk_list = $stmt->fetchAll();

// Nomor urut awal
$no_awal = $offset + 1;

// Cari nama kategori yang aktif (untuk judul)
$judul_aktif = 'All Menu';
foreach ($kategori_list as $k) {
    if ($k['slug'] === $kategori_filter) {
        $judul_aktif = $k['nama'];
        break;
    }
}

// =========================================================
// Helper URL (mempertahankan filter kategori & page)
// =========================================================
function url_menu(int $page = 1, string $kategori = 'all'): string
{
    $q = [];
    if ($kategori !== '' && $kategori !== 'all') $q['kategori'] = $kategori;
    if ($page > 1) $q['page'] = $page;
    $qs = http_build_query($q);
    return 'menu.php' . ($qs ? '?' . $qs : '');
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu — Waroeng Bwakekok</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/css/menu.css" />
  </head>
  <body>
    <section class="website">

      <!-- ===== NAVBAR ===== -->
      <nav class="navbar">
        <a class="logo" href="beranda.php" aria-label="Waroeng Bwakekok">
          <img src="assets/img/logo.png" alt="Waroeng Bwakekok" />
        </a>
        <div class="nav-menu">
          <a href="beranda.php">Home</a>
          <a href="menu.php" class="active">Menu</a>
          <a href="beranda.php#review">Review</a>
        </div>
        <div class="nav-right">
          <button class="hamburger" aria-label="Buka menu"
                  aria-expanded="false" aria-controls="sidebar">
            <span></span><span></span><span></span>
          </button>
        </div>
      </nav>

      <!-- ===== HEADER MENU ===== -->
      <section class="menu-header">
        <h1>Nasi Padang Menu</h1>

        <div class="categories">
          <a href="<?= e(url_menu(1, 'all')) ?>"
             class="<?= $kategori_filter === 'all' ? 'category-active' : '' ?>">
            All Menu
          </a>
          <?php foreach ($kategori_list as $k): ?>
            <a href="<?= e(url_menu(1, $k['slug'])) ?>"
               class="<?= $kategori_filter === $k['slug'] ? 'category-active' : '' ?>">
              <?= e($k['nama']) ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="hiasan" aria-hidden="true">
          <svg class="leaf" viewBox="0 0 48 32">
            <path d="M2 22C6 8 20 2 46 4 44 22 30 32 12 28L2 30l6-8z" fill="#3E7C4F" />
            <path d="M8 24C18 16 28 11 40 8"
                  stroke="#EBA83C" stroke-width="2.5"
                  fill="none" stroke-linecap="round" />
          </svg>
          <span class="chili">🌶️</span>
          <svg class="sprout" viewBox="0 0 40 44">
            <path d="M20 42V22" stroke="#3E7C4F" stroke-width="4" stroke-linecap="round" />
            <path d="M20 24C8 26 2 16 2 6c12 0 18 6 18 18z" fill="#3E7C4F" />
            <path d="M20 20C20 8 28 2 38 2c0 10-6 18-18 18z" fill="#3E7C4F" />
          </svg>
        </div>
      </section>

      <!-- ===== JUDUL ===== -->
      <div class="menu-title">
        <h2><?= e($judul_aktif) ?></h2>
        <div class="sort">
          Sort by:
          <select>
            <option>Harga</option>
            <option>Nama</option>
            <option>Popularitas</option>
          </select>
        </div>
      </div>

      <!-- ===== DAFTAR MENU ===== -->
      <section class="food-container" id="foodContainer">
        <?php if (empty($produk_list)): ?>
          <p style="grid-column:1/-1;text-align:center;color:#8A7663;font-style:italic;padding:40px 0;">
            Belum ada menu untuk kategori ini.
          </p>
        <?php else: ?>
          <?php foreach ($produk_list as $p): ?>
            <div class="food-card" data-category="<?= e($p['kategori_slug'] ?? '') ?>">
              <img src="<?= e(gambar_produk($p['gambar_utama'])) ?>"
                   alt="<?= e($p['nama']) ?>" loading="lazy" />
              <div class="food-info">
                <h3><?= e($p['nama']) ?></h3>
                <p><?= e($p['deskripsi_singkat']) ?></p>

                <div class="harga">
                  <?= rupiah($p['harga']) ?>
                  <?php if (!empty($p['harga_asli']) && $p['harga_asli'] > $p['harga']): ?>
                    <s><?= rupiah($p['harga_asli']) ?></s>
                  <?php endif; ?>
                </div>

                <div class="card-bottom">
                  <a href="<?= e(url_detail($p['slug'])) ?>" class="btn-detail">
                    Lihat Detail <i class="fa-solid fa-arrow-right"></i>
                  </a>
                  <button class="love" aria-label="Simpan ke favorit">
                    <i class="fa-regular fa-heart"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>

      <!-- ===== PAGINATION (selalu tampil) ===== -->
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="<?= e(url_menu($page - 1, $kategori_filter)) ?>"
             class="page-btn" aria-label="Sebelumnya">
            <i class="fa-solid fa-chevron-left"></i>
          </a>
        <?php else: ?>
          <span class="page-btn page-btn--disabled" aria-disabled="true">
            <i class="fa-solid fa-chevron-left"></i>
          </span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="page page-active"><?= $i ?></span>
          <?php else: ?>
            <a href="<?= e(url_menu($i, $kategori_filter)) ?>" class="page">
              <?= $i ?>
            </a>
          <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <a href="<?= e(url_menu($page + 1, $kategori_filter)) ?>"
             class="page-btn" aria-label="Berikutnya">
            <i class="fa-solid fa-chevron-right"></i>
          </a>
        <?php else: ?>
          <span class="page-btn page-btn--disabled" aria-disabled="true">
            <i class="fa-solid fa-chevron-right"></i>
          </span>
        <?php endif; ?>
      </div>

      <p class="pagination-info">
        Menampilkan <strong><?= $no_awal ?></strong>–<strong><?= min($no_awal + $per_page - 1, $total_produk) ?></strong>
        dari <strong><?= $total_produk ?></strong> produk
      </p>

      <!-- ===== FOOTER ===== -->
      <footer>
        <a href="beranda.php" class="footer-logo" aria-label="Waroeng Bwakekok">
          <img src="assets/img/logo.png" alt="Waroeng Bwakekok" />
        </a>
        <div class="copyright">
          <p>Waroeng Bwakekok — makan enak, gak pakai ribet.</p>
          <small>&copy; <?= date('Y') ?> Waroeng Bwakekok. All rights reserved.</small>
        </div>
        <div class="sosial">
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
      </footer>
    </section>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
    <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
      <div class="sidebar-head">
        <span class="label">
          <img src="assets/img/logo.png" alt="Waroeng Bwakekok" />
        </span>
        <button class="sidebar-close" aria-label="Tutup menu">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <a href="beranda.php">Home</a>
      <a href="menu.php" class="active">Menu</a>
      <a href="beranda.php#review">Review</a>
    </aside>

    <script>
      // ===== Toggle love =====
      document.querySelectorAll(".love").forEach(btn => {
        btn.addEventListener("click", () => {
          const icon = btn.querySelector("i");
          btn.classList.toggle("liked");
          icon.classList.toggle("fa-regular");
          icon.classList.toggle("fa-solid");
        });
      });

      // ===== Hamburger & Sidebar =====
      const hamburger = document.querySelector(".hamburger");
      const sidebar = document.getElementById("sidebar");
      const sidebarOverlay = document.getElementById("sidebarOverlay");
      const sidebarClose = document.querySelector(".sidebar-close");

      function openSidebar() {
        sidebar.classList.add("active");
        sidebarOverlay.classList.add("active");
        hamburger.classList.add("active");
        hamburger.setAttribute("aria-expanded", "true");
        document.body.style.overflow = "hidden";
      }
      function closeSidebar() {
        sidebar.classList.remove("active");
        sidebarOverlay.classList.remove("active");
        hamburger.classList.remove("active");
        hamburger.setAttribute("aria-expanded", "false");
        document.body.style.overflow = "";
      }
      hamburger.addEventListener("click", () =>
        sidebar.classList.contains("active") ? closeSidebar() : openSidebar());
      sidebarOverlay.addEventListener("click", closeSidebar);
      sidebarClose.addEventListener("click", closeSidebar);
      document.addEventListener("keydown", e => {
        if (e.key === "Escape" && sidebar.classList.contains("active")) closeSidebar();
      });
    </script>
  </body>
</html>