<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Visit Requests</h1>
            <p class="text-slate-500">All scheduled virtual ICU visits.</p>
        </div>
        @if(auth()->user()->role === 'family')
        <a href="{{ route('appointments.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Request Visit
        </a>
        @endif
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-sm uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-3 font-medium">Patient</th>
                        <th class="pb-3 font-medium">Requested By</th>
                        <th class="pb-3 font-medium">Scheduled</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($appointments as $appointment)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-4">
                            <p class="font-bold text-slate-800">{{ $appointment->patient->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">{{ $appointment->patient->icu_ward ?? '' }}</p>
                        </td>
                        <td class="py-4 text-sm text-slate-600">
                            {{ $appointment->user->name ?? 'N/A' }}
                        </td>
                        <td class="py-4">
                            <p class="text-sm font-medium text-slate-700">{{ $appointment->scheduled_at->format('M d, Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $appointment->scheduled_at->format('H:i') }}</p>
                        </td>
                        <td class="py-4">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                @if($appointment->status === 'approved') bg-emerald-50 text-emerald-600
                                @elseif($appointment->status === 'pending') bg-amber-50 text-amber-600
                                @elseif($appointment->status === 'rejected') bg-rose-50 text-rose-600
                                @else bg-slate-50 text-slate-600 @endif">
                                {{ $appointment->status }}
                            </span>
                        </td>
                        <td class="py-4 text-right">
                            <div class="flex justify-end gap-2 flex-wrap">
                                @if($appointment->status === 'approved' && $appointment->room_id && auth()->user()->role !== 'admin')
                                    <a href="{{ route('video.call', $appointment->room_id) }}" class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all">
                                        Join Call
                                    </a>
                                @endif

                                @if($appointment->status === 'pending' && in_array(auth()->user()->role, ['admin', 'doctor']))
                                    <form action="{{ route('appointments.approve', $appointment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-all">Approve</button>
                                    </form>
                                    <form action="{{ route('appointments.reject', $appointment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 rounded-lg hover:bg-rose-100 transition-all">Reject</button>
                                    </form>
                                @endif

                                @if($appointment->status === 'pending' && auth()->user()->id === $appointment->user_id)
                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Cancel this visit request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold text-slate-500 bg-slate-100 rounded-lg hover:bg-slate-200 transition-all">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-slate-500 font-medium">No visit requests found.</p>
                            @if(auth()->user()->role === 'family')
                                <a href="{{ route('appointments.create') }}" class="mt-3 inline-block btn-primary text-sm">Request a Visit</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $appointments->links() }}
        </div>
    </x-card>
</x-app-layout>
