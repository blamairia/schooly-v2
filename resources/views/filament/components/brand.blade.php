<div
    x-data="{ isCollapsed: $store.sidebar?.isOpen === false }"
    x-init="$watch('$store.sidebar?.isOpen', value => isCollapsed = !value)"
    class="flex items-center gap-2"
>
    <!-- Icon (always visible, used when collapsed) -->
    <svg 
        viewBox="0 0 24 24" 
        fill="none" 
        xmlns="http://www.w3.org/2000/svg"
        class="h-8 w-8 flex-shrink-0"
        :class="{ 'mx-auto': isCollapsed }"
    >
        <path stroke="#1e40af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
        <path stroke="#1e40af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
        <path stroke="#1e40af" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
    </svg>
    
    <!-- Text (hidden when collapsed) -->
    <span 
        x-show="!isCollapsed"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="text-xl font-semibold text-blue-800"
        style="font-family: 'Outfit', sans-serif;"
    >
        Schooly
    </span>
</div>
