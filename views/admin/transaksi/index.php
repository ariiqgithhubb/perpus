<?php
$title = 'Transaksi Peminjaman - Sistem Perpustakaan';
$pageTitle = 'Transaksi Peminjaman';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div class="fade-in-left">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Peminjaman</h1>
        <p class="text-gray-500 text-sm">Kelola semua transaksi peminjaman buku</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/transaksi/create" class="fade-in-right px-4 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 flex items-center justify-center gap-2 transition-all btn-press hover-lift w-full sm:w-auto">
        <i class="fas fa-plus"></i>
        <span>Buat Peminjaman</span>
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-animate">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Anggota</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Tanggal Pinjam</th>
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
                                <i class="fas fa-exchange-alt text-5xl mb-4 text-gray-300"></i>
                                <p class="font-medium">Belum ada transaksi peminjaman</p>
                                <a href="<?= BASE_URL ?>/admin/transaksi/create" class="inline-block mt-3 text-primary-600 hover:underline">
                                    + Buat peminjaman pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['peminjaman'] as $p): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($p['kode_peminjaman']) ?></span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($p['nama_anggota']) ?></p>
                                <p class="text-xs text-gray-500"><?= $p['nis'] ?></p>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden md:table-cell"><?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell"><?= date('d/m/Y', strtotime($p['tanggal_harus_kembali'])) ?></td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <?php
                                $statusClass = 'bg-gray-100 text-gray-700';
                                if ($p['status'] === 'dipinjam') {
                                    $statusClass = 'bg-amber-100 text-amber-700';
                                } elseif ($p['status'] === 'dikembalikan') {
                                    $statusClass = 'bg-green-100 text-green-700';
                                } elseif ($p['status'] === 'terlambat') {
                                    $statusClass = 'bg-red-100 text-red-700 animate-pulse';
                                }
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    <a href="<?= BASE_URL ?>/admin/transaksi/show?id=<?= $p['id'] ?>" 
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($p['status'] === 'dipinjam'): ?>
                                        <a href="<?= BASE_URL ?>/admin/pengembalian/process?id=<?= $p['id'] ?>" 
                                           class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Proses Pengembalian">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    <?php endif; ?>
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
