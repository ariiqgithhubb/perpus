<?php
$title = 'Pengembalian Buku - Sistem Perpustakaan';
$pageTitle = 'Pengembalian Buku';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<div class="mb-6 fade-in">
    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Pengembalian Buku</h1>
    <p class="text-gray-500 text-sm">Kembalikan buku yang sedang dipinjam</p>
</div>

<?php if (empty($data['peminjaman'])): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center fade-in-up">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak ada buku yang perlu dikembalikan</h3>
        <p class="text-gray-500 mb-4">Semua buku Anda sudah dikembalikan</p>
        <a href="<?= BASE_URL ?>/siswa/peminjaman" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-all btn-press">
            <i class="fas fa-book"></i>
            Pinjam Buku Baru
        </a>
    </div>
<?php else: ?>
    <div class="grid gap-4 sm:gap-6">
        <?php foreach ($data['peminjaman'] as $index => $p): ?>
            <?php
            $harusKembali = new DateTime($p['tanggal_harus_kembali']);
            $today = new DateTime();
            $diff = $today->diff($harusKembali);
            $isLate = $today > $harusKembali;
            ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover" style="animation: fadeInUp 0.5s ease forwards; animation-delay: <?= $index * 0.1 ?>s; opacity: 0;">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 sm:p-6">
                    <!-- Book Info -->
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-16 h-20 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center shadow-sm shrink-0">
                            <i class="fas fa-book text-2xl text-emerald-500"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-mono text-xs text-gray-500 mb-1"><?= $p['kode_peminjaman'] ?></p>
                            <p class="font-bold text-gray-800">Peminjaman #<?= $p['id'] ?></p>
                            <p class="text-sm text-gray-500">Dipinjam: <?= date('d M Y', strtotime($p['tanggal_pinjam'])) ?></p>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="sm:text-right">
                        <?php if ($isLate): ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 font-medium rounded-xl text-sm animate-pulse">
                                <i class="fas fa-exclamation-triangle"></i>
                                Terlambat <?= $diff->days ?> hari
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Denda: Rp <?= number_format($diff->days * 1000, 0, ',', '.') ?></p>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-700 font-medium rounded-xl text-sm">
                                <i class="fas fa-clock"></i>
                                <?= $diff->days ?> hari lagi
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Kembali: <?= date('d M Y', strtotime($p['tanggal_harus_kembali'])) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Action -->
                    <div class="sm:pl-4 sm:border-l border-gray-200">
                        <a href="<?= BASE_URL ?>/siswa/pengembalian/process?id=<?= $p['id'] ?>" 
                           class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all btn-press hover-lift">
                            <i class="fas fa-undo"></i>
                            Kembalikan
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
