<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 text-center lg:text-left">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Request Virtual Visit</h1>
            <p class="text-slate-500">Choose a patient linked to your profile and propose a time.</p>
        </div>

        @if($patients->isEmpty())
            <x-card>
                <div class="text-center py-10 max-w-md mx-auto">
                    <x-health-illustration class="max-w-xs mx-auto mb-6 opacity-90" />
                    <h3 class="text-lg font-bold text-slate-800 mb-2">No linked patients</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Ask your care team to register the patient and assign you as <strong>primary family contact</strong> before you can submit visit requests.</p>
                    <a href="{{ route('dashboard') }}" class="inline-block mt-6 btn-secondary">Back to dashboard</a>
                </div>
            </x-card>
        @else
            <x-card>
                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="patient_id" class="block text-sm font-bold text-slate-700 mb-2">Patient</label>
                        <select name="patient_id" id="patient_id" class="w-full input-glass" required>
                            <option value="">-- Choose patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ (int) old('patient_id') === (int) $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} — {{ $patient->icu_ward }}, Bed {{ $patient->bed_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="scheduled_at" class="block text-sm font-bold text-slate-700 mb-2">Preferred date and time</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}" class="w-full input-glass" required>
                        @error('scheduled_at') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-bold text-slate-700 mb-2">Notes for the care team (optional)</label>
                        <textarea name="notes" id="notes" rows="4" class="w-full input-glass" placeholder="Topics you would like to discuss during the visit">{{ old('notes') }}</textarea>
                        @error('notes') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 flex gap-4">
                        <a href="{{ route('dashboard') }}" class="flex-1 btn-secondary text-center">Cancel</a>
                        <button type="submit" class="flex-1 btn-primary">Submit request</button>
                    </div>
                </form>
            </x-card>

            <div class="mt-8 glass-panel p-6 text-slate-600">
                <h4 class="font-bold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Before you submit
                </h4>
                <ul class="text-sm space-y-2 list-disc pl-5">
                    <li>Visits require approval from the assigned ICU physician.</li>
                    <li>Slots near existing pending or approved visits may be declined automatically.</li>
                    <li>Use a stable connection with a working camera and microphone.</li>
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>
