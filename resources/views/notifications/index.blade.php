<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">All Notifications</h1>
            <p class="text-slate-500">Stay updated with your latest visit requests and reports.</p>
        </div>
        <form action="{{ route('notifications.read_all') }}" method="POST">
            @csrf
            <button type="submit" class="btn-secondary">Mark All as Read</button>
        </form>
    </div>

    <x-card>
        @if($notifications->isEmpty())
            <div class="text-center py-12">
                <p class="text-slate-500 italic">No notifications found.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($notifications as $notification)
                <div class="py-6 flex flex-col md:flex-row md:items-center justify-between gap-4 {{ $notification->read_at ? 'opacity-60' : '' }}">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-xl {{ $notification->read_at ? 'bg-slate-100' : 'bg-blue-100' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 {{ $notification->read_at ? 'text-slate-400' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $notification->data['message'] }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ $notification->created_at->format('M d, Y \a\t H:i') }} ({{ $notification->created_at->diffForHumans() }})</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if(!$notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-all">Mark as Read</button>
                        </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
