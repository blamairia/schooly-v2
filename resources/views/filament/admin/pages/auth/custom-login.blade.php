<div>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-gray-600 mt-2">Sign in to access your dashboard</p>
    </div>

    <!-- Filament Login Form -->
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <!-- Demo Credentials -->
    <div class="mt-8 p-5 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-bold text-blue-900 uppercase">Demo Credentials</span>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between py-2 px-3 bg-white rounded border border-blue-100">
                <span class="text-gray-600 font-medium">Email:</span>
                <code class="text-blue-700 font-mono bg-blue-50 px-2 py-1 rounded">admin@schooly.com</code>
            </div>
            <div class="flex items-center justify-between py-2 px-3 bg-white rounded border border-blue-100">
                <span class="text-gray-600 font-medium">Password:</span>
                <code class="text-blue-700 font-mono bg-blue-50 px-2 py-1 rounded">admin</code>
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="mt-6 text-center">
        <p class="text-xs text-gray-500">
            © {{ date('Y') }} Schooly. All rights reserved.
        </p>
    </div>
</div>


