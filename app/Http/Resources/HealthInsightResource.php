<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// ── HealthInsightResource ──────────────────────────────────────────────────

class HealthInsightResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'longevity_score'      => $this->longevity_score,
            'previous_score'       => $this->previous_score,
            'score_improvement'    => $this->score_improvement,
            'score_since'          => $this->score_since?->format('M Y'),
            'biological_age_offset'=> $this->biological_age_offset,
            'sleep_index'          => $this->sleep_index,
            'cardio_fitness'       => $this->cardio_fitness,
            'markers_optimal'      => $this->markers_optimal,
            'markers_total'        => $this->markers_total,
            'kit_number'           => $this->kit_number,
            'test_date'            => $this->test_date?->format('M d, Y'),
            'analysis_status'      => $this->analysis_status,
            'analysis_badge_label' => $this->analysis_badge_label,
            'alert_message'        => $this->alert_message,
            'retest_reminder_date' => $this->retest_reminder_date?->format('M Y'),
            'retest_note'          => $this->retest_note,
            'updated_ago'          => $this->updated_ago,

            // Nested
            'biomarkers'           => HealthBiomarkerResource::collection(
                $this->whenLoaded('biomarkers')
            ),
            'smart_insights'       => HealthSmartInsightResource::collection(
                $this->whenLoaded('smartInsights')
            ),

            // Grouped biomarkers (convenience)
            'primary_focus'        => HealthBiomarkerResource::collection(
                $this->whenLoaded('biomarkers', fn() => $this->primary_focus_biomarkers)
            ),
            'improving'            => HealthBiomarkerResource::collection(
                $this->whenLoaded('biomarkers', fn() => $this->improving_biomarkers)
            ),
            'stable'               => HealthBiomarkerResource::collection(
                $this->whenLoaded('biomarkers', fn() => $this->stable_biomarkers)
            ),
        ];
    }
}


// ── HealthBiomarkerResource ───────────────────────────────────────────────

class HealthBiomarkerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'value'               => $this->value,
            'unit'                => $this->unit,
            'range_min'           => $this->range_min,
            'range_max'           => $this->range_max,
            'previous_value'      => $this->previous_value,
            'change_percent'      => $this->change_percent,
            'change_label'        => $this->change_label,
            'status'              => $this->status,
            'status_label'        => $this->status_label,
            'badge_type'          => $this->badge_type,
            'focus_category'      => $this->focus_category,
            'priority'            => $this->priority,
            'note'                => $this->note,
            'icon'                => $this->icon,
            'is_trending_up'      => $this->is_trending_up,
            'trend_points'        => $this->trend_points ?? [],
            'range_fill_percent'  => $this->range_fill_percent,
        ];
    }
}


// ── HealthSmartInsightResource ────────────────────────────────────────────

class HealthSmartInsightResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'body'         => $this->body,
            'impact_label' => $this->impact_label,
            'priority'     => $this->priority,
            'badge_label'  => $this->badge_label,
            'badge_type'   => $this->badge_type,
            'sort_order'   => $this->sort_order,
        ];
    }
}