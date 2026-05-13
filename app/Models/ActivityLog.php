<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'activity_logs';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'metadata',
    ];
}
