<?php
/**
 * ============================================================================
 * FILE: Anggota.php
 * LOKASI: models/Anggota.php
 * FUNGSI: Model untuk mengelola data anggota/siswa perpustakaan
 * ============================================================================
 * 
 * Model ini mengelola tabel 'anggota' yang menyimpan data siswa
 * yang terdaftar sebagai anggota perpustakaan.
 * 
 * Anggota bisa melakukan:
 * - Login ke sistem
 * - Melihat katalog buku
 * - Melakukan peminjaman mandiri
 * - Melihat riwayat peminjaman
 * 
 * ============================================================================
 */

class Anggota {
    
    // ========================================================================
    // PROPERTI KELAS
    // ========================================================================
    
    /**
     * @var PDO $db - Objek koneksi database PDO
     */
    private $db;
    
    /**
     * @var string $table - Nama tabel di database
     */
    private $table = 'anggota';
    
    // ========================================================================
    // CONSTRUCTOR
    // ========================================================================
    
    /**
     * Constructor - Inisialisasi koneksi database
     * Dipanggil otomatis saat: new Anggota()
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ========================================================================
    // METHOD UNTUK MENGAMBIL DATA (READ)
    // ========================================================================
    
    /**
     * Mengambil semua data anggota
     * 
     * @return array Daftar semua anggota
     * 
     * ORDER BY created_at DESC = urutkan berdasarkan tanggal daftar terbaru
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil anggota yang berstatus aktif saja
     * 
     * @return array Daftar anggota aktif
     * 
     * Digunakan untuk dropdown di form peminjaman
     * Hanya anggota aktif yang bisa melakukan peminjaman
     */
    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE status = 'aktif' ORDER BY nama ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Mencari anggota berdasarkan ID
     * 
     * @param int $id ID anggota
     * @return array|false Data anggota atau false jika tidak ditemukan
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Mencari anggota berdasarkan username
     * 
     * @param string $username Username yang dicari
     * @return array|false Data anggota atau false
     * 
     * Digunakan untuk proses login dan validasi username unik
     */
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    /**
     * Mencari anggota berdasarkan NIS (Nomor Induk Siswa)
     * 
     * @param string $nis NIS yang dicari
     * @return array|false Data anggota atau false
     * 
     * NIS adalah identitas unik siswa di sekolah
     */
    public function findByNis($nis) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nis = ?");
        $stmt->execute([$nis]);
        return $stmt->fetch();
    }
    
    // ========================================================================
    // METHOD AUTENTIKASI (LOGIN)
    // ========================================================================
    
    /**
     * Memverifikasi username dan password untuk login
     * 
     * @param string $username Username yang dimasukkan
     * @param string $password Password yang dimasukkan
     * @return array|false Data anggota jika berhasil, false jika gagal
     * 
     * Syarat login berhasil:
     * 1. Username ditemukan
     * 2. Password cocok
     * 3. Status anggota = 'aktif'
     * 
     * CATATAN: Password disimpan dalam bentuk plain text (tidak di-hash)
     * untuk kemudahan demo. Di aplikasi produksi, gunakan password_hash()
     */
    public function authenticate($username, $password) {
        // Cari anggota berdasarkan username
        $anggota = $this->findByUsername($username);
        
        // Verifikasi password dan status
        // === adalah strict comparison (bandingkan nilai DAN tipe data)
        if ($anggota && $anggota['password'] === $password && $anggota['status'] === 'aktif') {
            return $anggota; // Login berhasil, kembalikan data anggota
        }
        
        return false; // Login gagal
    }
    
    // ========================================================================
    // METHOD UNTUK REGISTRASI / CREATE
    // ========================================================================
    
    /**
     * Mendaftarkan anggota baru (untuk fitur registrasi mandiri)
     * 
     * @param array $data Data anggota dari form registrasi
     * @return int|false ID anggota baru atau false jika gagal
     * 
     * CURDATE() = fungsi MySQL untuk mendapatkan tanggal hari ini
     * Status default 'aktif' agar bisa langsung login
     */
    public function register($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (nis, nama, jenis_kelamin, kelas, jurusan, alamat, telepon, email, username, password, tanggal_daftar, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'aktif')
        ");
        
        // Execute dengan data dari form
        // ?? null artinya gunakan null jika data tidak ada (field opsional)
        $result = $stmt->execute([
            $data['nis'],              // NIS wajib diisi
            $data['nama'],             // Nama lengkap wajib
            $data['jenis_kelamin'],    // L/P wajib
            $data['kelas'],            // Kelas wajib
            $data['jurusan'] ?? null,  // Jurusan opsional
            $data['alamat'] ?? null,   // Alamat opsional
            $data['telepon'] ?? null,  // No HP opsional
            $data['email'] ?? null,    // Email opsional
            $data['username'],         // Username wajib
            $data['password']          // Password wajib (plain text untuk demo)
        ]);
        
        if ($result) {
            return $this->db->lastInsertId(); // Kembalikan ID baru
        }
        return false;
    }
    
    /**
     * Menambahkan anggota baru (digunakan oleh admin)
     * Alias dari method register()
     * 
     * @param array $data Data anggota
     * @return int|false ID anggota baru atau false
     */
    public function create($data) {
        return $this->register($data);
    }
    
    // ========================================================================
    // METHOD UNTUK UPDATE DATA
    // ========================================================================
    
    /**
     * Mengupdate data anggota
     * 
     * @param int   $id   ID anggota yang akan diupdate
     * @param array $data Data yang akan diupdate
     * @return bool       True jika berhasil
     * 
     * Method ini dinamis - hanya mengupdate kolom yang dikirim
     */
    public function update($id, $data) {
        $fields = [];  // Array untuk "kolom = ?"
        $values = [];  // Array untuk nilai
        
        // Bangun query UPDATE secara dinamis
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id; // ID untuk WHERE clause
        
        // implode() menggabungkan array dengan separator ', '
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }
    
    // ========================================================================
    // METHOD UNTUK HAPUS DATA
    // ========================================================================
    
    /**
     * Menghapus anggota dari database
     * 
     * @param int $id ID anggota yang akan dihapus
     * @return bool   True jika berhasil
     * 
     * PERHATIAN: Jika anggota memiliki riwayat peminjaman,
     * penghapusan bisa gagal karena foreign key constraint
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========================================================================
    // METHOD PENCARIAN
    // ========================================================================
    
    /**
     * Mencari anggota berdasarkan keyword
     * 
     * @param string $keyword Kata kunci pencarian
     * @return array          Daftar anggota yang cocok
     * 
     * Mencari di: NIS, nama, kelas, jurusan
     */
    public function search($keyword) {
        $keyword = "%$keyword%"; // Wildcard untuk pencarian fleksibel
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE nis LIKE ? OR nama LIKE ? OR kelas LIKE ? OR jurusan LIKE ?
            ORDER BY nama ASC
        ");
        $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    // ========================================================================
    // METHOD UNTUK STATISTIK
    // ========================================================================
    
    /**
     * Menghitung total jumlah anggota
     * 
     * @return int Jumlah anggota
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return $stmt->fetchColumn();
    }
    
    /**
     * Menghitung jumlah anggota aktif
     * 
     * @return int Jumlah anggota dengan status 'aktif'
     */
    public function countActive() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE status = 'aktif'");
        return $stmt->fetchColumn();
    }
    
    // ========================================================================
    // METHOD UNTUK VALIDASI UNIK
    // ========================================================================
    
    /**
     * Mengecek apakah NIS sudah digunakan
     * 
     * @param string   $nis       NIS yang dicek
     * @param int|null $excludeId ID yang dikecualikan (untuk edit)
     * @return bool               True jika NIS sudah ada
     */
    public function nisExists($nis, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nis = ?";
        $params = [$nis];
        
        // Jika edit, kecualikan ID sendiri
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Mengecek apakah username sudah digunakan
     * 
     * @param string   $username  Username yang dicek
     * @param int|null $excludeId ID yang dikecualikan (untuk edit)
     * @return bool               True jika username sudah ada
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = ?";
        $params = [$username];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    // ========================================================================
    // METHOD UNTUK DATA DETAIL
    // ========================================================================
    
    /**
     * Mengambil data anggota beserta statistik peminjamannya
     * 
     * @param int $id ID anggota
     * @return array  Data anggota dengan jumlah_pinjam dan total_pinjam
     * 
     * Menggunakan subquery untuk menghitung:
     * - jumlah_pinjam: peminjaman yang sedang aktif (status = 'dipinjam')
     * - total_pinjam: total peminjaman sepanjang waktu
     */
    public function getWithPeminjamanCount($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   (SELECT COUNT(*) FROM peminjaman p WHERE p.anggota_id = a.id AND p.status = 'dipinjam') as jumlah_pinjam,
                   (SELECT COUNT(*) FROM peminjaman p2 WHERE p2.anggota_id = a.id) as total_pinjam
            FROM {$this->table} a 
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN:
 * ============================================================================
 * 
 * // Di AuthController (Login)
 * $anggota = $this->anggotaModel->authenticate($username, $password);
 * if ($anggota) {
 *     Session::set('user_id', $anggota['id']);
 *     Session::set('user_type', 'siswa');
 * }
 * 
 * // Di AnggotaController (CRUD)
 * $this->anggotaModel->create($_POST);
 * $this->anggotaModel->update($id, $_POST);
 * $this->anggotaModel->delete($id);
 * 
 * ============================================================================
 */
