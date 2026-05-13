<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CallLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'call_logs';

    public $timestamps = true;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'room_id',
        'action',
        'timestamp',
        'duration_seconds',
        'participants',
        'started_at',
        'ended_at',
        'quality_metrics',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
