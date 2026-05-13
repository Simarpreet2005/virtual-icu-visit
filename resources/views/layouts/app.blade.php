<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ICU Virtual Visit') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" x-data="{ navOpen: false }" @keydown.escape.window="navOpen = false">
    <div class="blob" style="top: -10%; left: -10%;"></div>
    <div class="blob" style="bottom: -10%; right: -10%; animation-delay: -5s;"></div>

    <div class="min-h-screen flex">
        <!-- Sidebar (desktop) -->
        @include('layouts.sidebar')

        <!-- Mobile nav drawer -->
        <div x-show="navOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
            <div class="flex-1 bg-slate-900/40 backdrop-blur-sm" @click="navOpen = false" aria-hidden="true"></div>
            <aside class="w-72 max-w-[85vw] h-screen max-h-[100dvh] bg-white/95 backdrop-blur-2xl border-l border-white/20 shadow-2xl flex flex-col min-h-0">
                <div class="p-6 flex items-center justify-between border-b border-slate-100">
                    <span class="text-lg font-bold text-slate-800">Menu</span>
                    <button type="button" @click="navOpen = false" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
                    @include('layouts.sidebar-links')
                </nav>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Navbar -->
            @include('layouts.navigation')

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 p-4 glass-card bg-emerald-50/80 border-emerald-200 text-emerald-800 flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 glass-card bg-rose-50/80 border-rose-200 text-rose-800 flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
