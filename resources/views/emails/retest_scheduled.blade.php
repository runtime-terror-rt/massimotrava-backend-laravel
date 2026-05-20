<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Retest Scheduled</title>
</head>
<body style="background-color: #0f172a; font-family: 'DM Sans', sans-serif; margin: 0; padding: 40px; color: #f1f5f9;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 30px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);">
        <h2 style="color: #6366f1; border-bottom: 1px solid #334155; padding-bottom: 15px; margin-top: 0;">
            Hello, {{ $schedule->user->name ?? 'User' }}!
        </h2>
        
        <p style="font-size: 15px; line-height: 1.6; color: #94a3b8;">
            Your upcoming wellness tracking biomarker **Retest Session** has been successfully scheduled. Tracking consistency helps us calculate accurate wellness indexes.
        </p>

        <div style="background: #0f172a; border-radius: 8px; padding: 20px; margin: 25px 0; border-left: 4px solid #10b981;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">📅 Retest Date:</td>
                    <td style="color: #ffffff; padding: 6px 0; font-weight: 600; font-size: 14px;">{{ $schedule->retest_date->format('M d, Y') }}</td>
                </tr>
                @if($schedule->retest_time)
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">⏰ Retest Time:</td>
                    <td style="color: #ffffff; padding: 6px 0; font-weight: 600; font-size: 14px;">{{ \Carbon\Carbon::parse($schedule->retest_time)->format('h:i A') }}</td>
                </tr>
                @endif
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">🧪 Connected Kit:</td>
                    <td style="color: #6366f1; padding: 6px 0; font-weight: 500; font-size: 14px;">{{ $schedule->kit->name ?? 'Default Active Kit ID' }}</td>
                </tr>
                @if($schedule->user_notes)
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">📝 Instructions:</td>
                    <td style="color: #e2e8f0; padding: 6px 0; font-size: 13px; font-style: italic;">"{{ $schedule->user_notes }}"</td>
                </tr>
                @endif
            </table>
        </div>

        <p style="font-size: 14px; color: #64748b; text-align: center; margin-bottom: 0; border-top: 1px solid #334155; padding-top: 20px;">
            Thank you for staying committed to your digital health tracking journey.<br>
            <strong>Team Vyralabs</strong>
        </p>
    </div>
</body>
</html>