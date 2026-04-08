<?php
$title = 'Dashboard Admin - Sistem Perpustakaan';
$pageTitle = 'Dashboard';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-primary-600 via-primary-700 to-secondary-700 rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-white card-animate shadow-xl relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm float">
            <i class="fas fa-chart-line text-2xl sm:text-3xl"></i>
        </div>
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-bold">Selamat Datang, <?= htmlspecialchars($data['user']['nama_lengkap'] ?? 'Admin') ?>!</h1>
            <p class="text-primary-100 mt-1">Kelola perpustakaan Anda dengan mudah dan efisien</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-sm text-primary-200">Terakhir login</p>
            <p class="font-medium"><?= date('d M Y, H:i') ?></p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Buku</p>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-800"><?= number_format($data['total_buku'] ?? 0) ?></h3>
                <p class="text-xs text-green-500 mt-1 hidden sm:block">
                    <i class="fas fa-arrow-up mr-1"></i>+12%
                </p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-book text-blue-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group" style="animation-delay: 0.1s">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Anggota</p>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-800"><?= number_format($data['total_anggota'] ?? 0) ?></h3>
                <p class="text-xs text-green-500 mt-1 hidden sm:block">
                    <i class="fas fa-arrow-up mr-1"></i>+5%
                </p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-users text-emerald-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group" style="animation-delay: 0.2s">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Sedang Dipinjam</p>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-800"><?= number_format($data['total_pinjam'] ?? 0) ?></h3>
                <p class="text-xs text-amber-500 mt-1 hidden sm:block">
                    <i class="fas fa-clock mr-1"></i>Aktif
                </p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-hand-holding text-amber-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
    
    <a href="<?= BASE_URL ?>/admin/pending" class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group" style="animation-delay: 0.3s">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Pending Request</p>
                <h3 class="text-xl sm:text-3xl font-bold text-blue-600"><?= number_format($data['total_pending'] ?? 0) ?></h3>
                <p class="text-xs text-blue-500 mt-1 hidden sm:block">
                    <i class="fas fa-clock mr-1"></i>Menunggu Approval
                </p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform <?= ($data['total_pending'] ?? 0) > 0 ? 'animate-pulse' : '' ?>">
                <i class="fas fa-hourglass-half text-blue-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </a>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group" style="animation-delay: 0.4s">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Terlambat</p>
                <h3 class="text-xl sm:text-3xl font-bold text-red-600"><?= number_format($data['total_terlambat'] ?? 0) ?></h3>
                <p class="text-xs text-red-500 mt-1 hidden sm:block">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Perlu Tindakan
                </p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform <?= ($data['total_terlambat'] ?? 0) > 0 ? 'animate-pulse' : '' ?>">
                <i class="fas fa-exclamation-circle text-red-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <!-- Recent Activity -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-animate" style="animation-delay: 0.4s">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <h3 class="font-semibold text-gray-800">
                <i class="fas fa-clock mr-2 text-primary-500"></i>
                Peminjaman Terbaru
            </h3>
            <a href="<?= BASE_URL ?>/admin/transaksi" class="text-sm text-primary-600 hover:underline">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Anggota</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase hidden sm:table-cell">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($data['peminjaman_terbaru'])): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                <p>Belum ada data peminjaman</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach (array_slice($data['peminjaman_terbaru'], 0, 5) as $p): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4">
                                    <p class="font-medium text-gray-800 text-sm"><?= htmlspecialchars($p['nama_anggota']) ?></p>
                                    <p class="text-xs text-gray-500"><?= $p['kode_peminjaman'] ?></p>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden sm:table-cell">
                                    <?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-center">
                                    <?php
                                    $statusClass = 'bg-gray-100 text-gray-700';
                                    if ($p['status'] === 'dipinjam') $statusClass = 'bg-amber-100 text-amber-700';
                                    elseif ($p['status'] === 'dikembalikan') $statusClass = 'bg-green-100 text-green-700';
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                                        <?= ucfirst($p['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Popular Books -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-animate" style="animation-delay: 0.5s">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">
                <i class="fas fa-fire mr-2 text-orange-500"></i>
                Buku Populer
            </h3>
        </div>
        <div class="p-4 space-y-3">
            <?php if (empty($data['buku_populer'])): ?>
                <p class="text-gray-500 text-center py-4">Belum ada data</p>
            <?php else: ?>
                <?php foreach (array_slice($data['buku_populer'], 0, 5) as $index => $b): ?>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors group">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                            <?= $index + 1 ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm truncate"><?= htmlspecialchars($b['judul']) ?></p>
                            <p class="text-xs text-gray-500"><?= $b['total_pinjam'] ?? 0 ?> kali dipinjam</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
    <a href="<?= BASE_URL ?>/admin/buku/create" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all card-hover group text-center">
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-500 transition-colors">
            <i class="fas fa-plus text-blue-600 text-lg sm:text-xl group-hover:text-white transition-colors"></i>
        </div>
        <h4 class="font-semibold text-gray-800 text-sm">Tambah Buku</h4>
    </a>
    
    <a href="<?= BASE_URL ?>/admin/anggota/create" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all card-hover group text-center">
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-500 transition-colors">
            <i class="fas fa-user-plus text-emerald-600 text-lg sm:text-xl group-hover:text-white transition-colors"></i>
        </div>
        <h4 class="font-semibold text-gray-800 text-sm">Tambah Anggota</h4>
    </a>
    
    <a href="<?= BASE_URL ?>/admin/transaksi/create" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all card-hover group text-center">
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-500 transition-colors">
            <i class="fas fa-exchange-alt text-amber-600 text-lg sm:text-xl group-hover:text-white transition-colors"></i>
        </div>
        <h4 class="font-semibold text-gray-800 text-sm">Peminjaman</h4>
    </a>
    
    <a href="<?= BASE_URL ?>/admin/pengembalian" class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all card-hover group text-center">
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-500 transition-colors">
            <i class="fas fa-undo text-purple-600 text-lg sm:text-xl group-hover:text-white transition-colors"></i>
        </div>
        <h4 class="font-semibold text-gray-800 text-sm">Pengembalian</h4>
    </a>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
