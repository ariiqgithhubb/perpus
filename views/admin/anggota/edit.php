<?php
$title = 'Edit Anggota - Sistem Perpustakaan';
$pageTitle = 'Edit Anggota';
$anggota = $data['anggota'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-3xl">
    <a href="<?= BASE_URL ?>/admin/anggota" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Anggota</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Edit Anggota</h2>
            <p class="text-gray-500">Perbarui informasi anggota</p>
        </div>
        
        <form action="<?= BASE_URL ?>/admin/anggota/update?id=<?= $anggota['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id" value="<?= $anggota['id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="nis" value="<?= htmlspecialchars($anggota['nis']) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($anggota['nama']) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="L" <?= $anggota['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= $anggota['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="kelas" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="X" <?= $anggota['kelas'] === 'X' ? 'selected' : '' ?>>X</option>
                        <option value="XI" <?= $anggota['kelas'] === 'XI' ? 'selected' : '' ?>>XI</option>
                        <option value="XII" <?= $anggota['kelas'] === 'XII' ? 'selected' : '' ?>>XII</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="">Pilih</option>
                        <option value="RPL" <?= $anggota['jurusan'] === 'RPL' ? 'selected' : '' ?>>RPL</option>
                        <option value="TKJ" <?= $anggota['jurusan'] === 'TKJ' ? 'selected' : '' ?>>TKJ</option>
                        <option value="MM" <?= $anggota['jurusan'] === 'MM' ? 'selected' : '' ?>>MM</option>
                        <option value="AKL" <?= $anggota['jurusan'] === 'AKL' ? 'selected' : '' ?>>AKL</option>
                        <option value="OTKP" <?= $anggota['jurusan'] === 'OTKP' ? 'selected' : '' ?>>OTKP</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <input type="text" name="telepon" value="<?= htmlspecialchars($anggota['telepon'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($anggota['email'] ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg"><?= htmlspecialchars($anggota['alamat'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto</label>
                <?php if (!empty($anggota['foto'])): ?>
                    <div class="mb-3">
                        <img src="<?= BASE_URL ?>/<?= $anggota['foto'] ?>" alt="" class="w-20 h-20 object-cover rounded-full">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option value="aktif" <?= $anggota['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= $anggota['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="<?= BASE_URL ?>/admin/anggota" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
