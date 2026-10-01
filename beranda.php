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
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mengenal Masakan Padang</title>

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
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span
          ><i>✦</i>
        </span>
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span
          ><i>✦</i>
        </span>
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span
          ><i>✦</i>
        </span>
        <span class="seq">
          <span>Rendang</span><i>✦</i><span>Gulai</span><i>✦</i>
          <span>Sate Padang</span><i>✦</i><span>Dendeng Balado</span><i>✦</i>
          <span>Sayur Nangka</span><i>✦</i><span>Sambal Lado Mudo</span><i>✦</i>
          <span>Telur Balado</span><i>✦</i><span>Gulai Kepala Kakap</span
          ><i>✦</i>
        </span>
      </div>
    </div>

    <!-- ===== HEADER ===== -->
    <header class="header">
      <div class="container header-dalam">
        <a class="logo" href="beranda.html">
          <svg viewBox="0 0 48 36" fill="currentColor" aria-hidden="true">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
          <span>Masakan<b>Padang</b></span>
        </a>

        <nav class="nav">
          <a href="beranda.php" class="active">Home</a>
          <a href="menu.php">Menu</a>
          <a href="#">Review</a>
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
          ✦ Mengenal Kuliner Nusantara ✦
        </p>
        <h1 class="naik" style="--d: 0.12s">
          Raso nan Lamak<br />
          dari <em>Ranah Minang</em>
        </h1>
        <p class="hero-desk naik" style="--d: 0.2s">
          Ini bukan etalase — ini perkenalan. Kenali rendang yang menunggu
          delapan jam di atas api kecil, gulai yang berpadu dengan santan, dan
          rempah-rempah yang tumbuh subur di tanah Minangkabau.
        </p>
        <div class="hero-aksi naik" style="--d: 0.28s">
          <a href="#tentang" class="btn btn--merah">Mulai Mengenal</a>
          <a href="#hidangan" class="btn btn--garis">Langsung ke Hidangan</a>
        </div>
        <ul class="hero-stat naik" style="--d: 0.36s">
          <li><b>8 jam</b><span>rendang di atas api kecil</span></li>
          <li><b>#1</b><span>CNN makanan terenak dunia, 2×</span></li>
          <li><b>30+</b><span>jenis lauk khas Minang</span></li>
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
                REMPAH ✦ SANTAN ✦ KESABARAN ✦ RASO NAN LAMAK ✦
              </textPath>
            </text>
          </svg>
          <img
            src="https://media.zcreators.id/crop/0x0:0x0/x/photo/p2/94/2025/04/10/download-1241873040.jpg"
            alt="Sajian nasi Padang dengan aneka lauk"
          />
        </div>

        <span class="stiker stiker--satu" style="--r: 7deg"
          >8 jam di atas api 🔥</span
        >
        <span class="stiker stiker--dua" style="--r: -9deg">lado mudo 🌶️</span>
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
          >dapur niniak ✦</span
        >
        <img
          class="foto-kecil"
          src="https://arenawisata.co.id/wp-content/uploads/2024/08/Masakan-Padang-II-Masakan-Padang.jpeg"
          alt="Hidangan matang siap dihidangkan"
        />
      </div>

      <div class="tentang-teks reveal">
        <p class="eyebrow">✦ Sekilas ✦</p>
        <h2>Masakan Padang itu<br /><em>apa, sebenarnya?</em></h2>
        <p>
          Masakan Padang — atau <em>masakan Minang</em> — adalah tradisi memasak
          dari dataran tinggi Minangkabau, Sumatera Barat. Ciri khasnya: kuah
          santan yang kental, rasa pedas yang berani, dan bumbu yang digiling
          dari belasan jenis rempah segar.
        </p>
        <p>
          Masakannya tak mengenal kata tergesa. Daging direndang berjam-jam di
          atas api kecil sampai bumbu meresap ke serat. Dari dapur inilah lahir
          rendang, gulai, dan belasan hidangan yang kini dikenal di seluruh
          nusantara — berkat roda perantau urang Minang.
        </p>
      </div>

      <div class="pilar-grid">
        <div class="pilar reveal">
          <span class="emoji">🥥</span>
          <h3>Santan</h3>
          <p>
            Santan kental dari kelapa muda menjadi dasar hampir semua kuah —
            diaduk pelan-pelan agar tak pecah.
          </p>
        </div>
        <div class="pilar reveal">
          <span class="emoji">🌶️</span>
          <h3>Rempah &amp; Lado</h3>
          <p>
            Cabai, lengkuas, kunyit, serai — digiling di batu samek, wajib
            segar, bukan bubuk instan.
          </p>
        </div>
        <div class="pilar reveal">
          <span class="emoji">⏳</span>
          <h3>Kesabaran</h3>
          <p>
            Api kecil dan waktu panjang. Di sinilah rasa "nempel sampai ke
            serat" itu berasal.
          </p>
        </div>
      </div>
    </section>

    <!-- ===== HIDANGAN IKONIK ===== -->
        <section class="hidangan container" id="hidangan">
      <div class="kepala reveal">
        <p class="eyebrow">✦ Hidangan Ikonik ✦</p>
        <h2>Berteman dengan <em>Lauk-Lauknya</em></h2>
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
          <p class="eyebrow">✦ Sang Raja ✦</p>
          <h2>Perjalanan Panjang <em>Rendang</em></h2>
          <p class="sub">
            Sebelum bernama rendang, sepotong daging itu berhenti di tiga
            perhentian.
          </p>
        </div>

        <div class="timeline">
          <div class="tahap reveal">
            <span class="bola">1</span>
            <h3>Gulai</h3>
            <p>
              Santan dan air berpadu menjadi kuah kuning bening. Daging mulai
              menyerap rempah, teksturnya mulai lunak.
            </p>
            <span class="waktu">± 1–2 jam</span>
          </div>
          <div class="tahap reveal">
            <span class="bola">2</span>
            <h3>Kalio</h3>
            <p>
              Kuah menyusut dan mengental keemasan. Rasa makin pekat — di banyak
              daerah, kalio inilah "rendang basah".
            </p>
            <span class="waktu">± 3–4 jam</span>
          </div>
          <div class="tahap reveal">
            <span class="bola">3</span>
            <h3>Rendang</h3>
            <p>
              Kuah mengering total. Warna berubah cokelat gelap mengkilap, rasa
              menempel sampai ke serat — dan tahan berminggu-minggu.
            </p>
            <span class="waktu">± 6–8 jam</span>
          </div>
        </div>

        <div class="cnn reveal">
          <span class="cnn-lencana"
            >✦ CNN World's 50 Best Foods — peringkat #1 versi pembaca, 2011
            &amp; 2017 ✦</span
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
        <p class="eyebrow">✦ Jiwa Dapur Minang ✦</p>
        <h2>Sepuluh Sahabat <em>Batu Samek</em></h2>
        <p class="sub">
          Bumbu segar yang digiling sendiri — fondasi seluruh rasa.
        </p>
      </div>

      <div class="rempah-grid">
        <div class="bumbu reveal">
          <span class="emoji">🌶️</span>
          <h3>Lado</h3>
          <p>
            Cabai — jiwa kepedasan; merah untuk balado, hijau untuk lado mudo.
          </p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🥥</span>
          <h3>Santan</h3>
          <p>Dari kelapa tua parut; dasar krim hampir semua kuah gulai.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🫚</span>
          <h3>Kunyit</h3>
          <p>Pemberi warna kuning keemasan yang jadi tanda tangan gulai.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🌿</span>
          <h3>Sereh</h3>
          <p>Serai — wanginya menyatu di hampir setiap hidangan berkuah.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🧅</span>
          <h3>Bawang</h3>
          <p>Merah dan putih, selalu digiling bersama sebagai dasar bumbu.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🫚</span>
          <h3>Langkueh</h3>
          <p>Lengkuas — aroma segar-pedas hangat pada kuah dan rendang.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🍃</span>
          <h3>Ruku-Ruku</h3>
          <p>Kemangi Minang; disentuhkan di akhir masakan untuk wangi segar.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🍋</span>
          <h3>Asam Kandis</h3>
          <p>Pemberi rasa asam-gurih khas pada gulai ikan dan pindang.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">🌰</span>
          <h3>Kemiri</h3>
          <p>Digoreng lalu digiling; membuat kuah lebih lembut dan mengilap.</p>
        </div>
        <div class="bumbu reveal">
          <span class="emoji">⭐</span>
          <h3>Bungo Lawang</h3>
          <p>Bunga lawang — rempah "penghangat" pada masakan berkuah pekat.</p>
        </div>
      </div>
    </section>

    <!-- ===== BUDAYA MAKAN ===== -->
    <section class="budaya" id="budaya">
      <div class="container">
        <div class="kepala kepala--terang reveal">
          <p class="eyebrow">✦ Adat di Meja Makan ✦</p>
          <h2>Santap ala <em>Urang Padang</em></h2>
          <p class="sub">
            Makan di rumah makan Padang punya tata caranya sendiri — begini
            adatnya.
          </p>
        </div>

        <ol class="langkah-grid">
          <li class="langkah reveal">
            <span class="nomor">01</span>
            <h3>Dabuih Datang</h3>
            <p>
              Pelayan menghantarkan nasi dan semua lauk sekaligus ke meja.
              Tugasmu hanya memilih yang menggoda.
            </p>
          </li>
          <li class="langkah reveal">
            <span class="nomor">02</span>
            <h3>Makan Pakai Tangan</h3>
            <p>
              Adat menyarankan tangan kanan — nasi dan lauk terasa lebih
              menyatu. Tenang, sendok tetap tersedia.
            </p>
          </li>
          <li class="langkah reveal">
            <span class="nomor">03</span>
            <h3>Hitung yang Disantap</h3>
            <p>
              Yang dihitung hanya lauk yang tersentuh; sisanya kembali ke
              pelayan. Prinsipnya berbagi, bukan berjaga.
            </p>
          </li>
          <li class="langkah reveal">
            <span class="nomor">04</span>
            <h3>Bungkus untuk Jalan</h3>
            <p>
              Nasi bungkus berisi rendang adalah sahabat perjalanan para
              perantau — awet dan mengenyangkan.
            </p>
          </li>
        </ol>

        <p class="adat reveal">
          “Lauk yang tidak tersentuh kembali ke dabuih — makan di Padang itu
          soal <em>berbagi</em>, bukan berjaga.”
        </p>
      </div>
    </section>

    <!-- ===== FAKTA MENARIK ===== -->
    <section class="fakta container" id="fakta">
      <div class="kepala reveal">
        <p class="eyebrow">✦ Tahukah Kamu? ✦</p>
        <h2>Beberapa <em>Fakta Menarik</em></h2>
      </div>

      <div class="fakta-grid">
        <article class="fakta-kartu reveal">
          <span class="nomor">01</span>
          <h3>Raja Dunia</h3>
          <p>
            Rendang dinobatkan CNN sebagai makanan terenak #1 versi pembaca pada
            2011 — dan kembali ke puncak pada 2017.
          </p>
        </article>
        <article class="fakta-kartu reveal">
          <span class="nomor">02</span>
          <h3>Ikut Merantau</h3>
          <p>
            Berkat budaya merantau urang Minang, masakan Padang menyebar ke
            hampir setiap kota di Indonesia — dari Sabang sampai Merauke.
          </p>
        </article>
        <article class="fakta-kartu reveal">
          <span class="nomor">03</span>
          <h3>Bekal Perjalanan</h3>
          <p>
            Rendang sengaja dimasak hingga kering agar awet berhari-hari tanpa
            kulkas — bekal para perantau di jalanan dahulu kala.
          </p>
        </article>
        <article class="fakta-kartu reveal">
          <span class="nomor">04</span>
          <h3>Wajah di Kapau</h3>
          <p>
            Di Nagari Kapau, lauk disajikan dalam gelas-gelas besar berjajar di
            atas dabuih — versi paling meriah dari tradisi Padang.
          </p>
        </article>
      </div>
    </section>

    <!-- ===== PITA PENUTUP ===== -->
    <div class="pita pita--hijau pita--balik" aria-hidden="true">
      <div class="pita-track">
        <span class="seq">
          <span>Rempah</span><i>✦</i><span>Santan</span><i>✦</i>
          <span>Kesabaran</span><i>✦</i><span>Adat</span><i>✦</i>
          <span>Cerita</span><i>✦</i><span>Raso nan Lamak</span><i>✦</i>
        </span>
        <span class="seq">
          <span>Rempah</span><i>✦</i><span>Santan</span><i>✦</i>
          <span>Kesabaran</span><i>✦</i><span>Adat</span><i>✦</i>
          <span>Cerita</span><i>✦</i><span>Raso nan Lamak</span><i>✦</i>
        </span>
        <span class="seq">
          <span>Rempah</span><i>✦</i><span>Santan</span><i>✦</i>
          <span>Kesabaran</span><i>✦</i><span>Adat</span><i>✦</i>
          <span>Cerita</span><i>✦</i><span>Raso nan Lamak</span><i>✦</i>
        </span>
        <span class="seq">
          <span>Rempah</span><i>✦</i><span>Santan</span><i>✦</i>
          <span>Kesabaran</span><i>✦</i><span>Adat</span><i>✦</i>
          <span>Cerita</span><i>✦</i><span>Raso nan Lamak</span><i>✦</i>
        </span>
      </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
      <div class="container footer-dalam">
        <div class="gonjong-deret" aria-hidden="true">
          <svg style="width: 34px" viewBox="0 0 48 36" fill="currentColor">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
          <svg style="width: 58px" viewBox="0 0 48 36" fill="currentColor">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
          <svg style="width: 80px" viewBox="0 0 48 36" fill="currentColor">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
          <svg style="width: 58px" viewBox="0 0 48 36" fill="currentColor">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
          <svg style="width: 34px" viewBox="0 0 48 36" fill="currentColor">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
        </div>

        <p class="wordmark">Padang</p>

        <p class="tagline">
          Sebuah pengenalan kecil atas masakan besar dari Ranah Minang.
        </p>

        <nav class="footer-nav">
          <a href="#tentang">Tentang</a>
          <a href="#hidangan">Hidangan</a>
          <a href="#rendang">Rendang</a>
          <a href="#rempah">Rempah</a>
          <a href="#budaya">Budaya</a>
          <a href="#fakta">Fakta</a>
        </nav>

        <p class="hak">© 2026 Masakan Padang — dibuat dengan sepenuh hati ✦</p>
      </div>
    </footer>

    <!-- ===== SIDEBAR (mobile) ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
    <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
      <div class="sidebar-head">
        <span class="label">
          <svg viewBox="0 0 48 36" fill="currentColor" aria-hidden="true">
            <path
              d="M4 30 C8 18 14 12 24 12 C34 12 40 18 44 30 L38 30 C35 22 30 18 24 18 C18 18 13 22 10 30 Z"
            />
            <path d="M4 30 C2 20 6 10 14 6 C10 14 10 22 12 30 Z" />
            <path d="M44 30 C46 20 42 10 34 6 C38 14 38 22 36 30 Z" />
            <path d="M24 1 L27.5 8.5 L20.5 8.5 Z" />
          </svg>
          <span>Masakan<b>Padang</b></span>
        </span>
        <button class="sidebar-close" aria-label="Tutup menu">✕</button>
      </div>
      <a href="beranda.html" class="active">Home</a>
      <a href="menu.html">Menu</a>
      <a href="#">Review</a>
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
