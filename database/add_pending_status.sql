-- =====================================================
-- Migration: Tambah status 'pending' dan 'ditolak' pada peminjaman
-- Jalankan query ini di phpMyAdmin atau MySQL CLI
-- =====================================================

USE db_perpustakaan;

-- Ubah ENUM status pada tabel peminjaman
ALTER TABLE peminjaman 
MODIFY COLUMN status ENUM('pending', 'dipinjam', 'dikembalikan', 'terlambat', 'ditolak') DEFAULT 'pending';

-- Verifikasi perubahan
DESCRIBE peminjaman;
