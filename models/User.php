<?php
/**
 * ============================================================================
 * FILE: User.php
 * LOKASI: models/User.php
 * FUNGSI: Model untuk mengelola data admin/petugas perpustakaan
 * ============================================================================
 * 
 * Model ini mengelola tabel 'users' yang menyimpan data:
 * - Admin perpustakaan
 * - Petugas perpustakaan
 * 
 * Perbedaan dengan Anggota:
 * - User adalah PENGELOLA sistem (admin/petugas)
 * - Anggota adalah PENGGUNA sistem (siswa yang meminjam buku)
 * 
 * ============================================================================
 */

class User {
    
    // ========================================================================
    // PROPERTI KELAS
    // ========================================================================
    
    /**
     * @var PDO $db - Objek koneksi database
     */
    private $db;
    
    /**
     * @var string $table - Nama tabel di database
     */
    private $table = 'users';
    
    // ========================================================================
    // CONSTRUCTOR
    // ========================================================================
    
    /**
     * Constructor - Inisialisasi koneksi database
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ========================================================================
    // METHOD UNTUK MENGAMBIL DATA (READ)
    // ========================================================================
    
    /**
     * Mengambil semua data user (admin/petugas)
     * 
     * @return array Daftar semua user
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Mencari user berdasarkan ID
     * 
     * @param int $id ID user yang dicari
     * @return array|false Data user atau false jika tidak ditemukan
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Mencari user berdasarkan username
     * 
     * @param string $username Username yang dicari
     * @return array|false Data user atau false jika tidak ditemukan
     */
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    // ========================================================================
    // METHOD AUTENTIKASI (LOGIN)
    // ========================================================================
    
    /**
     * Memverifikasi username dan password untuk login admin
     * 
     * @param string $username Username yang dimasukkan
     * @param string $password Password yang dimasukkan
     * @return array|false Data user jika berhasil, false jika gagal
     * 
     * Proses:
     * 1. Cari user berdasarkan username
     * 2. Bandingkan password (plain text untuk demo)
     * 3. Cek status harus 'aktif'
     * 4. Update waktu login terakhir
     * 5. Kembalikan data user
     * 
     * CATATAN KEAMANAN:
     * Password disimpan plain text untuk kemudahan demo.
     * Di produksi, gunakan password_hash() dan password_verify()
     */
    public function authenticate($username, $password) {
        // Cari user berdasarkan username
        $user = $this->findByUsername($username);
        
        // Verifikasi: user ditemukan, password cocok, status aktif
        if ($user && $user['password'] === $password && $user['status'] === 'aktif') {
            // Update waktu login terakhir
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false; // Login gagal
    }
    
    /**
     * Mengupdate waktu login terakhir
     * 
     * @param int $id ID user
     * @return bool True jika berhasil
     * 
     * NOW() adalah fungsi MySQL yang mengembalikan tanggal dan waktu saat ini
     */
    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========================================================================
    // METHOD UNTUK MENYIMPAN DATA (CREATE)
    // ========================================================================
    
    /**
     * Menambahkan user baru (admin/petugas)
     * 
     * @param array $data Data user dari form
     * @return bool True jika berhasil
     * 
     * Kolom yang bisa diisi:
     * - username (wajib)
     * - password (wajib)
     * - nama_lengkap (wajib)
     * - email (opsional)
     * - role (default: 'admin')
     * - foto (opsional)
     * - status (default: 'aktif')
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (username, password, nama_lengkap, email, role, foto, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['username'],
            $data['password'],              // Disimpan plain text untuk demo
            $data['nama_lengkap'],
            $data['email'] ?? null,         // Email opsional
            $data['role'] ?? 'admin',       // Role default 'admin'
            $data['foto'] ?? null,          // Foto profil opsional
            $data['status'] ?? 'aktif'      // Status default 'aktif'
        ]);
    }
    
    // ========================================================================
    // METHOD UNTUK UPDATE DATA
    // ========================================================================
    
    /**
     * Mengupdate data user
     * 
     * @param int   $id   ID user yang akan diupdate
     * @param array $data Data yang akan diupdate
     * @return bool       True jika berhasil
     * 
     * Method ini dinamis - hanya update kolom yang dikirim
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        // Bangun query UPDATE secara dinamis
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }
    
    // ========================================================================
    // METHOD UNTUK HAPUS DATA
    // ========================================================================
    
    /**
     * Menghapus user dari database
     * 
     * @param int $id ID user yang akan dihapus
     * @return bool   True jika berhasil
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========================================================================
    // METHOD PENCARIAN
    // ========================================================================
    
    /**
     * Mencari user berdasarkan keyword
     * 
     * @param string $keyword Kata kunci pencarian
     * @return array          Daftar user yang cocok
     * 
     * Mencari di: username, nama_lengkap, email
     */
    public function search($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE username LIKE ? OR nama_lengkap LIKE ? OR email LIKE ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    // ========================================================================
    // METHOD VALIDASI
    // ========================================================================
    
    /**
     * Mengecek apakah username sudah digunakan
     * 
     * @param string   $username  Username yang dicek
     * @param int|null $excludeId ID yang dikecualikan (untuk edit)
     * @return bool               True jika sudah ada
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = ?";
        $params = [$username];
        
        // Jika edit, kecualikan ID sendiri
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN:
 * ============================================================================
 * 
 * // Di AuthController (Login Admin)
 * $userModel = new User();
 * $user = $userModel->authenticate($username, $password);
 * if ($user) {
 *     // Login berhasil
 *     Session::set('user_id', $user['id']);
 *     Session::set('user_type', 'admin');
 * }
 * 
 * ============================================================================
 */
