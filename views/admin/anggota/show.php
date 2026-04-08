<?php
$title = 'Detail Anggota - Sistem Perpustakaan';
$pageTitle = 'Detail Anggota';
$anggota = $data['anggota'];
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/admin_sidebar.php';
?>

<div class="max-w-4xl">
    <a href="<?= BASE_URL ?>/admin/anggota" class="inline-flex items-center gap-2 text-gray-600 hover:text-primary-600 mb-6">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Anggota</span>
    </a>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6 bg-gray-50 flex flex-col items-center justify-center">
                <?php if (!empty($anggota['foto'])): ?>
                    <img src="<?= BASE_URL ?>/<?= $anggota['foto'] ?>" alt="" class="w-32 h-32 object-cover rounded-full shadow-lg mb-4">
                <?php else: ?>
                    <div class="w-32 h-32 bg-primary-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user text-primary-600 text-5xl"></i>
                    </div>
                <?php endif; ?>
                <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($anggota['nama']) ?></h2>
                <p class="text-gray-500"><?= htmlspecialchars($anggota['nis']) ?></p>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full mt-2 
                    <?= $anggota['status'] === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= ucfirst($anggota['status']) ?>
                </span>
            </div>
            
            <div class="md:w-2/3 p-6">
                <div class="flex justify-end mb-4 gap-2">
                    <a href="<?= BASE_URL ?>/admin/anggota/edit?id=<?= $anggota['id'] ?>" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Jenis Kelamin</p>
                        <p class="font-medium"><?= $anggota['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Kelas</p>
                        <p class="font-medium"><?= htmlspecialchars($anggota['kelas']) ?> <?= $anggota['jurusan'] ? '- ' . htmlspecialchars($anggota['jurusan']) : '' ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Telepon</p>
                        <p class="font-medium"><?= htmlspecialchars($anggota['telepon'] ?? '-') ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="font-medium"><?= htmlspecialchars($anggota['email'] ?? '-') ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Alamat</p>
                        <p class="font-medium"><?= htmlspecialchars($anggota['alamat'] ?? '-') ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Daftar</p>
                        <p class="font-medium"><?= date('d F Y', strtotime($anggota['tanggal_daftar'])) ?></p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Username</p>
                        <p class="font-medium"><?= htmlspecialchars($anggota['username'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </main>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
