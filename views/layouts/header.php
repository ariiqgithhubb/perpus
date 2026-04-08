<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $title ?? 'Sistem Perpustakaan' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    screens: {
                        'xs': '320px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px',
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* =========================================
           ANIMATION CLASSES 
           ========================================= */
        
        /* Fade Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .fade-in-down {
            animation: fadeInDown 0.6s ease-out forwards;
        }
        .fade-in-left {
            animation: fadeInLeft 0.6s ease-out forwards;
        }
        .fade-in-right {
            animation: fadeInRight 0.6s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Slide Animations */
        .slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }
        .slide-up {
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Scale Animations */
        .scale-in {
            animation: scaleIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .pop-in {
            animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }
        .bounce-in {
            animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        /* Floating Animation */
        .float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Pulse Glow */
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8), 0 0 30px rgba(59, 130, 246, 0.4); }
        }
        
        /* Shimmer Effect */
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Stagger Animation Delays */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }
        .delay-800 { animation-delay: 0.8s; }
        
        /* Initially hidden for animation */
        .animate-ready {
            opacity: 0;
        }
        
        /* Hover Animations */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .hover-scale {
            transition: all 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .hover-glow {
            transition: all 0.3s ease;
        }
        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        
        /* Card Hover Effect */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        
        /* Button Press Effect */
        .btn-press {
            transition: all 0.15s ease;
        }
        .btn-press:active {
            transform: scale(0.95);
        }
        
        /* Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .5s, opacity 1s;
        }
        .ripple:active::after {
            transform: scale(0, 0);
            opacity: .3;
            transition: 0s;
        }
        
        /* Gradient Text Animation */
        .gradient-text {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientMove 4s linear infinite;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            100% { background-position: 300% 50%; }
        }
        
        /* Modal Animations */
        .modal-overlay {
            animation: fadeIn 0.3s ease-out forwards;
        }
        .modal-content {
            animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes modalSlideIn {
            from { 
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to { 
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Table Row Animation */
        .table-row-animate {
            animation: tableRowIn 0.4s ease-out forwards;
            opacity: 0;
        }
        @keyframes tableRowIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Loading Spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* =========================================
           RESPONSIVE UTILITIES
           ========================================= */
        
        /* Mobile-first responsive table */
        @media (max-width: 768px) {
            .responsive-table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .responsive-table table {
                min-width: 600px;
            }
            
            /* Card-style table for mobile */
            .table-cards tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                padding: 1rem;
            }
            .table-cards thead {
                display: none;
            }
            .table-cards tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border-bottom: 1px solid #f3f4f6;
            }
            .table-cards tbody td:last-child {
                border-bottom: none;
            }
            .table-cards tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6b7280;
            }
        }
        
        /* Touch-friendly buttons on mobile */
        @media (max-width: 640px) {
            .touch-target {
                min-height: 44px;
                min-width: 44px;
            }
        }
        
        /* Hide scrollbar on mobile but keep functionality */
        @media (max-width: 768px) {
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        }
        
        /* Safe area for notched phones */
        @supports(padding: max(0px)) {
            .safe-area-inset {
                padding-left: max(1rem, env(safe-area-inset-left));
                padding-right: max(1rem, env(safe-area-inset-right));
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
        }
    </style>
</head>
<body class="bg-gray-50 font-inter antialiased">

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[9999] hidden">
    <div class="modal-overlay fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100" id="modalIconContainer">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-500" id="modalIcon"></i>
                </div>
                <h3 class="text-xl font-bold text-center text-gray-800 mb-2" id="modalTitle">Konfirmasi</h3>
                <p class="text-center text-gray-600 mb-6" id="modalMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
                <div class="flex gap-3">
                    <button onclick="closeConfirmModal()" 
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all btn-press">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button id="confirmBtn" 
                            class="flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all btn-press">
                        <i class="fas fa-check mr-2"></i>Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastContainer" class="fixed top-4 right-4 z-[9998] space-y-2"></div>

<script>
// Confirmation Modal Functions
let confirmCallback = null;

function showConfirmModal(options = {}) {
    const modal = document.getElementById('confirmModal');
    const title = document.getElementById('modalTitle');
    const message = document.getElementById('modalMessage');
    const icon = document.getElementById('modalIcon');
    const iconContainer = document.getElementById('modalIconContainer');
    const confirmBtn = document.getElementById('confirmBtn');
    
    title.textContent = options.title || 'Konfirmasi';
    message.textContent = options.message || 'Apakah Anda yakin?';
    
    // Set icon and colors based on type
    if (options.type === 'danger' || options.type === 'delete') {
        iconContainer.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100';
        icon.className = 'fas fa-trash-alt text-2xl text-red-500';
        confirmBtn.className = 'flex-1 px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-all btn-press';
    } else if (options.type === 'logout') {
        iconContainer.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100';
        icon.className = 'fas fa-sign-out-alt text-2xl text-amber-500';
        confirmBtn.className = 'flex-1 px-4 py-3 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition-all btn-press';
    } else if (options.type === 'warning') {
        iconContainer.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-amber-100';
        icon.className = 'fas fa-exclamation-triangle text-2xl text-amber-500';
        confirmBtn.className = 'flex-1 px-4 py-3 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition-all btn-press';
    } else {
        iconContainer.className = 'flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100';
        icon.className = 'fas fa-question-circle text-2xl text-blue-500';
        confirmBtn.className = 'flex-1 px-4 py-3 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-600 transition-all btn-press';
    }
    
    confirmCallback = options.onConfirm || null;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    confirmCallback = null;
}

document.getElementById('confirmBtn').addEventListener('click', function() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirmModal();
});

// Delete Confirmation
function confirmDelete(message, url) {
    showConfirmModal({
        type: 'delete',
        title: 'Hapus Data',
        message: message || 'Data yang dihapus tidak dapat dikembalikan. Lanjutkan?',
        onConfirm: function() {
            if (url) {
                window.location.href = url;
            }
        }
    });
    return false;
}

// Logout Confirmation
function confirmLogout(url) {
    showConfirmModal({
        type: 'logout',
        title: 'Keluar dari Sistem',
        message: 'Anda akan keluar dari sistem. Lanjutkan?',
        onConfirm: function() {
            window.location.href = url;
        }
    });
    return false;
}

// Toast Notifications
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    
    let bgColor = 'bg-blue-500';
    let icon = 'fa-info-circle';
    
    if (type === 'success') {
        bgColor = 'bg-green-500';
        icon = 'fa-check-circle';
    } else if (type === 'error') {
        bgColor = 'bg-red-500';
        icon = 'fa-exclamation-circle';
    } else if (type === 'warning') {
        bgColor = 'bg-amber-500';
        icon = 'fa-exclamation-triangle';
    }
    
    toast.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 animate__animated animate__fadeInRight`;
    toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('animate__fadeInRight');
        toast.classList.add('animate__fadeOutRight');
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

// Animate elements on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                entry.target.classList.remove('animate-ready');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    elements.forEach(el => observer.observe(el));
}

// Initialize animations on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate page elements
    animateOnScroll();
    
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.stagger-animate');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in-up');
    });
    
    // Add table row animations
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
        row.classList.add('table-row-animate');
    });
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});
</script>
