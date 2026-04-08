<?php
$title = 'Pengembalian Buku - Sistem Perpustakaan';
$pageTitle = 'Kelola Pengembalian';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div class="fade-in-left">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Peminjaman Aktif</h1>
        <p class="text-gray-500 text-sm">Proses pengembalian buku yang masih dipinjam</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-animate card-hover">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Sedang Dipinjam</p>
                <p class="text-xl font-bold text-gray-800"><?= count($data['peminjaman'] ?? []) ?></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-animate card-hover" style="animation-delay: 0.1s">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Terlambat</p>
                <p class="text-xl font-bold text-red-600">
                    <?php 
                    $terlambat = 0;
                    $today = new DateTime();
                    foreach ($data['peminjaman'] ?? [] as $p) {
                        $harusKembali = new DateTime($p['tanggal_harus_kembali']);
                        if ($today > $harusKembali) $terlambat++;
                    }
                    echo $terlambat;
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 card-animate card-hover" style="animation-delay: 0.2s">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Tepat Waktu</p>
                <p class="text-xl font-bold text-green-600"><?= count($data['peminjaman'] ?? []) - $terlambat ?></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-animate" style="animation-delay: 0.2s">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Anggota</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Tgl Pinjam</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase hidden lg:table-cell">Harus Kembali</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data['peminjaman'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="fade-in">
                                <i class="fas fa-check-circle text-5xl mb-4 text-green-300"></i>
                                <p class="font-medium">Tidak ada peminjaman aktif</p>
                                <p class="text-sm text-gray-400">Semua buku sudah dikembalikan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['peminjaman'] as $p): ?>
                        <?php
                        $harusKembali = new DateTime($p['tanggal_harus_kembali']);
                        $today = new DateTime();
                        $diff = $today->diff($harusKembali);
                        $isLate = $today > $harusKembali;
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($p['kode_peminjaman']) ?></span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($p['nama_anggota']) ?></p>
                                <p class="text-xs text-gray-500"><?= $p['nis'] ?></p>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden md:table-cell">
                                <?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">
                                <?= date('d/m/Y', strtotime($p['tanggal_harus_kembali'])) ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <?php if ($isLate): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 animate-pulse">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span class="hidden sm:inline">Terlambat</span> <?= $diff->days ?> hari
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        <i class="fas fa-clock"></i>
                                        <span class="hidden sm:inline">Sisa</span> <?= $diff->days ?> hari
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= BASE_URL ?>/admin/transaksi/show?id=<?= $p['id'] ?>" 
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/pengembalian/process?id=<?= $p['id'] ?>" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all btn-press">
                                        <i class="fas fa-undo"></i>
                                        <span class="hidden sm:inline">Kembalikan</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
