<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Patient::with(['doctor', 'nurse']);

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        } elseif ($user->isNurse()) {
            $query->where('nurse_id', $user->id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search').'%');
        }

        $patients = $query->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $this->authorizeCreate();

        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $nurses = User::where('role', 'nurse')->orderBy('name')->get();
        $families = User::where('role', 'family')->where('is_active', true)->orderBy('name')->get();

        return view('patients.create', compact('doctors', 'nurses', 'families'));
    }

    public function store(Request $request)
    {
        $this->authorizeCreate();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icu_ward' => 'required|string|max:255',
            'bed_number' => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:users,id',
            'nurse_id' => 'nullable|exists:users,id',
            'family_user_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:stable,critical,improving',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        $this->assertValidStaffIds($validated);
        $this->assertValidFamilyUserId($validated['family_user_id'] ?? null);

        $user = auth()->user();
        if ($user->isNurse()) {
            $validated['nurse_id'] = $user->id;
        }

        $patient = Patient::create($validated);

        LoggingService::log('create_patient', "Registered new patient: {$patient->name}", ['patient_id' => $patient->id]);

        return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $patient->load(['doctor', 'nurse', 'familyUser', 'appointments.user', 'reports']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $this->authorizeWritePatient();

        $this->authorizePatientAccess($patient);

        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        $nurses = User::where('role', 'nurse')->orderBy('name')->get();
        $families = User::where('role', 'family')->where('is_active', true)->orderBy('name')->get();

        return view('patients.edit', compact('patient', 'doctors', 'nurses', 'families'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorizeWritePatient();

        $this->authorizePatientAccess($patient);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icu_ward' => 'required|string|max:255',
            'bed_number' => 'required|string|max:255',
            'doctor_id' => 'nullable|exists:users,id',
            'nurse_id' => 'nullable|exists:users,id',
            'family_user_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:stable,critical,improving',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        $this->assertValidStaffIds($validated);
        $this->assertValidFamilyUserId($validated['family_user_id'] ?? null);

        if (auth()->user()->isNurse()) {
            unset($validated['nurse_id']);
        }

        $patient->update($validated);

        LoggingService::log('update_patient', "Updated patient info for: {$patient->name}", ['patient_id' => $patient->id]);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only administrators may remove patient records.');
        }

        LoggingService::log('delete_patient', "Removed patient: {$patient->name}", ['patient_id' => $patient->id]);

        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient removed successfully.');
    }

    private function authorizeCreate(): void
    {
        $this->authorizeWritePatient();
    }

    private function authorizeWritePatient(): void
    {
        $user = auth()->user();
        if ($user->isAdmin() || $user->isDoctor()) {
            return;
        }

        abort(403);
    }

    private function authorizePatientAccess(Patient $patient): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isDoctor() && (int) $patient->doctor_id === (int) $user->id) {
            return;
        }

        if ($user->isNurse() && (int) $patient->nurse_id === (int) $user->id) {
            return;
        }

        abort(403, 'You are not authorized to view this patient.');
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function assertValidStaffIds(array $validated): void
    {
        if (! empty($validated['doctor_id'])) {
            $role = User::whereKey($validated['doctor_id'])->value('role');
            if ($role !== 'doctor') {
                abort(422, 'Invalid doctor assignment.');
            }
        }
        if (! empty($validated['nurse_id'])) {
            $role = User::whereKey($validated['nurse_id'])->value('role');
            if ($role !== 'nurse') {
                abort(422, 'Invalid nurse assignment.');
            }
        }
    }

    private function assertValidFamilyUserId(null|string|int $familyUserId): void
    {
        if ($familyUserId === null || $familyUserId === '') {
            return;
        }

        $role = User::whereKey($familyUserId)->value('role');
        if ($role !== 'family') {
            abort(422, 'Primary family contact must be a family-role account.');
        }
    }
}
