<?php
$title = 'Detail Peminjaman - Sistem Perpustakaan';
$pageTitle = 'Detail Peminjaman';
$peminjaman = $data['peminjaman'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-4xl">
    <a href="<?= BASE_URL ?>/admin/transaksi" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Transaksi</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Peminjaman</h2>
                <p class="text-gray-500 font-mono"><?= $peminjaman['kode_peminjaman'] ?></p>
            </div>
            <?php
            $statusClass = 'bg-gray-100 text-gray-700';
            if ($peminjaman['status'] === 'dipinjam') $statusClass = 'bg-amber-100 text-amber-700';
            elseif ($peminjaman['status'] === 'dikembalikan') $statusClass = 'bg-green-100 text-green-700';
            elseif ($peminjaman['status'] === 'terlambat') $statusClass = 'bg-red-100 text-red-700';
            ?>
            <span class="px-4 py-2 text-sm font-medium rounded-full <?= $statusClass ?>">
                <?= ucfirst($peminjaman['status']) ?>
            </span>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Anggota</p>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($peminjaman['nama_anggota']) ?></p>
                    <p class="text-sm text-gray-500"><?= $peminjaman['nis'] ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Petugas</p>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($peminjaman['nama_petugas'] ?? 'Self-service') ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Pinjam</p>
                    <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($peminjaman['tanggal_pinjam'])) ?></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Harus Kembali</p>
                    <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($peminjaman['tanggal_harus_kembali'])) ?></p>
                </div>
            </div>
            
            <h3 class="font-semibold text-gray-800 mb-3">Buku yang Dipinjam</h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Buku</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($data['detail'] as $d): ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($d['judul']) ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($d['penulis']) ?></p>
                                </td>
                                <td class="px-4 py-3 text-center"><?= $d['jumlah'] ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full bg-gray-100"><?= ucfirst($d['kondisi_pinjam']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($peminjaman['status'] === 'dipinjam'): ?>
                <a href="<?= BASE_URL ?>/admin/pengembalian/process?id=<?= $peminjaman['id'] ?>" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                    <i class="fas fa-undo"></i>
                    Proses Pengembalian
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
