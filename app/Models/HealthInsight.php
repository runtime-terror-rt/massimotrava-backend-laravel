<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'longevity_score',
        'previous_score',
        'score_improvement',
        'score_since',
        'biological_age_offset',
        'sleep_index',
        'cardio_fitness',
        'markers_optimal',
        'markers_total',
        'kit_number',
        'test_date',
        'analysis_status',
        'alert_message',
        'retest_reminder_date',
        'retest_note',
    ];

    protected $casts = [
        'score_since'          => 'date',
        'test_date'            => 'date',
        'retest_reminder_date' => 'date',
        'biological_age_offset'=> 'float',
        'score_improvement'    => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function biomarkers(): HasMany
    {
        return $this->hasMany(HealthBiomarker::class);
    }

    public function smartInsights(): HasMany
    {
        return $this->hasMany(HealthSmartInsight::class)->orderBy('sort_order');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatest($query)
    {
        return $query->orderByDesc('test_date');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function getAnalysisBadgeLabelAttribute(): string
    {
        return match ($this->analysis_status) {
            'complete'   => 'Analysis complete',
            'processing' => 'Processing…',
            default      => 'Pending',
        };
    }

    public function getUpdatedAgoAttribute(): string
    {
        return $this->updated_at?->diffForHumans() ?? '—';
    }

    public function getPrimaryFocusBiomarkersAttribute()
    {
        return $this->biomarkers->where('focus_category', 'primary_focus');
    }

    public function getImprovingBiomarkersAttribute()
    {
        return $this->biomarkers->where('focus_category', 'improving');
    }

    public function getStableBiomarkersAttribute()
    {
        return $this->biomarkers->where('focus_category', 'stable');
    }
}