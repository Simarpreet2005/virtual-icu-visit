<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Family Dashboard</h1>
            <p class="text-slate-500">Welcome back. Stay connected with your loved ones.</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Request Visit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- My Appointments -->
            <x-card>
                <h3 class="text-lg font-bold text-slate-800 mb-6">My Scheduled Visits</h3>
                @if($appointments->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-slate-500 mb-6">You haven't requested any visits yet.</p>
                        <a href="{{ route('appointments.create') }}" class="text-blue-600 font-bold hover:underline">Request your first visit</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($appointments as $appointment)
                        <div class="p-6 rounded-2xl border border-slate-100 flex flex-col md:flex-row md:items-center gap-6 hover:shadow-lg transition-all bg-white/50">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                        @if($appointment->status === 'approved') bg-emerald-100 text-emerald-600 
                                        @elseif($appointment->status === 'pending') bg-amber-100 text-amber-600
                                        @else bg-slate-100 text-slate-600 @endif">
                                        {{ $appointment->status }}
                                    </span>
                                    <span class="text-sm text-slate-400">#{{ $appointment->id }}</span>
                                </div>
                                <h4 class="text-xl font-bold text-slate-800 mb-1">Patient: {{ $appointment->patient->name }}</h4>
                                <p class="text-slate-500 text-sm">{{ $appointment->scheduled_at->format('l, M d \a\t H:i') }}</p>
                                @if($appointment->patient->reports->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($appointment->patient->reports as $report)
                                            <a href="{{ route('reports.download', $report) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all border border-blue-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                {{ $report->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex gap-3">
                                @if($appointment->status === 'approved')
                                    <a href="{{ route('video.call', $appointment->room_id) }}" class="btn-primary flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        Join Meeting
                                    </a>
                                @endif
                                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this visit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-xl border border-rose-100 text-rose-500 hover:bg-rose-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </x-card>
        </div>

        <div class="space-y-8">
            <x-card>
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Linked patients &amp; status</h3>
                    <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wide">From records</span>
                </div>
                @if($linkedPatients->isEmpty())
                    <p class="text-sm text-slate-500 leading-relaxed">No patients are linked to your account yet. Ask the ward clerk or your care coordinator to connect your profile so you can request virtual visits.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($linkedPatients as $p)
                        <li class="flex flex-col gap-3 pb-4 border-b border-slate-100 last:border-0 last:pb-0">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $p->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $p->icu_ward }} · Bed {{ $p->bed_number }}</p>
                                </div>
                                <span class="inline-block px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wide
                                    @if($p->status === 'stable') bg-emerald-100 text-emerald-600
                                    @elseif($p->status === 'critical') bg-rose-100 text-rose-600
                                    @else bg-amber-100 text-amber-600 @endif">
                                    {{ $p->status }}
                                </span>
                            </div>
                            @if($p->reports->isNotEmpty())
                                <div class="mt-2 space-y-2 pl-4 border-l-2 border-slate-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Medical Reports</p>
                                    @foreach($p->reports as $report)
                                    <div class="flex items-center justify-between gap-3 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">{{ $report->title }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $report->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <a href="{{ route('reports.download', $report) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-blue-600 hover:bg-blue-50 transition-all shadow-sm" title="Download Report">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @if($patientStatusCounts->isNotEmpty())
                    <p class="text-xs text-slate-500 mt-4">Summary:
                        @foreach($patientStatusCounts as $status => $count)
                            <span class="ml-1 text-slate-700">{{ $status }}: {{ $count }}</span>@if(!$loop->last),@endif
                        @endforeach
                    </p>
                    @endif
                @endif
                @if($nextApprovedVisit)
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <p class="text-xs uppercase font-bold text-slate-500 mb-1">Next approved visit</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $nextApprovedVisit->patient->name }}</p>
                        <p class="text-xs text-slate-500">{{ $nextApprovedVisit->scheduled_at->format('l, M j · H:i') }}</p>
                    </div>
                @endif
            </x-card>

            <!-- Notifications -->
            <x-card>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">Recent Notifications</h3>
                    <form action="{{ route('notifications.read_all') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 font-bold hover:underline">Mark all as read</button>
                    </form>
                </div>
                <div class="space-y-4">
                    @forelse($notifications as $notification)
                    <div class="flex gap-3 items-start">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-600">{{ $notification->data['message'] }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500 italic">No new notifications.</p>
                    @endforelse
                </div>
                @if($notifications->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 font-bold hover:underline flex items-center gap-1">
                        View all notifications
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
