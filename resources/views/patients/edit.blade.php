<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('patients.show', $patient) }}" class="text-slate-400 hover:text-blue-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Edit Patient</h1>
                <p class="text-slate-500">Update details for <strong>{{ $patient->name }}</strong></p>
            </div>
        </div>

        <x-card>
            <form action="{{ route('patients.update', $patient) }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Patient Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $patient->name) }}" class="w-full input-glass" required>
                        @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="icu_ward" class="block text-sm font-bold text-slate-700 mb-2">ICU Ward / Unit</label>
                        <input type="text" name="icu_ward" id="icu_ward" value="{{ old('icu_ward', $patient->icu_ward) }}" class="w-full input-glass" placeholder="e.g. Level 3 ICU — East Block" required>
                        @error('icu_ward') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="bed_number" class="block text-sm font-bold text-slate-700 mb-2">Bed Number</label>
                        <input type="text" name="bed_number" id="bed_number" value="{{ old('bed_number', $patient->bed_number) }}" class="w-full input-glass" placeholder="e.g. E-12" required>
                        @error('bed_number') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="doctor_id" class="block text-sm font-bold text-slate-700 mb-2">Primary Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="w-full input-glass">
                            <option value="">-- Assign Doctor --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $patient->doctor_id) == $doctor->id ? 'selected' : '' }}>Dr. {{ $doctor->name }}</option>
                            @endforeach
                        </select>
                        @error('doctor_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    @if(! auth()->user()->isNurse())
                    <div>
                        <label for="nurse_id" class="block text-sm font-bold text-slate-700 mb-2">Assigned Nurse</label>
                        <select name="nurse_id" id="nurse_id" class="w-full input-glass">
                            <option value="">-- Optional --</option>
                            @foreach($nurses as $nurse)
                                <option value="{{ $nurse->id }}" {{ (int) old('nurse_id', $patient->nurse_id) === (int) $nurse->id ? 'selected' : '' }}>{{ $nurse->name }}</option>
                            @endforeach
                        </select>
                        @error('nurse_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    <div class="md:col-span-2">
                        <label for="family_user_id" class="block text-sm font-bold text-slate-700 mb-2">Primary family contact (can request visits)</label>
                        <select name="family_user_id" id="family_user_id" class="w-full input-glass">
                            <option value="">-- Not linked --</option>
                            @foreach($families as $family)
                                <option value="{{ $family->id }}" {{ (int) old('family_user_id', $patient->family_user_id) === (int) $family->id ? 'selected' : '' }}>{{ $family->name }} — {{ $family->email }}</option>
                            @endforeach
                        </select>
                        @error('family_user_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-bold text-slate-700 mb-2">Current Status</label>
                        <select name="status" id="status" class="w-full input-glass" required>
                            <option value="stable" {{ old('status', $patient->status) === 'stable' ? 'selected' : '' }}>Stable</option>
                            <option value="critical" {{ old('status', $patient->status) === 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="improving" {{ old('status', $patient->status) === 'improving' ? 'selected' : '' }}>Improving</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="emergency_contact" class="block text-sm font-bold text-slate-700 mb-2">Emergency Contact Info</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}" class="w-full input-glass" placeholder="Name and reachable mobile number">
                        @error('emergency_contact') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <a href="{{ route('patients.show', $patient) }}" class="flex-1 btn-secondary text-center">Cancel</a>
                    <button type="submit" class="flex-1 btn-primary">Save Changes</button>
                </div>
            </form>
        </x-card>

        @if(auth()->user()->isAdmin())
        <div class="mt-6">
            <x-card class="border border-rose-100 bg-rose-50/40">
                <h4 class="font-bold text-rose-700 mb-2">Danger Zone</h4>
                <p class="text-sm text-slate-600 mb-4">Removing a patient will soft-delete them and preserve all appointment history.</p>
                <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this patient? This action cannot be easily undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-sm font-bold hover:bg-rose-700 transition-all">
                        Remove Patient
                    </button>
                </form>
            </x-card>
        </div>
        @endif
    </div>
</x-app-layout>
