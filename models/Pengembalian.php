<?php
/**
 * Pengembalian Model
 * Mengelola data pengembalian buku
 */

class Pengembalian {
    private $db;
    private $table = 'pengembalian';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all pengembalian with relations
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT pg.*, p.kode_peminjaman, p.tanggal_pinjam, p.tanggal_harus_kembali,
                   a.nama as nama_anggota, a.nis, u.nama_lengkap as nama_petugas
            FROM {$this->table} pg
            LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
            LEFT JOIN anggota a ON p.anggota_id = a.id
            LEFT JOIN users u ON pg.user_id = u.id
            ORDER BY pg.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Get pengembalian by anggota
     */
    public function getByAnggota($anggotaId) {
        $stmt = $this->db->prepare("
            SELECT pg.*, p.kode_peminjaman, p.tanggal_pinjam, p.tanggal_harus_kembali
            FROM {$this->table} pg
            LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
            WHERE p.anggota_id = ?
            ORDER BY pg.tanggal_pengembalian DESC
        ");
        $stmt->execute([$anggotaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Find pengembalian by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT pg.*, p.kode_peminjaman, p.tanggal_pinjam, p.tanggal_harus_kembali,
                   a.nama as nama_anggota, a.nis, a.kelas, u.nama_lengkap as nama_petugas
            FROM {$this->table} pg
            LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
            LEFT JOIN anggota a ON p.anggota_id = a.id
            LEFT JOIN users u ON pg.user_id = u.id
            WHERE pg.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Find by peminjaman ID
     */
    public function findByPeminjaman($peminjamanId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE peminjaman_id = ?");
        $stmt->execute([$peminjamanId]);
        return $stmt->fetch();
    }
    
    /**
     * Create pengembalian
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (kode_pengembalian, peminjaman_id, user_id, tanggal_pengembalian, kondisi_buku, 
             keterlambatan, denda_keterlambatan, denda_kerusakan, total_denda, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['kode_pengembalian'],
            $data['peminjaman_id'],
            $data['user_id'] ?? null,
            $data['tanggal_pengembalian'],
            $data['kondisi_buku'] ?? 'baik',
            $data['keterlambatan'] ?? 0,
            $data['denda_keterlambatan'] ?? 0,
            $data['denda_kerusakan'] ?? 0,
            $data['total_denda'] ?? 0,
            $data['keterangan'] ?? null
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Calculate denda keterlambatan
     */
    public function calculateDenda($tanggalHarusKembali, $tanggalPengembalian = null) {
        $tanggalPengembalian = $tanggalPengembalian ?? date('Y-m-d');
        
        $harusKembali = new DateTime($tanggalHarusKembali);
        $pengembalian = new DateTime($tanggalPengembalian);
        
        $selisih = $pengembalian->diff($harusKembali);
        $hari = $selisih->days;
        
        // Jika pengembalian setelah tanggal harus kembali
        if ($pengembalian > $harusKembali) {
            // Get denda per hari from pengaturan
            $stmt = $this->db->query("SELECT denda_per_hari FROM pengaturan LIMIT 1");
            $pengaturan = $stmt->fetch();
            $dendaPerHari = $pengaturan ? $pengaturan['denda_per_hari'] : 1000;
            
            return [
                'hari' => $hari,
                'denda' => $hari * $dendaPerHari
            ];
        }
        
        return [
            'hari' => 0,
            'denda' => 0
        ];
    }
    
    /**
     * Search pengembalian
     */
    public function search($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("
            SELECT pg.*, p.kode_peminjaman, a.nama as nama_anggota, a.nis
            FROM {$this->table} pg
            LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE pg.kode_pengembalian LIKE ? OR p.kode_peminjaman LIKE ? OR a.nama LIKE ?
            ORDER BY pg.created_at DESC
        ");
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    /**
     * Count total pengembalian
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return $stmt->fetchColumn();
    }
    
    /**
     * Get total denda collected
     */
    public function getTotalDenda() {
        $stmt = $this->db->query("SELECT SUM(total_denda) FROM {$this->table}");
        return $stmt->fetchColumn() ?? 0;
    }
    
    /**
     * Generate kode pengembalian
     */
    public function generateKode() {
        $prefix = 'RET' . date('Ymd');
        $stmt = $this->db->prepare("
            SELECT kode_pengembalian FROM {$this->table} 
            WHERE kode_pengembalian LIKE ? 
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$prefix . '%']);
        $last = $stmt->fetch();
        
        if ($last) {
            $num = (int) substr($last['kode_pengembalian'], -4) + 1;
        } else {
            $num = 1;
        }
        
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get recent pengembalian
     */
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT pg.*, p.kode_peminjaman, a.nama as nama_anggota
            FROM {$this->table} pg
            LEFT JOIN peminjaman p ON pg.peminjaman_id = p.id
            LEFT JOIN anggota a ON p.anggota_id = a.id
            ORDER BY pg.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
