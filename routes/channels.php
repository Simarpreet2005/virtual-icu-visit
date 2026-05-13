<?php

use App\Models\Appointment;
use App\Support\VideoRoomGate;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('video-room.{roomId}', function ($user, string $roomId) {
    $appointment = Appointment::query()
        ->where('room_id', $roomId)
        ->with('patient')
        ->first();

    if (! $appointment || $appointment->status !== 'approved') {
        return false;
    }

    if (! VideoRoomGate::allows($user, $appointment)) {
        return false;
    }

    return ['id' => $user->id, 'name' => $user->name];
});
