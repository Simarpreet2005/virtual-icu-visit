<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\LoggingService;
use App\Support\VideoRoomGate;

class VideoCallController extends Controller
{
    public function show($room_id)
    {
        $appointment = Appointment::where('room_id', $room_id)
            ->with(['patient', 'user'])
            ->firstOrFail();

        $user = auth()->user();

        if ($appointment->status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'This meeting is not active.');
        }

        if (! VideoRoomGate::allows($user, $appointment)) {
            LoggingService::activity([
                'user_id' => $user->id,
                'action' => 'unauthorized_call_attempt',
                'description' => "Unauthorized attempt to join room {$room_id}",
                'ip_address' => request()->ip(),
            ]);
            abort(403, 'You are not authorized to join this meeting.');
        }

        LoggingService::callLog([
            'appointment_id' => $appointment->id,
            'user_id' => $user->id,
            'room_id' => $room_id,
            'action' => 'join',
            'timestamp' => now()->toDateTimeString(),
            'metadata' => [
                'user_name' => $user->name,
                'role' => $user->role,
                'patient_name' => $appointment->patient->name,
            ],
        ]);

        LoggingService::activity([
            'user_id' => $user->id,
            'action' => 'join_call',
            'description' => "Joined virtual visit for patient {$appointment->patient->name}",
            'ip_address' => request()->ip(),
        ]);

        return view('video.call', compact('appointment'));
    }
}
