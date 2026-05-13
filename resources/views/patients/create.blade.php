<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Register New Patient</h1>
            <p class="text-slate-500">Assign a patient to an ICU ward and a primary doctor.</p>
        </div>

        <x-card>
            <form action="{{ route('patients.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Patient Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full input-glass" required>
                        @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="icu_ward" class="block text-sm font-bold text-slate-700 mb-2">ICU Ward / Unit</label>
                        <input type="text" name="icu_ward" id="icu_ward" value="{{ old('icu_ward') }}" class="w-full input-glass" placeholder="e.g. Level 3 ICU — East Block" required>
                        @error('icu_ward') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="bed_number" class="block text-sm font-bold text-slate-700 mb-2">Bed Number</label>
                        <input type="text" name="bed_number" id="bed_number" value="{{ old('bed_number') }}" class="w-full input-glass" placeholder="e.g. E-12" required>
                        @error('bed_number') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="doctor_id" class="block text-sm font-bold text-slate-700 mb-2">Primary Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="w-full input-glass">
                            <option value="">-- Assign Doctor --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>Dr. {{ $doctor->name }}</option>
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
                                <option value="{{ $nurse->id }}" {{ old('nurse_id') == $nurse->id ? 'selected' : '' }}>{{ $nurse->name }}</option>
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
                                <option value="{{ $family->id }}" {{ (int) old('family_user_id') === (int) $family->id ? 'selected' : '' }}>{{ $family->name }} — {{ $family->email }}</option>
                            @endforeach
                        </select>
                        @error('family_user_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-bold text-slate-700 mb-2">Initial Status</label>
                        <select name="status" id="status" class="w-full input-glass" required>
                            <option value="stable" {{ old('status') == 'stable' ? 'selected' : '' }}>Stable</option>
                            <option value="critical" {{ old('status') == 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="improving" {{ old('status') == 'improving' ? 'selected' : '' }}>Improving</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="emergency_contact" class="block text-sm font-bold text-slate-700 mb-2">Emergency Contact Info</label>
                        <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}" class="w-full input-glass" placeholder="Name and reachable mobile number">
                        @error('emergency_contact') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <a href="{{ route('patients.index') }}" class="flex-1 btn-secondary text-center">Back to List</a>
                    <button type="submit" class="flex-1 btn-primary">Register Patient</button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
