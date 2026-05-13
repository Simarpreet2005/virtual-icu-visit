<x-app-layout>
    <div class="max-w-xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Edit User</h1>
            <p class="text-slate-500"><a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Back to users</a></p>
        </div>
        <x-card>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full input-glass" required>
                    @error('name')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full input-glass" required>
                    @error('email')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full input-glass">
                    @error('phone')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Role</label>
                    <select name="role" class="w-full input-glass" required>
                        @foreach(['admin','doctor','nurse','family'] as $r)
                            <option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">New password (optional)</label>
                    <input type="password" name="password" class="w-full input-glass">
                    @error('password')<p class="mt-1 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Confirm new password</label>
                    <input type="password" name="password_confirmation" class="w-full input-glass">
                </div>
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-blue-600" @checked(old('is_active', $user->is_active))>
                    <label for="is_active" class="text-sm text-slate-700">Account active</label>
                </div>
                <button type="submit" class="w-full btn-primary py-3">Save changes</button>
            </form>
        </x-card>
    </div>
</x-app-layout>
