<?php
$title = 'Proses Pengembalian - Sistem Perpustakaan';
$pageTitle = 'Proses Pengembalian';
$peminjaman = $data['peminjaman'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<div class="max-w-2xl">
    <a href="<?= BASE_URL ?>/siswa/pengembalian" class="inline-flex items-center gap-2 text-gray-600 hover:text-emerald-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Konfirmasi Pengembalian</h2>
            <p class="text-gray-500">Kode: <?= $peminjaman['kode_peminjaman'] ?></p>
        </div>
        
        <div class="p-4 bg-gray-50 rounded-lg mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Tanggal Pinjam</p>
                    <p class="font-medium"><?= date('d/m/Y', strtotime($peminjaman['tanggal_pinjam'])) ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Harus Kembali</p>
                    <p class="font-medium"><?= date('d/m/Y', strtotime($peminjaman['tanggal_harus_kembali'])) ?></p>
                </div>
            </div>
        </div>
        
        <h3 class="font-semibold text-gray-800 mb-3">Buku yang Dikembalikan</h3>
        <div class="border border-gray-200 rounded-lg mb-6">
            <?php foreach ($data['detail'] as $d): ?>
                <div class="p-4 border-b last:border-0">
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($d['judul']) ?></p>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($d['penulis']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($data['keterlambatan'] > 0): ?>
            <div class="p-4 bg-red-50 rounded-lg border border-red-200 mb-6">
                <h4 class="font-semibold text-red-800 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Terlambat <?= $data['keterlambatan'] ?> Hari
                </h4>
                <p class="text-red-700">Denda: <span class="font-bold">Rp <?= number_format($data['denda_keterlambatan'], 0, ',', '.') ?></span></p>
            </div>
        <?php else: ?>
            <div class="p-4 bg-green-50 rounded-lg border border-green-200 mb-6">
                <p class="text-green-700">
                    <i class="fas fa-check-circle mr-2"></i>
                    Pengembalian tepat waktu. Tidak ada denda.
                </p>
            </div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>/siswa/pengembalian/store" method="POST">
            <input type="hidden" name="peminjaman_id" value="<?= $peminjaman['id'] ?>">
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Buku</label>
                <select name="kondisi_buku" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
            </div>
            
            <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                <i class="fas fa-check mr-2"></i>
                Konfirmasi Pengembalian
            </button>
        </form>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
