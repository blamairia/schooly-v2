<x-filament-panels::page.simple>
    <style>
        /* Custom styling for the login page */
        .fi-simple-page {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        
        .fi-simple-main {
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-left-panel {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .login-form-panel {
            padding: 3rem;
        }

        @media (max-width: 768px) {
            .fi-simple-main {
                grid-template-columns: 1fr;
            }
            .login-left-panel {
                display: none;
            }
        }
    </style>

    <div class="login-left-panel">
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
    </div>

    <div class="login-form-panel">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Sign in to access your dashboard</p>
        </div>

        <!-- Filament Login Form -->
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />

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
</x-filament-panels::page.simple>
