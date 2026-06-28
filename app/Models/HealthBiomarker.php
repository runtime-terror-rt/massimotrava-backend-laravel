<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthBiomarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_insight_id',
        'user_id',
        'name',
        'slug',
        'value',
        'unit',
        'range_min',
        'range_max',
        'previous_value',
        'change_percent',
        'status',
        'focus_category',
        'priority',
        'note',
        'icon',
        'is_trending_up',
        'trend_points',
    ];

    protected $casts = [
        'trend_points'   => 'array',
        'is_trending_up' => 'boolean',
        'value'          => 'float',
        'previous_value' => 'float',
        'change_percent' => 'float',
        'range_min'      => 'float',
        'range_max'      => 'float',
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

    // ── Helpers ────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'optimal'        => 'Optimal',
            'needs_attention'=> 'Needs Attention',
            'low'            => 'Low',
            'high'           => 'High',
            default          => 'Unknown',
        };
    }

    public function getBadgeTypeAttribute(): string
    {
        return match ($this->status) {
            'optimal'         => 'good',
            'needs_attention' => 'high',
            'low'             => 'low',
            'high'            => 'high',
            default           => 'stable',
        };
    }

    public function getChangeLabelAttribute(): string
    {
        $sign = $this->change_percent >= 0 ? '+' : '';
        return "{$sign}{$this->change_percent}%";
    }

    // Range bar fill % (0–100)
    public function getRangeFillPercentAttribute(): float
    {
        if (!$this->range_min || !$this->range_max) return 50;
        $range = $this->range_max - $this->range_min;
        if ($range <= 0) return 50;
        $filled = (($this->value - $this->range_min) / $range) * 100;
        return max(0, min(100, round($filled)));
    }
}