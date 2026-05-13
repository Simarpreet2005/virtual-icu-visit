<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Patient Management</h1>
            <p class="text-slate-500">View and manage all active ICU patients.</p>
        </div>
        @unless(auth()->user()->isNurse())
        <a href="{{ route('patients.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Register Patient
        </a>
        @endunless
    </div>

    <x-card>
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
            <form action="{{ route('patients.index') }}" method="GET" class="w-full md:w-96 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patients..." class="w-full pl-10 pr-4 py-2 input-glass">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-sm uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-3 font-medium">Patient Details</th>
                        <th class="pb-3 font-medium">Location</th>
                        <th class="pb-3 font-medium">Doctor</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patients as $patient)
                    <tr class="group hover:bg-slate-50/50 transition-all">
                        <td class="py-4">
                            <p class="font-bold text-slate-800">{{ $patient->name }}</p>
                            <p class="text-xs text-slate-500">ID: #PT-{{ $patient->id }}</p>
                        </td>
                        <td class="py-4">
                            <p class="text-sm text-slate-700 font-medium">{{ $patient->icu_ward }}</p>
                            <p class="text-xs text-slate-500">Bed {{ $patient->bed_number }}</p>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                                    {{ $patient->doctor ? substr($patient->doctor->name, 0, 2) : '??' }}
                                </div>
                                <span class="text-sm text-slate-600">{{ $patient->doctor ? $patient->doctor->name : 'Unassigned' }}</span>
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                @if($patient->status === 'stable') bg-emerald-50 text-emerald-600
                                @elseif($patient->status === 'critical') bg-rose-50 text-rose-600
                                @else bg-amber-50 text-amber-600 @endif">
                                {{ $patient->status }}
                            </span>
                        </td>
                        <td class="py-4 text-right space-x-2">
                            <a href="{{ route('patients.show', $patient) }}" class="p-2 inline-block rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @unless(auth()->user()->isNurse())
                            <a href="{{ route('patients.edit', $patient) }}" class="p-2 inline-block rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @endunless
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            @if(request('search'))
                                <p class="text-slate-500 font-medium">No patients found matching "{{ request('search') }}"</p>
                                <a href="{{ route('patients.index') }}" class="mt-2 inline-block text-blue-600 text-sm hover:underline">Clear search</a>
                            @else
                                <p class="text-slate-500 font-medium">No patients registered yet.</p>
                                @unless(auth()->user()->isNurse())
                                <a href="{{ route('patients.create') }}" class="mt-3 inline-block btn-primary text-sm">Register First Patient</a>
                                @endunless
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $patients->links() }}
        </div>
    </x-card>
</x-app-layout>
