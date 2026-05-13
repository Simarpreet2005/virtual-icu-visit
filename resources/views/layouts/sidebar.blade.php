<aside class="hidden lg:flex flex-col w-72 h-screen bg-white/40 backdrop-blur-2xl border-r border-white/20 sticky top-0">
    <div class="p-8 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
        </div>
        <span class="text-xl font-bold text-slate-800 tracking-tight">ICU Visit</span>
    </div>

    <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
        @include('layouts.sidebar-links')
    </nav>

    <div class="p-6 border-t border-white/20">
        <div class="glass-panel p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white overflow-hidden shadow-sm">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=0D8ABC&color=fff" alt="Avatar">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>
</aside>
