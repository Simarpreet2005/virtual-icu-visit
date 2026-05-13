<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get list of patients (Doctors/Admins only)
     */
    public function patients()
    {
        $this->authorizeMedicalApi();

        $user = auth()->user();
        $query = Patient::with(['doctor', 'nurse']);

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        } elseif ($user->isNurse()) {
            $query->where('nurse_id', $user->id);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    /**
     * Get list of appointments
     */
    public function appointments()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            $appointments = Appointment::with(['patient', 'user'])->get();
        } elseif ($user->role === 'nurse') {
            $appointments = Appointment::whereHas('patient', function ($q) use ($user) {
                $q->where('nurse_id', $user->id);
            })->with(['patient', 'user'])->get();
        } elseif ($user->role === 'doctor') {
            $appointments = Appointment::whereHas('patient', function ($q) use ($user) {
                $q->where('doctor_id', $user->id);
            })->with(['patient', 'user'])->get();
        } else {
            $appointments = Appointment::where('user_id', $user->id)->with('patient')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Get unread notifications
     */
    public function notifications()
    {
        return response()->json([
            'success' => true,
            'data' => auth()->user()->unreadNotifications
        ]);
    }

    private function authorizeMedicalApi(): void
    {
        if (! auth()->user()->isMedicalStaff()) {
            abort(403, 'Unauthorized API access.');
        }
    }
}
