<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

class CampaignAnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public Campaign $campaign;
    public NewsletterSubscriber $subscriber;

    public function __construct(Campaign $campaign, NewsletterSubscriber $subscriber)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->campaign->title,
        );
    }

    public function content(): Content
    {
        $unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', [
            'subscriber' => $this->subscriber->id,
        ]);

        return new Content(
            view: 'emails.campaign-announcement',
            with: [
                'campaign'       => $this->campaign,
                'unsubscribeUrl' => $unsubscribeUrl,
            ],
        );
    }
}