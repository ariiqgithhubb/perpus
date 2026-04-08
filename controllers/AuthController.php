<?php
/**
 * ============================================================================
 * FILE: AuthController.php
 * LOKASI: controllers/AuthController.php
 * FUNGSI: Controller untuk menangani autentikasi (login, logout, register)
 * ============================================================================
 * 
 * Controller ini menangani semua proses autentikasi:
 * - Login admin (petugas perpustakaan)
 * - Login siswa (anggota perpustakaan)
 * - Registrasi siswa baru
 * - Logout
 * - Pencatatan aktivitas (logging)
 * 
 * ============================================================================
 */

class AuthController {
    
    // ========================================================================
    // PROPERTI KELAS
    // ========================================================================
    
    /**
     * @var User $userModel - Model untuk tabel users (admin/petugas)
     */
    private $userModel;
    
    /**
     * @var Anggota $anggotaModel - Model untuk tabel anggota (siswa)
     */
    private $anggotaModel;
    
    // ========================================================================
    // CONSTRUCTOR
    // ========================================================================
    
    /**
     * Constructor - Inisialisasi model yang dibutuhkan
     * 
     * Constructor di controller biasanya digunakan untuk:
     * 1. Membuat instance model yang diperlukan
     * 2. Mengecek autentikasi (tidak dilakukan di sini karena ini controller auth)
     */
    public function __construct() {
        $this->userModel = new User();       // Model untuk data admin
        $this->anggotaModel = new Anggota(); // Model untuk data siswa
    }
    
    // ========================================================================
    // HALAMAN UTAMA LOGIN
    // ========================================================================
    
    /**
     * Menampilkan halaman utama login (pilih role)
    // LOGIN (UNIFIED)
    // ========================================================================
    
    /**
     * Menampilkan halaman login atau memproses login (Unified Login)
     * 
     * Alur:
     * 1. GET: Tampilkan form login gabungan
     * 2. POST: Proses login
     *    - Cek tabel users (admin)
     *    - Jika gagal, cel tabel anggota (siswa)
     */
    public function index() {
        // Jika sudah login, redirect sesuai role
        if (Session::isLoggedIn()) {
            if (Session::isAdmin()) {
                Redirect::to('admin/dashboard');
            } else {
                Redirect::to('siswa/dashboard');
            }
            return;
        }
        
        // Handle Request POST (Proses Login)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
            return;
        }
        
        // Tampilkan halaman login gabungan
        include BASE_PATH . '/views/auth/login.php';
    }
    
    /**
     * Memproses login gabungan (Admin & Siswa)
     */
    private function processLogin() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validasi
        $validation = new Validation($_POST);
        $validation->required('username', 'Username')
                   ->required('password', 'Password');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('login');
            return;
        }
        
        // 1. Coba login sebagai ADMIN
        $user = $this->userModel->authenticate($username, $password);
        if ($user) {
            Session::set('user_id', $user['id']);
            Session::set('user_type', 'admin');
            Session::set('user_data', $user);
            
            $this->logActivity('admin', $user['id'], 'Login sebagai admin');
            Session::setFlash('success', 'Selamat datang, ' . $user['nama_lengkap']);
            Redirect::to('admin/dashboard');
            return;
        }
        
        // 2. Coba login sebagai SISWA
        $anggota = $this->anggotaModel->authenticate($username, $password);
        if ($anggota) {
            Session::set('user_id', $anggota['id']);
            Session::set('user_type', 'siswa');
            Session::set('user_data', $anggota);
            
            $this->logActivity('anggota', $anggota['id'], 'Login sebagai siswa');
            Session::setFlash('success', 'Selamat datang, ' . $anggota['nama']);
            Redirect::to('siswa/dashboard');
            return;
        }
        
        // 3. Login Gagal
        Session::setFlash('error', 'Username atau password salah');
        Redirect::to('login');
    }
    
    // ========================================================================
    // REGISTRASI SISWA
    // ========================================================================
    
    /**
     * Menampilkan form registrasi atau memproses registrasi
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
            return;
        }
        
        include BASE_PATH . '/views/auth/register.php';
    }
    
    /**
     * Memproses registrasi siswa baru
     * 
     * Alur:
     * 1. Sanitize input (bersihkan dari karakter berbahaya)
     * 2. Validasi semua field wajib
     * 3. Cek apakah NIS sudah terdaftar
     * 4. Cek apakah username sudah digunakan
     * 5. Simpan data ke database
     * 6. Redirect ke halaman login
     */
    private function processRegister() {
        // Sanitize semua input POST untuk mencegah XSS
        $data = Validation::sanitize($_POST);
        
        // Validasi lengkap dengan method chaining
        $validation = new Validation($data);
        $validation->required('nis', 'NIS')
                   ->required('nama', 'Nama')
                   ->required('jenis_kelamin', 'Jenis Kelamin')
                   ->required('kelas', 'Kelas')
                   ->required('username', 'Username')
                   ->required('password', 'Password')
                   ->minLength('password', 6, 'Password')      // Minimal 6 karakter
                   ->match('password', 'confirm_password', 'Konfirmasi Password'); // Harus sama
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('register');
            return;
        }
        
        // Cek apakah NIS sudah terdaftar
        if ($this->anggotaModel->nisExists($data['nis'])) {
            Session::setFlash('error', 'NIS sudah terdaftar');
            Redirect::to('register');
            return;
        }
        
        // Cek apakah username sudah digunakan
        if ($this->anggotaModel->usernameExists($data['username'])) {
            Session::setFlash('error', 'Username sudah digunakan');
            Redirect::to('register');
            return;
        }
        
        // Semua validasi lolos, simpan ke database
        $result = $this->anggotaModel->register($data);
        
        if ($result) {
            Session::setFlash('success', 'Pendaftaran berhasil! Silakan login.');
            Redirect::to('login/siswa');
        } else {
            Session::setFlash('error', 'Pendaftaran gagal. Silakan coba lagi.');
            Redirect::to('register');
        }
    }
    
    // ========================================================================
    // LOGOUT
    // ========================================================================
    
    /**
     * Proses logout - Menghapus session dan redirect ke login
     * 
     * Alur:
     * 1. Catat aktivitas logout
     * 2. Hapus semua data session
     * 3. Tampilkan pesan sukses
     * 4. Redirect ke halaman login
     */
    public function logout() {
        // Catat aktivitas sebelum session dihapus
        if (Session::isLoggedIn()) {
            $this->logActivity(
                Session::getUserType() === 'admin' ? 'admin' : 'anggota',
                Session::getUserId(),
                'Logout dari sistem'
            );
        }
        
        // Hapus semua data session
        Session::destroy();
        
        // Set flash message (ini membuat session baru, tapi hanya berisi flash)
        Session::setFlash('success', 'Anda telah berhasil logout');
        
        // Redirect ke halaman login
        Redirect::to('login');
    }
    
    // ========================================================================
    // HELPER METHOD - LOGGING
    // ========================================================================
    
    /**
     * Mencatat aktivitas user ke tabel log_aktivitas
     * 
     * @param string $userType Tipe user ('admin' atau 'anggota')
     * @param int    $userId   ID user yang melakukan aktivitas
     * @param string $aktivitas Keterangan aktivitas
     * 
     * Method ini menggunakan try-catch karena logging bersifat opsional
     * Jika gagal, aplikasi tetap berjalan (silently fail)
     */
    private function logActivity($userType, $userId, $aktivitas) {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                INSERT INTO log_aktivitas (user_type, user_id, aktivitas, ip_address)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $userType,
                $userId,
                $aktivitas,
                $_SERVER['REMOTE_ADDR'] ?? null  // Alamat IP user
            ]);
        } catch (Exception $e) {
            // Silently fail - jangan tampilkan error ke user
            // Logging gagal bukan masalah kritis
        }
    }
}

/**
 * ============================================================================
 * RINGKASAN ALUR LOGIN:
 * ============================================================================
 * 
 * 1. User buka halaman login (GET /login/admin)
 *    -> Method loginAdmin() dipanggil
 *    -> Tampilkan form login (login_admin.php)
 * 
 * 2. User submit form (POST /login/admin)
 *    -> Method loginAdmin() dipanggil
 *    -> Cek REQUEST_METHOD = POST
 *    -> Panggil processLoginAdmin()
 * 
 * 3. processLoginAdmin() dijalankan:
 *    -> Ambil username & password dari $_POST
 *    -> Validasi tidak kosong
 *    -> Cek ke database via userModel->authenticate()
 *    -> Jika valid: simpan ke session, redirect ke dashboard
 *    -> Jika tidak: set error, redirect ke form login
 * 
 * ============================================================================
 */
