@props(['type' => 'primary', 'message' => ''])

@php
    $classes = 'flex items-center justify-between w-full p-4 rounded-xl border shadow-xl transform ';
    
    switch ($type) {
        case 'success':
            $classes .= 'bg-[#124d27] border-[#1e6b39] text-white';
            break;
        case 'destructive':
        case 'error':
            $classes .= 'bg-[#7a1820] border-[#9c222b] text-white';
            break;
        case 'primary':
        default:
            $classes .= 'bg-[#24306e] border-[#344499] text-white';
            break;
    }
@endphp

<div x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 50); setTimeout(() => show = false, 3000);"
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-12 -translate-y-4"
     x-transition:enter-end="opacity-100 translate-x-0 translate-y-0"
     x-transition:leave="transition ease-in duration-200" 
     x-transition:leave-start="opacity-100 translate-x-0" 
     x-transition:leave-end="opacity-0 translate-x-12" 
     {{ $attributes->merge(['class' => $classes]) }} 
     role="alert">
     
    <div class="flex items-center">
        @if ($type === 'success')
            <svg class="w-5 h-5 mr-3 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        @elseif ($type === 'destructive' || $type === 'error')
            <svg class="w-5 h-5 mr-3 text-[#f87171]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        @else
            <!-- Primary icon (exclamation inside speech bubble) -->
            <svg class="w-5 h-5 mr-3 text-[#60a5fa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        @endif
        
        <span class="text-sm font-medium tracking-wide">{{ $message ?? $slot }}</span>
    </div>
    
    <button @click="show = false" type="button" class="ml-4 text-gray-300 hover:text-white transition-colors focus:outline-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
