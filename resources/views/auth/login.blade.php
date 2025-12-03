<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col items-center">
                <!-- Animated Logo Container -->
                <div class="relative group">
                    <!-- Main Logo -->
                    <div class="w-24 h-24 rounded-2xl flex items-center justify-center transform rotate-3 group-hover:rotate-0 transition-all duration-500 group-hover:scale-105"
                        style="background: linear-gradient(135deg, #06b6d4, #8b5cf6, #ec4899); box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.4);">
                        <svg class="w-14 h-14 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                        </svg>
                    </div>
                    <!-- Glow Ring -->
                    <div class="absolute inset-0 w-24 h-24 rounded-2xl animate-ping" 
                        style="background: linear-gradient(135deg, #06b6d4, #8b5cf6); opacity: 0.2;"></div>
                </div>
                
                <!-- Brand Name -->
                <h1 class="mt-6 text-4xl font-bold tracking-tight"
                    style="background: linear-gradient(90deg, #06b6d4, #8b5cf6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Schooly
                </h1>
                <p class="text-sm mt-2 font-medium tracking-wider uppercase" style="color: rgba(255,255,255,0.5);">Payment Management</p>
            </div>
        </x-slot>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl text-sm" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171;">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl text-sm font-medium" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #34d399;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold mb-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('Email Address') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5" style="color: rgba(255,255,255,0.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="w-full pl-12 pr-4 py-3.5 rounded-xl text-sm transition-all duration-300"
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none;"
                        onfocus="this.style.borderColor='rgba(6,182,212,0.5)'; this.style.boxShadow='0 0 0 3px rgba(6,182,212,0.2)';"
                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';" />
                </div>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-semibold mb-2" style="color: rgba(255,255,255,0.8);">
                    {{ __('Password') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5" style="color: rgba(255,255,255,0.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-12 pr-4 py-3.5 rounded-xl text-sm transition-all duration-300"
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none;"
                        onfocus="this.style.borderColor='rgba(6,182,212,0.5)'; this.style.boxShadow='0 0 0 3px rgba(6,182,212,0.2)';"
                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';" />
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <input id="remember_me" 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 rounded transition-colors"
                        style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); accent-color: #06b6d4;" />
                    <span class="ml-3 text-sm transition-colors" style="color: rgba(255,255,255,0.5);">{{ __('Remember me') }}</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm transition-colors duration-300" 
                        style="color: rgba(255,255,255,0.5);"
                        onmouseover="this.style.color='#06b6d4';"
                        onmouseout="this.style.color='rgba(255,255,255,0.5)';">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full py-4 px-6 text-white font-bold rounded-xl transform hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-3 text-sm uppercase tracking-wider"
                style="background: linear-gradient(90deg, #06b6d4, #8b5cf6, #ec4899); box-shadow: 0 10px 40px -10px rgba(139, 92, 246, 0.5);"
                onmouseover="this.style.boxShadow='0 20px 50px -10px rgba(139, 92, 246, 0.6)';"
                onmouseout="this.style.boxShadow='0 10px 40px -10px rgba(139, 92, 246, 0.5)';">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                {{ __('Sign In') }}
            </button>
        </form>

        <!-- Demo Credentials Box -->
        <div class="mt-8 p-5 rounded-xl" style="background: linear-gradient(135deg, rgba(6,182,212,0.05), rgba(139,92,246,0.1)); border: 1px solid rgba(6,182,212,0.2);">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(6,182,212,0.2);">
                    <svg class="w-4 h-4" style="color: #06b6d4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-bold uppercase tracking-wider" style="color: #06b6d4;">Demo Access</span>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg" style="background: rgba(255,255,255,0.05);">
                    <span class="text-xs uppercase tracking-wide" style="color: rgba(255,255,255,0.4);">Email</span>
                    <code class="text-sm font-mono px-3 py-1 rounded-md" style="color: #67e8f9; background: rgba(6,182,212,0.1);">admin@example.com</code>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg" style="background: rgba(255,255,255,0.05);">
                    <span class="text-xs uppercase tracking-wide" style="color: rgba(255,255,255,0.4);">Password</span>
                    <code class="text-sm font-mono px-3 py-1 rounded-md" style="color: #67e8f9; background: rgba(6,182,212,0.1);">password</code>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="mt-6 flex items-center gap-4">
            <div class="flex-1 h-px" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);"></div>
            <span class="text-xs uppercase tracking-widest" style="color: rgba(255,255,255,0.3);">Secure Login</span>
            <div class="flex-1 h-px" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);"></div>
        </div>
    </x-authentication-card>
</x-guest-layout>
