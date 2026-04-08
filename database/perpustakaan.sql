-- =====================================================
-- Database: db_perpustakaan
-- Sistem Peminjaman Buku - MVC Native OOP
-- =====================================================

-- Buat database
CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;

-- =====================================================
-- Tabel: users (Admin)
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'petugas') DEFAULT 'admin',
    foto VARCHAR(255) DEFAULT NULL,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: kategori_buku
-- =====================================================
CREATE TABLE kategori_buku (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    kode_kategori VARCHAR(10) UNIQUE NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: anggota (Siswa/User)
-- =====================================================
CREATE TABLE anggota (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nis VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    jurusan VARCHAR(100),
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    tanggal_daftar DATE NOT NULL,
    status ENUM('aktif', 'nonaktif', 'suspend') DEFAULT 'aktif',
    max_pinjam INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: buku
-- =====================================================
CREATE TABLE buku (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_buku VARCHAR(20) UNIQUE NOT NULL,
    isbn VARCHAR(20),
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(255) NOT NULL,
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    kategori_id INT,
    jumlah_halaman INT,
    stok INT DEFAULT 0,
    stok_tersedia INT DEFAULT 0,
    lokasi_rak VARCHAR(50),
    deskripsi TEXT,
    cover VARCHAR(255) DEFAULT NULL,
    status ENUM('tersedia', 'tidak_tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_buku(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: peminjaman
-- =====================================================
CREATE TABLE peminjaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_peminjaman VARCHAR(20) UNIQUE NOT NULL,
    anggota_id INT NOT NULL,
    user_id INT,
    tanggal_pinjam DATE NOT NULL,
    tanggal_harus_kembali DATE NOT NULL,
    tanggal_kembali DATE,
    status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam',
    total_denda DECIMAL(10,2) DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: detail_peminjaman
-- =====================================================
CREATE TABLE detail_peminjaman (
    id INT PRIMARY KEY AUTO_INCREMENT,
    peminjaman_id INT NOT NULL,
    buku_id INT NOT NULL,
    jumlah INT DEFAULT 1,
    kondisi_pinjam ENUM('baik', 'rusak_ringan', 'rusak_berat') DEFAULT 'baik',
    kondisi_kembali ENUM('baik', 'rusak_ringan', 'rusak_berat', 'hilang') DEFAULT NULL,
    denda DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: pengembalian
-- =====================================================
CREATE TABLE pengembalian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_pengembalian VARCHAR(20) UNIQUE NOT NULL,
    peminjaman_id INT NOT NULL,
    user_id INT,
    tanggal_pengembalian DATE NOT NULL,
    kondisi_buku ENUM('baik', 'rusak_ringan', 'rusak_berat', 'hilang') DEFAULT 'baik',
    keterlambatan INT DEFAULT 0,
    denda_keterlambatan DECIMAL(10,2) DEFAULT 0,
    denda_kerusakan DECIMAL(10,2) DEFAULT 0,
    total_denda DECIMAL(10,2) DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: pengaturan
-- =====================================================
CREATE TABLE pengaturan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_perpustakaan VARCHAR(255) DEFAULT 'Perpustakaan Sekolah',
    alamat TEXT,
    telepon VARCHAR(20),
    email VARCHAR(100),
    logo VARCHAR(255),
    denda_per_hari DECIMAL(10,2) DEFAULT 1000,
    max_hari_pinjam INT DEFAULT 7,
    max_buku_pinjam INT DEFAULT 3,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- Tabel: log_aktivitas
-- =====================================================
CREATE TABLE log_aktivitas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_type ENUM('admin', 'anggota') NOT NULL,
    user_id INT NOT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    keterangan TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================
-- DATA DUMMY
-- =====================================================

-- Insert Admin
INSERT INTO users (username, password, nama_lengkap, email, role) VALUES
('admin', 'admin123', 'Administrator', 'admin@perpustakaan.com', 'admin'),
('petugas1', 'petugas123', 'Budi Santoso', 'budi@perpustakaan.com', 'petugas');

-- Insert Kategori Buku
INSERT INTO kategori_buku (nama_kategori, kode_kategori, deskripsi) VALUES
('Fiksi', 'FIK', 'Novel, cerpen, dan karya fiksi lainnya'),
('Non-Fiksi', 'NFI', 'Buku pengetahuan umum dan ilmiah'),
('Pendidikan', 'EDU', 'Buku pelajaran dan referensi pendidikan'),
('Teknologi', 'TEK', 'Buku tentang teknologi dan komputer'),
('Sejarah', 'SEJ', 'Buku tentang sejarah dan kebudayaan'),
('Agama', 'AGM', 'Buku tentang agama dan spiritualitas'),
('Bahasa', 'BHS', 'Buku tentang bahasa dan sastra'),
('Sains', 'SCI', 'Buku tentang ilmu pengetahuan alam');

-- Insert Anggota
INSERT INTO anggota (nis, nama, jenis_kelamin, kelas, jurusan, alamat, telepon, username, password, tanggal_daftar) VALUES
('2024001', 'Ahmad Rizki', 'L', 'XII', 'RPL', 'Jl. Merdeka No. 10', '081234567890', 'ahmad', 'siswa123', '2024-01-15'),
('2024002', 'Siti Nurhaliza', 'P', 'XI', 'TKJ', 'Jl. Sudirman No. 25', '081234567891', 'siti', 'siswa123', '2024-01-16'),
('2024003', 'Budi Pratama', 'L', 'X', 'MM', 'Jl. Gatot Subroto No. 5', '081234567892', 'budi', 'siswa123', '2024-01-17'),
('2024004', 'Dewi Lestari', 'P', 'XII', 'RPL', 'Jl. Ahmad Yani No. 15', '081234567893', 'dewi', 'siswa123', '2024-01-18'),
('2024005', 'Rizky Ramadhan', 'L', 'XI', 'TKJ', 'Jl. Diponegoro No. 8', '081234567894', 'rizky', 'siswa123', '2024-01-19');

-- Insert Buku
INSERT INTO buku (kode_buku, isbn, judul, penulis, penerbit, tahun_terbit, kategori_id, jumlah_halaman, stok, stok_tersedia, lokasi_rak, deskripsi) VALUES
('BK001', '978-602-123-001', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 1, 529, 5, 5, 'A-01', 'Novel tentang perjuangan anak-anak Belitung dalam mengejar mimpi'),
('BK002', '978-602-123-002', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 1, 535, 3, 3, 'A-02', 'Novel sejarah tentang perjuangan di era kolonial'),
('BK003', '978-602-123-003', 'Pemrograman PHP', 'Abdul Kadir', 'Andi Publisher', 2020, 4, 450, 4, 4, 'B-01', 'Panduan lengkap pemrograman PHP untuk pemula'),
('BK004', '978-602-123-004', 'Matematika Dasar', 'Tim Matematika', 'Erlangga', 2022, 3, 320, 10, 10, 'C-01', 'Buku pelajaran matematika untuk SMA'),
('BK005', '978-602-123-005', 'Sejarah Indonesia', 'Sartono Kartodirdjo', 'Gramedia', 2018, 5, 280, 6, 6, 'D-01', 'Sejarah Indonesia dari masa ke masa'),
('BK006', '978-602-123-006', 'Fisika Modern', 'Halliday & Resnick', 'Erlangga', 2019, 8, 520, 4, 4, 'C-02', 'Buku referensi fisika untuk tingkat lanjut'),
('BK007', '978-602-123-007', 'Bahasa Indonesia', 'Tim Bahasa', 'Kemendikbud', 2023, 7, 200, 8, 8, 'E-01', 'Buku pelajaran Bahasa Indonesia'),
('BK008', '978-602-123-008', 'Database MySQL', 'Bunafit Nugroho', 'Andi Publisher', 2021, 4, 380, 5, 5, 'B-02', 'Panduan lengkap database MySQL'),
('BK009', '978-602-123-009', 'Pendidikan Agama Islam', 'Tim PAI', 'Kemendikbud', 2023, 6, 180, 7, 7, 'F-01', 'Buku pelajaran Pendidikan Agama Islam'),
('BK010', '978-602-123-010', 'Perahu Kertas', 'Dee Lestari', 'Bentang Pustaka', 2009, 1, 444, 3, 3, 'A-03', 'Novel tentang mimpi dan cinta');

-- Insert Pengaturan
INSERT INTO pengaturan (nama_perpustakaan, alamat, telepon, email, denda_per_hari, max_hari_pinjam, max_buku_pinjam) VALUES
('Perpustakaan SMK Nusantara', 'Jl. Pendidikan No. 1, Kota', '021-12345678', 'perpus@smknusantara.sch.id', 1000, 7, 3);

-- Insert sample peminjaman
INSERT INTO peminjaman (kode_peminjaman, anggota_id, user_id, tanggal_pinjam, tanggal_harus_kembali, status) VALUES
('PJ2024020001', 1, 1, '2024-02-01', '2024-02-08', 'dipinjam'),
('PJ2024020002', 2, 1, '2024-02-02', '2024-02-09', 'dikembalikan');

-- Insert detail peminjaman
INSERT INTO detail_peminjaman (peminjaman_id, buku_id, jumlah, kondisi_pinjam) VALUES
(1, 1, 1, 'baik'),
(1, 3, 1, 'baik'),
(2, 2, 1, 'baik');

-- Update stok tersedia
UPDATE buku SET stok_tersedia = stok_tersedia - 1 WHERE id IN (1, 3);

-- Insert pengembalian untuk peminjaman yang sudah dikembalikan
INSERT INTO pengembalian (kode_pengembalian, peminjaman_id, user_id, tanggal_pengembalian, kondisi_buku, keterlambatan, denda_keterlambatan, total_denda) VALUES
('RET2024020001', 2, 1, '2024-02-08', 'baik', 0, 0, 0);

-- Update peminjaman yang sudah dikembalikan
UPDATE peminjaman SET tanggal_kembali = '2024-02-08', status = 'dikembalikan' WHERE id = 2;
UPDATE buku SET stok_tersedia = stok_tersedia + 1 WHERE id = 2;
