<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">System activity</h1>
            <p class="text-slate-500">MongoDB activity log (last 100 entries).</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-secondary text-sm">Dashboard</a>
    </div>

    @if(!$mongoConnected)
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            <strong>MongoDB not reachable.</strong> Activity rows below may be empty until the local MongoDB service is running and <code class="text-xs bg-amber-100/80 px-1 rounded">DB_*_MONGO</code> settings in <code class="text-xs bg-amber-100/80 px-1 rounded">.env</code> match your instance.
        </div>
    @endif

    @if($logs->isEmpty())
        <x-card>
            <p class="text-slate-600">No log entries loaded. @if(!$mongoConnected)Check your MongoDB service and connection settings.@else If you expect entries, trigger actions such as login, visit requests, or approvals while MongoDB is running.@endif</p>
        </x-card>
    @else
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <th class="pb-3 font-medium">When</th>
                            <th class="pb-3 font-medium">User ID</th>
                            <th class="pb-3 font-medium">Action</th>
                            <th class="pb-3 font-medium">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                        <tr>
                            <td class="py-2 text-slate-500 whitespace-nowrap">{{ $log->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="py-2 font-mono text-xs">{{ $log->user_id ?? '—' }}</td>
                            <td class="py-2 font-medium text-slate-800">{{ $log->action }}</td>
                            <td class="py-2 text-slate-600">{{ $log->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif
</x-app-layout>
