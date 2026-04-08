<?php
$title = 'Tambah Buku - Sistem Perpustakaan';
$pageTitle = 'Tambah Buku';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-3xl">
    <!-- Back Button -->
    <a href="<?= BASE_URL ?>/admin/buku" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Buku</span>
    </a>
    
    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Tambah Buku Baru</h2>
            <p class="text-gray-500">Isi formulir berikut untuk menambah buku</p>
        </div>
        
        <form action="<?= BASE_URL ?>/admin/buku/store" method="POST" enctype="multipart/form-data" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kode Buku -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_buku" value="<?= $data['kode_buku'] ?? '' ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <!-- ISBN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                    <input type="text" name="isbn"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="978-xxx-xxx-xxx">
                </div>
            </div>
            
            <!-- Judul -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" name="judul" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Masukkan judul buku">
            </div>
            
            <!-- Penulis & Penerbit -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="penulis" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Nama penulis">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                    <input type="text" name="penerbit"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Nama penerbit">
                </div>
            </div>
            
            <!-- Tahun & Kategori -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" min="1900" max="<?= date('Y') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="<?= date('Y') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($data['kategori'] as $k): ?>
                            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Stok & Lokasi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" min="0" value="1" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Halaman</label>
                    <input type="number" name="jumlah_halaman" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Rak</label>
                    <input type="text" name="lokasi_rak"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="A-01">
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                          placeholder="Deskripsi singkat buku"></textarea>
            </div>
            
            <!-- Cover -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                <input type="file" name="cover" accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB</p>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Buku
                </button>
                <a href="<?= BASE_URL ?>/admin/buku" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
