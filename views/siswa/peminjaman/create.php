<?php
$title = 'Konfirmasi Peminjaman - Sistem Perpustakaan';
$pageTitle = 'Konfirmasi Peminjaman';
$buku = $data['buku'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<div class="max-w-2xl">
    <a href="<?= BASE_URL ?>/siswa/peminjaman" class="inline-flex items-center gap-2 text-gray-600 hover:text-emerald-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Katalog Buku</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6 bg-gray-50 flex items-center justify-center">
                <?php if ($buku['cover']): ?>
                    <img src="<?= BASE_URL ?>/<?= $buku['cover'] ?>" alt="" class="w-32 h-44 object-cover rounded-lg shadow-lg">
                <?php else: ?>
                    <div class="w-32 h-44 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-emerald-400 text-4xl"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="md:w-2/3 p-6">
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 mb-2">
                    <?= htmlspecialchars($buku['nama_kategori'] ?? 'Umum') ?>
                </span>
                <h1 class="text-xl font-bold text-gray-800 mb-1"><?= htmlspecialchars($buku['judul']) ?></h1>
                <p class="text-gray-600 mb-4"><?= htmlspecialchars($buku['penulis']) ?></p>
                
                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Penerbit</p>
                        <p class="font-medium"><?= htmlspecialchars($buku['penerbit'] ?? '-') ?></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Tahun</p>
                        <p class="font-medium"><?= $buku['tahun_terbit'] ?? '-' ?></p>
                    </div>
                </div>
                
                <form action="<?= BASE_URL ?>/siswa/peminjaman/store" method="POST">
                    <input type="hidden" name="buku_id" value="<?= $buku['id'] ?>">
                    
                    <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-200 mb-4">
                        <h4 class="font-semibold text-emerald-800 mb-2">Detail Peminjaman</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Kode Peminjaman</p>
                                <p class="font-mono font-medium"><?= $data['kode_peminjaman'] ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tanggal Pinjam</p>
                                <p class="font-medium"><?= date('d/m/Y', strtotime($data['tanggal_pinjam'])) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tanggal Kembali</p>
                                <p class="font-medium"><?= date('d/m/Y', strtotime($data['tanggal_harus_kembali'])) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-600">Sisa Slot Pinjam</p>
                                <p class="font-medium"><?= $data['sisa_slot'] ?> buku</p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                        <i class="fas fa-hand-holding mr-2"></i>
                        Pinjam Buku Ini
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
