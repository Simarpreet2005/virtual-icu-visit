<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\CallLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoggingService
{
    public static function log(string $action, string $description, array $metadata = []): void
    {
        self::activity([
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Write to MongoDB-backed activity_logs when the driver is available; never break the HTTP flow.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function activity(array $attributes): void
    {
        try {
            ActivityLog::create(array_merge([
                'user_id' => Auth::id(),
                'ip_address' => request()?->ip(),
            ], $attributes));
        } catch (\Throwable $e) {
            Log::warning('activity_log_failed', [
                'message' => $e->getMessage(),
                'action' => $attributes['action'] ?? null,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function callLog(array $attributes): void
    {
        try {
            CallLog::create($attributes);
        } catch (\Throwable $e) {
            Log::warning('call_log_failed', [
                'message' => $e->getMessage(),
                'room_id' => $attributes['room_id'] ?? null,
            ]);
        }
    }
}
