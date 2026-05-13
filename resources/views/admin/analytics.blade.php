<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Analytics</h1>
        <p class="text-slate-500">Live counts from the application database.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stats-widget title="Total users" :value="$totals['users']" color="blue">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"></path></svg></x-slot>
        </x-stats-widget>
        <x-stats-widget title="Active accounts" :value="$totals['active_users']" color="emerald">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
        </x-stats-widget>
        <x-stats-widget title="Patients" :value="$totals['patients']" color="violet">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></x-slot>
        </x-stats-widget>
        <x-stats-widget title="Appointments" :value="$totals['appointments']" color="amber">
            <x-slot name="icon"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></x-slot>
        </x-stats-widget>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <x-card>
            <h3 class="text-lg font-bold text-slate-800 mb-4">Users by role</h3>
            <ul class="space-y-2">
                @forelse($users_by_role as $role => $count)
                    <li class="flex justify-between py-2 border-b border-slate-100"><span class="capitalize text-slate-600">{{ $role }}</span><span class="font-bold text-slate-800">{{ $count }}</span></li>
                @empty
                    <li class="text-slate-500">No data</li>
                @endforelse
            </ul>
        </x-card>
        <x-card>
            <h3 class="text-lg font-bold text-slate-800 mb-4">Appointments by status</h3>
            <ul class="space-y-2">
                @forelse($appointments_by_status as $status => $count)
                    <li class="flex justify-between py-2 border-b border-slate-100"><span class="capitalize text-slate-600">{{ $status }}</span><span class="font-bold text-slate-800">{{ $count }}</span></li>
                @empty
                    <li class="text-slate-500">No data</li>
                @endforelse
            </ul>
        </x-card>
    </div>
</x-app-layout>
