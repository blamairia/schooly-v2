<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden transition-colors duration-300
    bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900
    dark:from-slate-900 dark:via-purple-900 dark:to-slate-900">
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 grid-pattern pointer-events-none"></div>
    
    <!-- Animated Background Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Orb 1 - Top Left -->
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        
        <!-- Orb 2 - Top Right -->
        <div class="absolute top-20 -right-20 w-96 h-96 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        
        <!-- Orb 3 - Bottom -->
        <div class="absolute -bottom-32 left-1/3 w-80 h-80 bg-gradient-to-br from-pink-500 to-orange-400 rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob animation-delay-4000"></div>
        
        <!-- Extra subtle orb -->
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-gradient-to-br from-emerald-400 to-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float"></div>
    </div>

    <!-- Geometric Lines SVG -->
    <svg class="absolute inset-0 w-full h-full opacity-[0.07] pointer-events-none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#06b6d4;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#8b5cf6;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
            </linearGradient>
        </defs>
        <line x1="0" y1="0" x2="100%" y2="100%" stroke="url(#lineGrad)" stroke-width="1"/>
        <line x1="100%" y1="0" x2="0" y2="100%" stroke="url(#lineGrad)" stroke-width="1"/>
        <circle cx="50%" cy="50%" r="25%" stroke="url(#lineGrad)" stroke-width="1" fill="none"/>
        <circle cx="50%" cy="50%" r="35%" stroke="url(#lineGrad)" stroke-width="0.5" fill="none"/>
        <circle cx="50%" cy="50%" r="45%" stroke="url(#lineGrad)" stroke-width="0.3" fill="none"/>
    </svg>

    <!-- Logo Section -->
    <div class="relative z-10 mb-8 animate-float" style="animation-duration: 4s;">
        {{ $logo }}
    </div>

    <!-- Login Card with Glassmorphism -->
    <div class="relative z-10 w-full sm:max-w-md mx-4">
        <!-- Glow Effect Behind Card -->
        <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500 via-purple-500 to-pink-500 rounded-3xl blur-lg opacity-30 animate-pulse-glow"></div>
        
        <!-- Main Card -->
        <div class="relative glass rounded-2xl shadow-2xl p-8 sm:p-10">
            {{ $slot }}
        </div>
    </div>

    <!-- Footer -->
    <div class="relative z-10 mt-10 text-center">
        <p class="text-white/60 text-sm font-medium tracking-wide">
            School Payment Management System
        </p>
        <p class="text-white/40 text-xs mt-2 flex items-center justify-center gap-2">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            Annaba, Algeria • {{ date('Y') }}
        </p>
    </div>
</div>
