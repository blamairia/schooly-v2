<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden"
    style="background: linear-gradient(135deg, #0f172a 0%, #581c87 50%, #0f172a 100%);">
    
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px); background-size: 60px 60px;"></div>
    
    <!-- Animated Background Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Orb 1 - Top Left - Cyan -->
        <div class="absolute -top-20 -left-20 w-80 h-80 rounded-full animate-blob" 
            style="background: linear-gradient(135deg, #06b6d4, #3b82f6); filter: blur(60px); opacity: 0.3;"></div>
        
        <!-- Orb 2 - Top Right - Purple -->
        <div class="absolute top-20 -right-20 w-96 h-96 rounded-full animate-blob animation-delay-2000" 
            style="background: linear-gradient(135deg, #8b5cf6, #ec4899); filter: blur(60px); opacity: 0.25;"></div>
        
        <!-- Orb 3 - Bottom - Pink/Orange -->
        <div class="absolute -bottom-32 left-1/3 w-80 h-80 rounded-full animate-blob animation-delay-4000" 
            style="background: linear-gradient(135deg, #ec4899, #f97316); filter: blur(60px); opacity: 0.2;"></div>
        
        <!-- Extra subtle orb - Green -->
        <div class="absolute top-1/2 left-1/4 w-64 h-64 rounded-full animate-float" 
            style="background: linear-gradient(135deg, #10b981, #06b6d4); filter: blur(60px); opacity: 0.15;"></div>
    </div>

    <!-- Geometric Lines SVG -->
    <svg class="absolute inset-0 w-full h-full pointer-events-none" style="opacity: 0.08;" xmlns="http://www.w3.org/2000/svg">
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
        <div class="absolute -inset-1 rounded-3xl blur-lg animate-pulse-glow" 
            style="background: linear-gradient(90deg, #06b6d4, #8b5cf6, #ec4899); opacity: 0.4;"></div>
        
        <!-- Main Card -->
        <div class="relative rounded-2xl shadow-2xl p-8 sm:p-10" 
            style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.15);">
            {{ $slot }}
        </div>
    </div>

    <!-- Footer -->
    <div class="relative z-10 mt-10 text-center">
        <p class="text-sm font-medium tracking-wide" style="color: rgba(255,255,255,0.6);">
            School Payment Management System
        </p>
        <p class="text-xs mt-2 flex items-center justify-center gap-2" style="color: rgba(255,255,255,0.4);">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            Annaba, Algeria • {{ date('Y') }}
        </p>
    </div>
</div>
