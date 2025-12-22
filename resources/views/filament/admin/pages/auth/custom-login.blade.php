<div>
    <!-- Welcome Header (hidden on mobile since it's shown in the layout) -->
    <div class="mb-6 lg:mb-8">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-gray-600 mt-1 lg:mt-2 text-sm lg:text-base">Sign in to access your dashboard</p>
    </div>

    <!-- Filament Login Form -->
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <!-- Demo Credentials - Compact on mobile -->
    <div class="mt-6 lg:mt-8 p-4 lg:p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl lg:rounded-lg">
        <div class="flex items-center gap-2 mb-2 lg:mb-3">
            <svg class="w-4 h-4 lg:w-5 lg:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs lg:text-sm font-bold text-blue-900 uppercase tracking-wide">Demo Credentials</span>
        </div>
        <div class="space-y-2 text-xs lg:text-sm">
            <div class="flex items-center justify-between py-2 px-3 bg-white/80 rounded-lg border border-blue-100/50">
                <span class="text-gray-600 font-medium">Email</span>
                <code class="text-blue-700 font-mono bg-blue-100/50 px-2 py-0.5 rounded text-xs">admin@schooly.com</code>
            </div>
            <div class="flex items-center justify-between py-2 px-3 bg-white/80 rounded-lg border border-blue-100/50">
                <span class="text-gray-600 font-medium">Password</span>
                <code class="text-blue-700 font-mono bg-blue-100/50 px-2 py-0.5 rounded text-xs">admin</code>
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="mt-4 lg:mt-6 text-center">
        <p class="text-[10px] lg:text-xs text-gray-400">
            © {{ date('Y') }} Schooly. All rights reserved.
        </p>
    </div>
</div>
