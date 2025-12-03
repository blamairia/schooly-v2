<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px); background-size: 50px 50px;"></div>
        
        <!-- Floating Orbs -->
        <div class="absolute top-20 left-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
        <div class="absolute top-40 right-20 w-72 h-72 bg-cyan-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-40 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        
        <!-- Geometric Lines -->
        <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                </linearGradient>
            </defs>
            <line x1="0" y1="0" x2="100%" y2="100%" stroke="url(#grad1)" stroke-width="1"/>
            <line x1="100%" y1="0" x2="0" y2="100%" stroke="url(#grad1)" stroke-width="1"/>
            <circle cx="50%" cy="50%" r="30%" stroke="url(#grad1)" stroke-width="1" fill="none" opacity="0.3"/>
            <circle cx="50%" cy="50%" r="40%" stroke="url(#grad1)" stroke-width="1" fill="none" opacity="0.2"/>
        </svg>
    </div>

    <!-- Logo Section -->
    <div class="relative z-10 mb-6">
        {{ $logo }}
    </div>

    <!-- Login Card with Glassmorphism -->
    <div class="relative z-10 w-full sm:max-w-md px-8 py-8 bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl sm:rounded-2xl">
        <!-- Glow Effect -->
        <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-purple-500 rounded-2xl blur opacity-20"></div>
        
        <div class="relative">
            {{ $slot }}
        </div>
    </div>

    <!-- Footer -->
    <div class="relative z-10 mt-8 text-center">
        <p class="text-white/50 text-sm">
            School Payment Management System
        </p>
        <p class="text-white/30 text-xs mt-1">
            Annaba, Algeria • {{ date('Y') }}
        </p>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</div>
