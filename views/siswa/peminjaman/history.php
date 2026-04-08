<?php
$title = 'Riwayat Peminjaman - Sistem Perpustakaan';
$pageTitle = 'Riwayat Peminjaman';
include BASE_PATH . '/views/layouts/header.php';
include BASE_PATH . '/views/layouts/siswa_sidebar.php';
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Buku</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Harus Kembali</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data['peminjaman'])): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-history text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada riwayat peminjaman</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['peminjaman'] as $p): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= htmlspecialchars($p['kode_peminjaman']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($p['detail'])): ?>
                                    <?php foreach ($p['detail'] as $d): ?>
                                        <p class="text-sm text-gray-800"><?= htmlspecialchars($d['judul']) ?></p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($p['tanggal_harus_kembali'])) ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                $statusClass = 'bg-gray-100 text-gray-700';
                                $statusLabel = ucfirst($p['status']);
                                if ($p['status'] === 'pending') {
                                    $statusClass = 'bg-blue-100 text-blue-700';
                                    $statusLabel = 'Menunggu Persetujuan';
                                } elseif ($p['status'] === 'dipinjam') {
                                    $statusClass = 'bg-amber-100 text-amber-700';
                                } elseif ($p['status'] === 'dikembalikan') {
                                    $statusClass = 'bg-green-100 text-green-700';
                                } elseif ($p['status'] === 'ditolak') {
                                    $statusClass = 'bg-red-100 text-red-700';
                                    $statusLabel = 'Ditolak';
                                }
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                                    <?= $statusLabel ?>
                                </span>
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
