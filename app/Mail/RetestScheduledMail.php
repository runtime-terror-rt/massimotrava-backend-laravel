<?php

namespace App\Mail;

use App\Models\ScheduleRetest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RetestScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    // Ensure your constructor signature mapping is exactly tracking like this
    public function __construct(ScheduleRetest $schedule)
    {
        $this->schedule = $schedule;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '👉 Vyralabs: Your Biomarker Retest Has Been Scheduled!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.retest_scheduled',
        );
    }
}