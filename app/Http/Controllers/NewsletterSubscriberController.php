<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App; 
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{

    public function subscribe(Request $request)
    {
        $request->validate([
            'newsletter_email' => 'required|email',
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $request->newsletter_email],
            [
                'locale'    => App::getLocale(),
                'source'    => 'homepage',
                'is_active' => true,
            ]
        );

        // Notify admin
        Mail::raw("New newsletter subscriber: {$subscriber->email}", function ($message) {
            $message->to('hello@vyralabs.health')
                    ->subject('New Newsletter Subscriber - Vyralabs');
        });

        return back()->with('newsletter_success', "You're subscribed! Check your inbox.");
    }


    // admin dashboard
     public function index(Request $request)
    {
        $query = NewsletterSubscriber::query()->latest();
 
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->string('search') . '%');
        }
 
        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status') === 'active');
        }
 
        $subscribers = $query->paginate(20)->withQueryString();
 
        $stats = [
            'total'    => NewsletterSubscriber::count(),
            'active'   => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];
 
        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }
 
    /**
     * PATCH /admin/newsletter/{subscriber}/toggle
     * Manually flip a subscriber between active / unsubscribed.
     */
    public function toggle(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'is_active'        => !$subscriber->is_active,
            'unsubscribed_at'  => $subscriber->is_active ? now() : null,
        ]);
 
        return back()->with('success', 'Subscriber status updated.');
    }
 
    /**
     * DELETE /admin/newsletter/{subscriber}
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
 
        return back()->with('success', 'Subscriber removed.');
    }
 
    /**
     * GET /admin/newsletter/export
     * Download all subscribers as a CSV file.
     */
    public function export(): StreamedResponse
    {
        $filename = 'newsletter-subscribers-' . now()->format('Y-m-d') . '.csv';
 
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
 
        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Locale', 'Source', 'Status', 'Subscribed At']);
 
            NewsletterSubscriber::query()->orderBy('created_at')->chunk(200, function ($subscribers) use ($handle) {
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [
                        $subscriber->email,
                        $subscriber->locale,
                        $subscriber->source,
                        $subscriber->is_active ? 'Active' : 'Unsubscribed',
                        $subscriber->created_at->format('Y-m-d H:i'),
                    ]);
                }
            });
 
            fclose($handle);
        };
 
        return response()->stream($callback, 200, $headers);
    }

    
    public function unsubscribe(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'is_active'       => false,
            'unsubscribed_at' => now(),
        ]);

        return view('user.unsubscribed', ['email' => $subscriber->email]);
    }
}
