<?php
/**
 * ============================================================================
 * FILE: index.php
 * LOKASI: /index.php (Root folder)
 * FUNGSI: Entry Point / Router - File utama yang menangani semua request
 * ============================================================================
 * 
 * File ini adalah FRONT CONTROLLER - satu titik masuk untuk semua request.
 * Semua URL akan diarahkan ke file ini melalui .htaccess
 * 
 * Alur kerja:
 * 1. User akses URL (misal: /admin/buku/edit?id=5)
 * 2. .htaccess meneruskan ke index.php?url=admin/buku/edit
 * 3. Router memparse URL dan menentukan Controller + Action
 * 4. Controller dipanggil dan mengeksekusi action
 * 5. View di-include dan response dikirim ke browser
 * 
 * ============================================================================
 */

// ============================================================================
// INISIALISASI SESSION
// ============================================================================

/**
 * Memulai session PHP
 * Session digunakan untuk menyimpan data login user
 * HARUS dipanggil di awal sebelum ada output apapun
 */
session_start();

// ============================================================================
// DEFINISI KONSTANTA
// ============================================================================

/**
 * BASE_PATH = Path absolut ke folder root aplikasi
 * __DIR__ adalah magic constant PHP yang berisi path folder file ini
 * 
 * Digunakan untuk: include file, upload file, dll
 * Contoh: BASE_PATH . '/views/admin/buku/index.php'
 */
define('BASE_PATH', __DIR__);

/**
 * BASE_URL = URL dasar aplikasi (tanpa domain)
 * 
 * PENTING: Sesuaikan dengan lokasi folder project Anda!
 * 
 * Contoh untuk XAMPP:
 * - Jika folder di htdocs/sistem_perpustakaan -> '/sistem_perpustakaan'
 * - Jika folder di htdocs/native_oop/joki/sistem_perpustakaan -> '/native_oop/joki/sistem_perpustakaan'
 * 
 * Digunakan untuk: membuat URL di view (link, form action, src gambar)
 */
define('BASE_URL', '/sistem_perpustakaan_ariq');

// ============================================================================
// AUTOLOAD CLASSES
// ============================================================================

/**
 * spl_autoload_register = Mendaftarkan fungsi autoload
 * 
 * Autoload bekerja seperti ini:
 * 1. Ketika kode memanggil class yang belum di-include (misal: new Buku())
 * 2. PHP mencari fungsi autoload yang terdaftar
 * 3. Fungsi autoload mencari file class berdasarkan nama class
 * 4. Jika ditemukan, file di-require secara otomatis
 * 
 * Keuntungan:
 * - Tidak perlu menulis require_once untuk setiap class
 * - Class hanya di-load saat dibutuhkan (lebih efisien)
 */
spl_autoload_register(function ($class) {
    // Daftar folder tempat mencari file class
    $paths = [
        BASE_PATH . '/config/',      // Class Database, dll
        BASE_PATH . '/models/',      // Class Buku, Anggota, Peminjaman, dll
        BASE_PATH . '/controllers/', // Class BukuController, AnggotaController, dll
        BASE_PATH . '/helpers/'      // Class Session, Validation, Redirect
    ];
    
    // Cari di setiap folder
    foreach ($paths as $path) {
        $file = $path . $class . '.php'; // Contoh: /models/Buku.php
        if (file_exists($file)) {
            require_once $file; // Load file jika ditemukan
            return; // Stop pencarian
        }
    }
});

// ============================================================================
// PARSING URL REQUEST
// ============================================================================

/**
 * Mengambil URL dari parameter GET 'url'
 * URL ini dikirim oleh .htaccess melalui RewriteRule
 * 
 * Contoh:
 * User akses: http://localhost/sistem_perpustakaan/admin/buku/edit
 * .htaccess kirim: index.php?url=admin/buku/edit
 * $_GET['url'] = 'admin/buku/edit'
 */
$request = $_GET['url'] ?? ''; // Null coalescing: gunakan '' jika url tidak ada

/**
 * rtrim = Remove chars from right side
 * Menghapus trailing slash (/) di akhir URL
 * Contoh: 'admin/buku/' menjadi 'admin/buku'
 */
$request = rtrim($request, '/');

/**
 * filter_var dengan FILTER_SANITIZE_URL
 * Membersihkan URL dari karakter yang tidak valid
 * Ini untuk keamanan agar tidak ada karakter berbahaya
 */
$request = filter_var($request, FILTER_SANITIZE_URL);

/**
 * explode = Memecah string menjadi array berdasarkan delimiter
 * 
 * Contoh: 'admin/buku/edit' menjadi ['admin', 'buku', 'edit']
 * $params[0] = 'admin'
 * $params[1] = 'buku'
 * $params[2] = 'edit'
 */
$params = explode('/', $request);

// ============================================================================
// MENENTUKAN CONTROLLER DAN ACTION DEFAULT
// ============================================================================

/**
 * Jika URL kosong, gunakan AuthController sebagai default
 * ucfirst = Uppercase first letter (admin -> Admin)
 * 
 * Contoh:
 * - URL '' -> AuthController
 * - URL 'admin' -> AdminController
 * - URL 'admin/buku' -> AdminController (tapi nanti di-override oleh route)
 */
$controllerName = !empty($params[0]) ? ucfirst($params[0]) . 'Controller' : 'AuthController';

/**
 * Action default adalah 'index'
 * Action = method yang akan dipanggil di controller
 */
$action = $params[1] ?? 'index';

/**
 * Parameter ID dari URL segment ke-3 (jika ada)
 * Contoh: admin/buku/show/5 -> $id = 5
 */
$id = $params[2] ?? null;

// ============================================================================
// ROUTE MAPPING (PETA URL KE CONTROLLER)
// ============================================================================

/**
 * Array yang memetakan URL ke Controller dan Action
 * 
 * Format: 'url_path' => ['NamaController', 'namaAction']
 * 
 * Keuntungan menggunakan route mapping:
 * - URL lebih fleksibel dan mudah diubah
 * - Controller dan action tidak harus sesuai nama URL
 * - Lebih aman karena hanya URL terdaftar yang bisa diakses
 */
$routes = [
    // ========================================================================
    // ROUTE AUTH
    // ========================================================================
    '' => ['AuthController', 'index'],                // Halaman utama (pilih login)
    'login' => ['AuthController', 'index'],           // Unified Login Page
    'logout' => ['AuthController', 'logout'],         // Logout
    'register' => ['AuthController', 'register'],     // Registrasi (jika ada)
    
    // ========================================================================
    // ROUTE ADMIN - DASHBOARD
    // ========================================================================
    'admin' => ['AdminController', 'index'],         // Dashboard admin
    'admin/dashboard' => ['AdminController', 'index'],
    
    // ========================================================================
    // ROUTE ADMIN - KELOLA BUKU (CRUD)
    // ========================================================================
    'admin/buku' => ['BukuController', 'index'],           // Daftar buku
    'admin/buku/create' => ['BukuController', 'create'],   // Form tambah buku
    'admin/buku/store' => ['BukuController', 'store'],     // Proses simpan buku baru
    'admin/buku/show' => ['BukuController', 'show'],       // Detail buku
    'admin/buku/edit' => ['BukuController', 'edit'],       // Form edit buku
    'admin/buku/update' => ['BukuController', 'update'],   // Proses update buku
    'admin/buku/delete' => ['BukuController', 'delete'],   // Proses hapus buku
    'admin/buku/search' => ['BukuController', 'search'],   // Cari buku
    
    // ========================================================================
    // ROUTE ADMIN - KELOLA ANGGOTA (CRUD)
    // ========================================================================
    'admin/anggota' => ['AnggotaController', 'index'],
    'admin/anggota/create' => ['AnggotaController', 'create'],
    'admin/anggota/store' => ['AnggotaController', 'store'],
    'admin/anggota/show' => ['AnggotaController', 'show'],
    'admin/anggota/edit' => ['AnggotaController', 'edit'],
    'admin/anggota/update' => ['AnggotaController', 'update'],
    'admin/anggota/delete' => ['AnggotaController', 'delete'],
    'admin/anggota/search' => ['AnggotaController', 'search'],
    
    // ========================================================================
    // ROUTE ADMIN - TRANSAKSI PEMINJAMAN
    // ========================================================================
    'admin/transaksi' => ['TransaksiController', 'index'],         // Daftar transaksi
    'admin/transaksi/create' => ['TransaksiController', 'create'], // Form peminjaman baru
    'admin/transaksi/store' => ['TransaksiController', 'store'],   // Proses simpan peminjaman
    'admin/transaksi/show' => ['TransaksiController', 'show'],     // Detail transaksi
    'admin/transaksi/edit' => ['TransaksiController', 'edit'],     // Form edit transaksi
    'admin/transaksi/update' => ['TransaksiController', 'update'], // Proses update transaksi
    'admin/transaksi/delete' => ['TransaksiController', 'delete'], // Hapus transaksi
    'admin/transaksi/search' => ['TransaksiController', 'search'], // Cari transaksi
    
    // ========================================================================
    // ROUTE ADMIN - PENGEMBALIAN BUKU
    // ========================================================================
    'admin/pengembalian' => ['TransaksiController', 'pengembalian'],            // Daftar pengembalian
    'admin/pengembalian/process' => ['TransaksiController', 'processPengembalian'], // Form proses kembali
    'admin/pengembalian/store' => ['TransaksiController', 'storePengembalian'], // Simpan pengembalian
    
    // ========================================================================
    // ROUTE ADMIN - PENDING APPROVAL (PERSETUJUAN PEMINJAMAN)
    // ========================================================================
    'admin/pending' => ['TransaksiController', 'pending'],           // Daftar pengajuan pending
    'admin/pending/approve' => ['TransaksiController', 'approve'],   // Setujui peminjaman
    'admin/pending/reject' => ['TransaksiController', 'reject'],     // Tolak peminjaman
    
    // ========================================================================
    // ROUTE SISWA - DASHBOARD
    // ========================================================================
    'siswa' => ['SiswaController', 'index'],         // Dashboard siswa
    'siswa/dashboard' => ['SiswaController', 'index'],
    
    // ========================================================================
    // ROUTE SISWA - PEMINJAMAN BUKU
    // ========================================================================
    'siswa/peminjaman' => ['PeminjamanController', 'index'],           // Katalog buku
    'siswa/peminjaman/create' => ['PeminjamanController', 'create'],   // Konfirmasi pinjam
    'siswa/peminjaman/store' => ['PeminjamanController', 'store'],     // Proses pinjam
    'siswa/peminjaman/history' => ['PeminjamanController', 'history'], // Riwayat peminjaman
    'siswa/peminjaman/search' => ['PeminjamanController', 'search'],   // Cari buku
    
    // ========================================================================
    // ROUTE SISWA - PENGEMBALIAN
    // ========================================================================
    'siswa/pengembalian' => ['PengembalianController', 'index'],
    'siswa/pengembalian/process' => ['PengembalianController', 'process'],
    'siswa/pengembalian/store' => ['PengembalianController', 'store'],
    'siswa/pengembalian/history' => ['PengembalianController', 'history'],
];

// ============================================================================
// PROSES ROUTING
// ============================================================================

/**
 * $routeKey = URL yang akan dicari di daftar routes
 * Awalnya sama dengan $request (URL asli)
 */
$routeKey = $request;

/**
 * Handle ID dalam URL path (format: admin/buku/show/11)
 * 
 * Beberapa sistem menggunakan ID di URL path, bukan query string
 * Contoh: /admin/buku/show/11 bukan /admin/buku/show?id=11
 * 
 * Jika ada 4 segment dan segment ke-4 adalah angka,
 * kita pisahkan ID dari route key
 */
$pathId = null;
if (count($params) >= 4 && is_numeric($params[3])) {
    $pathId = $params[3];
    // Rebuild route key tanpa ID
    // Contoh: ['admin', 'buku', 'show', '11'] -> 'admin/buku/show'
    $routeKey = $params[0] . '/' . $params[1] . '/' . $params[2];
}

/**
 * Cari route key di daftar routes
 * Jika ditemukan, override controller dan action
 */
if (isset($routes[$routeKey])) {
    $controllerName = $routes[$routeKey][0]; // Nama controller dari route
    $action = $routes[$routeKey][1];          // Nama action dari route
}

// ============================================================================
// LOAD DAN EKSEKUSI CONTROLLER
// ============================================================================

/**
 * Membangun path lengkap ke file controller
 * Contoh: /var/www/html/sistem_perpustakaan/controllers/BukuController.php
 */
$controllerFile = BASE_PATH . '/controllers/' . $controllerName . '.php';

/**
 * Cek apakah file controller ada
 */
if (file_exists($controllerFile)) {
    // Load file controller
    require_once $controllerFile;
    
    /**
     * Cek apakah class controller ada di file tersebut
     * class_exists() mengecek apakah class sudah didefinisikan
     */
    if (class_exists($controllerName)) {
        // Buat instance (objek) baru dari controller
        // Contoh: new BukuController()
        $controller = new $controllerName();
        
        /**
         * Cek apakah method (action) ada di controller
         * method_exists() mengecek keberadaan method dalam object
         */
        if (method_exists($controller, $action)) {
            /**
             * Panggil method controller
             * 
             * Jika ada ID dari path URL (pathId), kirim sebagai parameter
             * Jika tidak, controller akan mengambil ID dari $_GET['id']
             * 
             * Contoh:
             * $controller->show($pathId)  -- jika pathId ada
             * $controller->show()         -- jika pathId null (ambil dari $_GET)
             */
            if ($pathId !== null) {
                $controller->$action($pathId);
            } else {
                $controller->$action();
            }
        } else {
            // Method tidak ditemukan di controller -> 404
            http_response_code(404);
            include BASE_PATH . '/views/errors/404.php';
        }
    } else {
        // Class tidak ditemukan dalam file -> 404
        http_response_code(404);
        include BASE_PATH . '/views/errors/404.php';
    }
} else {
    // File controller tidak ditemukan -> 404
    http_response_code(404);
    include BASE_PATH . '/views/errors/404.php';
}

/**
 * ============================================================================
 * RINGKASAN ALUR ROUTING:
 * ============================================================================
 * 
 * 1. User akses: http://localhost/sistem_perpustakaan/admin/buku/edit?id=5
 * 
 * 2. .htaccess rewrite menjadi: index.php?url=admin/buku/edit
 *    (parameter ?id=5 tetap tersimpan di $_GET['id'])
 * 
 * 3. Router ekstrak: $request = 'admin/buku/edit'
 * 
 * 4. Router cari di $routes -> ditemukan: ['BukuController', 'edit']
 * 
 * 5. Router load: /controllers/BukuController.php
 * 
 * 6. Router panggil: $controller->edit()
 * 
 * 7. BukuController::edit() mengambil ID dari $_GET['id']
 * 
 * 8. BukuController::edit() load view: /views/admin/buku/edit.php
 * 
 * 9. Response HTML dikirim ke browser
 * 
 * ============================================================================
 */
