<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/helpers.php';

// Ambil 8 produk unggulan (fallback: 8 produk terbaru jika unggulan kurang)
$stmt = $pdo->query("
    SELECT p.id, p.nama, p.slug, p.deskripsi_singkat, p.gambar_utama,
           k.nama AS kategori
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
    ORDER BY p.is_unggulan DESC, p.id ASC
    LIMIT 8
");
$produk_unggulan = $stmt->fetchAll();

// Ambil 6 review pelanggan terbaru
$review_list = $pdo->query("
    SELECT nama, kota, rating, pesan, dibuat_pada
    FROM review
    WHERE is_tampil = 1
    ORDER BY dibuat_pada DESC
    LIMIT 6
")->fetchAll();
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waroeng Bwakekok — Makan Enak Gak Pakai Ribet</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="assets/css/beranda.css" />
  </head>
  <body>
    <!-- ===== PITA BERJALAN: DAFTAR NAMA HIDANGAN ===== -->
    <div class="pita pita--hijau" aria-hidden="true">
      <div class="pita-track">
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span><i>✦</i>
        </span>
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span><i>✦</i>
        </span>
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span><i>✦</i>
        </span>
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span><i>✦</i>
        </span>
      </div>
    </div>

    <!-- ===== HEADER ===== -->
    <header class="header">
      <div class="container header-dalam">
        <a class="logo" href="beranda.php">
          <img src="assets/img/logo.png" alt="Waroeng Bwakekok" />
        </a>

        <nav class="nav">
          <a href="beranda.php" class="active">Home</a>
          <a href="menu.php">Menu</a>
          <a href="#review">Review</a>
        </nav>

        <div class="nav-right">
          <button
            class="hamburger"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="sidebar"
          >
            <span></span>
            <span></span>
            <span></span>
          </button>
        </div>
      </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="hero container">
      <div class="hero-kiri">
        <p class="eyebrow naik" style="--d: 0.05s">
          ✦ SELAMAT DATANG DI WAROENG BWAKEKOK ✦
        </p>
        <h1 class="naik" style="--d: 0.12s">
          MAKAN ENAK <br />
          GAK <em>PAKAI RIBET</em>
        </h1>
        <p class="hero-desk naik" style="--d: 0.2s">
          Dari rendang yang kaya rempah sampai sambal yang bikin nambah nasi. Temukan berbagai hidangan favorit dalam satu tempat.
        </p>
        <div class="hero-aksi naik" style="--d: 0.28s">
          <a href="#tentang" class="btn btn--merah">Tentang Kami</a>
          <a href="#hidangan" class="btn btn--garis">Jelajahi Hidangan</a>
        </div>
        <ul class="hero-stat naik" style="--d: 0.36s">
          <li><b>8+ </b><span>Pilihan Menu</span></li>
          <li><b>10+</b><span>Sambal & Pelengkap</span></li>
        </ul>
      </div>

      <div class="hero-kanan naik" style="--d: 0.25s">
        <div class="piring">
          <svg class="cincin" viewBox="0 0 200 200" aria-hidden="true">
            <defs>
              <path
                id="lingkar"
                d="M100,100 m-84,0 a84,84 0 1,1 168,0 a84,84 0 1,1 -168,0"
              />
            </defs>
            <text>
              <textPath
                href="#lingkar"
                textLength="527"
                lengthAdjust="spacingAndGlyphs"
              >
                WAROENG BWAKEKOK ✦ MAKAN ENAK ✦ PILIH LAUK ✦ TAMBAH NASI ✦
              </textPath>
            </text>
          </svg>
          <img
            src="https://media.zcreators.id/crop/0x0:0x0/x/photo/p2/94/2025/04/10/download-1241873040.jpg"
            alt="Sajian nasi Padang dengan aneka lauk"
          />
        </div>

        <span class="stiker stiker--satu" style="--r: 7deg"
          >8+ Pilihan menu</span
        >
        <span class="stiker stiker--dua" style="--r: -9deg">Rasa Nagih 🌶️</span>
      </div>
    </section>

    <!-- ===== TENTANG ===== -->
    <section class="tentang container" id="tentang">
      <div class="kolase reveal">
        <img
          class="foto-besar"
          src="https://katasumbar.com/wp-content/uploads/2023/09/Pekerja-rumah-makan-Padang-membawakan-makanan-e1694174742385.jpg"
          alt="Suasana dapur sedang memasak"
        />
        <span class="stiker stiker--dapur" style="--r: 6deg"
          >Waroeng Bwakekok ✦</span
        >
        <img
          class="foto-kecil"
          src="https://arenawisata.co.id/wp-content/uploads/2024/08/Masakan-Padang-II-Masakan-Padang.jpeg"
          alt="Hidangan matang siap dihidangkan"
        />
      </div>

      <div class="tentang-teks reveal">
        <p class="eyebrow">✦ Sekilas ✦</p>
        <h2>Tentang<br /><em>Waroeng Bwakekok</em></h2>
        <p>
Waroeng Bwakekok hadir untuk menyajikan berbagai hidangan favorit dengan tampilan yang sederhana, pilihan yang beragam, dan rasa yang bikin ingin kembali lagi.
Mulai dari rendang, ayam pop, telur balado, gulai, hingga sambal pelengkap — semuanya bisa kamu temukan dalam satu katalog.
        </p>
      </div>

      <div class="pilar-grid">
        <div class="pilar reveal">
          <span class="emoji">🥥</span>
          <h3>Banyak Pilihan</h3>
          <p>Beragam lauk untuk menemani sepiring nasi hangat.</p>
        </div>
        <div class="pilar reveal">
          <span class="emoji">🌶️</span>
          <h3>Rasa Nendang</h3>
          <p>Dari gurih, pedas, sampai kaya rempah.</p>
        </div>
        <div class="pilar reveal">
          <span class="emoji">⏳</span>
          <h3>Bikin Balik</h3>
          <p>Sekali coba, siapa tahu jadi menu favoritmu.</p>
        </div>
      </div>
    </section>

    <!-- ===== HIDANGAN IKONIK ===== -->
    <section class="hidangan container" id="hidangan">
      <div class="kepala reveal">
        <p class="eyebrow">✦ Hidangan Ikonik ✦</p>
        <h2>Pilihan Yang <em>Bikin Ngiler</em></h2>
        <p class="sub">
          Delapan hidangan yang paling sering menyapa meja makan.
        </p>
      </div>

      <div class="menu-grid">
        <?php if (empty($produk_unggulan)): ?>
          <p style="grid-column: 1/-1; text-align:center; color: rgba(43,27,16,.6);">
            Belum ada hidangan untuk ditampilkan.
          </p>
        <?php else: ?>
          <?php foreach ($produk_unggulan as $p): ?>
            <article class="kartu reveal">
              <a href="<?= e(url_detail($p['slug'])) ?>">
                <figure>
                  <img
                    src="<?= e(gambar_produk($p['gambar_utama'])) ?>"
                    alt="<?= e($p['nama']) ?>"
                    loading="lazy"
                  />
                </figure>
              </a>
              <?php if (!empty($p['kategori'])): ?>
                <span class="tag"><?= e($p['kategori']) ?></span>
              <?php endif; ?>
              <h3><?= e($p['nama']) ?></h3>
              <p><?= e($p['deskripsi_singkat']) ?></p>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>

    <!-- ===== SPOTLIGHT RENDANG ===== -->
    <svg
      class="gerigi"
      style="color: var(--hijau)"
      viewBox="0 0 120 14"
      preserveAspectRatio="none"
      aria-hidden="true"
    >
      <path
        d="M0 13 L7.5 2 L15 13 L22.5 2 L30 13 L37.5 2 L45 13 L52.5 2 L60 13 L67.5 2 L75 13 L82.5 2 L90 13 L97.5 2 L105 13 L112.5 2 L120 13"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
      />
    </svg>

    <section class="rendang" id="rendang">
      <div class="container">
        <div class="kepala kepala--terang reveal">
          <p class="eyebrow">✦ Menu Favorit ✦</p>
          <h2>Menu Favorit <em>Minggu Ini</em></h2>
          <p class="sub">
            Tiga hidangan yang siap bikin nasi di piring terasa kurang.
          </p>
        </div>

        <div class="timeline">
          <div class="tahap reveal">
            <span class="bola">1</span>
            <h3>Rendang</h3>
            <p>
              Daging empuk dengan bumbu rempah yang kaya dan rasa gurih yang meresap sampai ke dalam.
            </p>
            <span class="waktu">Rp 25.000</span>
          </div>
          <div class="tahap reveal">
            <span class="bola">2</span>
            <h3>Dendeng Balado</h3>
            <p>
              Irisan daging tipis yang gurih dipadukan dengan sambal balado pedas yang bikin susah berhenti.
            </p>
            <span class="waktu">Rp 22.000</span>
          </div>
          <div class="tahap reveal">
            <span class="bola">3</span>
            <h3>Gulai Ayam</h3>
            <p>
              Ayam lembut dengan kuah santan gurih dan rempah yang harum. Cocok buat teman nasi hangat.
            </p>
            <span class="waktu">Rp 25.000</span>
          </div>
        </div>

        <div class="cnn reveal">
          <span class="cnn-lencana"
            >✦ Paling Sering Dipilih Di Waroeng Bwakekok ✦</span
          >
        </div>
      </div>
    </section>

    <svg
      class="gerigi gerigi--balik"
      style="color: var(--hijau)"
      viewBox="0 0 120 14"
      preserveAspectRatio="none"
      aria-hidden="true"
    >
      <path
        d="M0 13 L7.5 2 L15 13 L22.5 2 L30 13 L37.5 2 L45 13 L52.5 2 L60 13 L67.5 2 L75 13 L82.5 2 L90 13 L97.5 2 L105 13 L112.5 2 L120 13"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
      />
    </svg>

    <!-- ===== REMPAH ===== -->
    <section class="rempah container" id="rempah">
      <div class="kepala reveal">
        <p class="eyebrow">✦ RAHASIA DI BALIK RASA ✦</p>
        <h2>Bahan Yang Bikin <em>Rasa Nendang</em></h2>
        <p class="sub">
          Dari rempah aromatik sampai bahan pelengkap, setiap bahan punya perannya sendiri dalam menghasilkan rasa yang khas.
        </p>
      </div>

      <div class="rempah-grid">
        <div class="bumbu reveal">
          <span class="emoji">🌶️</span>
          <h3>Cabai</h3>
          <p>Memberikan rasa pedas sekaligus warna dan karakter pada berbagai hidangan.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🥥</span>
          <h3>Santan</h3>
          <p>Memberikan rasa gurih dan tekstur creamy pada hidangan berkuah.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🫚</span>
          <h3>Kunyit</h3>
          <p>Memberikan warna kuning alami serta aroma khas pada masakan.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🌿</span>
          <h3>Serai</h3>
          <p>Memberikan aroma segar dan wangi yang membuat masakan semakin harum.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🧅</span>
          <h3>Bawang</h3>
          <p>Menjadi salah satu dasar bumbu untuk membangun rasa gurih dan aromatik.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🫚</span>
          <h3>Lengkuas</h3>
          <p>Memberikan aroma hangat dan rasa khas pada masakan berbumbu.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🍃</span>
          <h3>Daun Kemangi</h3>
          <p>Menambahkan aroma segar yang melengkapi rasa dari bumbu utama.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🍋</span>
          <h3>Asam</h3>
          <p>Memberikan sentuhan rasa asam untuk menyeimbangkan gurih dan pedas.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🌰</span>
          <h3>Kemiri</h3>
          <p>Membantu menghasilkan rasa gurih sekaligus membuat bumbu terasa lebih pekat.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">⭐</span>
          <h3>Bungo Lawang</h3>
          <p>Memberikan aroma rempah yang hangat dan khas pada hidangan tertentu.</p>
        </div>
      </div>
    </section>

    <!-- ===== BUDAYA MAKAN ===== -->
    <section class="budaya" id="budaya">
      <div class="container">
        <div class="kepala kepala--terang reveal">
          <p class="eyebrow">✦ KENAPA BWAKEKOK? ✦</p>
          <h2>Biar Makan Nggak <em>Bingung</em></h2>
          <p class="sub">
            Pilih menu favoritmu, temukan rasa yang kamu suka, lalu nikmati.
          </p>
        </div>

        <ol class="langkah-grid">
          <li class="langkah reveal">
            <span class="nomor">01</span>
            <h3>Banyak Pilihan</h3>
            <p>
              Mau yang gurih, pedas, berkuah, atau berbumbu pekat? Pilih sesuai selera dari berbagai menu yang tersedia.
            </p>
          </li>
          <li class="langkah reveal">
            <span class="nomor">02</span>
            <h3>Menu Mudah Dicari</h3>
            <p>
              Semua pilihan makanan tersusun rapi, jadi kamu nggak perlu bingung menentukan mau makan apa hari ini.
            </p>
          </li>
          <li class="langkah reveal">
            <span class="nomor">03</span>
            <h3>Lihat Sebelum Pilih</h3>
            <p>
              Intip foto, nama, harga, dan deskripsi menu sebelum menentukan pilihanmu.
            </p>
          </li>
          <li class="langkah reveal">
            <span class="nomor">04</span>
            <h3>Tinggal Pilih & Nikmati</h3>
            <p>
              Sudah menemukan yang cocok? Langsung pilih menu favoritmu dan lanjutkan petualangan rasa di Waroeng Bwakekok.
            </p>
          </li>
        </ol>

        <p class="adat reveal">
          “Pilih lauknya, nikmati rasanya.”
        </p>
      </div>
    </section>

    <!-- ===== REVIEW PELANGGAN ===== -->
    <section class="review container" id="review">
      <div class="kepala reveal">
        <p class="eyebrow">✦ Kata Mereka ✦</p>
        <h2>Review <em>Pelanggan</em></h2>
        <p class="sub">
          Cerita kecil dari orang-orang yang sudah mencicipi meja kami.
        </p>
      </div>

      <?php if (empty($review_list)): ?>
        <p style="text-align:center;color:rgba(43,27,16,.55);font-style:italic;">
          Belum ada review untuk ditampilkan.
        </p>
      <?php else: ?>
        <div class="review-grid">
          <?php foreach ($review_list as $r): ?>
            <?php $r_rating = max(1, min(5, (int)$r['rating'])); ?>
            <article class="review-card reveal">
              <div class="review-head">
                <span class="review-avatar" aria-hidden="true">
                  <?= e(mb_strtoupper(mb_substr($r['nama'], 0, 1))) ?>
                </span>
                <div class="review-meta">
                  <h4><?= e($r['nama']) ?></h4>
                  <?php if (!empty($r['kota'])): ?>
                    <span class="review-loc">📍 <?= e($r['kota']) ?></span>
                  <?php endif; ?>
                </div>
              </div>

              <div class="review-stars"
                   aria-label="Rating <?= $r_rating ?> dari 5">
                <?= str_repeat('★', $r_rating) . str_repeat('☆', 5 - $r_rating) ?>
              </div>

              <p class="review-text"><?= e($r['pesan']) ?></p>

              <span class="review-date"><?= e(tanggal_relatif($r['dibuat_pada'])) ?></span>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- ===== PITA PENUTUP ===== -->
    <div class="pita pita--hijau pita--balik" aria-hidden="true">
      <div class="pita-track">
        <span class="seq">
          <span>WAROENG BWAKEKOK</span><i>✦</i><span>MAKAN ENAK</span><i>✦</i>
          <span>PILIH LAUK</span><i>✦</i><span>BIKIN NAGIH</span><i>✦</i>
          <span>SAMBAL PEDAS</span><i>✦</i><span>LAUK MELIMPAH</span><i>✦</i>
        </span>
        <span class="seq">
         <span>WAROENG BWAKEKOK</span><i>✦</i><span>MAKAN ENAK</span><i>✦</i>
          <span>PILIH LAUK</span><i>✦</i><span>BIKIN NAGIH</span><i>✦</i>
          <span>SAMBAL PEDAS</span><i>✦</i><span>LAUK MELIMPAH</span><i>✦</i>
        </span>
        <span class="seq">
          <span>WAROENG BWAKEKOK</span><i>✦</i><span>MAKAN ENAK</span><i>✦</i>
          <span>PILIH LAUK</span><i>✦</i><span>BIKIN NAGIH</span><i>✦</i>
          <span>SAMBAL PEDAS</span><i>✦</i><span>LAUK MELIMPAH</span><i>✦</i>
        </span>
        <span class="seq">
          <span>WAROENG BWAKEKOK</span><i>✦</i><span>MAKAN ENAK</span><i>✦</i>
          <span>PILIH LAUK</span><i>✦</i><span>BIKIN NAGIH</span><i>✦</i>
          <span>SAMBAL PEDAS</span><i>✦</i><span>LAUK MELIMPAH</span><i>✦</i>
        </span>
      </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
      <div class="container footer-dalam">
          <div class="makanan-deret" aria-hidden="true">

  <!-- 1. PIRING NASI -->
  <svg viewBox="0 0 64 64" style="width: 40px;">
    <!-- plate -->
    <ellipse cx="32" cy="48" rx="26" ry="6" fill="currentColor" opacity=".55" />
    <ellipse cx="32" cy="44" rx="22" ry="5" fill="currentColor" />
    <!-- rice mound -->
    <path d="M10 44 Q32 14 54 44 Q32 50 10 44 Z" fill="currentColor" />
    <!-- rice texture -->
    <path d="M22 38 Q32 34 42 38"
          stroke="#221207" stroke-width="1.6" fill="none"
          stroke-linecap="round" opacity=".35" />
    <path d="M26 42 Q32 40 38 42"
          stroke="#221207" stroke-width="1.6" fill="none"
          stroke-linecap="round" opacity=".25" />
  </svg>

  <!-- 2. CABAI -->
  <svg viewBox="0 0 64 64" style="width: 52px;">
    <!-- stem -->
    <path d="M28 10 L32 22"
          stroke="currentColor" stroke-width="3.5"
          stroke-linecap="round" fill="none" />
    <path d="M28 10 Q22 8 18 12"
          stroke="currentColor" stroke-width="3.5"
          stroke-linecap="round" fill="none" />
    <!-- body -->
    <path d="M32 20 Q46 26 46 40 Q46 56 32 58 Q18 56 20 40 Q22 26 32 20 Z"
          fill="currentColor" />
    <!-- highlight -->
    <path d="M27 30 Q25 40 27 50"
          stroke="#221207" stroke-width="1.6" fill="none"
          stroke-linecap="round" opacity=".35" />
  </svg>

  <!-- 3. MANGKUK GULAI (paling besar) -->
  <svg viewBox="0 0 64 64" style="width: 72px;">
    <!-- steam -->
    <path d="M22 8 Q25 14 22 20"
          stroke="currentColor" stroke-width="3"
          stroke-linecap="round" fill="none" opacity=".55" />
    <path d="M32 4 Q35 11 32 20"
          stroke="currentColor" stroke-width="3"
          stroke-linecap="round" fill="none" opacity=".55" />
    <path d="M42 8 Q45 14 42 20"
          stroke="currentColor" stroke-width="3"
          stroke-linecap="round" fill="none" opacity=".55" />
    <!-- bowl -->
    <path d="M6 26 Q32 34 58 26 Q56 54 32 56 Q8 54 6 26 Z"
          fill="currentColor" />
    <!-- liquid line -->
    <path d="M14 30 Q32 34 50 30"
          stroke="#221207" stroke-width="1.8" fill="none"
          stroke-linecap="round" opacity=".3" />
  </svg>

  <!-- 4. IKAN -->
  <svg viewBox="0 0 64 64" style="width: 52px;">
    <!-- tail -->
    <path d="M10 32 L2 20 L2 44 Z" fill="currentColor" />
    <!-- body -->
    <ellipse cx="32" cy="32" rx="22" ry="12" fill="currentColor" />
    <!-- top fin -->
    <path d="M28 20 Q32 12 38 20 Z" fill="currentColor" />
    <!-- bottom fin -->
    <path d="M24 44 Q28 50 34 44 Z" fill="currentColor" />
    <!-- eye -->
    <circle cx="46" cy="30" r="2.5" fill="#221207" opacity=".65" />
    <!-- scale lines -->
    <path d="M24 27 Q26 32 24 37"
          stroke="#221207" stroke-width="1.5" fill="none"
          stroke-linecap="round" opacity=".3" />
    <path d="M32 25 Q34 32 32 39"
          stroke="#221207" stroke-width="1.5" fill="none"
          stroke-linecap="round" opacity=".3" />
  </svg>

  <!-- 5. TELUR BALADO -->
  <svg viewBox="0 0 64 64" style="width: 40px;">
    <!-- sambal blob -->
    <path d="M8 40 Q10 26 22 28 Q26 14 38 22 Q54 18 56 34 Q58 50 44 50 Q28 56 18 50 Q8 50 8 40 Z"
          fill="currentColor" opacity=".55" />
    <!-- egg -->
    <ellipse cx="32" cy="36" rx="14" ry="16" fill="currentColor" />
    <!-- highlight -->
    <ellipse cx="26" cy="30" rx="4" ry="3" fill="#221207" opacity=".3" />
    <!-- sambal flecks -->
    <circle cx="14" cy="34" r="1.6" fill="#221207" opacity=".35" />
    <circle cx="52" cy="38" r="1.6" fill="#221207" opacity=".35" />
  </svg>

</div>

        <p class="wordmark">Bwakekok</p>

        <p class="tagline">
          Sebuah katalog hidangan Waroeng Bwakekok, dibuat untuk memudahkan pencarian menu favoritmu.
        </p>

        <!-- <nav class="footer-nav">
          <a href="#tentang">Tentang</a>
          <a href="#hidangan">Hidangan</a>
          <a href="#rendang">Rendang</a>
          <a href="#rempah">Rempah</a>
          <a href="#budaya">Budaya</a>
          <a href="#review">Review</a>
        </nav> -->

        <p class="hak">© <?= date('Y') ?> Waroeng Bwakekok — dibuat dengan sepenuh hati ✦</p>
      </div>
    </footer>

    <!-- ===== SIDEBAR (mobile) ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
    <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
      <div class="sidebar-head">
        <span class="label">
          <img src="assets/img/logo.png" alt="Waroeng Bwakekok" class="logo-mini" />
        </span>
        <button class="sidebar-close" aria-label="Tutup menu">✕</button>
      </div>
      <a href="beranda.php" class="active">Home</a>
      <a href="menu.php">Menu</a>
      <a href="#review">Review</a>
    </aside>

    <script>
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

      hamburger.addEventListener("click", function () {
        if (sidebar.classList.contains("active")) {
          closeSidebar();
        } else {
          openSidebar();
        }
      });

      sidebarOverlay.addEventListener("click", closeSidebar);
      sidebarClose.addEventListener("click", closeSidebar);

      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && sidebar.classList.contains("active")) {
          closeSidebar();
        }
      });
    </script>
  </body>
</html>