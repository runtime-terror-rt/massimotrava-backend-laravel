<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\CampaignAnnouncementMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
{
    /**
     * Web Only Form Trigger
     */
    public function create()
    {
        return view('admin.campaigns.create');
    }

    /**
     * Display Campaigns List
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $campaigns = Campaign::activeCampaigns()->latest()->get();
            return response()->json([
                'status' => 'success',
                'data'   => $campaigns
            ], 200);
        }

        $campaigns = Campaign::latest()->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Store New Campaign Record
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'status'       => 'required|in:draft,active,paused',
            'action_url'   => 'nullable|url',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'banner_image' => 'nullable|string|max:255',
        ]);
    
        try {
            $campaign = Campaign::create([
                'title'        => $request->title,
                'slug'         => Str::slug($request->title) . '-' . time(),
                'description'  => $request->description,
                'banner_image' => $request->banner_image,
                'action_url'   => $request->action_url,
                'status'       => $request->status,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
            ]);
    
            if ($campaign->status === 'active') {
                $this->notifySubscribers($campaign);
            }
    
            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Campaign successfully launched into the system.',
                    'data'    => $campaign
                ], 201);
            }
    
            return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully!');
    
        } catch (\Exception $e) {
            \Log::error('Campaign Pipeline Error: ' . $e->getMessage());
    
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    
    private function notifySubscribers(Campaign $campaign): void
    {
        NewsletterSubscriber::where('is_active', true)
            ->chunk(100, function ($subscribers) use ($campaign) {
                foreach ($subscribers as $subscriber) {
                    Mail::to($subscriber->email)->queue(new CampaignAnnouncementMail($campaign, $subscriber));
                }
            });
    }

    
}
