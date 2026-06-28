<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthSmartInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_insight_id',
        'user_id',
        'title',
        'body',
        'impact_label',
        'priority',
        'badge_label',
        'badge_type',
        'sort_order',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function healthInsight(): BelongsTo
    {
        return $this->belongsTo(HealthInsight::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}