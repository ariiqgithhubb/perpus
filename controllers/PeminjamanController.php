<?php
/**
 * PeminjamanController
 * Fitur peminjaman buku untuk siswa
 */

class PeminjamanController {
    private $peminjamanModel;
    private $bukuModel;
    private $anggotaModel;
    private $pengaturanModel;
    
    public function __construct() {
        // Check if siswa is logged in
        if (!Session::isLoggedIn() || !Session::isSiswa()) {
            Session::setFlash('error', 'Silakan login sebagai siswa');
            Redirect::to('login');
        }
        
        $this->peminjamanModel = new Peminjaman();
        $this->bukuModel = new Buku();
        $this->anggotaModel = new Anggota();
        $this->pengaturanModel = new Pengaturan();
    }
    
    /**
     * List buku tersedia
     */
    public function index() {
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $this->bukuModel->getAvailable()
        ];
        
        include BASE_PATH . '/views/siswa/peminjaman/index.php';
    }
    
    /**
     * Create peminjaman form
     */
    public function create() {
        $bukuId = $_GET['buku_id'] ?? null;
        
        if (!$bukuId) {
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        $buku = $this->bukuModel->findById($bukuId);
        
        if (!$buku || $buku['stok_tersedia'] <= 0) {
            Session::setFlash('error', 'Buku tidak tersedia');
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        $anggotaId = Session::getUserId();
        $pengaturan = $this->pengaturanModel->get();
        $maxBuku = $pengaturan['max_buku_pinjam'] ?? 3;
        $maxHari = $pengaturan['max_hari_pinjam'] ?? 7;
        
        // Check current peminjaman count
        $currentPinjam = $this->peminjamanModel->countByAnggota($anggotaId);
        
        if ($currentPinjam >= $maxBuku) {
            Session::setFlash('error', "Anda sudah mencapai batas maksimal peminjaman ($maxBuku buku)");
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $buku,
            'kode_peminjaman' => $this->peminjamanModel->generateKode(),
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_harus_kembali' => date('Y-m-d', strtotime("+$maxHari days")),
            'sisa_slot' => $maxBuku - $currentPinjam
        ];
        
        include BASE_PATH . '/views/siswa/peminjaman/create.php';
    }
    
    /**
     * Store peminjaman
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        $anggotaId = Session::getUserId();
        $bukuId = $_POST['buku_id'] ?? null;
        
        if (!$bukuId) {
            Session::setFlash('error', 'Data tidak lengkap');
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        // Validate buku availability
        $buku = $this->bukuModel->findById($bukuId);
        
        if (!$buku || $buku['stok_tersedia'] <= 0) {
            Session::setFlash('error', 'Buku tidak tersedia');
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        // Check max peminjaman
        $pengaturan = $this->pengaturanModel->get();
        $maxBuku = $pengaturan['max_buku_pinjam'] ?? 3;
        $maxHari = $pengaturan['max_hari_pinjam'] ?? 7;
        $currentPinjam = $this->peminjamanModel->countByAnggota($anggotaId);
        
        if ($currentPinjam >= $maxBuku) {
            Session::setFlash('error', 'Anda sudah mencapai batas maksimal peminjaman');
            Redirect::to('siswa/peminjaman');
            return;
        }
        
        // Create peminjaman
        $peminjamanData = [
            'kode_peminjaman' => $this->peminjamanModel->generateKode(),
            'anggota_id' => $anggotaId,
            'user_id' => null, // Self service
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_harus_kembali' => date('Y-m-d', strtotime("+$maxHari days")),
            'keterangan' => 'Peminjaman mandiri oleh siswa'
        ];
        
        $peminjamanId = $this->peminjamanModel->create($peminjamanData);
        
        if ($peminjamanId) {
            // Add detail
            $this->peminjamanModel->addDetail($peminjamanId, $bukuId, 1, 'baik');
            
            // TIDAK update stok di sini karena masih pending
            // Stok akan dikurangi saat admin menyetujui peminjaman
            
            Session::setFlash('success', 'Pengajuan peminjaman berhasil! Mohon tunggu persetujuan admin.');
            Redirect::to('siswa/peminjaman/history');
        } else {
            Session::setFlash('error', 'Gagal memproses pengajuan peminjaman');
            Redirect::to('siswa/peminjaman');
        }
    }
    
    /**
     * History peminjaman
     */
    public function history() {
        $anggotaId = Session::getUserId();
        
        $peminjaman = $this->peminjamanModel->getByAnggota($anggotaId);
        
        // Add detail to each peminjaman
        foreach ($peminjaman as &$p) {
            $p['detail'] = $this->peminjamanModel->getDetail($p['id']);
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $peminjaman
        ];
        
        include BASE_PATH . '/views/siswa/peminjaman/history.php';
    }
    
    /**
     * Search buku
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $this->bukuModel->searchAvailable($keyword),
            'keyword' => $keyword
        ];
        
        include BASE_PATH . '/views/siswa/peminjaman/index.php';
    }
}
