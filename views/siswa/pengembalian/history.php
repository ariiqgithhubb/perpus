<?php
$title = 'Riwayat Pengembalian - Sistem Perpustakaan';
$pageTitle = 'Riwayat Pengembalian';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Kembali</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kondisi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Keterlambatan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data['pengembalian'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-clipboard-check text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada riwayat pengembalian</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['pengembalian'] as $p): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($p['kode_pengembalian']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($p['tanggal_pengembalian'])) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    <?= $p['kondisi_buku'] === 'baik' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>">
                                    <?= ucfirst(str_replace('_', ' ', $p['kondisi_buku'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?= $p['keterlambatan'] > 0 ? $p['keterlambatan'] . ' hari' : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium <?= $p['total_denda'] > 0 ? 'text-red-600' : 'text-gray-600' ?>">
                                <?= $p['total_denda'] > 0 ? 'Rp ' . number_format($p['total_denda'], 0, ',', '.') : '-' ?>
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
