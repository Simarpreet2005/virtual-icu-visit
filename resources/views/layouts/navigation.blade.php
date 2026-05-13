<nav class="sticky top-0 z-10 bg-white/40 backdrop-blur-xl border-b border-white/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Mobile Menu Button -->
            <div class="flex items-center lg:hidden">
                <button type="button" @click="navOpen = true" class="text-slate-500 hover:text-slate-600 focus:outline-none p-2 rounded-lg hover:bg-slate-100" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Page Title (Mobile) -->
            <div class="flex-1 flex items-center justify-center lg:hidden">
                <span class="text-lg font-bold text-slate-800">ICU Visit</span>
            </div>

            <!-- Right side -->
            <div class="flex items-center gap-4 ml-auto">
                <!-- Notifications -->
                <a href="{{ route('notifications.index') }}" class="p-2 rounded-xl text-slate-500 hover:bg-slate-50 transition-all relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-rose-500 rounded-full border-2 border-white text-[9px] font-bold text-white flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>

                <!-- Profile menu (click/touch friendly) -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open" class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-50 transition-all" :aria-expanded="open ? 'true' : 'false'">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" class="w-8 h-8 rounded-lg" alt="">
                        <span class="text-sm font-bold text-slate-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-48 glass-card py-2 z-50 shadow-lg border border-slate-100/80">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50">Profile Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
