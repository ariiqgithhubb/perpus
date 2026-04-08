<?php
$title = 'Edit Transaksi - Sistem Perpustakaan';
$pageTitle = 'Edit Transaksi';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';

$peminjaman = $data['peminjaman'];
$detail = $data['detail'];
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6 fade-in">
        <a href="<?= BASE_URL ?>/admin/transaksi" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Daftar Transaksi</span>
        </a>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in-up">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-white">
            <h2 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <i class="fas fa-edit text-primary-500"></i>
                Edit Transaksi <?= htmlspecialchars($peminjaman['kode_peminjaman']) ?>
            </h2>
        </div>
        
        <form action="<?= BASE_URL ?>/admin/transaksi/update?id=<?= $peminjaman['id'] ?>" method="POST" class="p-6 space-y-6">
            <input type="hidden" name="id" value="<?= $peminjaman['id'] ?>">
            
            <!-- Info Peminjaman -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-xl">
                <div>
                    <p class="text-sm text-gray-500">Kode Peminjaman</p>
                    <p class="font-bold text-gray-800"><?= htmlspecialchars($peminjaman['kode_peminjaman']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Anggota</p>
                    <p class="font-bold text-gray-800"><?= htmlspecialchars($peminjaman['nama_anggota']) ?></p>
                    <p class="text-sm text-gray-500"><?= $peminjaman['nis'] ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                    <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($peminjaman['tanggal_pinjam'])) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Saat Ini</p>
                    <?php
                    $statusClass = 'bg-gray-100 text-gray-700';
                    if ($peminjaman['status'] === 'dipinjam') $statusClass = 'bg-amber-100 text-amber-700';
                    elseif ($peminjaman['status'] === 'dikembalikan') $statusClass = 'bg-green-100 text-green-700';
                    ?>
                    <span class="inline-flex px-2 py-1 text-sm font-medium rounded-full <?= $statusClass ?>">
                        <?= ucfirst($peminjaman['status']) ?>
                    </span>
                </div>
            </div>
            
            <!-- Buku yang Dipinjam -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buku yang Dipinjam</label>
                <div class="space-y-2">
                    <?php foreach ($detail as $d): ?>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-primary-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($d['judul']) ?></p>
                                <p class="text-sm text-gray-500"><?= $d['kode_buku'] ?> • <?= htmlspecialchars($d['penulis']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Tanggal Harus Kembali -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Harus Kembali <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_harus_kembali" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                       value="<?= $peminjaman['tanggal_harus_kembali'] ?>">
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    <option value="dipinjam" <?= $peminjaman['status'] === 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="dikembalikan" <?= $peminjaman['status'] === 'dikembalikan' ? 'selected' : '' ?>>Dikembalikan</option>
                </select>
            </div>
            
            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none"
                          placeholder="Catatan tambahan..."><?= htmlspecialchars($peminjaman['keterangan'] ?? '') ?></textarea>
            </div>
            
            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all btn-press hover-lift">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
                <a href="<?= BASE_URL ?>/admin/transaksi" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all text-center btn-press">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
