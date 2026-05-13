<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">{{ $dashboard_title ?? "Doctor's Dashboard" }}</h1>
            <p class="text-slate-500">{{ $dashboard_subtitle ?? 'Managing ICU visits and patient approvals.' }}</p>
        </div>
        @if($can_add_patients ?? true)
        <a href="{{ route('patients.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Patient
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upcoming Visits -->
        <div class="lg:col-span-2 space-y-8">
            <x-card>
                <h3 class="text-lg font-bold text-slate-800 mb-6">Upcoming Scheduled Visits</h3>
                @if($upcoming_visits->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-slate-500">No upcoming visits scheduled.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($upcoming_visits as $visit)
                        <div class="p-4 rounded-2xl bg-blue-50/50 border border-blue-100 flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">In {{ $visit->scheduled_at->diffForHumans() }}</p>
                                    <p class="text-lg font-bold text-slate-800">{{ $visit->patient->name }}</p>
                                </div>
                                <div class="px-2 py-1 rounded-lg bg-white text-blue-600 text-xs font-bold border border-blue-100">
                                    {{ $visit->scheduled_at->format('H:i') }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="text-sm text-slate-600">{{ $visit->user->name }} (Family)</span>
                            </div>
                            <a href="{{ route('video.call', $visit->room_id) }}" class="btn-primary text-center py-2 text-sm">Join Call</a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </x-card>

            <x-card>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">{{ $patients_section_title ?? 'My Patients' }}</h3>
                    <a href="{{ route('patients.index') }}" class="text-sm text-blue-600 font-medium hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 text-sm uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 font-medium">Patient</th>
                                <th class="pb-3 font-medium">Location</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($patients as $patient)
                            <tr>
                                <td class="py-4 font-bold text-slate-800">{{ $patient->name }}</td>
                                <td class="py-4 text-sm text-slate-600">{{ $patient->icu_ward }} - Bed {{ $patient->bed_number }}</td>
                                <td class="py-4">
                                    <span class="px-2 py-1 rounded-lg text-xs font-bold uppercase 
                                        {{ $patient->status === 'stable' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $patient->status }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <!-- Pending approvals (doctors & admins only) -->
        <div class="space-y-6">
            <x-card>
                <h3 class="text-lg font-bold text-slate-800 mb-6">Visit requests</h3>
                @if($show_pending_actions ?? true)
                    @if($pending_approvals->isEmpty())
                        <p class="text-center py-8 text-slate-500">No pending requests.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($pending_approvals as $request)
                            <div class="p-4 rounded-xl border border-slate-100 hover:border-blue-200 transition-all">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden">
                                        <img src="https://ui-avatars.com/api/?name={{ $request->user->name }}" alt="User">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-800 truncate">{{ $request->user->name }}</p>
                                        <p class="text-xs text-slate-500">For: {{ $request->patient->name }}</p>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm font-bold text-slate-800">{{ $request->scheduled_at->format('M d, Y') }}</p>
                                    <p class="text-sm text-slate-500">{{ $request->scheduled_at->format('H:i') }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('appointments.approve', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-2 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition-all">Approve</button>
                                    </form>
                                    <form action="{{ route('appointments.reject', $request) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-2 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold hover:bg-rose-100 transition-all">Reject</button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="text-sm text-slate-600 leading-relaxed">Visit approvals are performed by the assigned ICU physician (or hospital administrator). Use <a href="{{ route('appointments.index') }}" class="text-blue-600 font-semibold hover:underline">Visits</a> to monitor the queue and join approved sessions you are assigned to.</p>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
