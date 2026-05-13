<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(): View
    {
        $users_by_role = User::query()
            ->select('role', DB::raw('count(*) as c'))
            ->groupBy('role')
            ->pluck('c', 'role');

        $appointments_by_status = Appointment::query()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $totals = [
            'users' => User::count(),
            'patients' => Patient::count(),
            'appointments' => Appointment::count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        return view('admin.analytics', compact('users_by_role', 'appointments_by_status', 'totals'));
    }
}
