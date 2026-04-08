<?php
$title = 'Detail Buku - Sistem Perpustakaan';
$pageTitle = 'Detail Buku';
$buku = $data['buku'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-4xl">
    <a href="<?= BASE_URL ?>/admin/buku" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6 transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Buku</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="md:flex">
            <!-- Cover -->
            <div class="md:w-1/3 p-6 bg-gray-50 flex items-center justify-center">
                <?php if ($buku['cover']): ?>
                    <img src="<?= BASE_URL ?>/<?= $buku['cover'] ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" 
                         class="w-48 h-64 object-cover rounded-lg shadow-lg">
                <?php else: ?>
                    <div class="w-48 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-gray-400 text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Details -->
            <div class="md:w-2/3 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 mb-2">
                            <?= htmlspecialchars($buku['nama_kategori'] ?? 'Umum') ?>
                        </span>
                        <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($buku['judul']) ?></h1>
                        <p class="text-gray-600"><?= htmlspecialchars($buku['penulis']) ?></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?= BASE_URL ?>/admin/buku/edit?id=<?= $buku['id'] ?>" 
                           class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/admin/buku/delete?id=<?= $buku['id'] ?>" 
                           onclick="return confirmDelete()"
                           class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Kode Buku</p>
                        <p class="font-mono font-medium"><?= htmlspecialchars($buku['kode_buku']) ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">ISBN</p>
                        <p class="font-medium"><?= htmlspecialchars($buku['isbn'] ?? '-') ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Penerbit</p>
                        <p class="font-medium"><?= htmlspecialchars($buku['penerbit'] ?? '-') ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Tahun Terbit</p>
                        <p class="font-medium"><?= $buku['tahun_terbit'] ?? '-' ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Jumlah Halaman</p>
                        <p class="font-medium"><?= $buku['jumlah_halaman'] ?? '-' ?> halaman</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Lokasi Rak</p>
                        <p class="font-medium"><?= htmlspecialchars($buku['lokasi_rak'] ?? '-') ?></p>
                    </div>
                </div>
                
                <!-- Stock Info -->
                <div class="flex gap-4 mb-6">
                    <div class="flex-1 p-4 bg-blue-50 rounded-lg text-center">
                        <p class="text-3xl font-bold text-blue-600"><?= $buku['stok'] ?></p>
                        <p class="text-sm text-blue-700">Total Stok</p>
                    </div>
                    <div class="flex-1 p-4 <?= $buku['stok_tersedia'] > 0 ? 'bg-green-50' : 'bg-red-50' ?> rounded-lg text-center">
                        <p class="text-3xl font-bold <?= $buku['stok_tersedia'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $buku['stok_tersedia'] ?>
                        </p>
                        <p class="text-sm <?= $buku['stok_tersedia'] > 0 ? 'text-green-700' : 'text-red-700' ?>">Tersedia</p>
                    </div>
                </div>
                
                <!-- Description -->
                <?php if ($buku['deskripsi']): ?>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>
                        <p class="text-gray-600"><?= nl2br(htmlspecialchars($buku['deskripsi'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
