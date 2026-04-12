-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Mar 2026 pada 03.46
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tanggal_daftar` date NOT NULL,
  `status` enum('aktif','nonaktif','suspend') DEFAULT 'aktif',
  `max_pinjam` int(11) DEFAULT 3,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id`, `nis`, `nama`, `jenis_kelamin`, `kelas`, `jurusan`, `alamat`, `telepon`, `email`, `username`, `password`, `foto`, `tanggal_daftar`, `status`, `max_pinjam`, `created_at`, `updated_at`) VALUES
(1, '2024001', 'Ahmad Rizki', 'L', 'XII', 'RPL', 'Jl. Merdeka No. 10', '081234567890', NULL, 'ahmad', 'siswa123', NULL, '2024-01-15', 'aktif', 3, '2026-03-12 07:07:41', '2026-03-12 07:07:41'),
(4, '2024004', 'Dewi Lestari', 'P', 'XII', 'RPL', 'Jl. Ahmad Yani No. 15', '081234567893', NULL, 'dewi', 'dewi123', NULL, '2024-01-18', 'aktif', 3, '2026-03-12 07:07:41', '2026-03-31 01:06:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `jumlah_halaman` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `stok_tersedia` int(11) DEFAULT 0,
  `lokasi_rak` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','tidak_tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `kode_buku`, `isbn`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `kategori_id`, `jumlah_halaman`, `stok`, `stok_tersedia`, `lokasi_rak`, `deskripsi`, `cover`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BK001', '978-602-123-001', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 1, 529, 5, 5, 'A-01', 'Novel tentang perjuangan anak-anak Belitung dalam mengejar mimpi', 'assets/images/covers/1774919931_1362193.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:18:51'),
(2, 'BK002', '978-602-123-002', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', '1980', 1, 535, 3, 3, 'A-02', 'Novel sejarah tentang perjuangan di era kolonial', 'assets/images/covers/1774919870_Karya-BUMI_MANUSIA.png', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:17:50'),
(3, 'BK003', '978-602-123-003', 'Pemrograman PHP', 'Abdul Kadir', 'Andi Publisher', '2020', 4, 450, 4, 4, 'B-01', 'Panduan lengkap pemrograman PHP untuk pemula', 'assets/images/covers/1774919990_dasar_pemrograman.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:19:50'),
(4, 'BK004', '978-602-123-004', 'Matematika Dasar', 'Tim Matematika', 'Erlangga', '2022', 3, 320, 10, 10, 'C-01', 'Buku pelajaran matematika untuk SMA', 'assets/images/covers/1774920029_img20211101_16151238.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:20:29'),
(5, 'BK005', '978-602-123-005', 'Sejarah Indonesia', 'Sartono Kartodirdjo', 'Gramedia', '2018', 5, 280, 6, 6, 'D-01', 'Sejarah Indonesia dari masa ke masa', 'assets/images/covers/1774920077_images.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:21:17'),
(6, 'BK006', '978-602-123-006', 'Fisika Modern', 'Halliday &amp; Resnick', 'Erlangga', '2019', 8, 520, 4, 4, 'C-02', 'Buku referensi fisika untuk tingkat lanjut', 'assets/images/covers/1774920154_images (1).jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:22:34'),
(7, 'BK007', '978-602-123-007', 'Bahasa Indonesia', 'Tim Bahasa', 'Kemendikbud', '2023', 7, 200, 8, 8, 'E-01', 'Buku pelajaran Bahasa Indonesia', 'assets/images/covers/1774920191_CVR-BAHASA-INDONESIA.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:23:11'),
(8, 'BK008', '978-602-123-008', 'Database MySQL', 'Bunafit Nugroho', 'Andi Publisher', '2021', 4, 380, 5, 4, 'B-02', 'Panduan lengkap database MySQL', 'assets/images/covers/1774920245_2005.38.3336.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:24:05'),
(9, 'BK009', '978-602-123-009', 'Pendidikan Agama Islam', 'Tim PAI', 'Kemendikbud', '2023', 6, 180, 7, 7, 'F-01', 'Buku pelajaran Pendidikan Agama Islam', 'assets/images/covers/1774920285_id-11134207-8224z-miii3ux6pxj7cb.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:24:45'),
(10, 'BK010', '978-602-123-010', 'Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', '2009', 1, 444, 3, 3, 'A-03', 'Novel tentang mimpi dan cinta', 'assets/images/covers/1774920321_covBT-543.jpg', 'tersedia', '2026-03-12 07:07:41', '2026-03-31 01:25:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id` int(11) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `buku_id` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `kondisi_pinjam` enum('baik','rusak_ringan','rusak_berat') DEFAULT 'baik',
  `kondisi_kembali` enum('baik','rusak_ringan','rusak_berat','hilang') DEFAULT NULL,
  `denda` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id`, `peminjaman_id`, `buku_id`, `jumlah`, `kondisi_pinjam`, `kondisi_kembali`, `denda`, `created_at`) VALUES
(6, 5, 8, 1, 'baik', NULL, 0.00, '2026-03-31 01:03:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `kode_kategori` varchar(10) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_buku`
--

INSERT INTO `kategori_buku` (`id`, `nama_kategori`, `kode_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Fiksi', 'FIK', 'Novel, cerpen, dan karya fiksi lainnya', '2026-03-12 07:07:41'),
(2, 'Non-Fiksi', 'NFI', 'Buku pengetahuan umum dan ilmiah', '2026-03-12 07:07:41'),
(3, 'Pendidikan', 'EDU', 'Buku pelajaran dan referensi pendidikan', '2026-03-12 07:07:41'),
(4, 'Teknologi', 'TEK', 'Buku tentang teknologi dan komputer', '2026-03-12 07:07:41'),
(5, 'Sejarah', 'SEJ', 'Buku tentang sejarah dan kebudayaan', '2026-03-12 07:07:41'),
(6, 'Agama', 'AGM', 'Buku tentang agama dan spiritualitas', '2026-03-12 07:07:41'),
(7, 'Bahasa', 'BHS', 'Buku tentang bahasa dan sastra', '2026-03-12 07:07:41'),
(8, 'Sains', 'SCI', 'Buku tentang ilmu pengetahuan alam', '2026-03-12 07:07:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','anggota') NOT NULL,
  `user_id` int(11) NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_type`, `user_id`, `aktivitas`, `keterangan`, `ip_address`, `created_at`) VALUES
(1, 'admin', 1, 'Login sebagai admin', NULL, '::1', '2026-03-12 07:11:31'),
(2, 'admin', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 00:56:03'),
(3, 'anggota', 1, 'Login sebagai siswa', NULL, '::1', '2026-03-31 00:57:17'),
(4, 'anggota', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 00:58:19'),
(5, 'admin', 1, 'Login sebagai admin', NULL, '::1', '2026-03-31 00:58:25'),
(6, 'admin', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 00:59:26'),
(7, 'anggota', 1, 'Login sebagai siswa', NULL, '::1', '2026-03-31 00:59:37'),
(8, 'anggota', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 00:59:57'),
(9, 'admin', 1, 'Login sebagai admin', NULL, '::1', '2026-03-31 01:00:06'),
(10, 'admin', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 01:00:21'),
(11, 'anggota', 1, 'Login sebagai siswa', NULL, '::1', '2026-03-31 01:00:27'),
(12, 'anggota', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 01:03:20'),
(13, 'admin', 1, 'Login sebagai admin', NULL, '::1', '2026-03-31 01:03:37'),
(14, 'admin', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 01:38:45'),
(15, 'anggota', 4, 'Login sebagai siswa', NULL, '::1', '2026-03-31 01:38:57'),
(16, 'anggota', 4, 'Logout dari sistem', NULL, '::1', '2026-03-31 01:39:25'),
(17, 'anggota', 1, 'Login sebagai siswa', NULL, '::1', '2026-03-31 01:39:31'),
(18, 'anggota', 1, 'Logout dari sistem', NULL, '::1', '2026-03-31 01:39:38'),
(19, 'admin', 2, 'Login sebagai admin', NULL, '::1', '2026-03-31 01:40:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `kode_peminjaman` varchar(20) NOT NULL,
  `anggota_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_harus_kembali` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('pending','dipinjam','dikembalikan','terlambat','ditolak') DEFAULT 'pending',
  `total_denda` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `kode_peminjaman`, `anggota_id`, `user_id`, `tanggal_pinjam`, `tanggal_harus_kembali`, `tanggal_kembali`, `status`, `total_denda`, `keterangan`, `created_at`, `updated_at`) VALUES
(5, 'PJ202603310001', 1, 1, '2026-03-30', '2026-04-07', NULL, 'dipinjam', 0.00, 'Peminjaman mandiri oleh siswa', '2026-03-31 01:03:07', '2026-03-31 01:04:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL,
  `nama_perpustakaan` varchar(255) DEFAULT 'Perpustakaan Sekolah',
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `denda_per_hari` decimal(10,2) DEFAULT 1000.00,
  `max_hari_pinjam` int(11) DEFAULT 7,
  `max_buku_pinjam` int(11) DEFAULT 3,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_perpustakaan`, `alamat`, `telepon`, `email`, `logo`, `denda_per_hari`, `max_hari_pinjam`, `max_buku_pinjam`, `created_at`, `updated_at`) VALUES
(1, 'Perpustakaan Nusantara', 'Jl. Pendidikan No. 1, Kota', '021-12345678', 'perpusnusantara@gmail.com', NULL, 1000.00, 7, 100, '2026-03-12 07:07:41', '2026-03-31 01:41:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id` int(11) NOT NULL,
  `kode_pengembalian` varchar(20) NOT NULL,
  `peminjaman_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `kondisi_buku` enum('baik','rusak_ringan','rusak_berat','hilang') DEFAULT 'baik',
  `keterlambatan` int(11) DEFAULT 0,
  `denda_keterlambatan` decimal(10,2) DEFAULT 0.00,
  `denda_kerusakan` decimal(10,2) DEFAULT 0.00,
  `total_denda` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas') DEFAULT 'admin',
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `foto`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'Administrator', 'admin@perpustakaan.com', 'admin', NULL, 'aktif', '2026-03-30 18:03:37', '2026-03-12 07:07:41', '2026-03-31 01:03:37'),
(2, 'petugas1', 'petugas123', 'Budi Santoso', 'budi@perpustakaan.com', 'petugas', NULL, 'aktif', '2026-03-30 18:40:08', '2026-03-12 07:07:41', '2026-03-31 01:40:08');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_id` (`peminjaman_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indeks untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kategori` (`kode_kategori`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_peminjaman` (`kode_peminjaman`),
  ADD KEY `anggota_id` (`anggota_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pengembalian` (`kode_pengembalian`),
  ADD KEY `peminjaman_id` (`peminjaman_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_buku` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_ibfk_1` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengembalian_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
