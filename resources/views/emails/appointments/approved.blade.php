<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background-color: #2563eb; color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 30px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .status-badge { display: inline-block; padding: 4px 12px; background-color: #ecfdf5; color: #059669; border-radius: 6px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ICU Virtual Visit</h1>
        </div>
        <div class="content">
            <p>Dear {{ $appointment->user->name }},</p>
            <p>We are pleased to inform you that your virtual visit request for <strong>{{ $appointment->patient->name }}</strong> has been approved.</p>
            
            <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 0;"><strong>Scheduled Time:</strong> {{ $appointment->scheduled_at->format('l, F d, Y \a\t H:i') }}</p>
                <p style="margin: 10px 0 0 0;"><strong>Room ID:</strong> {{ $appointment->room_id }}</p>
            </div>

            <p>You can join the visit directly from your dashboard at the scheduled time.</p>
            
            <a href="{{ route('video.call', $appointment->room_id) }}" class="btn">Join Virtual Visit</a>

            <p style="margin-top: 30px;">Thank you for your patience.</p>
            <p>Best regards,<br>The ICU Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ICU Virtual Visit System. All rights reserved.
        </div>
    </div>
</body>
</html>
