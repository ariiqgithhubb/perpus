<?php
/**
 * PengembalianController
 * Fitur pengembalian buku untuk siswa
 */

class PengembalianController {
    private $peminjamanModel;
    private $pengembalianModel;
    private $bukuModel;
    
    public function __construct() {
        // Check if siswa is logged in
        if (!Session::isLoggedIn() || !Session::isSiswa()) {
            Session::setFlash('error', 'Silakan login sebagai siswa');
            Redirect::to('login');
        }
        
        $this->peminjamanModel = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
        $this->bukuModel = new Buku();
    }
    
    /**
     * List buku yang sedang dipinjam
     */
    public function index() {
        $anggotaId = Session::getUserId();
        
        $peminjamanAktif = $this->peminjamanModel->getActiveByAnggota($anggotaId);
        
        // Add detail and calculate denda for each
        foreach ($peminjamanAktif as &$p) {
            $p['detail'] = $this->peminjamanModel->getDetail($p['id']);
            $dendaInfo = $this->pengembalianModel->calculateDenda($p['tanggal_harus_kembali']);
            $p['keterlambatan'] = $dendaInfo['hari'];
            $p['estimasi_denda'] = $dendaInfo['denda'];
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $peminjamanAktif
        ];
        
        include BASE_PATH . '/views/siswa/pengembalian/index.php';
    }
    
    /**
     * Process pengembalian form
     */
    public function process() {
        $peminjamanId = $_GET['id'] ?? null;
        
        if (!$peminjamanId) {
            Redirect::to('siswa/pengembalian');
            return;
        }
        
        $anggotaId = Session::getUserId();
        $peminjaman = $this->peminjamanModel->findById($peminjamanId);
        
        // Validate ownership and status
        if (!$peminjaman || $peminjaman['anggota_id'] != $anggotaId || $peminjaman['status'] !== 'dipinjam') {
            Session::setFlash('error', 'Peminjaman tidak ditemukan atau tidak valid');
            Redirect::to('siswa/pengembalian');
            return;
        }
        
        // Calculate denda
        $dendaInfo = $this->pengembalianModel->calculateDenda($peminjaman['tanggal_harus_kembali']);
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $peminjaman,
            'detail' => $this->peminjamanModel->getDetail($peminjamanId),
            'kode_pengembalian' => $this->pengembalianModel->generateKode(),
            'tanggal_pengembalian' => date('Y-m-d'),
            'keterlambatan' => $dendaInfo['hari'],
            'denda_keterlambatan' => $dendaInfo['denda']
        ];
        
        include BASE_PATH . '/views/siswa/pengembalian/process.php';
    }
    
    /**
     * Store pengembalian
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('siswa/pengembalian');
            return;
        }
        
        $anggotaId = Session::getUserId();
        $peminjamanId = $_POST['peminjaman_id'] ?? null;
        
        if (!$peminjamanId) {
            Session::setFlash('error', 'Data tidak lengkap');
            Redirect::to('siswa/pengembalian');
            return;
        }
        
        $peminjaman = $this->peminjamanModel->findById($peminjamanId);
        
        // Validate ownership and status
        if (!$peminjaman || $peminjaman['anggota_id'] != $anggotaId || $peminjaman['status'] !== 'dipinjam') {
            Session::setFlash('error', 'Peminjaman tidak valid');
            Redirect::to('siswa/pengembalian');
            return;
        }
        
        // Calculate denda
        $dendaInfo = $this->pengembalianModel->calculateDenda($peminjaman['tanggal_harus_kembali']);
        
        $pengembalianData = [
            'kode_pengembalian' => $this->pengembalianModel->generateKode(),
            'peminjaman_id' => $peminjamanId,
            'user_id' => null, // Self service
            'tanggal_pengembalian' => date('Y-m-d'),
            'kondisi_buku' => $_POST['kondisi_buku'] ?? 'baik',
            'keterlambatan' => $dendaInfo['hari'],
            'denda_keterlambatan' => $dendaInfo['denda'],
            'denda_kerusakan' => 0,
            'total_denda' => $dendaInfo['denda'],
            'keterangan' => 'Pengembalian mandiri oleh siswa'
        ];
        
        $result = $this->pengembalianModel->create($pengembalianData);
        
        if ($result) {
            // Update peminjaman status
            $this->peminjamanModel->markAsReturned($peminjamanId);
            $this->peminjamanModel->update($peminjamanId, ['total_denda' => $dendaInfo['denda']]);
            
            // Restore stock
            $detail = $this->peminjamanModel->getDetail($peminjamanId);
            foreach ($detail as $item) {
                $this->bukuModel->updateStokTersedia($item['buku_id'], $item['jumlah'], 'add');
            }
            
            $message = 'Buku berhasil dikembalikan!';
            if ($dendaInfo['denda'] > 0) {
                $message .= ' Denda keterlambatan: Rp ' . number_format($dendaInfo['denda'], 0, ',', '.');
            }
            
            Session::setFlash('success', $message);
            Redirect::to('siswa/pengembalian/history');
        } else {
            Session::setFlash('error', 'Gagal memproses pengembalian');
            Redirect::to('siswa/pengembalian');
        }
    }
    
    /**
     * History pengembalian
     */
    public function history() {
        $anggotaId = Session::getUserId();
        
        $data = [
            'user' => Session::get('user_data'),
            'pengembalian' => $this->pengembalianModel->getByAnggota($anggotaId)
        ];
        
        include BASE_PATH . '/views/siswa/pengembalian/history.php';
    }
}
