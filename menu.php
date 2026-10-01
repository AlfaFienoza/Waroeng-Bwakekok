<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/helpers.php';

// Ambil semua kategori
$kategori_list = $pdo->query("
    SELECT slug, nama FROM kategori ORDER BY urutan ASC, nama ASC
")->fetchAll();

// Ambil semua produk + nama kategori
$produk_list = $pdo->query("
    SELECT p.id, p.nama, p.slug, p.deskripsi_singkat, p.harga, p.harga_asli,
           p.gambar_utama, k.slug AS kategori_slug, k.nama AS kategori_nama
    FROM produk p
    LEFT JOIN kategori k ON k.id = p.kategori_id
    ORDER BY p.id ASC
")->fetchAll();
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu Nasi Padang</title>
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
        <div class="logo">Masakan <strong>Padang</strong></div>

        <div class="nav-menu">
          <a href="beranda.php">Home</a>
          <a href="menu.php" class="active">Menu</a>
          <a href="#">Review</a>
        </div>

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
      </nav>

      <!-- ===== HEADER MENU ===== -->
      <section class="menu-header">
        <h1>Nasi Padang Menu</h1>

        <div class="categories">
          <a href="#" class="category-active" data-filter="all">All Menu</a>
          <a href="#" data-filter="ayam">Ayam</a>
          <a href="#" data-filter="ikan">Ikan</a>
          <a href="#" data-filter="daging">Daging</a>
          <a href="#" data-filter="telur">Telur</a>
          <a href="#" data-filter="sayuran">Sayuran</a>
          <a href="#" data-filter="sambal">Sambal</a>
        </div>

        <div class="hiasan" aria-hidden="true">
          <svg class="leaf" viewBox="0 0 48 32">
            <path
              d="M2 22C6 8 20 2 46 4 44 22 30 32 12 28L2 30l6-8z"
              fill="#3E7C4F"
            />
            <path
              d="M8 24C18 16 28 11 40 8"
              stroke="#EBA83C"
              stroke-width="2.5"
              fill="none"
              stroke-linecap="round"
            />
          </svg>
          <span class="chili">🌶️</span>
          <svg class="sprout" viewBox="0 0 40 44">
            <path
              d="M20 42V22"
              stroke="#3E7C4F"
              stroke-width="4"
              stroke-linecap="round"
            />
            <path d="M20 24C8 26 2 16 2 6c12 0 18 6 18 18z" fill="#3E7C4F" />
            <path d="M20 20C20 8 28 2 38 2c0 10-6 18-18 18z" fill="#3E7C4F" />
          </svg>
        </div>
      </section>

      <!-- ===== JUDUL ===== -->
      <div class="menu-title">
        <h2 id="menuTitle">All Menu</h2>

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
        <!-- CARD 1 - IKAN -->
        <div class="food-card" data-category="ikan">
          <img src="ikangoreng.jpg" alt="Ikan Goreng" />
          <div class="food-info">
            <h3>Ikan Goreng</h3>
            <p>Ikan goreng renyah</p>
            <div class="harga">Rp25.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 2 - AYAM -->
        <div class="food-card" data-category="ayam">
          <img src="ayamgoreng.jpg" alt="Ayam Goreng" />
          <div class="food-info">
            <h3>Ayam Goreng</h3>
            <p>Ayam goreng gurih</p>
            <div class="harga">Rp20.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 3 - AYAM -->
        <div class="food-card" data-category="ayam">
          <img src="ayambakar.jpg" alt="Ayam Bakar" />
          <div class="food-info">
            <h3>Ayam Bakar</h3>
            <p>Ayam bakar pedas</p>
            <div class="harga">Rp22.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 4 - DAGING -->
        <div class="food-card" data-category="daging">
          <img src="rendang.jpg" alt="Rendang" />
          <div class="food-info">
            <h3>Rendang</h3>
            <p>Rendang pedas gurih</p>
            <div class="harga">Rp25.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 5 - AYAM -->
        <div class="food-card" data-category="ayam">
          <img src="ayamgulai.jpg" alt="Ayam Gulai" />
          <div class="food-info">
            <h3>Ayam Gulai</h3>
            <p>Ayam dengan kuah gulai khas Minang</p>
            <div class="harga">Rp23.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 6 - IKAN -->
        <div class="food-card" data-category="ikan">
          <img src="ikangulai.jpg" alt="Ikan Gulai" />
          <div class="food-info">
            <h3>Ikan Gulai</h3>
            <p>Ikan dengan kuah gulai khas Minang</p>
            <div class="harga">Rp26.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 7 - TELUR -->
        <div class="food-card" data-category="telur">
          <img src="telur.jpg" alt="Telur Sambal" />
          <div class="food-info">
            <h3>Telur Sambal</h3>
            <p>Telur rebus dengan sambal pedas jeletot</p>
            <div class="harga">Rp10.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 8 - IKAN -->
        <div class="food-card" data-category="ikan">
          <img src="cumi.jpg" alt="Cumi Goreng" />
          <div class="food-info">
            <h3>Cumi Goreng</h3>
            <p>Cumi goreng dengan sambal hijau pedas</p>
            <div class="harga">Rp28.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 9 - SAYURAN -->
        <div class="food-card" data-category="sayuran">
          <img src="Gulai-daun-singkong.jpeg" alt="Sayur Daun Singkong" />
          <div class="food-info">
            <h3>Sayur Daun Singkong</h3>
            <p>Sayur daun singkong dengan kuah kuning</p>
            <div class="harga">Rp8.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 10 - SAYURAN -->
        <div class="food-card" data-category="sayuran">
          <img src="sayurnangkaa.jpg" alt="Sayur Nangka" />
          <div class="food-info">
            <h3>Sayur Nangka</h3>
            <p>Sayur nangka kuah gurih</p>
            <div class="harga">Rp8.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>

        <!-- CARD 11 - SAYURAN -->
        <div class="food-card" data-category="sayuran">
          <img src="perkedel.jpg" alt="Perkedel" />
          <div class="food-info">
            <h3>Perkedel</h3>
            <p>Perkedel gurih mantap</p>
            <div class="harga">Rp5.000</div>
            <div class="card-bottom">
              <button class="btn-detail">
                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
              </button>
              <span class="love" role="button" aria-label="Simpan ke favorit"
                ><i class="fa-regular fa-heart"></i
              ></span>
            </div>
          </div>
        </div>
      </section>

      <!-- ===== PAGINATION ===== -->
      <div class="pagination">
        <button aria-label="Sebelumnya">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        <span class="page page-active">1</span>
        <span class="page">2</span>
        <span class="page">3</span>
        <button aria-label="Berikutnya">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>

      <!-- ===== FOOTER ===== -->
      <footer>
        <div class="footer-logo">masakanpadang</div>

        <div class="copyright">
          <p>Masakan Padang dengan cita rasa terbaik.</p>
          <small>&copy; 2026 masakanpadang. All rights reserved.</small>
        </div>

        <div class="sosial">
          <a href="#" aria-label="Instagram"
            ><i class="fab fa-instagram"></i
          ></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
      </footer>
    </section>

    <!-- ===== SIDEBAR (mobile) ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
    <aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
      <div class="sidebar-head">
        <span class="label">Masakan <strong>Padang</strong></span>
        <button class="sidebar-close" aria-label="Tutup menu">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <a href="beranda.html">Home</a>
      <a href="menu.html" class="active">Menu</a>
      <a href="#">Review</a>
    </aside>

    <script>
      // ===== Toggle ikon hati (favorit) =====
      document.querySelectorAll(".love").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var icon = btn.querySelector("i");
          btn.classList.toggle("liked");
          icon.classList.toggle("fa-regular");
          icon.classList.toggle("fa-solid");
        });
      });

      // ===== Filter kategori =====
      const categoryLinks = document.querySelectorAll(".categories a");
      const cards = document.querySelectorAll(".food-card");
      const menuTitle = document.getElementById("menuTitle");
      const foodContainer = document.getElementById("foodContainer");

      categoryLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
          e.preventDefault();

          categoryLinks.forEach(function (l) {
            l.classList.remove("category-active");
          });
          link.classList.add("category-active");

          const filter = link.dataset.filter;
          const label = link.textContent.trim();

          if (menuTitle) {
            menuTitle.textContent = filter === "all" ? "All Menu" : label;
          }

          let visibleCount = 0;
          cards.forEach(function (card) {
            const match = filter === "all" || card.dataset.category === filter;
            if (match) {
              card.style.display = "";
              visibleCount++;
            } else {
              card.style.display = "none";
            }
          });

          showEmptyMessage(visibleCount);
        });
      });

      function showEmptyMessage(count) {
        let empty = document.getElementById("emptyMessage");
        if (count === 0) {
          if (!empty) {
            empty = document.createElement("p");
            empty.id = "emptyMessage";
            empty.textContent = "Belum ada menu untuk kategori ini.";
            empty.style.textAlign = "center";
            empty.style.color = "#8A7663";
            empty.style.fontSize = "16px";
            empty.style.padding = "40px 0";
            empty.style.gridColumn = "1 / -1";
            foodContainer.appendChild(empty);
          }
          empty.style.display = "block";
        } else if (empty) {
          empty.style.display = "none";
        }
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
