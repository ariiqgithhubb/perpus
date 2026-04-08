<?php
/**
 * Pengaturan Model
 * Mengelola pengaturan sistem perpustakaan
 */

class Pengaturan {
    private $db;
    private $table = 'pengaturan';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get pengaturan
     */
    public function get() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} LIMIT 1");
        return $stmt->fetch();
    }
    
    /**
     * Update pengaturan
     */
    public function update($data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $stmt = $this->db->prepare("UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = 1");
        return $stmt->execute($values);
    }
    
    /**
     * Get denda per hari
     */
    public function getDendaPerHari() {
        $pengaturan = $this->get();
        return $pengaturan ? $pengaturan['denda_per_hari'] : 1000;
    }
    
    /**
     * Get max hari pinjam
     */
    public function getMaxHariPinjam() {
        $pengaturan = $this->get();
        return $pengaturan ? $pengaturan['max_hari_pinjam'] : 7;
    }
    
    /**
     * Get max buku pinjam
     */
    public function getMaxBukuPinjam() {
        $pengaturan = $this->get();
        return $pengaturan ? $pengaturan['max_buku_pinjam'] : 3;
    }
}
