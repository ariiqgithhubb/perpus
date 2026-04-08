<?php
$user = $data['user'] ?? Session::get('user_data');
$currentUrl = $_GET['url'] ?? '';
?>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" onclick="closeSidebar()" class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden transition-opacity"></div>

<!-- Sidebar Siswa -->
<aside id="siswa-sidebar" class="fixed left-0 top-0 z-40 h-screen w-72 bg-gradient-to-b from-emerald-600 via-emerald-700 to-teal-800 shadow-2xl transition-all duration-300 ease-out -translate-x-full lg:translate-x-0">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg hover-scale float">
            <i class="fas fa-book-reader text-emerald-600 text-2xl"></i>
        </div>
        <div>
            <h1 class="text-white font-bold text-xl">Perpustakaan</h1>
            <p class="text-emerald-200 text-xs font-medium">Portal Siswa</p>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="px-4 py-6 space-y-1 overflow-y-auto max-h-[calc(100vh-200px)] hide-scrollbar">
        <p class="text-emerald-300 text-xs font-semibold uppercase tracking-wider px-4 mb-3">Menu Utama</p>
        
        <a href="<?= BASE_URL ?>/siswa/dashboard" 
           class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= strpos($currentUrl, 'siswa/dashboard') !== false || $currentUrl === 'siswa' ? 'bg-white/15 text-white shadow-lg' : 'text-emerald-100 hover:bg-white/10 hover:text-white hover:translate-x-1' ?>">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                <i class="fas fa-home"></i>
            </div>
            <span class="font-medium">Dashboard</span>
            <?php if (strpos($currentUrl, 'siswa/dashboard') !== false || $currentUrl === 'siswa'): ?>
                <div class="ml-auto w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
            <?php endif; ?>
        </a>
        
        <a href="<?= BASE_URL ?>/siswa/peminjaman" 
           class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= strpos($currentUrl, 'siswa/peminjaman') !== false ? 'bg-white/15 text-white shadow-lg' : 'text-emerald-100 hover:bg-white/10 hover:text-white hover:translate-x-1' ?>">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                <i class="fas fa-book"></i>
            </div>
            <span class="font-medium">Pinjam Buku</span>
            <?php if (strpos($currentUrl, 'siswa/peminjaman') !== false): ?>
                <div class="ml-auto w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
            <?php endif; ?>
        </a>
        
        <a href="<?= BASE_URL ?>/siswa/pengembalian" 
           class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= strpos($currentUrl, 'siswa/pengembalian') !== false ? 'bg-white/15 text-white shadow-lg' : 'text-emerald-100 hover:bg-white/10 hover:text-white hover:translate-x-1' ?>">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                <i class="fas fa-undo"></i>
            </div>
            <span class="font-medium">Pengembalian</span>
            <?php if (strpos($currentUrl, 'siswa/pengembalian') !== false): ?>
                <div class="ml-auto w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
            <?php endif; ?>
        </a>
        
        <p class="text-emerald-300 text-xs font-semibold uppercase tracking-wider px-4 mb-3 mt-6">Riwayat</p>
        
        <a href="<?= BASE_URL ?>/siswa/peminjaman/history" 
           class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= strpos($currentUrl, 'peminjaman/history') !== false ? 'bg-white/15 text-white shadow-lg' : 'text-emerald-100 hover:bg-white/10 hover:text-white hover:translate-x-1' ?>">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                <i class="fas fa-history"></i>
            </div>
            <span class="font-medium">Riwayat Pinjam</span>
        </a>
        
        <a href="<?= BASE_URL ?>/siswa/pengembalian/history" 
           class="nav-item flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= strpos($currentUrl, 'pengembalian/history') !== false ? 'bg-white/15 text-white shadow-lg' : 'text-emerald-100 hover:bg-white/10 hover:text-white hover:translate-x-1' ?>">
            <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <span class="font-medium">Riwayat Kembali</span>
        </a>
    </nav>
    
    <!-- User Info -->
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-black/20 backdrop-blur-sm border-t border-white/10">
        <div class="flex items-center gap-3 p-2">
            <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-user-graduate text-white text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-semibold text-sm truncate"><?= $user['nama'] ?? 'Siswa' ?></p>
                <p class="text-emerald-200 text-xs flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                    Siswa
                </p>
            </div>
            <button onclick="confirmLogout('<?= BASE_URL ?>/logout')" 
                    class="w-10 h-10 rounded-xl bg-white/10 text-emerald-200 hover:bg-red-500/20 hover:text-red-400 transition-all duration-300 flex items-center justify-center"
                    title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>
</aside>

<!-- Mobile Menu Button -->
<button onclick="toggleSidebar()" 
        id="mobileMenuBtn"
        class="lg:hidden fixed top-4 left-4 z-50 w-12 h-12 bg-emerald-600 text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-emerald-700 transition-all duration-300 btn-press">
    <i class="fas fa-bars text-lg" id="menuIcon"></i>
</button>

<!-- Main Content Wrapper -->
<div class="lg:ml-72 min-h-screen transition-all duration-300">
    <!-- Top Bar -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 sm:px-6 py-4">
            <div class="flex items-center gap-4 ml-14 lg:ml-0">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 fade-in-down"><?= $pageTitle ?? 'Dashboard' ?></h2>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-4">
                <span class="hidden sm:flex text-sm text-gray-500 items-center gap-2 bg-gray-100 px-3 py-2 rounded-lg">
                    <i class="fas fa-calendar-alt text-emerald-500"></i>
                    <?= date('d M Y') ?>
                </span>
                <div class="lg:hidden">
                    <button onclick="confirmLogout('<?= BASE_URL ?>/logout')" 
                            class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 hover:bg-red-100 hover:text-red-600 transition-all flex items-center justify-center">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <?php $flash = Session::getFlash(); ?>
    <?php if ($flash): ?>
        <div class="px-4 sm:px-6 py-4">
            <div class="alert-auto-hide fade-in-up p-4 rounded-xl shadow-sm <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : ($flash['type'] === 'error' ? 'bg-red-50 text-red-800 border border-red-200' : 'bg-amber-50 text-amber-800 border border-amber-200') ?>">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $flash['type'] === 'success' ? 'bg-green-100' : ($flash['type'] === 'error' ? 'bg-red-100' : 'bg-amber-100') ?>">
                        <i class="fas <?= $flash['type'] === 'success' ? 'fa-check-circle text-green-500' : ($flash['type'] === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-exclamation-triangle text-amber-500') ?>"></i>
                    </div>
                    <span class="font-medium"><?= $flash['message'] ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Page Content -->
    <main class="p-4 sm:p-6 fade-in-up">

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('siswa-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuIcon = document.getElementById('menuIcon');
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        menuIcon.classList.remove('fa-times');
        menuIcon.classList.add('fa-bars');
    } else {
        menuIcon.classList.remove('fa-bars');
        menuIcon.classList.add('fa-times');
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('siswa-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuIcon = document.getElementById('menuIcon');
    
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
    menuIcon.classList.remove('fa-times');
    menuIcon.classList.add('fa-bars');
}

// Close sidebar on window resize to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1024) {
        closeSidebar();
    }
});

// Animate nav items on load
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        setTimeout(() => {
            item.style.transition = 'all 0.4s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 100 + (index * 50));
    });
});
</script>
