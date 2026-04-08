<?php
$title = 'Proses Pengembalian - Sistem Perpustakaan';
$pageTitle = 'Proses Pengembalian';
$peminjaman = $data['peminjaman'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-3xl">
    <a href="<?= BASE_URL ?>/admin/transaksi" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Transaksi</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Proses Pengembalian Buku</h2>
            <p class="text-gray-500">Kode: <?= $peminjaman['kode_peminjaman'] ?></p>
        </div>
        
        <!-- Info Peminjaman -->
        <div class="p-4 bg-gray-50 rounded-lg mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Anggota</p>
                    <p class="font-medium"><?= htmlspecialchars($peminjaman['nama_anggota']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Pinjam</p>
                    <p class="font-medium"><?= date('d/m/Y', strtotime($peminjaman['tanggal_pinjam'])) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Harus Kembali</p>
                    <p class="font-medium"><?= date('d/m/Y', strtotime($peminjaman['tanggal_harus_kembali'])) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <?php if ($data['keterlambatan'] > 0): ?>
                        <p class="font-medium text-red-600">Terlambat <?= $data['keterlambatan'] ?> hari</p>
                    <?php else: ?>
                        <p class="font-medium text-green-600">Tepat Waktu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Buku -->
        <h3 class="font-semibold text-gray-800 mb-3">Buku yang Dikembalikan</h3>
        <div class="border border-gray-200 rounded-lg mb-6">
            <?php foreach ($data['detail'] as $d): ?>
                <div class="flex items-center justify-between p-4 border-b border-gray-100 last:border-0">
                    <div>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($d['judul']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($d['penulis']) ?></p>
                    </div>
                    <span class="text-sm text-gray-600"><?= $d['jumlah'] ?> buku</span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Form -->
        <form action="<?= BASE_URL ?>/admin/pengembalian/store" method="POST" class="space-y-5">
            <input type="hidden" name="peminjaman_id" value="<?= $peminjaman['id'] ?>">
            <input type="hidden" name="kode_pengembalian" value="<?= $data['kode_pengembalian'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pengembalian</label>
                    <input type="text" value="<?= $data['kode_pengembalian'] ?>" readonly
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_pengembalian" value="<?= date('Y-m-d') ?>" readonly
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Buku</label>
                <select name="kondisi_buku" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
            
            <!-- Denda -->
            <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">
                <h4 class="font-semibold text-amber-800 mb-2">Kalkulasi Denda</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Keterlambatan</p>
                        <p class="font-bold text-lg"><?= $data['keterlambatan'] ?> hari</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Denda Keterlambatan</p>
                        <p class="font-bold text-lg text-red-600">Rp <?= number_format($data['denda_keterlambatan'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <input type="hidden" name="keterlambatan" value="<?= $data['keterlambatan'] ?>">
                <input type="hidden" name="denda_keterlambatan" value="<?= $data['denda_keterlambatan'] ?>">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>Proses Pengembalian
                </button>
                <a href="<?= BASE_URL ?>/admin/transaksi" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
