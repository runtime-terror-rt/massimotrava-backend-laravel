<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Retest Reminder</title>
</head>
<body style="background-color: #0f172a; font-family: 'DM Sans', sans-serif; margin: 0; padding: 40px; color: #f1f5f9;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 30px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);">
        <h2 style="color: #f59e0b; border-bottom: 1px solid #334155; padding-bottom: 15px; margin-top: 0;">⏰ Retest Reminder!</h2>
        
        <p style="font-size: 15px; line-height: 1.6; color: #94a3b8;">
            Hello {{ $schedule->user->name ?? 'User' }}, eiti ekti gentle reminder je apnar biomarker retest session-ti **agamikal** onusthito hote jachche.
        </p>

        <div style="background: #0f172a; border-radius: 8px; padding: 20px; margin: 25px 0; border-left: 4px solid #f59e0b;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">📅 Retest Date:</td>
                    <td style="color: #ffffff; padding: 6px 0; font-weight: 600; font-size: 14px;">Tomorrow ({{ $schedule->retest_date->format('M d, Y') }})</td>
                </tr>
                @if($schedule->retest_time)
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">⏰ Retest Time:</td>
                    <td style="color: #ffffff; padding: 6px 0; font-weight: 600; font-size: 14px;">{{ \Carbon\Carbon::parse($schedule->retest_time)->format('h:i A') }}</td>
                </tr>
                @endif
                <tr>
                    <td style="color: #64748b; padding: 6px 0; font-size: 14px;">🧪 Kit Selected:</td>
                    <td style="color: #6366f1; padding: 6px 0; font-weight: 500; font-size: 14px;">{{ $schedule->kit->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        @if($schedule->user_notes)
        <p style="font-size: 14px; color: #cbd5e1; background: #334155; padding: 12px; border-radius: 6px; font-style: italic;">
            <strong>Preparation Notes:</strong> "{{ $schedule->user_notes }}"
        </p>
        @endif

        <p style="font-size: 14px; color: #64748b; text-align: center; margin-bottom: 0; border-top: 1px solid #334155; padding-top: 20px;">
            Thank you for staying committed to your tracking journey.<br>
            <strong>Team Vyralabs</strong>
        </p>
    </div>
</body>
</html>