<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col items-center">
                <!-- Animated Logo Container -->
                <div class="relative group">
                    <!-- Main Logo -->
                    <div class="w-24 h-24 bg-gradient-to-br from-cyan-400 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-purple-500/40 transform rotate-3 group-hover:rotate-0 transition-all duration-500 group-hover:scale-105">
                        <svg class="w-14 h-14 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                        </svg>
                    </div>
                    <!-- Glow Ring -->
                    <div class="absolute inset-0 w-24 h-24 bg-gradient-to-br from-cyan-400 to-purple-500 rounded-2xl animate-ping opacity-20"></div>
                </div>
                
                <!-- Brand Name -->
                <h1 class="mt-6 text-4xl font-bold bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-400 bg-clip-text text-transparent tracking-tight">
                    Schooly
                </h1>
                <p class="text-white/50 text-sm mt-2 font-medium tracking-wider uppercase">Payment Management</p>
            </div>
        </x-slot>

        <!-- Error Messages -->
        <x-validation-errors class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm" />

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-white/80 mb-2">
                    {{ __('Email Address') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="admin@example.com"
                        class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-white/30 rounded-xl focus:outline-none focus:border-cyan-400/50 focus:ring-2 focus:ring-cyan-400/20 transition-all duration-300 text-sm" />
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-semibold text-white/80 mb-2">
                    {{ __('Password') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-white/30 rounded-xl focus:outline-none focus:border-cyan-400/50 focus:ring-2 focus:ring-cyan-400/20 transition-all duration-300 text-sm" />
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <input id="remember_me" 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 bg-white/5 border-white/20 rounded text-cyan-500 focus:ring-cyan-400/30 focus:ring-offset-0 transition-colors" />
                    <span class="ml-3 text-sm text-white/50 group-hover:text-white/70 transition-colors">{{ __('Remember me') }}</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-white/50 hover:text-cyan-400 transition-colors duration-300">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full py-4 px-6 bg-gradient-to-r from-cyan-500 via-purple-500 to-pink-500 hover:from-cyan-400 hover:via-purple-400 hover:to-pink-400 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] hover:shadow-xl hover:shadow-purple-500/40 transition-all duration-300 flex items-center justify-center gap-3 text-sm uppercase tracking-wider">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                {{ __('Sign In') }}
            </button>
        </form>

        <!-- Demo Credentials Box -->
        <div class="mt-8 p-5 bg-gradient-to-br from-cyan-500/5 to-purple-500/10 border border-cyan-500/20 rounded-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-cyan-400 uppercase tracking-wider">Demo Access</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                    <span class="text-xs text-white/40 uppercase tracking-wide">Email</span>
                    <code class="text-sm text-cyan-300 font-mono bg-cyan-500/10 px-3 py-1 rounded-md">admin@example.com</code>
                </div>
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                    <span class="text-xs text-white/40 uppercase tracking-wide">Password</span>
                    <code class="text-sm text-cyan-300 font-mono bg-cyan-500/10 px-3 py-1 rounded-md">password</code>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="mt-6 flex items-center gap-4">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
            <span class="text-xs text-white/30 uppercase tracking-widest">Secure Login</span>
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        </div>
    </x-authentication-card>
</x-guest-layout>
