<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">System Overview</h1>
        <p class="text-slate-500">Welcome back, Admin. Here's what's happening today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stats-widget title="Total Users" :value="$stats['total_users']" color="blue">
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </x-slot>
        </x-stats-widget>

        <x-stats-widget title="Active Patients" :value="$stats['active_patients']" color="emerald">
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </x-slot>
        </x-stats-widget>

        <x-stats-widget title="Total Visits" :value="$stats['total_appointments']" color="violet">
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </x-slot>
        </x-stats-widget>

        <x-stats-widget title="Pending Requests" :value="$stats['pending_appointments']" color="amber">
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </x-slot>
        </x-stats-widget>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users -->
        <x-card>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 font-medium hover:underline">Manage users</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-sm uppercase tracking-wider border-b border-slate-100">
                            <th class="pb-3 font-medium">User</th>
                            <th class="pb-3 font-medium">Role</th>
                            <th class="pb-3 font-medium">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recent_users as $user)
                        <tr>
                            <td class="py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <span class="font-medium text-slate-700">{{ $user->name }}</span>
                            </td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium uppercase">{{ $user->role }}</span>
                            </td>
                            <td class="py-4 text-sm text-slate-500">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- Recent Activity -->
        <x-card>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-slate-800">Recent Appointments</h3>
                <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 font-medium hover:underline">Manage</a>
            </div>
            <div class="space-y-4">
                @forelse($recent_appointments as $appointment)
                <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                    <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center text-violet-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-slate-800">{{ $appointment->patient->name }}</p>
                        <p class="text-sm text-slate-500">Visit with {{ $appointment->user->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-800">{{ $appointment->scheduled_at->format('M d, H:i') }}</p>
                        <span class="text-xs font-medium uppercase {{ $appointment->status === 'approved' ? 'text-emerald-500' : ($appointment->status === 'pending' ? 'text-amber-500' : 'text-slate-400') }}">{{ $appointment->status }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-slate-400 italic text-sm">No appointments yet.</p>
                </div>
                @endforelse
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        <x-card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800">Users by role</h3>
                <a href="{{ route('admin.analytics') }}" class="text-xs text-blue-600 font-bold hover:underline">Full analytics</a>
            </div>
            <ul class="space-y-2 text-sm">
                @foreach($users_by_role ?? [] as $role => $count)
                    <li class="flex justify-between border-b border-slate-100 py-2"><span class="capitalize text-slate-600">{{ $role }}</span><span class="font-bold text-slate-800">{{ $count }}</span></li>
                @endforeach
            </ul>
        </x-card>
        <x-card>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800">Visits by status</h3>
                <a href="{{ route('admin.activity.index') }}" class="text-xs text-blue-600 font-bold hover:underline">Activity log</a>
            </div>
            <ul class="space-y-2 text-sm">
                @foreach($appointments_by_status ?? [] as $status => $count)
                    <li class="flex justify-between border-b border-slate-100 py-2"><span class="capitalize text-slate-600">{{ $status }}</span><span class="font-bold text-slate-800">{{ $count }}</span></li>
                @endforeach
            </ul>
        </x-card>
    </div>
</x-app-layout>
