<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = collect();
        $mongoConnected = false;

        try {
            DB::connection('mongodb')->getMongoDB()->command(['ping' => 1]);
            $mongoConnected = true;
            $logs = ActivityLog::query()
                ->latest()
                ->take(100)
                ->get();
        } catch (\Throwable) {
            // Mongo unavailable — show empty; message in view
        }

        return view('admin.activity.index', compact('logs', 'mongoConnected'));
    }
}
