<?php
/**
 * ============================================================================
 * FILE: Database.php
 * LOKASI: config/Database.php
 * FUNGSI: Kelas untuk mengelola koneksi ke database MySQL
 * ============================================================================
 * 
 * Kelas ini menggunakan SINGLETON PATTERN, yaitu pola desain yang memastikan
 * hanya ada SATU instance (objek) koneksi database di seluruh aplikasi.
 * 
 * Mengapa menggunakan Singleton?
 * - Menghemat memori karena tidak membuat koneksi baru setiap kali dibutuhkan
 * - Memastikan konsistensi koneksi di seluruh aplikasi
 * - Mencegah terlalu banyak koneksi yang terbuka ke database
 * 
 * Cara penggunaan:
 * $db = Database::getInstance()->getConnection();
 * 
 * ============================================================================
 */

class Database {
    
    // ========================================================================
    // PROPERTI (VARIABEL) KELAS
    // ========================================================================
    
    /**
     * Menyimpan instance tunggal dari kelas Database
     * Static berarti variabel ini milik kelas, bukan milik objek
     * Private berarti hanya bisa diakses dari dalam kelas ini
     */
    private static $instance = null;
    
    /**
     * Menyimpan objek koneksi PDO ke database
     * PDO = PHP Data Objects, cara modern untuk koneksi database di PHP
     */
    private $connection;
    
    // ========================================================================
    // KONFIGURASI DATABASE
    // Ubah nilai-nilai ini sesuai dengan setting database Anda
    // ========================================================================
    
    private $host = 'localhost';        // Alamat server database (biasanya localhost)
    private $db_name = 'db_perpustakaan'; // Nama database yang akan digunakan
    private $username = 'root';          // Username untuk login ke MySQL
    private $password = '';              // Password MySQL (kosong untuk XAMPP default)
    private $charset = 'utf8mb4';        // Charset untuk mendukung emoji dan karakter unicode
    
    // ========================================================================
    // CONSTRUCTOR (FUNGSI YANG DIPANGGIL SAAT OBJEK DIBUAT)
    // ========================================================================
    
    /**
     * Constructor dibuat PRIVATE agar tidak bisa dibuat objek dari luar kelas
     * Ini adalah bagian penting dari Singleton Pattern
     * Objek hanya bisa dibuat melalui method getInstance()
     */
    private function __construct() {
        // Try-Catch digunakan untuk menangani error yang mungkin terjadi
        try {
            // DSN (Data Source Name) = string yang berisi informasi koneksi database
            // Format: "mysql:host=xxx;dbname=xxx;charset=xxx"
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            
            // Opsi konfigurasi PDO
            $options = [
                // ERRMODE_EXCEPTION = PDO akan melempar exception jika ada error
                // Ini memudahkan debugging karena error akan ditampilkan
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                
                // FETCH_ASSOC = Hasil query dikembalikan sebagai array asosiatif
                // Contoh: ['id' => 1, 'nama' => 'Buku A'] bukan [0 => 1, 1 => 'Buku A']
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                
                // EMULATE_PREPARES false = Gunakan prepared statement asli dari MySQL
                // Ini lebih aman dari SQL Injection
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            // Membuat koneksi baru ke database menggunakan PDO
            // Parameter: DSN, username, password, options
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            // Jika koneksi gagal, tampilkan pesan error dan hentikan program
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    // ========================================================================
    // METHOD UNTUK SINGLETON PATTERN
    // ========================================================================
    
    /**
     * Method untuk mendapatkan instance Database (Singleton)
     * 
     * Static berarti method ini bisa dipanggil tanpa membuat objek:
     * Database::getInstance() bukan $db->getInstance()
     * 
     * @return Database Instance dari kelas Database
     */
    public static function getInstance() {
        // Cek apakah instance sudah ada
        if (self::$instance === null) {
            // Jika belum ada, buat instance baru
            // self:: digunakan untuk mengakses kelas itu sendiri
            self::$instance = new self();
        }
        // Kembalikan instance yang sudah ada atau baru dibuat
        return self::$instance;
    }
    
    /**
     * Method untuk mendapatkan objek koneksi PDO
     * 
     * @return PDO Objek koneksi PDO yang bisa digunakan untuk query
     */
    public function getConnection() {
        return $this->connection;
    }
    
    // ========================================================================
    // METHOD UNTUK MENCEGAH DUPLIKASI SINGLETON
    // ========================================================================
    
    /**
     * Method __clone dibuat private untuk mencegah cloning objek
     * Contoh yang dicegah: $db2 = clone $db1;
     */
    private function __clone() {}
    
    /**
     * Method __wakeup mencegah unserialization objek
     * Unserialization bisa membuat instance baru, yang akan merusak Singleton
     * 
     * @throws Exception Jika ada percobaan unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN DI FILE LAIN:
 * ============================================================================
 * 
 * // Membuat koneksi (otomatis singleton)
 * $db = Database::getInstance()->getConnection();
 * 
 * // Contoh query SELECT
 * $stmt = $db->query("SELECT * FROM buku");
 * $buku = $stmt->fetchAll();
 * 
 * // Contoh query dengan parameter (prepared statement - aman dari SQL Injection)
 * $stmt = $db->prepare("SELECT * FROM buku WHERE id = ?");
 * $stmt->execute([1]);
 * $buku = $stmt->fetch();
 * 
 * ============================================================================
 */
