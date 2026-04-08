<?php
/**
 * Kategori Model
 * Mengelola data kategori buku
 */

class Kategori {
    private $db;
    private $table = 'kategori_buku';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all kategori
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY nama_kategori ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Find kategori by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Create kategori
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (nama_kategori, kode_kategori, deskripsi)
            VALUES (?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['nama_kategori'],
            $data['kode_kategori'],
            $data['deskripsi'] ?? null
        ]);
    }
    
    /**
     * Update kategori
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET nama_kategori = ?, kode_kategori = ?, deskripsi = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['nama_kategori'],
            $data['kode_kategori'],
            $data['deskripsi'] ?? null,
            $id
        ]);
    }
    
    /**
     * Delete kategori
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get kategori with book count
     */
    public function getAllWithCount() {
        $stmt = $this->db->query("
            SELECT k.*, COUNT(b.id) as jumlah_buku
            FROM {$this->table} k
            LEFT JOIN buku b ON k.id = b.kategori_id
            GROUP BY k.id
            ORDER BY k.nama_kategori ASC
        ");
        return $stmt->fetchAll();
    }
}
