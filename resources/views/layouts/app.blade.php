<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(auth()->check())
        <meta name="user-id" content="{{ auth()->id() }}">
    @endif

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
    
    <!-- Real-time Toast Notifications -->
    <div x-data="{ 
            notifications: [],
            add(detail) {
                const id = Date.now();
                this.notifications.push({ id, ...detail });
                setTimeout(() => this.remove(id), 5000);
            },
            remove(id) {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }
        }"
        @new-notification.window="add($event.detail)"
        class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2 w-full max-w-sm pointer-events-none">
        
        <template x-for="notification in notifications" :key="notification.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-2 opacity-0 scale-95"
                 x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="pointer-events-auto bg-white/90 backdrop-blur-xl border border-slate-200 rounded-2xl shadow-2xl p-4 flex items-start gap-4 cursor-pointer hover:bg-white transition-all group"
                 @click="window.location.href = notification.action_url">
                 
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800" x-text="notification.message"></p>
                    <p class="text-xs text-slate-500 mt-1">Click to view details</p>
                </div>
                <button @click.stop="remove(notification.id)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>
</body>

</html>
