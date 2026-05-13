<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VideoCallController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Medical Reports
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    Route::middleware(['role:family'])->group(function () {
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    // Video Call (authorized family / doctor / nurse only)
    Route::get('/video-call/{room_id}', [VideoCallController::class, 'show'])->name('video.call');

    // Patient Management — list for clinical roles; literal /patients/create before /patients/{patient}
    Route::middleware(['role:admin,doctor,nurse'])->group(function () {
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    });

    Route::middleware(['role:admin,doctor'])->group(function () {
        Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    });

    Route::middleware(['role:admin,doctor,nurse'])->group(function () {
        Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    });

    Route::middleware(['role:admin,doctor'])->group(function () {
        Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::patch('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::put('/patients/{patient}', [PatientController::class, 'update']);
    });

    // Patient Delete — Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    });

    // Appointment approval — Doctor (assigned patients) and Admin only (nurses coordinate, do not approve)
    Route::middleware(['role:admin,doctor'])->group(function () {
        Route::post('/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
        Route::post('/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('appointments.reject');
    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::get('activity', [AdminActivityLogController::class, 'index'])->name('activity.index');
        Route::get('analytics', AdminAnalyticsController::class)->name('analytics');
    });
});

require __DIR__.'/auth.php';
