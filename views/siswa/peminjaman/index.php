<?php
$title = 'Pinjam Buku - Sistem Perpustakaan';
$pageTitle = 'Katalog Buku';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div class="fade-in-left">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Katalog Buku</h1>
        <p class="text-gray-500 text-sm">Pilih dan pinjam buku yang tersedia</p>
    </div>
    <form action="<?= BASE_URL ?>/siswa/peminjaman/search" method="GET" class="flex gap-2 fade-in-right">
        <div class="relative flex-1">
            <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>" 
                   class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                   placeholder="Cari buku...">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
        <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all btn-press">Cari</button>
    </form>
</div>

<!-- Book Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
    <?php if (empty($data['buku'])): ?>
        <div class="col-span-full text-center py-12 fade-in">
            <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-medium">Tidak ada buku yang ditemukan</p>
        </div>
    <?php else: ?>
        <?php foreach ($data['buku'] as $index => $b): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover group" style="animation: fadeInUp 0.5s ease forwards; animation-delay: <?= $index * 0.1 ?>s; opacity: 0;">
                <!-- Book Cover -->
                <div class="relative h-40 sm:h-48 bg-gradient-to-br from-emerald-50 to-teal-50 overflow-hidden">
                    <?php if (!empty($b['cover'])): ?>
                        <img src="<?= BASE_URL ?>/<?= $b['cover'] ?>" alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-book text-5xl text-emerald-200"></i>
                        </div>
                    <?php endif; ?>
                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 px-2 py-1 text-xs font-medium bg-white/90 backdrop-blur-sm text-emerald-700 rounded-lg shadow-sm">
                        <?= htmlspecialchars($b['nama_kategori'] ?? 'Umum') ?>
                    </span>
                    <!-- Stock Badge -->
                    <span class="absolute top-3 right-3 px-2 py-1 text-xs font-bold <?= $b['stok_tersedia'] > 0 ? 'bg-green-500' : 'bg-red-500' ?> text-white rounded-lg shadow-sm">
                        Stok: <?= $b['stok_tersedia'] ?>
                    </span>
                </div>
                
                <!-- Book Info -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 truncate mb-1 group-hover:text-emerald-600 transition-colors"><?= htmlspecialchars($b['judul']) ?></h3>
                    <p class="text-sm text-gray-500 truncate mb-3"><?= htmlspecialchars($b['penulis']) ?></p>
                    
                    <?php if ($b['stok_tersedia'] > 0): ?>
                        <a href="<?= BASE_URL ?>/siswa/peminjaman/create?buku_id=<?= $b['id'] ?>" 
                           class="w-full block text-center px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all btn-press">
                            <i class="fas fa-hand-holding mr-2"></i>Pinjam
                        </a>
                    <?php else: ?>
                        <button disabled class="w-full px-4 py-2.5 bg-gray-200 text-gray-500 font-medium rounded-xl cursor-not-allowed">
                            <i class="fas fa-times mr-2"></i>Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
