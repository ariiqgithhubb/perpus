<?php
$title = 'Kelola Buku - Sistem Perpustakaan';
$pageTitle = 'Kelola Buku';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div class="fade-in-left">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Buku</h1>
        <p class="text-gray-500 text-sm">Kelola semua koleksi buku perpustakaan</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 fade-in-right">
        <form action="<?= BASE_URL ?>/admin/buku/search" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>" 
                       class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                       placeholder="Cari buku...">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all btn-press">Cari</button>
        </form>
        <a href="<?= BASE_URL ?>/admin/buku/create" class="px-4 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 flex items-center justify-center gap-2 transition-all btn-press hover-lift">
            <i class="fas fa-plus"></i>
            <span>Tambah Buku</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-animate">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Buku</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kategori</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Stok</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase hidden sm:table-cell">Status</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data['buku'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="fade-in">
                                <i class="fas fa-book text-5xl mb-4 text-gray-300"></i>
                                <p class="font-medium">Belum ada data buku</p>
                                <a href="<?= BASE_URL ?>/admin/buku/create" class="inline-block mt-3 text-primary-600 hover:underline">
                                    + Tambah buku pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['buku'] as $b): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <?php if (!empty($b['cover'])): ?>
                                        <img src="<?= BASE_URL ?>/<?= $b['cover'] ?>" alt="" class="w-10 h-14 sm:w-12 sm:h-16 object-cover rounded-lg shadow-sm">
                                    <?php else: ?>
                                        <div class="w-10 h-14 sm:w-12 sm:h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg flex items-center justify-center shadow-sm">
                                            <i class="fas fa-book text-primary-500"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate max-w-[150px] sm:max-w-none"><?= htmlspecialchars($b['judul']) ?></p>
                                        <p class="text-xs sm:text-sm text-gray-500 truncate max-w-[150px] sm:max-w-none"><?= htmlspecialchars($b['penulis']) ?></p>
                                        <span class="md:hidden inline-block mt-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                                            <?= htmlspecialchars($b['nama_kategori'] ?? 'Umum') ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-primary-100 text-primary-700">
                                    <?= htmlspecialchars($b['nama_kategori'] ?? 'Umum') ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <span class="font-bold <?= $b['stok_tersedia'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $b['stok_tersedia'] ?>
                                </span>
                                <span class="text-gray-400">/<?= $b['stok'] ?></span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center hidden sm:table-cell">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    <?= $b['status'] === 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= ucfirst($b['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    <a href="<?= BASE_URL ?>/admin/buku/show?id=<?= $b['id'] ?>" 
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/buku/edit?id=<?= $b['id'] ?>" 
                                       class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('Hapus buku <?= htmlspecialchars($b['judul']) ?>?', '<?= BASE_URL ?>/admin/buku/delete?id=<?= $b['id'] ?>')" 
                                            class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
