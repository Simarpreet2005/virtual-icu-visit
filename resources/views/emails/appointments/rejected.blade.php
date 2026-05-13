<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background-color: #e11d48; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 30px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #334155; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ICU Virtual Visit Update</h1>
        </div>
        <div class="content">
            <p>Dear {{ $appointment->user->name }},</p>
            <p>Regarding your virtual visit request for <strong>{{ $appointment->patient->name }}</strong> on {{ $appointment->scheduled_at->format('M d, Y') }}.</p>
            
            <p>Unfortunately, we are unable to approve this visit at the requested time. This could be due to medical procedures, patient stability, or ward requirements.</p>

            <p>You may try requesting a different time slot from your dashboard.</p>
            
            <a href="{{ route('appointments.create') }}" class="btn">Request New Slot</a>

            <p style="margin-top: 30px;">Thank you for your understanding.</p>
            <p>Best regards,<br>The ICU Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ICU Virtual Visit System. All rights reserved.
        </div>
    </div>
</body>
</html>
