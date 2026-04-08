<?php
$title = 'Buat Peminjaman - Sistem Perpustakaan';
$pageTitle = 'Buat Peminjaman';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-4xl">
    <a href="<?= BASE_URL ?>/admin/transaksi" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Transaksi</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Buat Peminjaman Baru</h2>
            <p class="text-gray-500">Isi formulir berikut untuk membuat transaksi peminjaman</p>
        </div>
        
        <form action="<?= BASE_URL ?>/admin/transaksi/store" method="POST" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Peminjaman</label>
                    <input type="text" name="kode_peminjaman" value="<?= $data['kode_peminjaman'] ?? '' ?>" readonly
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Anggota <span class="text-red-500">*</span></label>
                    <select name="anggota_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">Pilih Anggota</option>
                        <?php foreach ($data['anggota'] as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nis'] . ' - ' . $a['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" readonly
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Harus Kembali</label>
                    <input type="date" name="tanggal_harus_kembali" value="<?= $data['tanggal_harus_kembali'] ?? date('Y-m-d', strtotime('+7 days')) ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Buku <span class="text-red-500">*</span></label>
                <div class="border border-gray-300 rounded-lg max-h-64 overflow-y-auto">
                    <?php if (empty($data['buku'])): ?>
                        <p class="p-4 text-gray-500 text-center">Tidak ada buku tersedia</p>
                    <?php else: ?>
                        <?php foreach ($data['buku'] as $b): ?>
                            <label class="flex items-center gap-4 p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="buku[]" value="<?= $b['id'] ?>" class="w-5 h-5 text-primary-600 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($b['judul']) ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($b['penulis']) ?> • Tersedia: <?= $b['stok_tersedia'] ?></p>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700">
                    <i class="fas fa-save mr-2"></i>Simpan Peminjaman
                </button>
                <a href="<?= BASE_URL ?>/admin/transaksi" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
