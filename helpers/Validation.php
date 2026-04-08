<?php
/**
 * ============================================================================
 * FILE: Validation.php
 * LOKASI: helpers/Validation.php
 * FUNGSI: Kelas helper untuk validasi input dari form
 * ============================================================================
 * 
 * Validasi input sangat PENTING untuk:
 * 1. Keamanan - Mencegah serangan SQL Injection dan XSS
 * 2. Integritas Data - Memastikan data yang masuk sesuai format
 * 3. User Experience - Memberikan pesan error yang jelas ke pengguna
 * 
 * Kelas ini menggunakan FLUENT INTERFACE / METHOD CHAINING
 * Artinya method bisa dipanggil beruntun:
 * $validation->required('nama')->minLength('nama', 3)->email('email');
 * 
 * ============================================================================
 */

class Validation {
    
    // ========================================================================
    // PROPERTI KELAS
    // ========================================================================
    
    /**
     * Array untuk menyimpan pesan error validasi
     * Format: ['field_name' => 'pesan error']
     */
    private $errors = [];
    
    /**
     * Array berisi data yang akan divalidasi
     * Biasanya berasal dari $_POST
     */
    private $data = [];
    
    // ========================================================================
    // CONSTRUCTOR
    // ========================================================================
    
    /**
     * Constructor - dipanggil saat membuat objek Validation baru
     * 
     * @param array $data Data yang akan divalidasi (biasanya $_POST)
     * 
     * Contoh: $validation = new Validation($_POST);
     */
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    // ========================================================================
    // METHOD VALIDASI
    // Semua method validasi mengembalikan $this untuk method chaining
    // ========================================================================
    
    /**
     * Validasi field wajib diisi (tidak boleh kosong)
     * 
     * @param string $field Nama field yang dicek
     * @param string $label Label untuk pesan error (opsional)
     * @return $this        Mengembalikan objek ini untuk chaining
     * 
     * Contoh: $validation->required('nama', 'Nama Lengkap');
     * Error: "Nama Lengkap wajib diisi"
     */
    public function required($field, $label = null) {
        $label = $label ?? $field; // Jika label tidak diisi, gunakan nama field
        
        // Cek apakah field ada dan tidak kosong setelah di-trim
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = "$label wajib diisi";
        }
        return $this; // Kembalikan $this untuk method chaining
    }
    
    /**
     * Validasi format email
     * 
     * @param string $field Nama field email
     * @param string $label Label untuk pesan error
     * @return $this
     * 
     * Menggunakan filter_var dengan FILTER_VALIDATE_EMAIL
     * Contoh valid: user@domain.com
     * Contoh tidak valid: user@domain, user.domain.com
     */
    public function email($field, $label = null) {
        $label = $label ?? $field;
        
        // Hanya validasi jika field ada dan tidak kosong
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            // FILTER_VALIDATE_EMAIL = filter bawaan PHP untuk validasi email
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = "$label tidak valid";
            }
        }
        return $this;
    }
    
    /**
     * Validasi panjang minimum string
     * 
     * @param string $field Nama field
     * @param int    $min   Jumlah karakter minimum
     * @param string $label Label untuk pesan error
     * @return $this
     * 
     * Contoh: $validation->minLength('password', 6, 'Password');
     * Error: "Password minimal 6 karakter"
     */
    public function minLength($field, $min, $label = null) {
        $label = $label ?? $field;
        
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "$label minimal $min karakter";
        }
        return $this;
    }
    
    /**
     * Validasi panjang maksimum string
     * 
     * @param string $field Nama field
     * @param int    $max   Jumlah karakter maksimum
     * @param string $label Label untuk pesan error
     * @return $this
     * 
     * Contoh: $validation->maxLength('username', 20, 'Username');
     * Error: "Username maksimal 20 karakter"
     */
    public function maxLength($field, $max, $label = null) {
        $label = $label ?? $field;
        
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "$label maksimal $max karakter";
        }
        return $this;
    }
    
    /**
     * Validasi field harus berupa angka
     * 
     * @param string $field Nama field
     * @param string $label Label untuk pesan error
     * @return $this
     * 
     * is_numeric() menerima: 123, "123", 12.5, "12.5"
     * Menolak: "abc", "12abc"
     */
    public function numeric($field, $label = null) {
        $label = $label ?? $field;
        
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!is_numeric($this->data[$field])) {
                $this->errors[$field] = "$label harus berupa angka";
            }
        }
        return $this;
    }
    
    /**
     * Validasi kecocokan dua field (biasanya untuk konfirmasi password)
     * 
     * @param string $field1 Nama field pertama
     * @param string $field2 Nama field kedua (yang akan dibandingkan)
     * @param string $label  Label untuk pesan error
     * @return $this
     * 
     * Contoh: $validation->match('password', 'confirm_password', 'Konfirmasi Password');
     * Error: "Konfirmasi Password tidak cocok"
     */
    public function match($field1, $field2, $label = null) {
        $label = $label ?? $field2;
        
        if (isset($this->data[$field1]) && isset($this->data[$field2])) {
            // !== artinya tidak sama (strict comparison)
            if ($this->data[$field1] !== $this->data[$field2]) {
                $this->errors[$field2] = "$label tidak cocok";
            }
        }
        return $this;
    }
    
    /**
     * Validasi kustom dengan callback function
     * 
     * @param string   $field    Nama field
     * @param callable $callback Fungsi yang mengembalikan true/false
     * @param string   $message  Pesan error jika validasi gagal
     * @return $this
     * 
     * Contoh:
     * $validation->custom('age', function($value) {
     *     return $value >= 17;
     * }, 'Umur minimal 17 tahun');
     */
    public function custom($field, $callback, $message) {
        if (isset($this->data[$field])) {
            // Panggil callback dan cek hasilnya
            if (!$callback($this->data[$field])) {
                $this->errors[$field] = $message;
            }
        }
        return $this;
    }
    
    // ========================================================================
    // METHOD UNTUK MENGECEK HASIL VALIDASI
    // ========================================================================
    
    /**
     * Mengecek apakah validasi berhasil (tidak ada error)
     * 
     * @return bool True jika tidak ada error
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Mengecek apakah validasi gagal (ada error)
     * 
     * @return bool True jika ada error
     * 
     * Contoh penggunaan:
     * if ($validation->fails()) {
     *     Session::setFlash('error', $validation->firstError());
     *     Redirect::back();
     * }
     */
    public function fails() {
        return !empty($this->errors);
    }
    
    /**
     * Mengambil semua pesan error
     * 
     * @return array Array asosiatif semua error
     */
    public function errors() {
        return $this->errors;
    }
    
    /**
     * Mengambil pesan error pertama
     * 
     * @return string|null Pesan error pertama atau null
     * 
     * reset() mengambil elemen pertama dari array
     */
    public function firstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Mengambil pesan error untuk field tertentu
     * 
     * @param string $field Nama field
     * @return string|null  Pesan error atau null
     */
    public function error($field) {
        return $this->errors[$field] ?? null;
    }
    
    // ========================================================================
    // SANITASI INPUT
    // ========================================================================
    
    /**
     * Membersihkan input dari karakter berbahaya (untuk mencegah XSS)
     * 
     * @param mixed $data Data yang akan dibersihkan (string atau array)
     * @return mixed      Data yang sudah dibersihkan
     * 
     * XSS (Cross-Site Scripting) adalah serangan dengan menyisipkan
     * script berbahaya ke dalam input. Contoh: <script>alert('hack')</script>
     * 
     * htmlspecialchars() mengubah:
     * < menjadi &lt;
     * > menjadi &gt;
     * " menjadi &quot;
     * ' menjadi &#039;
     * 
     * Static method = bisa dipanggil tanpa membuat objek
     * Contoh: $cleanData = Validation::sanitize($_POST);
     */
    public static function sanitize($data) {
        // Jika data adalah array, proses setiap elemennya secara rekursif
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        
        // Untuk string: trim (hapus spasi) dan convert karakter special HTML
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * ============================================================================
 * CONTOH PENGGUNAAN DI CONTROLLER:
 * ============================================================================
 * 
 * public function store() {
 *     // Sanitize semua input
 *     $data = Validation::sanitize($_POST);
 *     
 *     // Buat objek validasi
 *     $validation = new Validation($data);
 *     
 *     // Jalankan validasi dengan method chaining
 *     $validation->required('judul', 'Judul Buku')
 *                ->required('penulis', 'Penulis')
 *                ->required('stok', 'Stok')
 *                ->numeric('stok', 'Stok')
 *                ->email('email', 'Email');
 *     
 *     // Cek apakah validasi gagal
 *     if ($validation->fails()) {
 *         Session::setFlash('error', $validation->firstError());
 *         Redirect::to('buku/create');
 *         return;
 *     }
 *     
 *     // Jika validasi berhasil, lanjut simpan data
 *     $this->bukuModel->create($data);
 * }
 * 
 * ============================================================================
 */
