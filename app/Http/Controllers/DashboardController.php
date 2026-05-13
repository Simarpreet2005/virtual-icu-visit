<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }
        if ($user->role === 'doctor') {
            return $this->doctorDashboard();
        }
        if ($user->role === 'nurse') {
            return $this->nurseDashboard();
        }

        return $this->familyDashboard();
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_appointments' => Appointment::count(),
            'active_patients' => Patient::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
        ];

        $users_by_role = User::query()
            ->select('role', DB::raw('count(*) as c'))
            ->groupBy('role')
            ->pluck('c', 'role');

        $appointments_by_status = Appointment::query()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $recent_users = User::latest()->take(5)->get();
        $recent_appointments = Appointment::with(['patient', 'user'])->latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'stats',
            'recent_users',
            'recent_appointments',
            'users_by_role',
            'appointments_by_status'
        ));
    }

    private function doctorDashboard()
    {
        $user = auth()->user();
        $patients = Patient::where('doctor_id', $user->id)->get();
        $upcoming_visits = Appointment::whereHas('patient', function ($query) use ($user) {
            $query->where('doctor_id', $user->id);
        })->where('scheduled_at', '>', now())->where('status', 'approved')->orderBy('scheduled_at')->get();

        $pending_approvals = Appointment::whereHas('patient', function ($query) use ($user) {
            $query->where('doctor_id', $user->id);
        })->where('status', 'pending')->get();

        $show_pending_actions = true;
        $can_add_patients = true;

        return view('dashboard.doctor', compact(
            'patients',
            'upcoming_visits',
            'pending_approvals',
            'show_pending_actions',
            'can_add_patients'
        ));
    }

    private function nurseDashboard()
    {
        $user = auth()->user();
        $patients = Patient::where('nurse_id', $user->id)->with('doctor')->orderBy('name')->get();

        $upcoming_visits = Appointment::where('status', 'approved')
            ->where('scheduled_at', '>', now())
            ->whereHas('patient', fn ($q) => $q->where('nurse_id', $user->id))
            ->with(['patient', 'user'])
            ->orderBy('scheduled_at')
            ->get();

        $pending_approvals = collect();
        $dashboard_title = 'Nurse Station';
        $dashboard_subtitle = 'Patients assigned to you and coordinated virtual visits.';
        $patients_section_title = 'My assigned patients';
        $show_pending_actions = false;
        $can_add_patients = false;

        return view('dashboard.doctor', compact(
            'patients',
            'upcoming_visits',
            'pending_approvals',
            'dashboard_title',
            'dashboard_subtitle',
            'patients_section_title',
            'show_pending_actions',
            'can_add_patients'
        ));
    }

    private function familyDashboard()
    {
        $user = auth()->user();
        $appointments = Appointment::where('user_id', $user->id)->with('patient')->orderBy('scheduled_at', 'desc')->get();
        $notifications = $user->unreadNotifications->take(5);

        $linkedPatients = Patient::query()
            ->where('family_user_id', $user->id)
            ->orderBy('name')
            ->get();

        $patientStatusCounts = $linkedPatients->groupBy('status')->map->count();

        $nextApprovedVisit = Appointment::query()
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('scheduled_at', '>', now())
            ->with('patient')
            ->orderBy('scheduled_at')
            ->first();

        return view('dashboard.family', compact(
            'appointments',
            'notifications',
            'linkedPatients',
            'patientStatusCounts',
            'nextApprovedVisit'
        ));
    }
}
