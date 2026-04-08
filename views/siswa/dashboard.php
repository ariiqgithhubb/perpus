<?php
$title = 'Dashboard Siswa - Sistem Perpustakaan';
$pageTitle = 'Dashboard';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 text-white card-animate shadow-xl relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm float">
            <i class="fas fa-book-reader text-2xl sm:text-3xl"></i>
        </div>
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-bold">Selamat Datang, <?= htmlspecialchars($data['user']['nama'] ?? 'Siswa') ?>!</h1>
            <p class="text-emerald-100 mt-1">Jelajahi koleksi buku dan nikmati membaca</p>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Buku Tersedia</p>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-800"><?= number_format($data['total_buku'] ?? 0) ?></h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-book text-blue-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group" style="animation-delay: 0.1s">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Sedang Dipinjam</p>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-800"><?= count($data['peminjaman_aktif'] ?? []) ?></h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-hand-holding text-amber-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 card-animate card-hover group col-span-2 lg:col-span-1" style="animation-delay: 0.2s">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Peminjaman</p>
                <h3 class="text-xl sm:text-3xl font-bold text-gray-800"><?= $data['anggota']['total_pinjam'] ?? 0 ?></h3>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Peminjaman Aktif -->
<?php if (!empty($data['peminjaman_aktif'])): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8 card-animate" style="animation-delay: 0.3s">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <h3 class="font-semibold text-gray-800">
            <i class="fas fa-clock mr-2 text-amber-500"></i>
            Buku yang Sedang Dipinjam
        </h3>
        <a href="<?= BASE_URL ?>/siswa/pengembalian" class="text-sm text-emerald-600 hover:underline">
            Kembalikan Buku <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="divide-y divide-gray-100">
        <?php foreach ($data['peminjaman_aktif'] as $p): ?>
            <div class="px-4 sm:px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <p class="font-medium text-gray-800"><?= $p['kode_peminjaman'] ?></p>
                        <p class="text-sm text-gray-500">Dipinjam: <?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></p>
                    </div>
                    <div class="text-left sm:text-right">
                        <?php
                        $harusKembali = new DateTime($p['tanggal_harus_kembali']);
                        $today = new DateTime();
                        $diff = $today->diff($harusKembali);
                        $isLate = $today > $harusKembali;
                        ?>
                        <?php if ($isLate): ?>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700 animate-pulse">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Terlambat <?= $diff->days ?> hari
                            </span>
                        <?php else: ?>
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                <i class="fas fa-clock mr-1"></i>
                                <?= $diff->days ?> hari lagi
                            </span>
                        <?php endif; ?>
                        <p class="text-xs text-gray-400 mt-1">Kembali: <?= date('d/m/Y', strtotime($p['tanggal_harus_kembali'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
    <a href="<?= BASE_URL ?>/siswa/peminjaman" class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-lg transition-all card-hover">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 transition-colors">
                <i class="fas fa-book text-2xl text-emerald-600 group-hover:text-white transition-colors"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800 text-lg">Pinjam Buku</h3>
                <p class="text-sm text-gray-500">Cari dan pinjam buku yang tersedia</p>
            </div>
            <i class="fas fa-chevron-right text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
        </div>
    </a>
    
    <a href="<?= BASE_URL ?>/siswa/peminjaman/history" class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 hover:shadow-lg transition-all card-hover">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                <i class="fas fa-history text-2xl text-blue-600 group-hover:text-white transition-colors"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800 text-lg">Riwayat Peminjaman</h3>
                <p class="text-sm text-gray-500">Lihat riwayat peminjaman Anda</p>
            </div>
            <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
        </div>
    </a>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
