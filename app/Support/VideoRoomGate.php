<?php

namespace App\Support;

use App\Models\Appointment;
use App\Models\User;

final class VideoRoomGate
{
    public static function allows(User $user, Appointment $appointment): bool
    {
        if ($user->role === 'admin') {
            return false;
        }

        $patient = $appointment->patient;

        if ($user->role === 'nurse' && (int) $patient->nurse_id === (int) $user->id) {
            return true;
        }

        if ((int) $user->id === (int) $appointment->user_id) {
            return true;
        }

        if ($patient->doctor_id && (int) $user->id === (int) $patient->doctor_id) {
            return true;
        }

        return false;
    }
}
