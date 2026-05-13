<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Services\LoggingService;
use App\Mail\AppointmentApprovedMail;
use App\Mail\AppointmentRejectedMail;
use App\Notifications\AppointmentStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin' || $user->role === 'nurse') {
            $appointments = Appointment::with(['patient', 'user'])->latest()->paginate(15);
        } elseif ($user->role === 'doctor') {
            $appointments = Appointment::whereHas('patient', function($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })->with(['patient', 'user'])->latest()->paginate(15);
        } else {
            $appointments = Appointment::where('user_id', $user->id)->with('patient')->latest()->paginate(15);
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::query()
            ->where('family_user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('appointments.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => [
                'required',
                Rule::exists('patients', 'id')->where(fn ($q) => $q->where('family_user_id', auth()->id())),
            ],
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        // Conflict Validation: No other appointment for the same patient within 30 minutes
        $startTime = \Carbon\Carbon::parse($validated['scheduled_at'])->subMinutes(30);
        $endTime = \Carbon\Carbon::parse($validated['scheduled_at'])->addMinutes(30);

        $hasConflict = Appointment::where('patient_id', $validated['patient_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->whereBetween('scheduled_at', [$startTime, $endTime])
            ->exists();

        if ($hasConflict) {
            return back()->withErrors(['scheduled_at' => 'This patient already has a visit scheduled around this time. Please choose another slot.'])->withInput();
        }

        $appointment = Appointment::create($validated);

        LoggingService::activity([
            'action' => 'request_appointment',
            'description' => "Requested visit for patient ID {$appointment->patient_id}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Visit request submitted successfully.');
    }

    public function approve(Appointment $appointment)
    {
        $appointment->load(['user', 'patient']);
        $this->ensureCanApproveOrReject($appointment);

        $appointment->update([
            'status' => 'approved',
            'room_id' => (string) Str::uuid(),
        ]);

        // Reload to get the new room_id
        $appointment->refresh();

        // Send Email
        Mail::to($appointment->user->email)->send(new AppointmentApprovedMail($appointment));

        // Database Notification
        $appointment->user->notify(new AppointmentStatusNotification($appointment, 'approved'));

        LoggingService::activity([
            'action' => 'approve_appointment',
            'description' => "Approved visit ID {$appointment->id} for patient {$appointment->patient->name}",
            'ip_address' => request()->ip(),
            'metadata' => ['room_id' => $appointment->room_id],
        ]);

        return back()->with('success', 'Appointment approved and room generated.');
    }

    public function reject(Appointment $appointment)
    {
        $appointment->load(['user', 'patient']);
        $this->ensureCanApproveOrReject($appointment);

        $appointment->update(['status' => 'rejected']);

        // Send Email
        Mail::to($appointment->user->email)->send(new AppointmentRejectedMail($appointment));

        // Database Notification
        $appointment->user->notify(new AppointmentStatusNotification($appointment, 'rejected'));

        LoggingService::activity([
            'action' => 'reject_appointment',
            'description' => "Rejected visit ID {$appointment->id} for patient {$appointment->patient->name}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Appointment rejected.');
    }

    public function destroy(Appointment $appointment)
    {
        $user = auth()->user();
        if ((int) $user->id !== (int) $appointment->user_id && ! $user->isAdmin()) {
            abort(403, 'You may only cancel your own visit requests.');
        }

        LoggingService::activity([
            'action' => 'cancel_appointment',
            'description' => "Cancelled visit ID {$appointment->id}",
            'ip_address' => request()->ip(),
        ]);

        $appointment->delete();
        return back()->with('success', 'Appointment cancelled.');
    }

    private function ensureCanApproveOrReject(Appointment $appointment): void
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return;
        }
        if ($user->role === 'doctor') {
            $doctorId = $appointment->patient?->doctor_id;
            if (! $doctorId || (int) $doctorId !== (int) $user->id) {
                abort(403, 'You may only review visits for patients assigned to you.');
            }

            return;
        }

        abort(403, 'Unauthorized action.');
    }
}
