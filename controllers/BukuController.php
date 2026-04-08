<?php
/**
 * BukuController
 * CRUD Buku untuk Admin
 */

class BukuController {
    private $bukuModel;
    private $kategoriModel;
    
    public function __construct() {
        // Check if admin is logged in
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            Session::setFlash('error', 'Silakan login sebagai admin');
            Redirect::to('login');
        }
        
        $this->bukuModel = new Buku();
        $this->kategoriModel = new Kategori();
    }
    
    /**
     * List all buku
     */
    public function index() {
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $this->bukuModel->getAll(),
            'kategori' => $this->kategoriModel->getAll()
        ];
        
        include BASE_PATH . '/views/admin/buku/index.php';
    }
    
    /**
     * Show create form
     */
    public function create() {
        $data = [
            'user' => Session::get('user_data'),
            'kategori' => $this->kategoriModel->getAll(),
            'kode_buku' => $this->bukuModel->generateKode()
        ];
        
        include BASE_PATH . '/views/admin/buku/create.php';
    }
    
    /**
     * Store new buku
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('admin/buku');
            return;
        }
        
        $data = Validation::sanitize($_POST);
        
        $validation = new Validation($data);
        $validation->required('kode_buku', 'Kode Buku')
                   ->required('judul', 'Judul')
                   ->required('penulis', 'Penulis')
                   ->required('stok', 'Stok')
                   ->numeric('stok', 'Stok');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('admin/buku/create');
            return;
        }
        
        // Check if kode exists
        if ($this->bukuModel->kodeExists($data['kode_buku'])) {
            Session::setFlash('error', 'Kode buku sudah digunakan');
            Redirect::to('admin/buku/create');
            return;
        }
        
        // Handle cover upload
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/assets/images/covers/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = time() . '_' . basename($_FILES['cover']['name']);
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $uploadFile)) {
                $data['cover'] = 'assets/images/covers/' . $filename;
            }
        }
        
        $result = $this->bukuModel->create($data);
        
        if ($result) {
            Session::setFlash('success', 'Buku berhasil ditambahkan');
            Redirect::to('admin/buku');
        } else {
            Session::setFlash('error', 'Gagal menambahkan buku');
            Redirect::to('admin/buku/create');
        }
    }
    
    /**
     * Show buku detail
     */
    public function show($id = null) {
        // Debug: show what's in $_GET
        // echo '<pre>$_GET: '; print_r($_GET); echo '</pre>';
        // echo '<pre>$id param: ' . var_export($id, true) . '</pre>';
        
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Session::setFlash('error', 'ID tidak ditemukan di request. GET: ' . json_encode($_GET));
            Redirect::to('admin/buku');
            return;
        }
        
        // Cast ID to integer to ensure proper type
        $id = (int) $id;
        
        $buku = $this->bukuModel->findById($id);
        
        if (!$buku) {
            Session::setFlash('error', 'Buku dengan ID ' . $id . ' tidak ditemukan di database');
            Redirect::to('admin/buku');
            return;
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $buku
        ];
        
        include BASE_PATH . '/views/admin/buku/show.php';
    }
    
    /**
     * Show edit form
     */
    public function edit($id = null) {
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Session::setFlash('error', 'ID tidak ditemukan di request');
            Redirect::to('admin/buku');
            return;
        }
        
        // Cast ID to integer to ensure proper type
        $id = (int) $id;
        
        $buku = $this->bukuModel->findById($id);
        
        if (!$buku) {
            Session::setFlash('error', 'Buku dengan ID ' . $id . ' tidak ditemukan di database');
            Redirect::to('admin/buku');
            return;
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $buku,
            'kategori' => $this->kategoriModel->getAll()
        ];
        
        include BASE_PATH . '/views/admin/buku/edit.php';
    }
    
    /**
     * Update buku
     */
    public function update($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            Redirect::to('admin/buku');
            return;
        }
        
        $data = Validation::sanitize($_POST);
        unset($data['id']);
        
        $validation = new Validation($data);
        $validation->required('kode_buku', 'Kode Buku')
                   ->required('judul', 'Judul')
                   ->required('penulis', 'Penulis')
                   ->required('stok', 'Stok')
                   ->numeric('stok', 'Stok');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('admin/buku/edit?id=' . $id);
            return;
        }
        
        // Check if kode exists (excluding current)
        if ($this->bukuModel->kodeExists($data['kode_buku'], $id)) {
            Session::setFlash('error', 'Kode buku sudah digunakan');
            Redirect::to('admin/buku/edit?id=' . $id);
            return;
        }
        
        // Handle cover upload
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/assets/images/covers/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = time() . '_' . basename($_FILES['cover']['name']);
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $uploadFile)) {
                $data['cover'] = 'assets/images/covers/' . $filename;
            }
        } else {
            unset($data['cover']);
        }
        
        $result = $this->bukuModel->update($id, $data);
        
        if ($result) {
            Session::setFlash('success', 'Buku berhasil diperbarui');
            Redirect::to('admin/buku');
        } else {
            Session::setFlash('error', 'Gagal memperbarui buku');
            Redirect::to('admin/buku/edit?id=' . $id);
        }
    }
    
    /**
     * Delete buku
     */
    public function delete($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/buku');
            return;
        }
        
        $result = $this->bukuModel->delete($id);
        
        if ($result) {
            Session::setFlash('success', 'Buku berhasil dihapus');
        } else {
            Session::setFlash('error', 'Gagal menghapus buku');
        }
        
        Redirect::to('admin/buku');
    }
    
    /**
     * Search buku
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        
        $data = [
            'user' => Session::get('user_data'),
            'buku' => $this->bukuModel->search($keyword),
            'kategori' => $this->kategoriModel->getAll(),
            'keyword' => $keyword
        ];
        
        include BASE_PATH . '/views/admin/buku/index.php';
    }
}
