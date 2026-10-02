-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2026 at 04:41 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masakan_padang`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('superadmin','editor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'editor',
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diubah_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password_hash`, `nama_lengkap`, `role`, `is_aktif`, `last_login`, `dibuat_pada`, `diubah_pada`) VALUES
(2, 'admin', 'admin@admin.com', '$2y$10$IL4RvkyNjsORM1AM.9wCHOAp1KFwSaYkA02TLs91kZvVF0d/8oEoK', 'Administrator Utama', 'superadmin', 1, '2026-10-01 23:17:54', '2026-10-01 13:49:32', '2026-10-01 16:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `admin_log`
--

CREATE TABLE `admin_log` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `aksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tabel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` int DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_log`
--

INSERT INTO `admin_log` (`id`, `admin_id`, `aksi`, `tabel`, `record_id`, `keterangan`, `ip_address`, `dibuat_pada`) VALUES
(1, 2, 'delete', 'produk', 11, 'Hapus produk: Perkedel', '127.0.0.1', '2026-10-01 13:54:21'),
(2, 2, 'delete', 'produk', 10, 'Hapus produk: Sayur Nangka', '127.0.0.1', '2026-10-01 13:54:23'),
(3, 2, 'delete', 'produk', 4, 'Hapus produk: Rendang Daging', '127.0.0.1', '2026-10-01 13:55:23'),
(4, 2, 'delete', 'produk', 9, 'Hapus produk: Sayur Daun Singkong', '127.0.0.1', '2026-10-01 13:55:27'),
(5, 2, 'delete', 'produk', 8, 'Hapus produk: Cumi Goreng', '127.0.0.1', '2026-10-01 13:55:29'),
(6, 2, 'create', 'produk', 13, 'Tambah produk: Ayam Geprek Pedas', '127.0.0.1', '2026-10-01 13:58:54'),
(7, 2, 'login', 'admin', 2, 'Login berhasil', '127.0.0.1', '2026-10-01 16:17:54'),
(8, 2, 'create', 'produk', 14, 'Tambah produk: Rendang Daging', '127.0.0.1', '2026-10-01 16:31:26'),
(9, 2, 'create', 'produk', 15, 'Tambah produk: Ayam Pop', '127.0.0.1', '2026-10-01 16:33:02'),
(10, 2, 'create', 'produk', 16, 'Tambah produk: Dendeng Balado', '127.0.0.1', '2026-10-01 16:35:15'),
(11, 2, 'create', 'produk', 17, 'Tambah produk: Gulai Ayam', '127.0.0.1', '2026-10-01 16:36:17'),
(12, 2, 'create', 'produk', 18, 'Tambah produk: Telur Balado', '127.0.0.1', '2026-10-01 16:37:48'),
(13, 2, 'update', 'produk', 18, 'Update produk: Telur Balado', '127.0.0.1', '2026-10-01 16:38:18'),
(14, 2, 'delete', 'produk', 7, 'Hapus produk: Telur Balado', '127.0.0.1', '2026-10-01 16:38:36'),
(15, 2, 'delete', 'produk', 12, 'Hapus produk: Gulai Kepala Ikan Kakap Spesial', '127.0.0.1', '2026-10-01 16:38:39'),
(16, 2, 'delete', 'produk', 6, 'Hapus produk: Ikan Gulai', '127.0.0.1', '2026-10-01 16:38:43'),
(17, 2, 'delete', 'produk', 5, 'Hapus produk: Ayam Gulai', '127.0.0.1', '2026-10-01 16:38:46'),
(18, 2, 'delete', 'produk', 3, 'Hapus produk: Ayam Bakar', '127.0.0.1', '2026-10-01 16:38:49'),
(19, 2, 'delete', 'produk', 2, 'Hapus produk: Ayam Goreng', '127.0.0.1', '2026-10-01 16:38:52'),
(20, 2, 'delete', 'produk', 1, 'Hapus produk: Ikan Goreng', '127.0.0.1', '2026-10-01 16:38:55'),
(21, 2, 'create', 'produk', 19, 'Tambah produk: Sate Padang', '127.0.0.1', '2026-10-01 16:40:01'),
(22, 2, 'create', 'produk', 20, 'Tambah produk: Gulai Kepala Kakap', '127.0.0.1', '2026-10-01 16:40:48'),
(23, 2, 'create', 'produk', 21, 'Tambah produk: Ikan Asam Padeh', '127.0.0.1', '2026-10-01 16:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `slug`, `nama`, `urutan`) VALUES
(1, 'ayam', 'Ayam', 1),
(2, 'ikan', 'Ikan', 2),
(3, 'daging', 'Daging', 3),
(4, 'telur', 'Telur', 4),
(5, 'sayuran', 'Sayuran', 5),
(6, 'sambal', 'Sambal', 6);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_singkat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi_lengkap` text COLLATE utf8mb4_unicode_ci,
  `komposisi` text COLLATE utf8mb4_unicode_ci,
  `harga` decimal(10,2) NOT NULL,
  `harga_asli` decimal(10,2) DEFAULT NULL,
  `porsi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kalori` int DEFAULT NULL,
  `lemak` int DEFAULT NULL,
  `karbohidrat` int DEFAULT NULL,
  `serat` int DEFAULT NULL,
  `protein` int DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `gambar_utama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_unggulan` tinyint(1) NOT NULL DEFAULT '0',
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `slug`, `deskripsi_singkat`, `deskripsi_lengkap`, `komposisi`, `harga`, `harga_asli`, `porsi`, `kalori`, `lemak`, `karbohidrat`, `serat`, `protein`, `kategori_id`, `gambar_utama`, `is_unggulan`, `dibuat_pada`) VALUES
(13, 'Ayam Geprek Pedas', 'ayam-geprek-pedas', 'Lorem Ipsum Dolor Sit Amet', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride Printing Library in London', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since 1966, when designers at Letraset and James Mosley', '17000.00', '22000.00', '1 Prosi / 250g', 120, 80, 130, 79, 65, 1, 'p-20261001135854-a3d7e30d.jpg', 1, '2026-10-01 13:58:54'),
(14, 'Rendang Daging', 'rendang-daging', 'Daging sapi empuk dengan bumbu rempah pekat dan rasa gurih yang meresap.', 'Potongan daging sapi yang dimasak perlahan bersama bumbu rempah hingga menghasilkan tekstur empuk dan rasa yang kaya. Cocok untuk kamu yang suka lauk dengan rasa kuat dan gurih.', 'Daging sapi, santan, cabai, bawang merah, bawang putih, serai, lengkuas, jahe, kunyit, daun jeruk, dan rempah pilihan.', '20000.00', '26000.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'p-20261001163126-aa63b95e.jpg', 0, '2026-10-01 16:31:26'),
(15, 'Ayam Pop', 'ayam-pop', 'Ayam lembut dengan rasa gurih ringan, cocok dipadukan dengan sambal.', 'Potongan ayam dengan tekstur lembut dan rasa gurih yang sederhana. Disajikan sebagai pilihan lauk yang ringan tetapi tetap nikmat bersama nasi hangat.', 'Ayam, bawang putih, bawang merah, jahe, serai, lengkuas, air kelapa, dan bumbu pilihan.', '14500.00', '18000.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'p-20261001163302-391e786f.jpg', 1, '2026-10-01 16:33:02'),
(16, 'Dendeng Balado', 'dendeng-balado', 'Irisan daging gurih dengan balado merah yang pedas dan menggugah selera.', 'Irisan daging sapi yang dimasak hingga menghasilkan tekstur khas dendeng, kemudian dipadukan dengan balado merah yang memberikan rasa pedas dan gurih.', 'Daging sapi, cabai merah, bawang merah, bawang putih, tomat, garam, dan rempah.', '20000.00', '26000.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'p-20261001163515-e6828e4d.jpg', 1, '2026-10-01 16:35:15'),
(17, 'Gulai Ayam', 'gulai-ayam', 'Ayam lembut dalam kuah gurih berbumbu dengan aroma rempah yang harum.', 'Potongan ayam yang disajikan dengan kuah berbumbu dan santan. Rasa gurih serta aroma rempah membuatnya cocok menjadi teman nasi hangat.', 'Ayam, santan, kunyit, cabai, bawang merah, bawang putih, serai, lengkuas, jahe, dan rempah.', '17000.00', '20000.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'p-20261001163617-f782471d.jpg', 1, '2026-10-01 16:36:17'),
(18, 'Telur Balado', 'telur-balado-2', 'Telur dengan balado merah yang pedas, gurih, dan cocok untuk lauk sehari-hari.', 'Telur yang dipadukan dengan sambal balado bercita rasa pedas dan gurih. Pilihan sederhana untuk melengkapi sepiring nasi.', 'Telur, cabai merah, bawang merah, bawang putih, tomat, garam, dan minyak.', '13000.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 'p-20261001163818-2f42b98f.jpg', 1, '2026-10-01 16:37:48'),
(19, 'Sate Padang', 'sate-padang', 'Potongan daging berbumbu dengan kuah sate yang gurih dan kaya rempah.', 'Potongan daging yang disajikan dengan kuah kental berbumbu. Dilengkapi dengan potongan lontong untuk hidangan yang lebih mengenyangkan.', 'Daging sapi, tepung beras, cabai, bawang merah, bawang putih, kunyit, serai, dan rempah.', '28000.00', '32000.00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'p-20261001164001-b48e5ad1.jpg', 1, '2026-10-01 16:40:01'),
(20, 'Gulai Kepala Kakap', 'gulai-kepala-kakap', 'Kepala kakap dengan kuah gulai gurih dan kaya rempah.', 'Olahan kepala ikan kakap yang dimasak dalam kuah gulai berbumbu. Cocok untuk pilihan menu berkuah dengan rasa gurih dan aroma rempah.', 'Kepala ikan kakap, santan, cabai, kunyit, bawang merah, bawang putih, serai, lengkuas, jahe, dan rempah.', '25000.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'p-20261001164048-e90ff008.jpg', 1, '2026-10-01 16:40:48'),
(21, 'Ikan Asam Padeh', 'ikan-asam-padeh', 'Ikan dengan kuah berbumbu yang menghadirkan perpaduan rasa pedas dan asam.', 'Potongan ikan yang dimasak dengan bumbu bercita rasa pedas dan asam. Pilihan yang cocok untuk kamu yang menyukai hidangan ikan dengan rasa yang lebih segar.', 'Ikan, cabai merah, bawang merah, bawang putih, tomat, asam kandis, jahe, kunyit, dan rempah.', '22000.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'p-20261001164132-f4ba52e3.jpg', 1, '2026-10-01 16:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `produk_gambar`
--

CREATE TABLE `produk_gambar` (
  `id` int NOT NULL,
  `produk_id` int NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk_gambar`
--

INSERT INTO `produk_gambar` (`id`, `produk_id`, `path`, `urutan`) VALUES
(5, 14, 'p-20261001163126-aa63b95e.jpg', 1),
(6, 15, 'p-20261001163302-391e786f.jpg', 1),
(7, 16, 'p-20261001163515-e6828e4d.jpg', 1),
(8, 17, 'p-20261001163617-f782471d.jpg', 1),
(9, 18, 'p-20261001163818-2f42b98f.jpg', 1),
(10, 19, 'p-20261001164001-b48e5ad1.jpg', 1),
(11, 20, 'p-20261001164048-e90ff008.jpg', 1),
(12, 21, 'p-20261001164132-f4ba52e3.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int NOT NULL,
  `nama` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_tampil` tinyint(1) NOT NULL DEFAULT '1',
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `nama`, `kota`, `rating`, `pesan`, `is_tampil`, `dibuat_pada`) VALUES
(1, 'Andi Saputra', 'Padang', 5, 'Rendang-nya juara. Bumbu meresap sampai ke serat daging, persis seperti masakan nenek saya dulu. Datang jauh-jauh dari Jakarta tidak sia-sia.', 1, '2026-09-28 15:27:14'),
(2, 'Siti Rahmawati', 'Bukittinggi', 5, 'Gulai kepala kakapnya segar banget, kuahnya kental dan wangi rempah. Saya suka karena tidak terlalu pedas, jadi anak-anak juga bisa makan.', 1, '2026-09-23 15:27:14'),
(3, 'Budi Hartono', 'Jakarta', 4, 'Porsi besar, harga masih masuk kantong. Ayam pop-nya empuk, sambalnya nampol. Sedikit ramai saat jam makan siang, tapi worth it.', 1, '2026-09-17 15:27:14'),
(4, 'Dewi Lestari', 'Yogyakarta', 5, 'Suasananya seperti rumah makan Padang di kampung halaman. Pelayanannya cepat, dan yang paling penting — sambal lado mudonya otentik!', 1, '2026-09-10 15:27:14'),
(5, 'Rizky Maulana', 'Surabaya', 5, 'Saya biasanya tidak suka masakan bersantan, tapi dendeng balado di sini bikin nagih. Renyah, pedas, manisnya pas. Recommended.', 1, '2026-09-01 15:27:14'),
(6, 'Fitri Handayani', 'Medan', 5, 'Sudah tiga kali ke sini, dan kualitasnya konsisten. Sayur nangkanya lembut, rendangnya legit. Cocok buat makan bareng keluarga besar.', 1, '2026-08-17 15:27:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admin_username` (`username`),
  ADD KEY `idx_admin_email` (`email`);

--
-- Indexes for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_admin` (`admin_id`),
  ADD KEY `idx_log_dibuat` (`dibuat_pada`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_produk_kategori` (`kategori_id`),
  ADD KEY `idx_produk_unggulan` (`is_unggulan`);

--
-- Indexes for table `produk_gambar`
--
ALTER TABLE `produk_gambar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gambar_produk` (`produk_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_review_tampil` (`is_tampil`),
  ADD KEY `idx_review_dibuat` (`dibuat_pada`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_log`
--
ALTER TABLE `admin_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `produk_gambar`
--
ALTER TABLE `produk_gambar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_log`
--
ALTER TABLE `admin_log`
  ADD CONSTRAINT `fk_log_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `produk_gambar`
--
ALTER TABLE `produk_gambar`
  ADD CONSTRAINT `fk_gambar_produk` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
