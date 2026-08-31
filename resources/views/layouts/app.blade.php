<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MBC Student Monitoring System') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/MBC-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50">
        <div x-data="{ sidebarExpanded: JSON.parse(localStorage.getItem('sidebarExpanded') ?? 'true') }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebarExpanded', JSON.stringify(value)))" class="flex h-screen overflow-hidden bg-slate-50">
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
                @include('layouts.topheader')

                <main class="w-full grow">
                    {{ $slot }}
                </main>
            </div>
            
            <!-- Toast Notifications -->
            <div class="fixed top-24 right-8 z-50 flex flex-col gap-3 min-w-[320px]">
                @if (session('toast_success'))
                    <x-toast type="success" :message="session('toast_success')" class="shadow-2xl" />
                @endif
                
                @if (session('success'))
                    <x-toast type="success" :message="session('success')" class="shadow-2xl" />
                @endif

                @if (session('status'))
                    <x-toast type="primary" :message="session('status')" class="shadow-2xl" />
                @endif

                @if (session('error'))
                    <x-toast type="destructive" :message="session('error')" class="shadow-2xl" />
                @endif
            </div>
        </div>
    </body>
</html>
