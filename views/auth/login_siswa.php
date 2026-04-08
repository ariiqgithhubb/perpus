<?php
$title = 'Login Siswa - Sistem Perpustakaan';
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-900 flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Back Button -->
        <a href="<?= BASE_URL ?>/login" class="inline-flex items-center gap-2 text-emerald-200 hover:text-white mb-6 transition-colors fade-in">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        
        <!-- Logo -->
        <div class="text-center mb-8 fade-in-down">
            <div class="w-20 h-20 bg-white rounded-2xl mx-auto flex items-center justify-center shadow-2xl mb-4 hover-scale">
                <i class="fas fa-user-graduate text-emerald-600 text-4xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Login Siswa</h1>
            <p class="text-emerald-200">Masuk ke portal siswa</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white/10 backdrop-blur-xl p-6 sm:p-8 rounded-3xl shadow-2xl border border-white/20 fade-in-up">
            <form action="<?= BASE_URL ?>/login/siswa" method="POST" class="space-y-5">
                <div class="space-y-2">
                    <label class="block text-white text-sm font-medium">Username</label>
                    <div class="relative">
                        <input type="text" name="username" required 
                               class="w-full px-4 py-3 pl-12 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               placeholder="Masukkan username">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-white/50"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-white text-sm font-medium">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                               class="w-full px-4 py-3 pl-12 pr-12 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               placeholder="Masukkan password">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-white/50"></i>
                        <button type="button" onclick="togglePassword('password', 'toggleIcon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 btn-press">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-emerald-200 text-sm">
                    Belum punya akun?
                    <a href="<?= BASE_URL ?>/register" class="text-white font-semibold hover:underline">Daftar di sini</a>
                </p>
            </div>
            
            <!-- Demo Credentials -->
            <div class="mt-6 p-4 bg-white/5 rounded-xl border border-white/10">
                <p class="text-white/70 text-sm text-center mb-2">Demo Credentials:</p>
                <div class="flex justify-center gap-6 text-sm">
                    <div class="text-center">
                        <p class="text-white/50">Username</p>
                        <p class="text-white font-mono">ahmad</p>
                    </div>
                    <div class="text-center">
                        <p class="text-white/50">Password</p>
                        <p class="text-white font-mono">siswa123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include BASE_PATH . '/views/layouts/footer.php'; ?>
