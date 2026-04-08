<?php $pageTitle = 'Pending Request'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Request - Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#f0fdf4","100":"#dcfce7","200":"#bbf7d0","300":"#86efac","400":"#4ade80","500":"#22c55e","600":"#16a34a","700":"#15803d","800":"#166534","900":"#14532d"},
                        secondary: {"50":"#fefce8","100":"#fef9c3","200":"#fef08a","300":"#fde047","400":"#facc15","500":"#eab308","600":"#ca8a04","700":"#a16207","800":"#854d0e","900":"#713f12"}
                    }
                }
            }
        }
    </script>
    <style>
        .btn-press { transition: transform 0.1s; }
        .btn-press:active { transform: scale(0.95); }
        .fade-in-up { animation: fadeInUp 0.5s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <?php include BASE_PATH . '/views/layouts/admin_sidebar.php'; ?>
    
    <!-- Page Content -->
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="fade-in-up">
                <h1 class="text-2xl font-bold text-gray-800">Pending Request</h1>
                <p class="text-gray-500 mt-1">Pengajuan peminjaman yang menunggu persetujuan</p>
            </div>
        </div>
        
        <!-- Pending List -->
        <?php if (empty($data['pending'])): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center fade-in-up">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak Ada Request Pending</h3>
                <p class="text-gray-500">Semua pengajuan peminjaman sudah diproses</p>
            </div>
        <?php else: ?>
            <div class="grid gap-4">
                <?php foreach ($data['pending'] as $index => $p): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden fade-in-up card-hover" style="animation-delay: <?= $index * 0.1 ?>s">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                <!-- Info Peminjam -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-800"><?= htmlspecialchars($p['nama_anggota']) ?></h3>
                                            <p class="text-sm text-gray-500">NIS: <?= htmlspecialchars($p['nis']) ?> • <?= htmlspecialchars($p['kelas'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <span class="text-gray-500 block">Kode</span>
                                            <span class="font-semibold text-gray-800"><?= htmlspecialchars($p['kode_peminjaman']) ?></span>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <span class="text-gray-500 block">Tanggal Request</span>
                                            <span class="font-semibold text-gray-800"><?= date('d M Y', strtotime($p['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Info Buku -->
                                <div class="flex-1 lg:border-l lg:border-gray-100 lg:pl-6">
                                    <h4 class="text-sm font-semibold text-gray-500 mb-2">Buku yang Dipinjam:</h4>
                                    <div class="space-y-2">
                                        <?php foreach ($p['detail'] as $d): ?>
                                            <div class="flex items-center gap-2 bg-primary-50 rounded-lg p-2">
                                                <i class="fas fa-book text-primary-600"></i>
                                                <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($d['judul']) ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex lg:flex-col gap-2 lg:min-w-[140px]">
                                    <a href="<?= BASE_URL ?>/admin/pending/approve?id=<?= $p['id'] ?>" 
                                       onclick="return confirm('Setujui peminjaman ini?')"
                                       class="flex-1 lg:w-full px-4 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all text-center btn-press shadow-lg shadow-primary-500/25">
                                        <i class="fas fa-check mr-2"></i>Setujui
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/pending/reject?id=<?= $p['id'] ?>&alasan=Stok tidak tersedia" 
                                       onclick="return confirm('Tolak peminjaman ini?')"
                                       class="flex-1 lg:w-full px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-red-100 hover:text-red-600 transition-all text-center btn-press">
                                        <i class="fas fa-times mr-2"></i>Tolak
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    </main>
</div>

<script>
// Auto-hide flash messages
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

function confirmLogout(url) {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        window.location.href = url;
    }
}
</script>
</body>
</html>
