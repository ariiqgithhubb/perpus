<?php
$title = 'Kelola Anggota - Sistem Perpustakaan';
$pageTitle = 'Kelola Anggota';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
    <div class="fade-in-left">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Anggota</h1>
        <p class="text-gray-500 text-sm">Kelola semua data anggota perpustakaan</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 fade-in-right">
        <form action="<?= BASE_URL ?>/admin/anggota/search" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>" 
                       class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                       placeholder="Cari anggota...">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all btn-press">Cari</button>
        </form>
        <a href="<?= BASE_URL ?>/admin/anggota/create" class="px-4 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 flex items-center justify-center gap-2 transition-all btn-press hover-lift">
            <i class="fas fa-plus"></i>
            <span>Tambah Anggota</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-animate">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">NIS</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kelas</th>
                    <th class="px-4 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase hidden lg:table-cell">Telepon</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-4 sm:px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data['anggota'])): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="fade-in">
                                <i class="fas fa-users text-5xl mb-4 text-gray-300"></i>
                                <p class="font-medium">Belum ada data anggota</p>
                                <a href="<?= BASE_URL ?>/admin/anggota/create" class="inline-block mt-3 text-primary-600 hover:underline">
                                    + Tambah anggota pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['anggota'] as $a): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($a['nis']) ?></span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($a['foto'])): ?>
                                        <img src="<?= BASE_URL ?>/<?= $a['foto'] ?>" alt="" class="w-10 h-10 object-cover rounded-full shadow-sm">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center shadow-sm">
                                            <i class="fas fa-user text-primary-500 text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate max-w-[120px] sm:max-w-none"><?= htmlspecialchars($a['nama']) ?></p>
                                        <p class="text-xs text-gray-500"><?= $a['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden md:table-cell">
                                <?= htmlspecialchars($a['kelas']) ?> <?= $a['jurusan'] ? '- ' . htmlspecialchars($a['jurusan']) : '' ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-gray-600 hidden lg:table-cell">
                                <?= htmlspecialchars($a['telepon'] ?? '-') ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    <?= $a['status'] === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= ucfirst($a['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    <a href="<?= BASE_URL ?>/admin/anggota/show?id=<?= $a['id'] ?>" 
                                       class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/anggota/edit?id=<?= $a['id'] ?>" 
                                       class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('Hapus anggota <?= htmlspecialchars($a['nama']) ?>?', '<?= BASE_URL ?>/admin/anggota/delete?id=<?= $a['id'] ?>')" 
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
