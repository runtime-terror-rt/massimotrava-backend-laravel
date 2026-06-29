<?php

namespace App\Http\Controllers\ActionItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionItemController extends Controller
{
    public function index()
    {
        return view('user.action-item.index');
    }
    public function helthProfile()
    {
        return view('user.action-item.health-profile');
    }
    public function instruction()
    {
        return view('user.action-item.instructions');
    }
    public function questionnaire()
    {
        return view('user.action-item.questionnaire');
    }

    public function storeHelthProfile(Request $request)
    {
        $validated = $request->validate([
            'age'                => 'required|integer|min:18|max:90',
            'biological_sex'     => 'required|string|in:Male,Female,Prefer not to say',
            'activity_level'     => 'required|string|in:sedentary,light,moderate,very_active',
            'health_goals'       => 'nullable|array',
            'health_goals.*'     => 'string|in:energy,heart,weight,hormones,longevity,performance',
            'medical_conditions' => 'nullable|array',
            'medical_conditions.*' => 'string|in:diabetes,hypertension,thyroid,cholesterol,none,prefer_not',
        ]);

        $user = Auth::user();

        $user->health_profile = $validated;
        $user->save();

        return redirect()->route('user.actionitem.index')
            ->with('success', 'Health profile saved successfully!');
    }


    public function storeKitQuestionnaire(Request $request)
    {
        $validated = $request->validate([
            'fasting_status'   => 'required|string|in:yes,no,partial,unsure',
            'collection_time'  => 'required|string|in:early_morning,morning,afternoon,evening',
            'medications'      => 'nullable|array',
            'medications.*'    => 'string|in:bp_meds,statins,thyroid_meds,hormones,supplements,none',
            'recent_illness'   => 'required|string|in:yes,no,mild,current',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        $user->kit_questionnaire = $validated;
        $user->save();

        return redirect()->route('user.actionitem.index')
            ->with('success', 'Kit questionnaire submitted successfully!');
    }

    public function markViewed(Request $request)
    {
        $user = Auth::user();

        $user->action_item_viewed = true;
        $user->save();

        return redirect()->back()->with('success', 'Successfully marked as viewed!');
    }
}
