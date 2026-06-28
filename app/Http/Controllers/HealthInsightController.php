<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthInsightResource;
use App\Http\Resources\HealthBiomarkerResource;
use App\Http\Resources\HealthSmartInsightResource;
use App\Models\HealthInsight;
use App\Models\HealthBiomarker;
use App\Models\HealthSmartInsight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class HealthInsightController extends Controller
{
    
    public function index(Request $request): JsonResponse
    {
        $insight = HealthInsight::with(['biomarkers', 'smartInsights'])
            ->forUser(auth()->id())
            ->latest()
            ->first();

        if (!$insight) {
            return response()->json([
                'success' => false,
                'message' => 'No health insights found. Please complete your first test kit.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => new HealthInsightResource($insight),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $insight = HealthInsight::with(['biomarkers', 'smartInsights'])
            ->forUser(auth()->id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new HealthInsightResource($insight),
        ]);
    }

    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Insight
            'longevity_score'       => 'required|integer|min:0|max:100',
            'previous_score'        => 'required|integer|min:0|max:100',
            'score_improvement'     => 'required|integer',
            'score_since'           => 'nullable|date',
            'biological_age_offset' => 'nullable|numeric',
            'sleep_index'           => 'nullable|integer|min:0|max:100',
            'cardio_fitness'        => 'nullable|string|max:30',
            'markers_optimal'       => 'nullable|integer|min:0',
            'markers_total'         => 'nullable|integer|min:0',
            'kit_number'            => 'nullable|string|max:50',
            'test_date'             => 'nullable|date',
            'analysis_status'       => ['nullable', Rule::in(['pending','processing','complete'])],
            'alert_message'         => 'nullable|string|max:255',
            'retest_reminder_date'  => 'nullable|date',
            'retest_note'           => 'nullable|string|max:255',

            // Biomarkers array
            'biomarkers'                        => 'nullable|array',
            'biomarkers.*.name'                 => 'required|string|max:100',
            'biomarkers.*.slug'                 => 'required|string|max:100',
            'biomarkers.*.value'                => 'required|numeric',
            'biomarkers.*.unit'                 => 'nullable|string|max:30',
            'biomarkers.*.range_min'            => 'nullable|numeric',
            'biomarkers.*.range_max'            => 'nullable|numeric',
            'biomarkers.*.previous_value'       => 'nullable|numeric',
            'biomarkers.*.change_percent'       => 'nullable|numeric',
            'biomarkers.*.status'               => ['nullable', Rule::in(['optimal','needs_attention','low','high'])],
            'biomarkers.*.focus_category'       => ['nullable', Rule::in(['primary_focus','improving','stable'])],
            'biomarkers.*.priority'             => ['nullable', Rule::in(['high','normal','low'])],
            'biomarkers.*.note'                 => 'nullable|string',
            'biomarkers.*.icon'                 => 'nullable|string|max:10',
            'biomarkers.*.is_trending_up'       => 'nullable|boolean',
            'biomarkers.*.trend_points'         => 'nullable|array',

            // Smart insights array
            'smart_insights'                    => 'nullable|array',
            'smart_insights.*.title'            => 'required|string|max:255',
            'smart_insights.*.body'             => 'required|string',
            'smart_insights.*.impact_label'     => 'nullable|string|max:255',
            'smart_insights.*.priority'         => ['nullable', Rule::in(['high','normal','low'])],
            'smart_insights.*.badge_label'      => 'nullable|string|max:50',
            'smart_insights.*.badge_type'       => ['nullable', Rule::in(['high','good','stable','normal'])],
            'smart_insights.*.sort_order'       => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->id();

            $insight = HealthInsight::create(array_merge(
                collect($validated)->except(['biomarkers', 'smart_insights'])->toArray(),
                ['user_id' => $userId]
            ));

            // Biomarkers save
            if (!empty($validated['biomarkers'])) {
                foreach ($validated['biomarkers'] as $bm) {
                    $insight->biomarkers()->create(array_merge($bm, ['user_id' => $userId]));
                }
            }

            // Smart insights save
            if (!empty($validated['smart_insights'])) {
                foreach ($validated['smart_insights'] as $index => $si) {
                    $insight->smartInsights()->create(array_merge($si, [
                        'user_id'    => $userId,
                        'sort_order' => $si['sort_order'] ?? $index,
                    ]));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Health insight created successfully.',
                'data'    => new HealthInsightResource($insight->load(['biomarkers', 'smartInsights'])),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('HealthInsight Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save health insight.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }



    public function update(Request $request, int $id): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'longevity_score'       => 'sometimes|integer|min:0|max:100',
            'previous_score'        => 'sometimes|integer|min:0|max:100',
            'score_improvement'     => 'sometimes|integer',
            'score_since'           => 'nullable|date',
            'biological_age_offset' => 'nullable|numeric',
            'sleep_index'           => 'nullable|integer|min:0|max:100',
            'cardio_fitness'        => 'nullable|string|max:30',
            'markers_optimal'       => 'nullable|integer|min:0',
            'markers_total'         => 'nullable|integer|min:0',
            'kit_number'            => 'nullable|string|max:50',
            'test_date'             => 'nullable|date',
            'analysis_status'       => ['nullable', Rule::in(['pending','processing','complete'])],
            'alert_message'         => 'nullable|string|max:255',
            'retest_reminder_date'  => 'nullable|date',
            'retest_note'           => 'nullable|string|max:255',
        ]);

        try {
            $insight->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Health insight updated.',
                'data'    => new HealthInsightResource($insight->load(['biomarkers', 'smartInsights'])),
            ]);
        } catch (\Exception $e) {
            Log::error('HealthInsight Update Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /api/health-insights/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);
        $insight->delete();

        return response()->json(['success' => true, 'message' => 'Insight deleted.']);
    }

    /**
     * GET /api/health-insights/{id}/biomarkers
     */
    public function biomarkers(int $id): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->with('biomarkers')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => HealthBiomarkerResource::collection($insight->biomarkers),
        ]);
    }

    /**
     * POST /api/health-insights/{id}/biomarkers
     */
    public function storeBiomarker(Request $request, int $id): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'slug'           => 'required|string|max:100',
            'value'          => 'required|numeric',
            'unit'           => 'nullable|string|max:30',
            'range_min'      => 'nullable|numeric',
            'range_max'      => 'nullable|numeric',
            'previous_value' => 'nullable|numeric',
            'change_percent' => 'nullable|numeric',
            'status'         => ['nullable', Rule::in(['optimal','needs_attention','low','high'])],
            'focus_category' => ['nullable', Rule::in(['primary_focus','improving','stable'])],
            'priority'       => ['nullable', Rule::in(['high','normal','low'])],
            'note'           => 'nullable|string',
            'icon'           => 'nullable|string|max:10',
            'is_trending_up' => 'nullable|boolean',
            'trend_points'   => 'nullable|array',
        ]);

        $bm = $insight->biomarkers()->create(array_merge($validated, ['user_id' => auth()->id()]));

        return response()->json([
            'success' => true,
            'data'    => new HealthBiomarkerResource($bm),
        ], 201);
    }

    /**
     * PUT /api/health-insights/{id}/biomarkers/{bmId}
     */
    public function updateBiomarker(Request $request, int $id, int $bmId): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);
        $bm = $insight->biomarkers()->findOrFail($bmId);

        $validated = $request->validate([
            'value'          => 'sometimes|numeric',
            'previous_value' => 'nullable|numeric',
            'change_percent' => 'nullable|numeric',
            'status'         => ['nullable', Rule::in(['optimal','needs_attention','low','high'])],
            'focus_category' => ['nullable', Rule::in(['primary_focus','improving','stable'])],
            'priority'       => ['nullable', Rule::in(['high','normal','low'])],
            'note'           => 'nullable|string',
            'trend_points'   => 'nullable|array',
            'is_trending_up' => 'nullable|boolean',
        ]);

        $bm->update($validated);

        return response()->json(['success' => true, 'data' => new HealthBiomarkerResource($bm)]);
    }

    /**
     * DELETE /api/health-insights/{id}/biomarkers/{bmId}
     */
    public function destroyBiomarker(int $id, int $bmId): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);
        $insight->biomarkers()->findOrFail($bmId)->delete();

        return response()->json(['success' => true, 'message' => 'Biomarker deleted.']);
    }

   
    /**
     * POST /api/health-insights/{id}/smart-insights
     */
    public function storeSmartInsight(Request $request, int $id): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'impact_label' => 'nullable|string|max:255',
            'priority'     => ['nullable', Rule::in(['high','normal','low'])],
            'badge_label'  => 'nullable|string|max:50',
            'badge_type'   => ['nullable', Rule::in(['high','good','stable','normal'])],
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $si = $insight->smartInsights()->create(array_merge($validated, ['user_id' => auth()->id()]));

        return response()->json(['success' => true, 'data' => new HealthSmartInsightResource($si)], 201);
    }

    /**
     * PUT /api/health-insights/{id}/smart-insights/{siId}
     */
    public function updateSmartInsight(Request $request, int $id, int $siId): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);
        $si = $insight->smartInsights()->findOrFail($siId);

        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'body'         => 'sometimes|string',
            'impact_label' => 'nullable|string|max:255',
            'priority'     => ['nullable', Rule::in(['high','normal','low'])],
            'badge_label'  => 'nullable|string|max:50',
            'badge_type'   => ['nullable', Rule::in(['high','good','stable','normal'])],
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $si->update($validated);

        return response()->json(['success' => true, 'data' => new HealthSmartInsightResource($si)]);
    }

    /**
     * DELETE /api/health-insights/{id}/smart-insights/{siId}
     */
    public function destroySmartInsight(int $id, int $siId): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);
        $insight->smartInsights()->findOrFail($siId)->delete();

        return response()->json(['success' => true, 'message' => 'Smart insight deleted.']);
    }

 
    
    public function sendRetestReminder(int $id): JsonResponse
    {
        $insight = HealthInsight::forUser(auth()->id())->findOrFail($id);

        // TODO: Notification::send() or Mail::send() 

        // $insight->user->notify(new RetestReminderNotification($insight));

        Log::info('Retest reminder sent', [
            'user_id'    => auth()->id(),
            'insight_id' => $insight->id,
            'retest_date'=> $insight->retest_reminder_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Retest reminder sent successfully.',
        ]);
    }
}
