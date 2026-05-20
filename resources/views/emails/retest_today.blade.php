<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="background-color: #0f172a; font-family: sans-serif; padding: 40px; color: #f1f5f9;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #1e293b; border-radius: 12px; padding: 30px; border: 1px solid #ef4444;">
        <h2 style="color: #ef4444; margin-top: 0;">🚨 Alert: Retest Session is Today!</h2>
        <p>Hello {{ $schedule->user->name ?? 'User' }},</p>
        <p>This is your final reminder for the scheduled session. Your booked biomarker retest tracking session is due **today** and must be completed within active lab hours.</p>
        
        <div style="background: #0f172a; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444; margin: 20px 0;">
            <strong>⏰ Scheduled Time:</strong> {{ $schedule->retest_time ? \Carbon\Carbon::parse($schedule->retest_time)->format('h:i A') : 'During Active Lab Hours' }}<br>
            <strong>🧪 Attached Kit:</strong> {{ $schedule->kit->name ?? 'N/A' }}
        </div>
        <p style="font-size: 13px; color: #64748b; text-align: center; border-top: 1px solid #334155; padding-top: 20px;">Team BioVue</p>
    </div>
</body>
</html>