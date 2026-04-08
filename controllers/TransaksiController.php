<?php
/**
 * TransaksiController
 * CRUD Transaksi Peminjaman/Pengembalian untuk Admin
 */

class TransaksiController {
    private $peminjamanModel;
    private $pengembalianModel;
    private $bukuModel;
    private $anggotaModel;
    private $pengaturanModel;
    
    public function __construct() {
        // Check if admin is logged in
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            Session::setFlash('error', 'Silakan login sebagai admin');
            Redirect::to('login');
        }
        
        $this->peminjamanModel = new Peminjaman();
        $this->pengembalianModel = new Pengembalian();
        $this->bukuModel = new Buku();
        $this->anggotaModel = new Anggota();
        $this->pengaturanModel = new Pengaturan();
    }
    
    /**
     * List all transaksi peminjaman
     */
    public function index() {
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $this->peminjamanModel->getAll()
        ];
        
        include BASE_PATH . '/views/admin/transaksi/index.php';
    }
    
    /**
     * Show create form
     */
    public function create() {
        $pengaturan = $this->pengaturanModel->get();
        $maxHari = $pengaturan['max_hari_pinjam'] ?? 7;
        
        $data = [
            'user' => Session::get('user_data'),
            'anggota' => $this->anggotaModel->getActive(),
            'buku' => $this->bukuModel->getAvailable(),
            'kode_peminjaman' => $this->peminjamanModel->generateKode(),
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_harus_kembali' => date('Y-m-d', strtotime("+$maxHari days")),
            'max_buku' => $pengaturan['max_buku_pinjam'] ?? 3
        ];
        
        include BASE_PATH . '/views/admin/transaksi/create.php';
    }
    
    /**
     * Store new transaksi
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('admin/transaksi');
            return;
        }
        
        $data = Validation::sanitize($_POST);
        $bukuIds = $_POST['buku'] ?? [];
        
        $validation = new Validation($data);
        $validation->required('kode_peminjaman', 'Kode Peminjaman')
                   ->required('anggota_id', 'Anggota')
                   ->required('tanggal_pinjam', 'Tanggal Pinjam')
                   ->required('tanggal_harus_kembali', 'Tanggal Harus Kembali');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('admin/transaksi/create');
            return;
        }
        
        if (empty($bukuIds)) {
            Session::setFlash('error', 'Pilih minimal 1 buku');
            Redirect::to('admin/transaksi/create');
            return;
        }
        
        // Check if anggota can borrow more
        $pengaturan = $this->pengaturanModel->get();
        $maxBuku = $pengaturan['max_buku_pinjam'] ?? 3;
        $currentPinjam = $this->peminjamanModel->countByAnggota($data['anggota_id']);
        
        if ($currentPinjam + count($bukuIds) > $maxBuku) {
            Session::setFlash('error', 'Anggota sudah mencapai batas maksimal peminjaman');
            Redirect::to('admin/transaksi/create');
            return;
        }
        
        // Add user_id
        $data['user_id'] = Session::getUserId();
        
        // Create peminjaman
        $peminjamanId = $this->peminjamanModel->create($data);
        
        if ($peminjamanId) {
            // Add detail and update stock
            foreach ($bukuIds as $bukuId) {
                $this->peminjamanModel->addDetail($peminjamanId, $bukuId);
                $this->bukuModel->updateStokTersedia($bukuId, 1, 'subtract');
            }
            
            Session::setFlash('success', 'Transaksi peminjaman berhasil dibuat');
            Redirect::to('admin/transaksi');
        } else {
            Session::setFlash('error', 'Gagal membuat transaksi');
            Redirect::to('admin/transaksi/create');
        }
    }
    
    /**
     * Show transaksi detail
     */
    public function show($id = null) {
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/transaksi');
            return;
        }
        
        $peminjaman = $this->peminjamanModel->findById($id);
        
        if (!$peminjaman) {
            Session::setFlash('error', 'Transaksi tidak ditemukan');
            Redirect::to('admin/transaksi');
            return;
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $peminjaman,
            'detail' => $this->peminjamanModel->getDetail($id),
            'pengembalian' => $this->pengembalianModel->findByPeminjaman($id)
        ];
        
        include BASE_PATH . '/views/admin/transaksi/show.php';
    }
    
    /**
     * Show edit form
     */
    public function edit($id = null) {
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/transaksi');
            return;
        }
        
        $peminjaman = $this->peminjamanModel->findById($id);
        
        if (!$peminjaman) {
            Session::setFlash('error', 'Transaksi tidak ditemukan');
            Redirect::to('admin/transaksi');
            return;
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $peminjaman,
            'detail' => $this->peminjamanModel->getDetail($id),
            'anggota' => $this->anggotaModel->getActive()
        ];
        
        include BASE_PATH . '/views/admin/transaksi/edit.php';
    }
    
    /**
     * Update transaksi
     */
    public function update($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            Redirect::to('admin/transaksi');
            return;
        }
        
        $data = [
            'tanggal_harus_kembali' => $_POST['tanggal_harus_kembali'] ?? null,
            'keterangan' => $_POST['keterangan'] ?? null,
            'status' => $_POST['status'] ?? 'dipinjam'
        ];
        
        $result = $this->peminjamanModel->update($id, $data);
        
        if ($result) {
            Session::setFlash('success', 'Transaksi berhasil diperbarui');
            Redirect::to('admin/transaksi');
        } else {
            Session::setFlash('error', 'Gagal memperbarui transaksi');
            Redirect::to('admin/transaksi/edit?id=' . $id);
        }
    }
    
    /**
     * Delete transaksi
     */
    public function delete($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/transaksi');
            return;
        }
        
        // Get detail to restore stock
        $detail = $this->peminjamanModel->getDetail($id);
        $peminjaman = $this->peminjamanModel->findById($id);
        
        if ($peminjaman && $peminjaman['status'] === 'dipinjam') {
            // Restore stock for active loans
            foreach ($detail as $item) {
                $this->bukuModel->updateStokTersedia($item['buku_id'], $item['jumlah'], 'add');
            }
        }
        
        $result = $this->peminjamanModel->delete($id);
        
        if ($result) {
            Session::setFlash('success', 'Transaksi berhasil dihapus');
        } else {
            Session::setFlash('error', 'Gagal menghapus transaksi');
        }
        
        Redirect::to('admin/transaksi');
    }
    
    /**
     * Search transaksi
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $this->peminjamanModel->search($keyword),
            'keyword' => $keyword
        ];
        
        include BASE_PATH . '/views/admin/transaksi/index.php';
    }
    
    /**
     * List pengembalian
     */
    public function pengembalian() {
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $this->peminjamanModel->getByStatus('dipinjam'),
            'pengembalian' => $this->pengembalianModel->getAll()
        ];
        
        include BASE_PATH . '/views/admin/pengembalian/index.php';
    }
    
    /**
     * Process pengembalian form
     */
    public function processPengembalian($id = null) {
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/pengembalian');
            return;
        }
        
        $peminjaman = $this->peminjamanModel->findById($id);
        
        if (!$peminjaman || $peminjaman['status'] !== 'dipinjam') {
            Session::setFlash('error', 'Peminjaman tidak ditemukan atau sudah dikembalikan');
            Redirect::to('admin/pengembalian');
            return;
        }
        
        // Calculate denda
        $dendaInfo = $this->pengembalianModel->calculateDenda($peminjaman['tanggal_harus_kembali']);
        
        $data = [
            'user' => Session::get('user_data'),
            'peminjaman' => $peminjaman,
            'detail' => $this->peminjamanModel->getDetail($id),
            'kode_pengembalian' => $this->pengembalianModel->generateKode(),
            'tanggal_pengembalian' => date('Y-m-d'),
            'keterlambatan' => $dendaInfo['hari'],
            'denda_keterlambatan' => $dendaInfo['denda']
        ];
        
        include BASE_PATH . '/views/admin/pengembalian/process.php';
    }
    
    /**
     * Store pengembalian
     */
    public function storePengembalian() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('admin/pengembalian');
            return;
        }
        
        $data = Validation::sanitize($_POST);
        
        $validation = new Validation($data);
        $validation->required('peminjaman_id', 'ID Peminjaman')
                   ->required('tanggal_pengembalian', 'Tanggal Pengembalian');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('admin/pengembalian');
            return;
        }
        
        $peminjamanId = $data['peminjaman_id'];
        
        // Calculate total denda
        $dendaKerusakan = floatval($data['denda_kerusakan'] ?? 0);
        $dendaKeterlambatan = floatval($data['denda_keterlambatan'] ?? 0);
        $totalDenda = $dendaKerusakan + $dendaKeterlambatan;
        
        $pengembalianData = [
            'kode_pengembalian' => $data['kode_pengembalian'],
            'peminjaman_id' => $peminjamanId,
            'user_id' => Session::getUserId(),
            'tanggal_pengembalian' => $data['tanggal_pengembalian'],
            'kondisi_buku' => $data['kondisi_buku'] ?? 'baik',
            'keterlambatan' => $data['keterlambatan'] ?? 0,
            'denda_keterlambatan' => $dendaKeterlambatan,
            'denda_kerusakan' => $dendaKerusakan,
            'total_denda' => $totalDenda,
            'keterangan' => $data['keterangan'] ?? null
        ];
        
        $result = $this->pengembalianModel->create($pengembalianData);
        
        if ($result) {
            // Update peminjaman status
            $this->peminjamanModel->markAsReturned($peminjamanId, $data['tanggal_pengembalian']);
            $this->peminjamanModel->update($peminjamanId, ['total_denda' => $totalDenda]);
            
            // Get detail and restore stock
            $detail = $this->peminjamanModel->getDetail($peminjamanId);
            foreach ($detail as $item) {
                $this->bukuModel->updateStokTersedia($item['buku_id'], $item['jumlah'], 'add');
            }
            
            Session::setFlash('success', 'Pengembalian berhasil diproses');
            Redirect::to('admin/pengembalian');
        } else {
            Session::setFlash('error', 'Gagal memproses pengembalian');
            Redirect::to('admin/pengembalian/process?id=' . $peminjamanId);
        }
    }
    
    // ========================================================================
    // APPROVAL/PENDING METHODS
    // ========================================================================
    
    /**
     * List semua pengajuan peminjaman yang pending (menunggu persetujuan)
     */
    public function pending() {
        $pending = $this->peminjamanModel->getPending();
        
        // Add detail buku untuk setiap pengajuan
        foreach ($pending as &$p) {
            $p['detail'] = $this->peminjamanModel->getDetail($p['id']);
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'pending' => $pending
        ];
        
        include BASE_PATH . '/views/admin/pending/index.php';
    }
    
    /**
     * Menyetujui pengajuan peminjaman
     */
    public function approve($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id) {
            Session::setFlash('error', 'ID peminjaman tidak valid');
            Redirect::to('admin/pending');
            return;
        }
        
        $peminjaman = $this->peminjamanModel->findById($id);
        
        if (!$peminjaman || $peminjaman['status'] !== 'pending') {
            Session::setFlash('error', 'Pengajuan tidak ditemukan atau sudah diproses');
            Redirect::to('admin/pending');
            return;
        }
        
        // Approve peminjaman
        $result = $this->peminjamanModel->approve($id, Session::getUserId());
        
        if ($result) {
            // Kurangi stok buku
            $detail = $this->peminjamanModel->getDetail($id);
            foreach ($detail as $item) {
                $this->bukuModel->updateStokTersedia($item['buku_id'], $item['jumlah'], 'subtract');
            }
            
            Session::setFlash('success', 'Peminjaman berhasil disetujui');
        } else {
            Session::setFlash('error', 'Gagal menyetujui peminjaman');
        }
        
        Redirect::to('admin/pending');
    }
    
    /**
     * Menolak pengajuan peminjaman
     */
    public function reject($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        $alasan = $_GET['alasan'] ?? $_POST['alasan'] ?? 'Tidak ada alasan';
        
        if (!$id) {
            Session::setFlash('error', 'ID peminjaman tidak valid');
            Redirect::to('admin/pending');
            return;
        }
        
        $peminjaman = $this->peminjamanModel->findById($id);
        
        if (!$peminjaman || $peminjaman['status'] !== 'pending') {
            Session::setFlash('error', 'Pengajuan tidak ditemukan atau sudah diproses');
            Redirect::to('admin/pending');
            return;
        }
        
        // Reject peminjaman - stok TIDAK berubah karena belum dipinjam
        $result = $this->peminjamanModel->reject($id, $alasan);
        
        if ($result) {
            Session::setFlash('success', 'Pengajuan peminjaman ditolak');
        } else {
            Session::setFlash('error', 'Gagal menolak pengajuan');
        }
        
        Redirect::to('admin/pending');
    }
}
