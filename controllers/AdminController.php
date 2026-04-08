<?php
/**
 * AdminController
 * Dashboard dan fitur admin
 */

class AdminController {
    private $bukuModel;
    private $anggotaModel;
    private $peminjamanModel;
    private $pengembalianModel;
    
    public function __construct() {
        // Check if admin is logged in
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            Session::setFlash('error', 'Silakan login sebagai admin');
            Redirect::to('login');
        }
        
        $this->bukuModel = new Buku();
        $this->anggotaModel = new Anggota();
        $this->peminjamanModel = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
    }
    
    /**
     * Dashboard admin
     */
    public function index() {
        $data = [
            'user' => Session::get('user_data'),
            'total_buku' => $this->bukuModel->count(),
            'total_anggota' => $this->anggotaModel->count(),
            'total_pinjam' => $this->peminjamanModel->countActive(),
            'total_pending' => $this->peminjamanModel->countPending(),
            'total_terlambat' => $this->peminjamanModel->countOverdue(),
            'peminjaman_terbaru' => $this->peminjamanModel->getRecent(5),
            'buku_populer' => $this->bukuModel->getPopular(5)
        ];
        
        include BASE_PATH . '/views/admin/dashboard.php';
    }
}
