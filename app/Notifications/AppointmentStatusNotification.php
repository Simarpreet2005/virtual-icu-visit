<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public $appointment;
    public $status;

    public function __construct(Appointment $appointment, string $status)
    {
        $this->appointment = $appointment;
        $this->status = $status;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'patient_name' => $this->appointment->patient->name,
            'status' => $this->status,
            'message' => "Your visit request for {$this->appointment->patient->name} has been {$this->status}.",
            'action_url' => $this->status === 'approved' ? route('video.call', $this->appointment->room_id) : route('dashboard'),
        ];
    }

    public function toBroadcast($notifiable): array
    {
        return [
            'message' => "Your visit request for {$this->appointment->patient->name} has been {$this->status}.",
            'status' => $this->status,
            'action_url' => $this->status === 'approved' ? route('video.call', $this->appointment->room_id) : route('dashboard'),
        ];
    }
}
