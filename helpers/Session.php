<?php
/**
 * ============================================================================
 * FILE: Session.php
 * LOKASI: helpers/Session.php
 * FUNGSI: Kelas helper untuk mengelola SESSION di PHP
 * ============================================================================
 * 
 * SESSION adalah cara menyimpan data pengguna di server yang bertahan
 * selama pengguna menggunakan website (sampai browser ditutup atau logout).
 * 
 * Kegunaan Session di aplikasi ini:
 * - Menyimpan data login (user_id, user_type)
 * - Menyimpan flash message (pesan sukses/error yang tampil sekali)
 * - Mengecek status login pengguna
 * 
 * Semua method menggunakan STATIC, artinya bisa dipanggil langsung:
 * Session::isLoggedIn() bukan $session->isLoggedIn()
 * 
 * ============================================================================
 */

class Session {
    
    // ========================================================================
    // METHOD DASAR SESSION
    // ========================================================================
    
    /**
     * Memulai session PHP
     * 
     * Session HARUS dimulai sebelum bisa digunakan.
     * Method ini mengecek apakah session sudah dimulai,
     * jika belum maka akan dimulai dengan session_start()
     * 
     * PHP_SESSION_NONE = konstanta yang berarti session belum dimulai
     */
    public static function start() {
        // Cek status session, jika belum dimulai maka mulai
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Memulai session PHP
        }
    }
    
    /**
     * Menyimpan data ke dalam session
     * 
     * @param string $key   Nama/key untuk data yang disimpan
     * @param mixed  $value Data yang ingin disimpan (bisa string, array, dll)
     * 
     * Contoh: Session::set('user_id', 5);
     * Hasilnya: $_SESSION['user_id'] = 5
     */
    public static function set($key, $value) {
        self::start(); // Pastikan session sudah dimulai
        $_SESSION[$key] = $value; // Simpan ke superglobal $_SESSION
    }
    
    /**
     * Mengambil data dari session
     * 
     * @param string $key     Nama/key data yang ingin diambil
     * @param mixed  $default Nilai default jika key tidak ditemukan
     * @return mixed          Data yang disimpan atau nilai default
     * 
     * Contoh: $userId = Session::get('user_id');
     * Contoh dengan default: $role = Session::get('role', 'guest');
     */
    public static function get($key, $default = null) {
        self::start();
        // Null coalescing operator (??) = kembalikan $default jika key tidak ada
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Mengecek apakah suatu key ada di session
     * 
     * @param string $key Nama/key yang ingin dicek
     * @return bool       True jika ada, False jika tidak
     * 
     * Contoh: if (Session::has('user_id')) { ... }
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]); // isset() mengecek apakah variabel ada
    }
    
    /**
     * Menghapus data tertentu dari session
     * 
     * @param string $key Nama/key data yang ingin dihapus
     * 
     * Contoh: Session::remove('user_id');
     */
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]); // unset() menghapus variabel
        }
    }
    
    /**
     * Menghapus SEMUA data session dan mengakhiri session
     * Biasanya dipanggil saat user logout
     * 
     * session_unset() = menghapus semua variabel session
     * session_destroy() = menghancurkan session
     */
    public static function destroy() {
        self::start();
        session_unset();   // Hapus semua data session
        session_destroy(); // Hancurkan session
    }
    
    // ========================================================================
    // FLASH MESSAGE
    // Flash message adalah pesan yang hanya tampil SATU KALI
    // Contoh: "Data berhasil disimpan!" setelah submit form
    // ========================================================================
    
    /**
     * Menyimpan flash message
     * 
     * @param string $type    Tipe pesan: 'success', 'error', 'warning', 'info'
     * @param string $message Isi pesan yang akan ditampilkan
     * 
     * Contoh: Session::setFlash('success', 'Data berhasil disimpan!');
     */
    public static function setFlash($type, $message) {
        self::set('flash', [
            'type' => $type,       // Tipe untuk styling (warna hijau/merah/dll)
            'message' => $message  // Pesan yang ditampilkan
        ]);
    }
    
    /**
     * Mengambil flash message dan langsung menghapusnya
     * 
     * @return array|null Array berisi type dan message, atau null jika tidak ada
     * 
     * Catatan: Flash message otomatis dihapus setelah diambil,
     * sehingga hanya tampil sekali (satu kali refresh)
     */
    public static function getFlash() {
        $flash = self::get('flash'); // Ambil flash message
        self::remove('flash');        // Langsung hapus setelah diambil
        return $flash;
    }
    
    // ========================================================================
    // CEK STATUS LOGIN
    // Method-method untuk mengecek apakah user sudah login dan rolenya
    // ========================================================================
    
    /**
     * Mengecek apakah user sudah login
     * 
     * User dianggap login jika memiliki user_id DAN user_type di session
     * 
     * @return bool True jika sudah login, False jika belum
     */
    public static function isLoggedIn() {
        // User login jika punya user_id DAN user_type
        return self::has('user_id') && self::has('user_type');
    }
    
    /**
     * Mengecek apakah user adalah Admin
     * 
     * @return bool True jika user_type adalah 'admin'
     */
    public static function isAdmin() {
        return self::get('user_type') === 'admin';
    }
    
    /**
     * Mengecek apakah user adalah Siswa
     * 
     * @return bool True jika user_type adalah 'siswa'
     */
    public static function isSiswa() {
        return self::get('user_type') === 'siswa';
    }
    
    // ========================================================================
    // GETTER UNTUK DATA USER
    // ========================================================================
    
    /**
     * Mengambil ID user yang sedang login
     * 
     * @return int|null ID user atau null jika belum login
     */
    public static function getUserId() {
        return self::get('user_id');
    }
    
    /**
     * Mengambil tipe user yang sedang login
     * 
     * @return string|null 'admin' atau 'siswa', atau null jika belum login
     */
    public static function getUserType() {
        return self::get('user_type');
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN:
 * ============================================================================
 * 
 * // Saat login berhasil
 * Session::set('user_id', $user['id']);
 * Session::set('user_type', 'admin');
 * Session::set('user_data', $user);
 * 
 * // Cek login di controller
 * if (!Session::isLoggedIn()) {
 *     Redirect::to('login');
 * }
 * 
 * // Cek role admin
 * if (!Session::isAdmin()) {
 *     Redirect::to('login/admin');
 * }
 * 
 * // Set flash message
 * Session::setFlash('success', 'Data berhasil disimpan!');
 * 
 * // Tampilkan flash message di view
 * $flash = Session::getFlash();
 * if ($flash) {
 *     echo "<div class='alert-{$flash['type']}'>{$flash['message']}</div>";
 * }
 * 
 * // Logout
 * Session::destroy();
 * 
 * ============================================================================
 */
