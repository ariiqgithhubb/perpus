<?php
/**
 * ============================================================================
 * FILE: Buku.php
 * LOKASI: models/Buku.php
 * FUNGSI: Model untuk mengelola data buku dalam database
 * ============================================================================
 * 
 * Model adalah bagian dari arsitektur MVC yang bertanggung jawab untuk:
 * - Berinteraksi dengan database (query SQL)
 * - Mengolah data bisnis
 * - Menyediakan data ke Controller
 * 
 * Dalam OOP, setiap Model merepresentasikan satu tabel di database.
 * Class Buku ini mengelola tabel 'buku'.
 * 
 * ============================================================================
 */

class Buku {
    
    // ========================================================================
    // PROPERTI (VARIABEL) KELAS
    // ========================================================================
    
    /**
     * @var PDO $db - Objek koneksi database
     * Private artinya hanya bisa diakses dari dalam kelas ini
     */
    private $db;
    
    /**
     * @var string $table - Nama tabel yang dikelola oleh model ini
     * Disimpan dalam variabel agar mudah diubah jika nama tabel berubah
     */
    private $table = 'buku';
    
    // ========================================================================
    // CONSTRUCTOR
    // ========================================================================
    
    /**
     * Constructor - Dipanggil saat membuat objek baru: new Buku()
     * 
     * Menginisialisasi koneksi database menggunakan Singleton pattern
     * dari class Database
     */
    public function __construct() {
        // Mendapatkan koneksi database dari Singleton Database
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ========================================================================
    // METHOD UNTUK MENGAMBIL DATA (READ)
    // ========================================================================
    
    /**
     * Mengambil semua data buku beserta nama kategorinya
     * 
     * @return array Daftar semua buku dalam bentuk array
     * 
     * Query menggunakan LEFT JOIN untuk menggabungkan tabel buku dengan kategori_buku
     * LEFT JOIN = Ambil semua buku, meskipun tidak punya kategori
     */
    public function getAll() {
        // query() digunakan untuk SQL tanpa parameter (aman karena tidak ada input user)
        $stmt = $this->db->query("
            SELECT b.*, k.nama_kategori 
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            ORDER BY b.created_at DESC
        ");
        
        // fetchAll() mengambil semua baris hasil query sebagai array
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil buku yang tersedia untuk dipinjam
     * 
     * Syarat buku tersedia:
     * - stok_tersedia > 0 (masih ada stok)
     * - status = 'tersedia' (tidak rusak/hilang)
     * 
     * @return array Daftar buku yang bisa dipinjam
     */
    public function getAvailable() {
        $stmt = $this->db->query("
            SELECT b.*, k.nama_kategori 
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            WHERE b.stok_tersedia > 0 AND b.status = 'tersedia'
            ORDER BY b.judul ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Mencari buku berdasarkan ID
     * 
     * @param int $id ID buku yang dicari
     * @return array|false Data buku atau false jika tidak ditemukan
     * 
     * Menggunakan prepare() dan execute() untuk keamanan (prepared statement)
     * Tanda ? adalah placeholder yang akan diganti dengan nilai dari execute()
     */
    public function findById($id) {
        // prepare() membuat prepared statement (aman dari SQL Injection)
        $stmt = $this->db->prepare("
            SELECT b.*, k.nama_kategori 
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            WHERE b.id = ?
        ");
        
        // execute() menjalankan query dengan parameter
        // Parameter dalam array menggantikan tanda ? sesuai urutan
        $stmt->execute([$id]);
        
        // fetch() mengambil satu baris hasil (karena ID unik)
        return $stmt->fetch();
    }
    
    /**
     * Mencari buku berdasarkan kode buku
     * 
     * @param string $kode Kode buku (contoh: BK001)
     * @return array|false Data buku atau false jika tidak ditemukan
     */
    public function findByKode($kode) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE kode_buku = ?");
        $stmt->execute([$kode]);
        return $stmt->fetch();
    }
    
    // ========================================================================
    // METHOD UNTUK MENYIMPAN DATA (CREATE)
    // ========================================================================
    
    /**
     * Menambahkan buku baru ke database
     * 
     * @param array $data Array asosiatif berisi data buku dari form
     *                    Contoh: ['judul' => 'PHP Dasar', 'penulis' => 'John', ...]
     * @return int|false  ID buku yang baru dibuat atau false jika gagal
     * 
     * Penjelasan proses:
     * 1. Siapkan SQL INSERT dengan placeholder (?)
     * 2. Handle nilai kosong untuk kolom integer (konversi ke null)
     * 3. Execute dengan data dari form
     * 4. Kembalikan ID buku baru dengan lastInsertId()
     */
    public function create($data) {
        // Siapkan SQL INSERT
        // Kolom-kolom yang akan diisi dipisahkan koma
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (kode_buku, isbn, judul, penulis, penerbit, tahun_terbit, kategori_id, jumlah_halaman, stok, stok_tersedia, lokasi_rak, deskripsi, cover, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        // Ambil stok, default 0 jika tidak diisi
        // ?? adalah null coalescing operator
        $stok = $data['stok'] ?? 0;
        
        /**
         * Handle kolom integer yang mungkin kosong
         * 
         * Masalah: Form mengirim string kosong '' untuk field yang tidak diisi
         * MySQL tidak bisa memasukkan '' ke kolom INT, akan error
         * Solusi: Konversi string kosong menjadi null
         * 
         * !empty() = false jika nilai kosong/null/0/''
         */
        $kategoriId = !empty($data['kategori_id']) ? $data['kategori_id'] : null;
        $tahunTerbit = !empty($data['tahun_terbit']) ? $data['tahun_terbit'] : null;
        $jumlahHalaman = !empty($data['jumlah_halaman']) ? $data['jumlah_halaman'] : null;
        
        // Execute dengan array parameter sesuai urutan placeholder (?)
        $result = $stmt->execute([
            $data['kode_buku'],          // Kode unik buku (wajib)
            $data['isbn'] ?? null,        // ISBN (opsional)
            $data['judul'],               // Judul buku (wajib)
            $data['penulis'],             // Nama penulis (wajib)
            $data['penerbit'] ?? null,    // Nama penerbit (opsional)
            $tahunTerbit,                 // Tahun terbit (opsional, sudah di-handle)
            $kategoriId,                  // ID kategori (opsional, sudah di-handle)
            $jumlahHalaman,               // Jumlah halaman (opsional, sudah di-handle)
            $stok,                        // Total stok buku
            $stok,                        // stok_tersedia = stok awal (belum ada yang dipinjam)
            $data['lokasi_rak'] ?? null,  // Lokasi rak (opsional)
            $data['deskripsi'] ?? null,   // Deskripsi buku (opsional)
            $data['cover'] ?? null,       // Path file cover (opsional)
            $data['status'] ?? 'tersedia' // Status default 'tersedia'
        ]);
        
        // Jika insert berhasil, kembalikan ID buku baru
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    // ========================================================================
    // METHOD UNTUK UPDATE DATA
    // ========================================================================
    
    /**
     * Mengupdate data buku yang sudah ada
     * 
     * @param int   $id   ID buku yang akan diupdate
     * @param array $data Array asosiatif berisi kolom dan nilai yang akan diupdate
     * @return bool       True jika berhasil, false jika gagal
     * 
     * Method ini dinamis - hanya mengupdate kolom yang ada di $data
     * Sehingga tidak perlu mengirim semua kolom
     */
    public function update($id, $data) {
        $fields = [];  // Untuk menyimpan "kolom = ?"
        $values = [];  // Untuk menyimpan nilai-nilai
        
        // Daftar kolom yang bertipe integer
        // Perlu penanganan khusus karena value '' (string kosong) tidak valid untuk INT
        $integerColumns = ['kategori_id', 'tahun_terbit', 'jumlah_halaman', 'stok', 'stok_tersedia'];
        
        // Loop setiap data yang akan diupdate
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";  // Contoh: "judul = ?"
            
            // Konversi string kosong ke null untuk kolom integer
            if (in_array($key, $integerColumns) && $value === '') {
                $value = null;
            }
            $values[] = $value;
        }
        
        // Tambahkan ID di akhir untuk WHERE clause
        $values[] = $id;
        
        // Gabungkan fields dengan koma: "judul = ?, penulis = ?, stok = ?"
        // implode() menggabungkan array menjadi string
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }
    
    // ========================================================================
    // METHOD UNTUK HAPUS DATA (DELETE)
    // ========================================================================
    
    /**
     * Menghapus buku dari database
     * 
     * @param int $id ID buku yang akan dihapus
     * @return bool   True jika berhasil, false jika gagal
     * 
     * PERHATIAN: Penghapusan bersifat permanen!
     * Sebaiknya tambahkan soft delete (set status = 'dihapus') untuk data penting
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========================================================================
    // METHOD UNTUK KELOLA STOK
    // ========================================================================
    
    /**
     * Mengupdate stok tersedia saat peminjaman/pengembalian
     * 
     * @param int    $id        ID buku
     * @param int    $jumlah    Jumlah yang ditambah/dikurangi
     * @param string $operation 'add' untuk menambah, 'subtract' untuk mengurangi
     * @return bool             True jika berhasil
     * 
     * Digunakan saat:
     * - Peminjaman: subtract (kurangi stok)
     * - Pengembalian: add (tambah stok)
     */
    public function updateStokTersedia($id, $jumlah, $operation = 'subtract') {
        // Tentukan operator berdasarkan operasi
        $operator = $operation === 'add' ? '+' : '-';
        
        // Query langsung menambah/mengurangi nilai di database
        // Lebih aman dari race condition dibanding SELECT lalu UPDATE
        $stmt = $this->db->prepare("UPDATE {$this->table} SET stok_tersedia = stok_tersedia $operator ? WHERE id = ?");
        return $stmt->execute([$jumlah, $id]);
    }
    
    // ========================================================================
    // METHOD PENCARIAN
    // ========================================================================
    
    /**
     * Mencari buku berdasarkan keyword (untuk admin)
     * 
     * @param string $keyword Kata kunci pencarian
     * @return array          Daftar buku yang cocok
     * 
     * Mencari di: kode_buku, judul, penulis, penerbit, nama_kategori
     * LIKE '%keyword%' = cari yang mengandung keyword di mana saja
     */
    public function search($keyword) {
        // Tambahkan wildcard % di awal dan akhir untuk pencarian fleksibel
        $keyword = "%$keyword%";
        
        $stmt = $this->db->prepare("
            SELECT b.*, k.nama_kategori 
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            WHERE b.kode_buku LIKE ? OR b.judul LIKE ? OR b.penulis LIKE ? OR b.penerbit LIKE ? OR k.nama_kategori LIKE ?
            ORDER BY b.judul ASC
        ");
        
        // Semua placeholder (?) diisi dengan keyword yang sama
        $stmt->execute([$keyword, $keyword, $keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mencari buku yang tersedia (untuk siswa)
     * Sama seperti search() tapi hanya menampilkan yang bisa dipinjam
     * 
     * @param string $keyword Kata kunci pencarian
     * @return array          Daftar buku tersedia yang cocok
     */
    public function searchAvailable($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("
            SELECT b.*, k.nama_kategori 
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            WHERE b.stok_tersedia > 0 AND b.status = 'tersedia'
            AND (b.kode_buku LIKE ? OR b.judul LIKE ? OR b.penulis LIKE ? OR k.nama_kategori LIKE ?)
            ORDER BY b.judul ASC
        ");
        $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mendapatkan buku berdasarkan kategori
     * 
     * @param int $kategoriId ID kategori
     * @return array          Daftar buku dalam kategori tersebut
     */
    public function getByKategori($kategoriId) {
        $stmt = $this->db->prepare("
            SELECT b.*, k.nama_kategori 
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            WHERE b.kategori_id = ?
            ORDER BY b.judul ASC
        ");
        $stmt->execute([$kategoriId]);
        return $stmt->fetchAll();
    }
    
    // ========================================================================
    // METHOD UNTUK STATISTIK / DASHBOARD
    // ========================================================================
    
    /**
     * Menghitung total jumlah judul buku
     * 
     * @return int Jumlah buku
     * 
     * COUNT(*) menghitung jumlah baris
     * fetchColumn() mengambil nilai kolom pertama (hasil COUNT)
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return $stmt->fetchColumn();
    }
    
    /**
     * Menghitung jumlah buku yang stoknya masih ada
     * 
     * @return int Jumlah buku dengan stok tersedia > 0
     */
    public function countAvailable() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE stok_tersedia > 0");
        return $stmt->fetchColumn();
    }
    
    /**
     * Mengambil total semua stok buku
     * 
     * @return int Total stok semua buku
     * 
     * SUM() menjumlahkan nilai dari semua baris
     */
    public function getTotalStok() {
        $stmt = $this->db->query("SELECT SUM(stok) FROM {$this->table}");
        return $stmt->fetchColumn() ?? 0; // Default 0 jika null
    }
    
    /**
     * Mengecek apakah kode buku sudah digunakan
     * 
     * @param string   $kode      Kode buku yang dicek
     * @param int|null $excludeId ID yang dikecualikan (untuk edit)
     * @return bool               True jika kode sudah ada
     * 
     * Parameter $excludeId digunakan saat edit:
     * - Saat create: tidak ada excludeId, cek semua
     * - Saat edit: excludeId = ID yang sedang diedit, agar tidak dianggap duplikat
     */
    public function kodeExists($kode, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE kode_buku = ?";
        $params = [$kode];
        
        // Jika ada excludeId, tambahkan kondisi untuk mengecualikan
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0; // True jika COUNT > 0
    }
    
    /**
     * Mendapatkan buku populer (paling sering dipinjam)
     * 
     * @param int $limit Jumlah buku yang diambil
     * @return array     Daftar buku populer dengan total_pinjam
     * 
     * Menggunakan COUNT + GROUP BY untuk menghitung jumlah peminjaman per buku
     */
    public function getPopular($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT b.*, k.nama_kategori, COUNT(dp.id) as total_pinjam
            FROM {$this->table} b
            LEFT JOIN kategori_buku k ON b.kategori_id = k.id
            LEFT JOIN detail_peminjaman dp ON b.id = dp.buku_id
            GROUP BY b.id
            ORDER BY total_pinjam DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Generate kode buku otomatis
     * 
     * @return string Kode buku baru (format: BK001, BK002, dst)
     * 
     * Cara kerja:
     * 1. Ambil kode buku terakhir
     * 2. Ekstrak angka dari kode (BK005 -> 5)
     * 3. Tambah 1 (5 -> 6)
     * 4. Format ulang dengan padding (6 -> BK006)
     */
    public function generateKode() {
        // Ambil kode buku terakhir berdasarkan ID tertinggi
        $stmt = $this->db->query("SELECT kode_buku FROM {$this->table} ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch();
        
        if ($last) {
            // substr() mengambil bagian string
            // BK005 -> substr mulai index 2 = 005 -> (int) = 5 -> +1 = 6
            $num = (int) substr($last['kode_buku'], 2) + 1;
        } else {
            // Jika belum ada buku, mulai dari 1
            $num = 1;
        }
        
        // str_pad() menambahkan karakter di depan sampai mencapai panjang tertentu
        // 6 -> str_pad dengan '0' dan panjang 3 -> '006'
        // Hasil akhir: 'BK' . '006' = 'BK006'
        return 'BK' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN DI CONTROLLER:
 * ============================================================================
 * 
 * class BukuController {
 *     private $bukuModel;
 *     
 *     public function __construct() {
 *         $this->bukuModel = new Buku();
 *     }
 *     
 *     // Menampilkan semua buku
 *     public function index() {
 *         $data = ['buku' => $this->bukuModel->getAll()];
 *         include 'views/admin/buku/index.php';
 *     }
 *     
 *     // Menyimpan buku baru
 *     public function store() {
 *         $data = Validation::sanitize($_POST);
 *         $id = $this->bukuModel->create($data);
 *         if ($id) {
 *             Redirect::success('admin/buku', 'Buku berhasil ditambahkan!');
 *         }
 *     }
 * }
 * 
 * ============================================================================
 */
