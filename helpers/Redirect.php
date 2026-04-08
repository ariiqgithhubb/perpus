<?php
/**
 * ============================================================================
 * FILE: Redirect.php
 * LOKASI: helpers/Redirect.php
 * FUNGSI: Kelas helper untuk mengarahkan user ke halaman lain
 * ============================================================================
 * 
 * Redirect adalah proses mengarahkan browser user ke URL lain.
 * 
 * Kapan redirect digunakan?
 * - Setelah submit form (Post-Redirect-Get pattern)
 * - Setelah login/logout berhasil
 * - Jika user tidak punya akses ke halaman tertentu
 * - Setelah operasi CRUD sukses
 * 
 * Kelas ini menggunakan HTTP Header 'Location' untuk redirect.
 * 
 * ============================================================================
 */

class Redirect {
    
    /**
     * Redirect ke URL tertentu
     * 
     * @param string $url Path tujuan (tanpa BASE_URL)
     * 
     * Cara kerja:
     * 1. Mengirim HTTP header 'Location' ke browser
     * 2. Browser menerima header dan otomatis pindah ke URL tersebut
     * 3. exit; menghentikan eksekusi PHP (penting agar tidak ada output lain)
     * 
     * Contoh:
     * Redirect::to('admin/buku');          // -> http://localhost/sistem_perpustakaan/admin/buku
     * Redirect::to('login/admin');         // -> http://localhost/sistem_perpustakaan/login/admin
     * Redirect::to('/siswa/peminjaman');   // -> http://localhost/sistem_perpustakaan/siswa/peminjaman
     */
    public static function to($url) {
        // ltrim() menghapus karakter '/' di awal string jika ada
        // BASE_URL adalah konstanta yang didefinisikan di index.php
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        
        // PENTING: exit diperlukan agar script berhenti
        // Jika tidak ada exit, kode setelah redirect tetap dieksekusi
        exit;
    }
    
    /**
     * Redirect kembali ke halaman sebelumnya
     * 
     * Menggunakan HTTP_REFERER untuk mengetahui dari mana user berasal.
     * Jika REFERER tidak tersedia (misal: user ketik URL langsung),
     * maka redirect ke BASE_URL.
     * 
     * Contoh penggunaan:
     * - Setelah validasi form gagal, kembalikan user ke form
     * - Tombol "Kembali" di halaman detail
     */
    public static function back() {
        // $_SERVER['HTTP_REFERER'] berisi URL halaman sebelumnya
        // Null coalescing (??) = gunakan BASE_URL jika REFERER tidak ada
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        
        header('Location: ' . $referer);
        exit;
    }
    
    /**
     * Redirect dengan flash message
     * 
     * Method ini menggabungkan setFlash dan redirect dalam satu panggilan.
     * Berguna untuk menampilkan pesan setelah operasi berhasil/gagal.
     * 
     * @param string $url     Path tujuan redirect
     * @param string $type    Tipe pesan: 'success', 'error', 'warning', 'info'
     * @param string $message Isi pesan yang akan ditampilkan
     * 
     * Contoh:
     * Redirect::withMessage('admin/buku', 'success', 'Buku berhasil disimpan!');
     */
    public static function withMessage($url, $type, $message) {
        Session::setFlash($type, $message); // Set flash message dulu
        self::to($url);                      // Lalu redirect
    }
    
    /**
     * Redirect dengan pesan sukses (shortcut)
     * 
     * @param string $url     Path tujuan
     * @param string $message Pesan sukses
     * 
     * Contoh: Redirect::success('admin/buku', 'Buku berhasil disimpan!');
     */
    public static function success($url, $message) {
        self::withMessage($url, 'success', $message);
    }
    
    /**
     * Redirect dengan pesan error (shortcut)
     * 
     * @param string $url     Path tujuan
     * @param string $message Pesan error
     * 
     * Contoh: Redirect::error('admin/buku/create', 'Gagal menyimpan data!');
     */
    public static function error($url, $message) {
        self::withMessage($url, 'error', $message);
    }
    
    /**
     * Redirect dengan pesan warning (shortcut)
     * 
     * @param string $url     Path tujuan
     * @param string $message Pesan peringatan
     * 
     * Contoh: Redirect::warning('admin/buku', 'Stok buku hampir habis!');
     */
    public static function warning($url, $message) {
        self::withMessage($url, 'warning', $message);
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN DI CONTROLLER:
 * ============================================================================
 * 
 * // Redirect sederhana
 * Redirect::to('admin/buku');
 * 
 * // Redirect dengan pesan sukses
 * Redirect::success('admin/buku', 'Buku berhasil ditambahkan!');
 * 
 * // Redirect dengan pesan error
 * Redirect::error('admin/buku/create', 'Gagal menambahkan buku!');
 * 
 * // Redirect kembali (misal setelah validasi gagal)
 * if ($validation->fails()) {
 *     Session::setFlash('error', $validation->firstError());
 *     Redirect::back();
 * }
 * 
 * // Redirect dengan flash message custom
 * Session::setFlash('info', 'Silakan login terlebih dahulu');
 * Redirect::to('login/admin');
 * 
 * ============================================================================
 * 
 * PATTERN: POST-REDIRECT-GET (PRG)
 * ============================================================================
 * 
 * Pattern ini digunakan untuk mencegah duplicate submission saat refresh.
 * 
 * Alur:
 * 1. User submit form (POST)
 * 2. Server proses data
 * 3. Server REDIRECT ke halaman lain (GET)
 * 4. Jika user refresh, yang di-refresh adalah halaman GET, bukan POST
 * 
 * Contoh di controller:
 * public function store() {
 *     // Proses data POST
 *     $result = $this->model->create($_POST);
 *     
 *     // Redirect (jangan tampilkan view langsung!)
 *     if ($result) {
 *         Redirect::success('admin/buku', 'Berhasil!');
 *     } else {
 *         Redirect::error('admin/buku/create', 'Gagal!');
 *     }
 * }
 * 
 * ============================================================================
 */
