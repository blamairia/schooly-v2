<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @filamentStyles
    @vite('resources/css/filament/admin/theme.css')
    
    <style>
        body, html {
            font-family: 'Figtree', sans-serif;
        }
        
        /* Desktop Layout - 60% blue panel, 40% form */
        @media (min-width: 1024px) {
            .auth-left-panel {
                width: 60% !important;
                flex: 0 0 60% !important;
            }
            .auth-right-panel {
                width: 40% !important;
                flex: 0 0 40% !important;
            }
        }
        
        /* Mobile gradient background */
        .mobile-gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 50%, #1e1b4b 100%);
        }
        
        /* Glassmorphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        /* Subtle animation for mobile */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Desktop View -->
    <div class="hidden lg:flex min-h-screen">
        <!-- Left Panel - Branding & Image (60% width) -->
        <div class="auth-left-panel bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            
            <!-- Decorative Circles -->
            <div class="absolute top-20 left-20 w-64 h-64 bg-blue-400 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-80 h-80 bg-indigo-400 rounded-full opacity-10 blur-3xl"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full h-full p-12 text-white">
                <!-- Logo -->
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-2xl">
                        <svg class="w-14 h-14 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                        </svg>
                    </div>
                    <h1 class="mt-6 text-4xl font-bold text-white">Schooly</h1>
                    <p class="text-blue-300 text-sm mt-2 uppercase tracking-widest">Payment Management</p>
                </div>
                
                <!-- Tagline -->
                <div class="mt-12 text-center max-w-md">
                    <h2 class="text-3xl font-bold mb-4">Modern School Management</h2>
                    <p class="text-blue-200 text-lg leading-relaxed">
                        Streamline your school's payment operations with our comprehensive management system.
                    </p>
                </div>
                
                <!-- Features -->
                <div class="mt-16 space-y-4 w-full max-w-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-700/50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Payment Tracking</h3>
                            <p class="text-blue-200 text-sm">Track and manage all student payments</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-700/50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Financial Reports</h3>
                            <p class="text-blue-200 text-sm">Generate detailed financial analytics</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-700/50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold">Automated Reminders</h3>
                            <p class="text-blue-200 text-sm">Send payment notifications automatically</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="absolute bottom-8 left-0 right-0 text-center">
                    <p class="text-blue-300 text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Annaba, Algeria
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Login Form (40% width) -->
        <div class="auth-right-panel flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
    
    <!-- Mobile View - Beautiful gradient background with glassmorphism card -->
    <div class="lg:hidden min-h-screen mobile-gradient-bg relative overflow-hidden">
        <!-- Decorative floating circles -->
        <div class="absolute top-10 right-10 w-32 h-32 bg-blue-400/20 rounded-full blur-2xl float-animation"></div>
        <div class="absolute bottom-20 left-5 w-40 h-40 bg-indigo-400/20 rounded-full blur-2xl float-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/3 left-1/4 w-24 h-24 bg-purple-400/15 rounded-full blur-xl float-animation" style="animation-delay: -1.5s;"></div>
        
        <!-- Content Container -->
        <div class="relative z-10 min-h-screen flex flex-col justify-center px-5 py-8">
            
            <!-- Logo and Branding -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl shadow-2xl mb-4 border border-white/20">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-1">Schooly</h1>
                <p class="text-blue-200/80 text-xs uppercase tracking-[0.2em]">Payment Management</p>
            </div>
            
            <!-- Glassmorphism Login Card -->
            <div class="glass-card rounded-3xl shadow-2xl p-6 mx-auto w-full max-w-sm">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-blue-200/60 text-xs flex items-center justify-center gap-1.5">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Annaba, Algeria
                </p>
            </div>
        </div>
    </div>

    @livewireScripts
    @filamentScripts
</body>
</html>
