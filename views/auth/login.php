<?php
$title = 'Login - Sistem Perpustakaan';
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
        
        <!-- Floating Books Animation -->
        <div class="absolute top-20 left-10 text-white/10 text-6xl float" style="animation-delay: 0s"><i class="fas fa-book"></i></div>
        <div class="absolute top-40 right-20 text-white/10 text-4xl float" style="animation-delay: 0.5s"><i class="fas fa-book-open"></i></div>
        <div class="absolute bottom-20 left-1/4 text-white/10 text-5xl float" style="animation-delay: 1s"><i class="fas fa-graduation-cap"></i></div>
        <div class="absolute bottom-40 right-1/3 text-white/10 text-3xl float" style="animation-delay: 1.5s"><i class="fas fa-bookmark"></i></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8 fade-in-down">
            <div class="w-20 h-20 bg-white rounded-2xl mx-auto flex items-center justify-center shadow-2xl mb-4 hover-scale pulse-glow">
                <i class="fas fa-book-open text-primary-600 text-4xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">Perpustakaan</h1>
            <p class="text-primary-200">Sistem Informasi Perpustakaan Digital</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white/10 backdrop-blur-xl p-6 sm:p-8 rounded-3xl shadow-2xl border border-white/20 fade-in-up">
            <h2 class="text-xl font-semibold text-white text-center mb-6">Silakan Login</h2>
            
            <!-- Flash Message -->
            <?php $flash = Session::getFlash(); ?>
            <?php if ($flash): ?>
                <div class="mb-6 p-4 rounded-xl <?= $flash['type'] === 'success' ? 'bg-green-500/20 border border-green-500/50 text-green-100' : 'bg-red-500/20 border border-red-500/50 text-red-100' ?> flex items-center gap-3 animate-pulse">
                    <i class="fas <?= $flash['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                    <span class="text-sm font-medium"><?= $flash['message'] ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login" method="POST" class="space-y-5">
                <!-- Username Input -->
                <div class="group">
                    <label class="block text-primary-200 text-sm font-medium mb-2 pl-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-primary-300 group-focus-within:text-white transition-colors"></i>
                        </div>
                        <input type="text" name="username" required 
                               class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:bg-white/20 focus:border-white/50 transition-all duration-300"
                               placeholder="Masukkan username Anda">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="group">
                    <label class="block text-primary-200 text-sm font-medium mb-2 pl-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-primary-300 group-focus-within:text-white transition-colors"></i>
                        </div>
                        <input type="password" name="password" required 
                               class="w-full pl-11 pr-4 py-3 bg-white/10 border border-white/10 rounded-xl text-white placeholder-white/30 focus:outline-none focus:bg-white/20 focus:border-white/50 transition-all duration-300"
                               placeholder="Masukkan password Anda">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 mt-2">
                    <span>Masuk Aplikasi</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="mt-6 pt-6 border-t border-white/10 text-center">
                <p class="text-primary-200 text-sm">
                    Belum punya akun? 
                    <a href="<?= BASE_URL ?>/register" class="text-white font-semibold hover:underline">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
