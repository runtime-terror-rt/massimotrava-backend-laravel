<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSetting extends Model
{
    protected $table = 'notification_settings'; 

    protected $fillable = [
        'user_id',
        'push_notification',
        'email_notification',
        'email_alerts',
        'sms_notification',
        'lab_kit_updates',
        'weekly_analytics',
        'generate_report'
    ];

    protected $casts = [
        'push_notification' => 'boolean',
        'email_notification' => 'boolean',
        'email_alerts' => 'boolean',
        'sms_notification' => 'boolean',
        'lab_kit_updates' => 'boolean',
        'weekly_analytics' => 'boolean',
        'generate_report' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}