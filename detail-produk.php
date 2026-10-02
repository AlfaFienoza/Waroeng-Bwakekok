<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/helpers.php';

// ---------- Ambil parameter slug ----------
$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
if ($slug === '') {
    header('Location: menu.php');
    exit;
}

// ---------- Query produk utama ----------
$stmt = $pdo->prepare("
    SELECT p.*, k.slug AS kategori_slug, k.nama AS kategori_nama
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
    WHERE p.slug = :slug
    LIMIT 1
");
$stmt->execute([':slug' => $slug]);
$produk = $stmt->fetch();

if (!$produk) {
    header('Location: menu.php');
    exit;
}

// ---------- Ambil galeri gambar ----------
$stmtG = $pdo->prepare("
    SELECT path FROM produk_gambar
    WHERE produk_id = :pid
    ORDER BY urutan ASC, id ASC
");
$stmtG->execute([':pid' => $produk['id']]);
$galeri = $stmtG->fetchAll(PDO::FETCH_COLUMN);

if (empty($galeri)) {
    $galeri = [$produk['gambar_utama']];
}

// ---------- Ambil produk serupa ----------
$stmtR = $pdo->prepare("
    SELECT p.nama, p.slug, p.deskripsi_singkat, p.gambar_utama
    FROM produk p
    WHERE p.kategori_id = :kid AND p.id <> :pid
    ORDER BY p.is_unggulan DESC, p.id ASC
    LIMIT 4
");
$stmtR->execute([
    ':kid' => $produk['kategori_id'],
    ':pid' => $produk['id'],
]);
$serupa = $stmtR->fetchAll();

$gambar_utama_url = gambar_produk($galeri[0]);
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($produk['nama']) ?> — Waroeng Bwakekok</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/css/detail-produk.css" />
  </head>
  <body>
    <div class="page">

      <!-- ===== NAVBAR ===== -->
      <header class="navbar">
        <a class="logo" href="beranda.php" aria-label="Waroeng Bwakekok">
          <img src="assets/img/logo.png" alt="Waroeng Bwakekok" />
        </a>
        <nav class="nav-links">
          <a href="beranda.php">Home</a>
          <a href="menu.php">Menu</a>
          <a href="beranda.php#review">Review</a>
        </nav>
        <div class="nav-right">
          <button class="hamburger" aria-label="Buka menu"
                  aria-expanded="false" aria-controls="sidebar">
            <span></span><span></span><span></span>
          </button>
        </div>
      </header>

      <!-- ===== DETAIL ===== -->
      <section id="detail" class="detail-section">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <a href="beranda.php">Home</a>
          <i class="fa-solid fa-chevron-right"></i>
          <a href="menu.php">Menu</a>
          <i class="fa-solid fa-chevron-right"></i>
          <?php if (!empty($produk['kategori_nama'])): ?>
            <a href="menu.php?kategori=<?= e($produk['kategori_slug']) ?>">
              <?= e($produk['kategori_nama']) ?>
            </a>
            <i class="fa-solid fa-chevron-right"></i>
          <?php endif; ?>
          <span class="current"><?= e($produk['nama']) ?></span>
        </nav>

        <div class="product-detail">

          <!-- Galeri -->
          <div class="product-gallery">
            <div class="main-image">
              <img id="mainImage"
                   src="<?= e($gambar_utama_url) ?>"
                   alt="<?= e($produk['nama']) ?>" />
            </div>
            <div class="thumbnail-list">
              <?php foreach ($galeri as $i => $g): ?>
                <img
                  src="<?= e(gambar_produk($g)) ?>"
                  alt="<?= e($produk['nama']) ?> - gambar <?= $i + 1 ?>"
                  class="<?= $i === 0 ? 'active' : '' ?>" />
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Info -->
          <div class="product-info">
            <h1 class="product-title"><?= e($produk['nama']) ?></h1>

            <p class="product-description">
              <?= e($produk['deskripsi_lengkap'] ?? $produk['deskripsi_singkat']) ?>
            </p>

            <div class="price-container">
              <span class="price-current"><?= rupiah($produk['harga']) ?></span>
              <?php if (!empty($produk['harga_asli']) && $produk['harga_asli'] > $produk['harga']): ?>
                <span class="price-original"><?= rupiah($produk['harga_asli']) ?></span>
              <?php endif; ?>
              <?php if (!empty($produk['porsi'])): ?>
                <span class="portion-info"><?= e($produk['porsi']) ?></span>
              <?php endif; ?>
            </div>

            <?php if (!empty($produk['komposisi'])): ?>
              <div class="section-block">
                <h3>Komposisi:</h3>
                <p class="text-muted"><?= e($produk['komposisi']) ?></p>
              </div>
            <?php endif; ?>

            <?php
              $gizi = [
                'Takaran saji'      => $produk['porsi'],
                'Kalori'            => $produk['kalori']      ? $produk['kalori'] . ' kcal' : null,
                'Total Lemak'       => $produk['lemak']       ? $produk['lemak'] . 'g'       : null,
                'Total Karbohidrat' => $produk['karbohidrat'] ? $produk['karbohidrat'] . 'g' : null,
                'Serat pangan'      => $produk['serat']       ? $produk['serat'] . 'g'       : null,
                'Protein'           => $produk['protein']     ? $produk['protein'] . 'g'     : null,
              ];
              $gizi = array_filter($gizi, fn($v) => $v !== null && $v !== '');
            ?>
            <?php if (!empty($gizi)): ?>
              <div class="section-block">
                <h3>Informasi Nilai Gizi:</h3>
                <div class="nutrition-grid">
                  <?php foreach ($gizi as $label => $nilai): ?>
                    <div class="nutrition-item">
                      <span><?= e($label) ?></span>
                      <span><?= e((string)$nilai) ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="action-row">
              <button class="btn-primary">
                Pesan Sekarang <i class="fa-solid fa-arrow-right"></i>
              </button>
              <button class="btn-love" aria-label="Simpan ke favorit">
                <i class="fa-regular fa-heart"></i>
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- ===== PRODUK SERUPA ===== -->
      <?php if (!empty($serupa)): ?>
        <section class="related-section">
          <h2>Anda mungkin juga menyukai</h2>
          <div class="related-grid">
            <?php foreach ($serupa as $s): ?>
              <a href="<?= e(url_detail($s['slug'])) ?>" class="related-card">
                <img src="<?= e(gambar_produk($s['gambar_utama'])) ?>"
                     alt="<?= e($s['nama']) ?>" loading="lazy" />
                <div class="related-body">
                  <h4><?= e($s['nama']) ?></h4>
                  <p><?= e($s['deskripsi_singkat']) ?></p>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <!-- ===== FOOTER ===== -->
      <footer id="kontak" class="footer">
        <div class="footer-left">
          <a href="beranda.php" aria-label="Waroeng Bwakekok">
            <img src="assets/img/logo.png"
                 alt="Waroeng Bwakekok"
                 class="footer-logo-img" />
          </a>
        </div>
        <div class="footer-center">
          <p>Waroeng Bwakekok — makan enak, gak pakai ribet.</p>
          <p class="hak">© <?= date('Y') ?> Waroeng Bwakekok — dibuat dengan sepenuh hati</p>
        </div>
        <div class="footer-right">
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
      </footer>
    </div>

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
      <a href="menu.php">Menu</a>
      <a href="beranda.php#review">Review</a>
    </aside>

    <script>
      // ===== Galeri thumbnail =====
      const mainImage = document.getElementById("mainImage");
      const thumbs = document.querySelectorAll(".thumbnail-list img");
      thumbs.forEach(thumb => {
        thumb.addEventListener("click", () => {
          mainImage.src = thumb.src;
          mainImage.alt = thumb.alt;
          thumbs.forEach(t => t.classList.remove("active"));
          thumb.classList.add("active");
        });
      });

      // ===== Love =====
      const loveBtn = document.querySelector(".btn-love");
      if (loveBtn) {
        loveBtn.addEventListener("click", () => {
          const icon = loveBtn.querySelector("i");
          loveBtn.classList.toggle("liked");
          icon.classList.toggle("fa-regular");
          icon.classList.toggle("fa-solid");
        });
      }

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