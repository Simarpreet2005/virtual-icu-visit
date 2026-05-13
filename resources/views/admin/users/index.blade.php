<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Users</h1>
            <p class="text-slate-500">Create, edit, assign roles, and deactivate platform accounts.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary inline-flex items-center justify-center gap-2">Add User</a>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email" class="flex-1 input-glass">
            <select name="role" class="input-glass sm:w-44">
                <option value="">All roles</option>
                @foreach(['admin','doctor','nurse','family'] as $r)
                    <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="pb-3 font-medium">User</th>
                        <th class="pb-3 font-medium">Role</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr>
                        <td class="py-3">
                            <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-slate-500">{{ $user->email }}</p>
                        </td>
                        <td class="py-3"><span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold uppercase">{{ $user->role }}</span></td>
                        <td class="py-3">
                            @if($user->is_active)
                                <span class="text-emerald-600 font-medium text-xs uppercase">Active</span>
                            @else
                                <span class="text-rose-600 font-medium text-xs uppercase">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 font-medium hover:underline">Edit</a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-medium hover:underline">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $users->links() }}</div>
    </x-card>
</x-app-layout>
