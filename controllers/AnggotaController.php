<?php
/**
 * AnggotaController
 * CRUD Anggota untuk Admin
 */

class AnggotaController {
    private $anggotaModel;
    
    public function __construct() {
        // Check if admin is logged in
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            Session::setFlash('error', 'Silakan login sebagai admin');
            Redirect::to('login');
        }
        
        $this->anggotaModel = new Anggota();
    }
    
    /**
     * List all anggota
     */
    public function index() {
        $data = [
            'user' => Session::get('user_data'),
            'anggota' => $this->anggotaModel->getAll()
        ];
        
        include BASE_PATH . '/views/admin/anggota/index.php';
    }
    
    /**
     * Show create form
     */
    public function create() {
        $data = [
            'user' => Session::get('user_data')
        ];
        
        include BASE_PATH . '/views/admin/anggota/create.php';
    }
    
    /**
     * Store new anggota
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Redirect::to('admin/anggota');
            return;
        }
        
        $data = Validation::sanitize($_POST);
        
        $validation = new Validation($data);
        $validation->required('nis', 'NIS')
                   ->required('nama', 'Nama')
                   ->required('jenis_kelamin', 'Jenis Kelamin')
                   ->required('kelas', 'Kelas')
                   ->required('username', 'Username')
                   ->required('password', 'Password')
                   ->minLength('password', 6, 'Password');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('admin/anggota/create');
            return;
        }
        
        // Check if NIS exists
        if ($this->anggotaModel->nisExists($data['nis'])) {
            Session::setFlash('error', 'NIS sudah terdaftar');
            Redirect::to('admin/anggota/create');
            return;
        }
        
        // Check if username exists
        if ($this->anggotaModel->usernameExists($data['username'])) {
            Session::setFlash('error', 'Username sudah digunakan');
            Redirect::to('admin/anggota/create');
            return;
        }
        
        // Handle foto upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/assets/images/anggota/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = time() . '_' . basename($_FILES['foto']['name']);
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                $data['foto'] = 'assets/images/anggota/' . $filename;
            }
        }
        
        $result = $this->anggotaModel->create($data);
        
        if ($result) {
            Session::setFlash('success', 'Anggota berhasil ditambahkan');
            Redirect::to('admin/anggota');
        } else {
            Session::setFlash('error', 'Gagal menambahkan anggota');
            Redirect::to('admin/anggota/create');
        }
    }
    
    /**
     * Show anggota detail
     */
    public function show($id = null) {
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/anggota');
            return;
        }
        
        $anggota = $this->anggotaModel->getWithPeminjamanCount($id);
        
        if (!$anggota) {
            Session::setFlash('error', 'Anggota tidak ditemukan');
            Redirect::to('admin/anggota');
            return;
        }
        
        // Get peminjaman history
        $peminjamanModel = new Peminjaman();
        
        $data = [
            'user' => Session::get('user_data'),
            'anggota' => $anggota,
            'peminjaman' => $peminjamanModel->getByAnggota($id)
        ];
        
        include BASE_PATH . '/views/admin/anggota/show.php';
    }
    
    /**
     * Show edit form
     */
    public function edit($id = null) {
        $id = $id ?? $_GET['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/anggota');
            return;
        }
        
        $anggota = $this->anggotaModel->findById($id);
        
        if (!$anggota) {
            Session::setFlash('error', 'Anggota tidak ditemukan');
            Redirect::to('admin/anggota');
            return;
        }
        
        $data = [
            'user' => Session::get('user_data'),
            'anggota' => $anggota
        ];
        
        include BASE_PATH . '/views/admin/anggota/edit.php';
    }
    
    /**
     * Update anggota
     */
    public function update($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            Redirect::to('admin/anggota');
            return;
        }
        
        $data = Validation::sanitize($_POST);
        unset($data['id']);
        
        $validation = new Validation($data);
        $validation->required('nis', 'NIS')
                   ->required('nama', 'Nama')
                   ->required('jenis_kelamin', 'Jenis Kelamin')
                   ->required('kelas', 'Kelas')
                   ->required('username', 'Username');
        
        if ($validation->fails()) {
            Session::setFlash('error', $validation->firstError());
            Redirect::to('admin/anggota/edit?id=' . $id);
            return;
        }
        
        // Check if NIS exists (excluding current)
        if ($this->anggotaModel->nisExists($data['nis'], $id)) {
            Session::setFlash('error', 'NIS sudah terdaftar');
            Redirect::to('admin/anggota/edit?id=' . $id);
            return;
        }
        
        // Check if username exists (excluding current)
        if ($this->anggotaModel->usernameExists($data['username'], $id)) {
            Session::setFlash('error', 'Username sudah digunakan');
            Redirect::to('admin/anggota/edit?id=' . $id);
            return;
        }
        
        // Handle foto upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_PATH . '/assets/images/anggota/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = time() . '_' . basename($_FILES['foto']['name']);
            $uploadFile = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                $data['foto'] = 'assets/images/anggota/' . $filename;
            }
        } else {
            unset($data['foto']);
        }
        
        // Remove empty password
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        $result = $this->anggotaModel->update($id, $data);
        
        if ($result) {
            Session::setFlash('success', 'Anggota berhasil diperbarui');
            Redirect::to('admin/anggota');
        } else {
            Session::setFlash('error', 'Gagal memperbarui anggota');
            Redirect::to('admin/anggota/edit?id=' . $id);
        }
    }
    
    /**
     * Delete anggota
     */
    public function delete($id = null) {
        $id = $id ?? $_GET['id'] ?? $_POST['id'] ?? null;
        
        if (!$id) {
            Redirect::to('admin/anggota');
            return;
        }
        
        $result = $this->anggotaModel->delete($id);
        
        if ($result) {
            Session::setFlash('success', 'Anggota berhasil dihapus');
        } else {
            Session::setFlash('error', 'Gagal menghapus anggota');
        }
        
        Redirect::to('admin/anggota');
    }
    
    /**
     * Search anggota
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        
        $data = [
            'user' => Session::get('user_data'),
            'anggota' => $this->anggotaModel->search($keyword),
            'keyword' => $keyword
        ];
        
        include BASE_PATH . '/views/admin/anggota/index.php';
    }
}
