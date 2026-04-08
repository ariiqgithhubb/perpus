<?php
$title = 'Edit Buku - Sistem Perpustakaan';
$pageTitle = 'Edit Buku';
$buku = $data['buku'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-3xl">
    <a href="<?= BASE_URL ?>/admin/buku" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Buku</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Edit Buku</h2>
            <p class="text-gray-500">Perbarui informasi buku</p>
        </div>
        
        <form action="<?= BASE_URL ?>/admin/buku/update?id=<?= $buku['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id" value="<?= $buku['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_buku" value="<?= htmlspecialchars($buku['kode_buku']) ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                    <input type="text" name="isbn" value="<?= htmlspecialchars($buku['isbn'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="penulis" value="<?= htmlspecialchars($buku['penulis']) ?>" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                    <input type="text" name="penerbit" value="<?= htmlspecialchars($buku['penerbit'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?? '' ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($data['kategori'] as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $buku['kategori_id'] == $k['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="<?= $buku['stok'] ?>" min="0" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Halaman</label>
                    <input type="number" name="jumlah_halaman" value="<?= $buku['jumlah_halaman'] ?? '' ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Rak</label>
                    <input type="text" name="lokasi_rak" value="<?= htmlspecialchars($buku['lokasi_rak'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"><?= htmlspecialchars($buku['deskripsi'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                <?php if ($buku['cover']): ?>
                    <div class="mb-3">
                        <img src="<?= BASE_URL ?>/<?= $buku['cover'] ?>" alt="" class="w-24 h-32 object-cover rounded shadow">
                    </div>
                <?php endif; ?>
                <input type="file" name="cover" accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah cover</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
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
