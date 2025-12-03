<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="flex flex-col items-center">
                <!-- Futuristic Logo -->
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                        </svg>
                    </div>
                    <!-- Pulse Ring -->
                    <div class="absolute inset-0 w-20 h-20 bg-gradient-to-br from-cyan-400 to-purple-600 rounded-2xl animate-ping opacity-20"></div>
                </div>
                <h1 class="mt-4 text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                    Schooly
                </h1>
                <p class="text-white/60 text-sm mt-1">Payment Management</p>
            </div>
        </x-slot>

        <x-validation-errors class="mb-4 text-red-400" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-white/80 font-medium" />
                <x-input id="email" 
                    class="block mt-2 w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl focus:border-cyan-400 focus:ring-cyan-400/50 transition-all duration-200" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Enter your email" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" class="text-white/80 font-medium" />
                <x-input id="password" 
                    class="block mt-2 w-full bg-white/10 border-white/20 text-white placeholder-white/40 rounded-xl focus:border-cyan-400 focus:ring-cyan-400/50 transition-all duration-200" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="bg-white/10 border-white/20 text-cyan-500 focus:ring-cyan-400/50" />
                    <span class="ms-2 text-sm text-white/60">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-400 hover:to-purple-500 text-white font-semibold rounded-xl shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Sign In') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="mt-4 text-center">
                    <a class="text-sm text-white/50 hover:text-cyan-400 transition-colors duration-200" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
            @endif
        </form>

        <!-- Demo Credentials Box -->
        <div class="mt-6 p-4 bg-gradient-to-r from-cyan-500/10 to-purple-500/10 border border-cyan-500/20 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-semibold text-cyan-400">Demo Credentials</span>
            </div>
            <div class="space-y-1 text-sm">
                <p class="text-white/70">
                    <span class="text-white/50">Email:</span> 
                    <code class="ml-2 px-2 py-0.5 bg-white/10 rounded text-cyan-300 font-mono">admin@example.com</code>
                </p>
                <p class="text-white/70">
                    <span class="text-white/50">Password:</span> 
                    <code class="ml-2 px-2 py-0.5 bg-white/10 rounded text-cyan-300 font-mono">password</code>
                </p>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
