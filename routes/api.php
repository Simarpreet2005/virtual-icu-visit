<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/patients', [ApiController::class, 'patients']);
    Route::get('/appointments', [ApiController::class, 'appointments']);
    Route::get('/notifications', [ApiController::class, 'notifications']);
});
