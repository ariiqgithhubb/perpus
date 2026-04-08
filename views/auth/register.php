<?php
$title = 'Daftar Anggota - Sistem Perpustakaan';
include BASE_PATH . '/views/layouts/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-900 flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
    </div>

    <div class="w-full max-w-lg relative z-10 my-8">
        <!-- Back Button -->
        <a href="<?= BASE_URL ?>/login" class="inline-flex items-center gap-2 text-emerald-200 hover:text-white mb-6 transition-colors fade-in">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Login</span>
        </a>
        
        <!-- Logo -->
        <div class="text-center mb-8 fade-in-down">
            <div class="w-20 h-20 bg-white rounded-2xl mx-auto flex items-center justify-center shadow-2xl mb-4 hover-scale">
                <i class="fas fa-user-plus text-emerald-600 text-4xl"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Daftar Anggota</h1>
            <p class="text-emerald-200">Buat akun untuk menjadi anggota perpustakaan</p>
        </div>
        
        <!-- Registration Form -->
        <div class="bg-white/10 backdrop-blur-xl p-6 sm:p-8 rounded-3xl shadow-2xl border border-white/20 fade-in-up">
            <form action="<?= BASE_URL ?>/register" method="POST" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">NIS <span class="text-red-400">*</span></label>
                        <input type="text" name="nis" required 
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all"
                               placeholder="Nomor Induk Siswa">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="nama" required 
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all"
                               placeholder="Nama lengkap">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">Jenis Kelamin <span class="text-red-400">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
                            <option value="" class="text-gray-800">Pilih</option>
                            <option value="L" class="text-gray-800">Laki-laki</option>
                            <option value="P" class="text-gray-800">Perempuan</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">Kelas <span class="text-red-400">*</span></label>
                        <select name="kelas" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
                            <option value="" class="text-gray-800">Pilih</option>
                            <option value="X" class="text-gray-800">X</option>
                            <option value="XI" class="text-gray-800">XI</option>
                            <option value="XII" class="text-gray-800">XII</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">Jurusan</label>
                        <select name="jurusan" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all">
                            <option value="" class="text-gray-800">Pilih</option>
                            <option value="RPL" class="text-gray-800">RPL</option>
                            <option value="TKJ" class="text-gray-800">TKJ</option>
                            <option value="MM" class="text-gray-800">MM</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">Username <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="text" name="username" required 
                                   class="w-full px-4 py-3 pl-12 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all"
                                   placeholder="Username untuk login">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-white/50"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-white text-sm font-medium">Password <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required 
                                   class="w-full px-4 py-3 pl-12 pr-12 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all"
                                   placeholder="Password">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-white/50"></i>
                            <button type="button" onclick="togglePassword('password', 'toggleIcon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-white text-sm font-medium">Telepon</label>
                    <div class="relative">
                        <input type="text" name="telepon" 
                               class="w-full px-4 py-3 pl-12 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all"
                               placeholder="Nomor telepon">
                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-white/50"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-white text-sm font-medium">Alamat</label>
                    <textarea name="alamat" rows="2" 
                              class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-all resize-none"
                              placeholder="Alamat lengkap"></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 btn-press">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-emerald-200 text-sm">
                    Sudah punya akun?
                    <a href="<?= BASE_URL ?>/login" class="text-white font-semibold hover:underline">Login di sini</a>
                </p>
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
