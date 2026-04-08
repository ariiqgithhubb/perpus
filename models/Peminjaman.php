<?php
/**
 * ============================================================================
 * FILE: Peminjaman.php
 * LOKASI: models/Peminjaman.php
 * FUNGSI: Model untuk mengelola data peminjaman buku
 * ============================================================================
 * 
 * Model ini mengelola 2 tabel yang saling berhubungan:
 * 1. peminjaman - Data transaksi peminjaman (header)
 * 2. detail_peminjaman - Data buku yang dipinjam (detail)
 * 
 * Hubungan tabel:
 * - 1 peminjaman bisa memiliki banyak detail (1 to Many)
 * - 1 detail berisi 1 buku yang dipinjam
 * 
 * Status peminjaman:
 * - 'dipinjam': Buku masih dipinjam
 * - 'dikembalikan': Buku sudah dikembalikan
 * 
 * ============================================================================
 */

class Peminjaman {
    
    // ========================================================================
    // PROPERTI KELAS
    // ========================================================================
    
    /**
     * @var PDO $db - Objek koneksi database
     */
    private $db;
    
    /**
     * @var string $table - Nama tabel utama
     */
    private $table = 'peminjaman';
    
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
    // METHOD UNTUK MENGAMBIL DATA (READ) - DENGAN RELASI
    // ========================================================================
    
    /**
     * Mengambil semua data peminjaman dengan data anggota dan petugas
     * 
     * @return array Daftar semua peminjaman
     * 
     * Query menggunakan LEFT JOIN untuk menggabungkan:
     * - peminjaman (p)
     * - anggota (a) - Untuk mendapatkan nama dan NIS peminjam
     * - users (u) - Untuk mendapatkan nama petugas yang memproses
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT p.*, a.nama as nama_anggota, a.nis, u.nama_lengkap as nama_petugas
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil peminjaman berdasarkan status
     * 
     * @param string $status Status yang dicari ('dipinjam' atau 'dikembalikan')
     * @return array Daftar peminjaman dengan status tersebut
     */
    public function getByStatus($status) {
        $stmt = $this->db->prepare("
            SELECT p.*, a.nama as nama_anggota, a.nis
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE p.status = ?
            ORDER BY p.tanggal_pinjam DESC
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil semua peminjaman milik anggota tertentu
     * 
     * @param int $anggotaId ID anggota
     * @return array Daftar peminjaman anggota (semua status)
     * 
     * Digunakan di halaman riwayat peminjaman siswa
     */
    public function getByAnggota($anggotaId) {
        $stmt = $this->db->prepare("
            SELECT p.*, a.nama as nama_anggota
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE p.anggota_id = ?
            ORDER BY p.tanggal_pinjam DESC
        ");
        $stmt->execute([$anggotaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mengambil peminjaman AKTIF milik anggota tertentu
     * 
     * @param int $anggotaId ID anggota
     * @return array Daftar peminjaman aktif (status = 'dipinjam')
     * 
     * Digunakan untuk menampilkan buku yang sedang dipinjam di dashboard siswa
     */
    public function getActiveByAnggota($anggotaId) {
        $stmt = $this->db->prepare("
            SELECT p.*, a.nama as nama_anggota
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE p.anggota_id = ? AND p.status = 'dipinjam'
            ORDER BY p.tanggal_pinjam DESC
        ");
        $stmt->execute([$anggotaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Mencari peminjaman berdasarkan ID
     * 
     * @param int $id ID peminjaman
     * @return array|false Data peminjaman atau false
     * 
     * Mengambil juga data anggota (nama, NIS, kelas) dan petugas
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, a.nama as nama_anggota, a.nis, a.kelas, u.nama_lengkap as nama_petugas
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Mencari peminjaman berdasarkan kode peminjaman
     * 
     * @param string $kode Kode peminjaman (contoh: PJ202602080001)
     * @return array|false Data peminjaman atau false
     */
    public function findByKode($kode) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE kode_peminjaman = ?");
        $stmt->execute([$kode]);
        return $stmt->fetch();
    }
    
    // ========================================================================
    // METHOD UNTUK MENYIMPAN DATA (CREATE)
    // ========================================================================
    
    /**
     * Membuat transaksi peminjaman baru
     * 
     * @param array $data Data peminjaman
     * @return int|false ID peminjaman baru atau false
     * 
     * Kolom:
     * - kode_peminjaman: Kode unik (generate dengan generateKode())
     * - anggota_id: ID anggota yang meminjam
     * - user_id: ID petugas yang memproses (null jika peminjaman mandiri)
     * - tanggal_pinjam: Tanggal mulai pinjam
     * - tanggal_harus_kembali: Tanggal batas pengembalian
     * - status: 'pending' (menunggu approval admin) atau 'dipinjam' (jika admin yang buat)
     * - keterangan: Catatan tambahan (opsional)
     */
    public function create($data) {
        // Status default 'pending' untuk siswa, 'dipinjam' jika ada user_id (admin)
        $status = isset($data['user_id']) && $data['user_id'] !== null ? 'dipinjam' : 'pending';
        
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (kode_peminjaman, anggota_id, user_id, tanggal_pinjam, tanggal_harus_kembali, status, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $data['kode_peminjaman'],
            $data['anggota_id'],
            $data['user_id'] ?? null,      // null jika siswa pinjam mandiri
            $data['tanggal_pinjam'],
            $data['tanggal_harus_kembali'],
            $status,                        // 'pending' atau 'dipinjam'
            $data['keterangan'] ?? null
        ]);
        
        if ($result) {
            return $this->db->lastInsertId(); // Kembalikan ID untuk addDetail()
        }
        return false;
    }
    
    /**
     * Menambahkan detail buku yang dipinjam
     * 
     * @param int    $peminjamanId ID peminjaman (header)
     * @param int    $bukuId       ID buku yang dipinjam
     * @param int    $jumlah       Jumlah eksemplar (default 1)
     * @param string $kondisi      Kondisi buku saat dipinjam (default 'baik')
     * @return bool                True jika berhasil
     * 
     * Method ini dipanggil setelah create() untuk setiap buku yang dipinjam
     */
    public function addDetail($peminjamanId, $bukuId, $jumlah = 1, $kondisi = 'baik') {
        $stmt = $this->db->prepare("
            INSERT INTO detail_peminjaman (peminjaman_id, buku_id, jumlah, kondisi_pinjam)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$peminjamanId, $bukuId, $jumlah, $kondisi]);
    }
    
    /**
     * Mengambil detail buku dari peminjaman
     * 
     * @param int $peminjamanId ID peminjaman
     * @return array Daftar buku yang dipinjam
     * 
     * Join dengan tabel buku untuk mendapatkan info buku (judul, penulis, dll)
     */
    public function getDetail($peminjamanId) {
        $stmt = $this->db->prepare("
            SELECT dp.*, b.kode_buku, b.judul, b.penulis
            FROM detail_peminjaman dp
            LEFT JOIN buku b ON dp.buku_id = b.id
            WHERE dp.peminjaman_id = ?
        ");
        $stmt->execute([$peminjamanId]);
        return $stmt->fetchAll();
    }
    
    // ========================================================================
    // METHOD UNTUK UPDATE DATA
    // ========================================================================
    
    /**
     * Mengupdate data peminjaman
     * 
     * @param int   $id   ID peminjaman
     * @param array $data Data yang akan diupdate
     * @return bool       True jika berhasil
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }
    
    /**
     * Mengupdate status peminjaman
     * 
     * @param int    $id     ID peminjaman
     * @param string $status Status baru ('dipinjam' atau 'dikembalikan')
     * @return bool          True jika berhasil
     */
    public function updateStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }
    
    /**
     * Menandai peminjaman sebagai sudah dikembalikan
     * 
     * @param int         $id             ID peminjaman
     * @param string|null $tanggalKembali Tanggal pengembalian (default: hari ini)
     * @return bool                       True jika berhasil
     * 
     * Method ini:
     * 1. Mengubah status menjadi 'dikembalikan'
     * 2. Mengisi tanggal_kembali
     */
    public function markAsReturned($id, $tanggalKembali = null) {
        // Default ke hari ini jika tidak diisi
        $tanggalKembali = $tanggalKembali ?? date('Y-m-d');
        
        return $this->update($id, [
            'status' => 'dikembalikan',
            'tanggal_kembali' => $tanggalKembali
        ]);
    }
    
    // ========================================================================
    // METHOD UNTUK HAPUS DATA (DELETE)
    // ========================================================================
    
    /**
     * Menghapus peminjaman dan detailnya
     * 
     * @param int $id ID peminjaman
     * @return bool   True jika berhasil
     * 
     * PENTING: Detail dihapus DULU sebelum header
     * Karena ada foreign key constraint dari detail_peminjaman ke peminjaman
     */
    public function delete($id) {
        // Hapus detail dulu (child records)
        $stmt = $this->db->prepare("DELETE FROM detail_peminjaman WHERE peminjaman_id = ?");
        $stmt->execute([$id]);
        
        // Baru hapus peminjaman (parent record)
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // ========================================================================
    // METHOD PENCARIAN
    // ========================================================================
    
    /**
     * Mencari peminjaman berdasarkan keyword
     * 
     * @param string $keyword Kata kunci pencarian
     * @return array          Daftar peminjaman yang cocok
     * 
     * Mencari di: kode_peminjaman, nama_anggota, NIS
     */
    public function search($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("
            SELECT p.*, a.nama as nama_anggota, a.nis
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE p.kode_peminjaman LIKE ? OR a.nama LIKE ? OR a.nis LIKE ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }
    
    // ========================================================================
    // METHOD UNTUK STATISTIK / DASHBOARD
    // ========================================================================
    
    /**
     * Menghitung total semua peminjaman
     * 
     * @return int Jumlah peminjaman
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return $stmt->fetchColumn();
    }
    
    /**
     * Menghitung peminjaman yang masih aktif
     * 
     * @return int Jumlah peminjaman dengan status 'dipinjam'
     */
    public function countActive() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE status = 'dipinjam'");
        return $stmt->fetchColumn();
    }
    
    /**
     * Menghitung peminjaman yang menunggu persetujuan
     * 
     * @return int Jumlah peminjaman dengan status 'pending'
     */
    public function countPending() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE status = 'pending'");
        return $stmt->fetchColumn();
    }
    
    /**
     * Mengambil semua peminjaman yang menunggu persetujuan
     * 
     * @return array Daftar peminjaman pending dengan data anggota dan buku
     */
    public function getPending() {
        $stmt = $this->db->query("
            SELECT p.*, a.nama as nama_anggota, a.nis, a.kelas
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE p.status = 'pending'
            ORDER BY p.created_at ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Menyetujui peminjaman (approve)
     * Mengubah status dari 'pending' ke 'dipinjam'
     * 
     * @param int $id ID peminjaman
     * @param int $userId ID admin yang menyetujui
     * @return bool True jika berhasil
     */
    public function approve($id, $userId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET status = 'dipinjam', user_id = ?, tanggal_pinjam = CURDATE()
            WHERE id = ? AND status = 'pending'
        ");
        return $stmt->execute([$userId, $id]);
    }
    
    /**
     * Menolak peminjaman (reject)
     * Mengubah status dari 'pending' ke 'ditolak'
     * 
     * @param int $id ID peminjaman
     * @param string $alasan Alasan penolakan
     * @return bool True jika berhasil
     */
    public function reject($id, $alasan = null) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET status = 'ditolak', keterangan = CONCAT(IFNULL(keterangan, ''), ' [DITOLAK: ', ?, ']')
            WHERE id = ? AND status = 'pending'
        ");
        return $stmt->execute([$alasan ?? 'Tidak ada alasan', $id]);
    }
    
    /**
     * Menghitung peminjaman yang sudah terlambat (overdue)
     * 
     * @return int Jumlah peminjaman terlambat
     * 
     * Kriteria terlambat:
     * - status = 'dipinjam' (belum dikembalikan)
     * - tanggal_harus_kembali < hari ini (sudah lewat)
     * 
     * CURDATE() = fungsi MySQL untuk tanggal hari ini
     */
    public function countOverdue() {
        $stmt = $this->db->query("
            SELECT COUNT(*) FROM {$this->table} 
            WHERE status = 'dipinjam' AND tanggal_harus_kembali < CURDATE()
        ");
        return $stmt->fetchColumn();
    }
    
    /**
     * Mengambil daftar peminjaman yang terlambat
     * 
     * @return array Daftar peminjaman terlambat dengan hari keterlambatan
     * 
     * DATEDIFF(CURDATE(), p.tanggal_harus_kembali) as hari_terlambat
     * -> Menghitung selisih hari antara hari ini dan tanggal harus kembali
     */
    public function getOverdue() {
        $stmt = $this->db->query("
            SELECT p.*, a.nama as nama_anggota, a.nis, 
                   DATEDIFF(CURDATE(), p.tanggal_harus_kembali) as hari_terlambat
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            WHERE p.status = 'dipinjam' AND p.tanggal_harus_kembali < CURDATE()
            ORDER BY p.tanggal_harus_kembali ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Menghitung jumlah peminjaman aktif milik anggota tertentu
     * 
     * @param int $anggotaId ID anggota
     * @return int           Jumlah peminjaman aktif
     * 
     * Digunakan untuk mengecek apakah anggota sudah mencapai batas peminjaman
     */
    public function countByAnggota($anggotaId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE anggota_id = ? AND status = 'dipinjam'");
        $stmt->execute([$anggotaId]);
        return $stmt->fetchColumn();
    }
    
    // ========================================================================
    // METHOD UNTUK GENERATE KODE
    // ========================================================================
    
    /**
     * Generate kode peminjaman otomatis
     * 
     * @return string Kode unik (format: PJ20260208001)
     * 
     * Format: PJ + YYYYMMDD + Nomor urut 4 digit
     * 
     * Cara kerja:
     * 1. Buat prefix dengan tanggal hari ini (PJ20260208)
     * 2. Cari kode terakhir dengan prefix yang sama
     * 3. Ambil 4 digit terakhir, tambah 1
     * 4. Gabungkan: PJ20260208 + 0002 = PJ202602080002
     */
    public function generateKode() {
        // Prefix = PJ + tanggal (YYYYMMDD)
        $prefix = 'PJ' . date('Ymd');
        
        // Cari kode terakhir hari ini
        $stmt = $this->db->prepare("
            SELECT kode_peminjaman FROM {$this->table} 
            WHERE kode_peminjaman LIKE ? 
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$prefix . '%']); // LIKE 'PJ20260208%'
        $last = $stmt->fetch();
        
        if ($last) {
            // Ambil 4 digit terakhir dari kode terakhir
            // PJ202602080001 -> substr(-4) -> '0001' -> (int) -> 1 -> +1 -> 2
            $num = (int) substr($last['kode_peminjaman'], -4) + 1;
        } else {
            // Belum ada peminjaman hari ini, mulai dari 1
            $num = 1;
        }
        
        // str_pad: 2 -> '0002' (padding dengan 0 sampai 4 digit)
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Mengambil peminjaman terbaru untuk dashboard
     * 
     * @param int $limit Jumlah record yang diambil (default 5)
     * @return array     Daftar peminjaman terbaru
     */
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT p.*, a.nama as nama_anggota, a.nis
            FROM {$this->table} p
            LEFT JOIN anggota a ON p.anggota_id = a.id
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}

/**
 * ============================================================================
 * CONTOH ALUR PEMINJAMAN:
 * ============================================================================
 * 
 * 1. MEMBUAT PEMINJAMAN:
 * $peminjamanModel = new Peminjaman();
 * 
 * // Buat header peminjaman
 * $data = [
 *     'kode_peminjaman' => $peminjamanModel->generateKode(),
 *     'anggota_id' => 5,
 *     'tanggal_pinjam' => '2026-02-08',
 *     'tanggal_harus_kembali' => '2026-02-15'
 * ];
 * $peminjamanId = $peminjamanModel->create($data);
 * 
 * // Tambahkan detail buku
 * $peminjamanModel->addDetail($peminjamanId, 1); // Buku ID 1
 * $peminjamanModel->addDetail($peminjamanId, 3); // Buku ID 3
 * 
 * // Update stok buku
 * $bukuModel->updateStokTersedia(1, 1, 'subtract');
 * $bukuModel->updateStokTersedia(3, 1, 'subtract');
 * 
 * 2. PROSES PENGEMBALIAN:
 * // Tandai sudah dikembalikan
 * $peminjamanModel->markAsReturned($peminjamanId);
 * 
 * // Kembalikan stok buku
 * $detail = $peminjamanModel->getDetail($peminjamanId);
 * foreach ($detail as $item) {
 *     $bukuModel->updateStokTersedia($item['buku_id'], 1, 'add');
 * }
 * 
 * ============================================================================
 */
