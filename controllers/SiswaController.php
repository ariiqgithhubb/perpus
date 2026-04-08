<?php
/**
 * SiswaController
 * Dashboard siswa
 */

class SiswaController {
    private $anggotaModel;
    private $peminjamanModel;
    private $pengembalianModel;
    private $bukuModel;
    
    public function __construct() {
        // Check if siswa is logged in
        if (!Session::isLoggedIn() || !Session::isSiswa()) {
            Session::setFlash('error', 'Silakan login sebagai siswa');
            Redirect::to('login');
        }
        
        $this->anggotaModel = new Anggota();
        $this->peminjamanModel = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
        $this->bukuModel = new Buku();
    }
    
    /**
     * Dashboard siswa
     */
    public function index() {
        $anggotaId = Session::getUserId();
        
        $data = [
            'user' => Session::get('user_data'),
            'anggota' => $this->anggotaModel->getWithPeminjamanCount($anggotaId),
            'peminjaman_aktif' => $this->peminjamanModel->getActiveByAnggota($anggotaId),
            'recent_peminjaman' => $this->peminjamanModel->getByAnggota($anggotaId),
            'total_buku' => $this->bukuModel->countAvailable()
        ];
        
        include BASE_PATH . '/views/siswa/dashboard.php';
    }
}
