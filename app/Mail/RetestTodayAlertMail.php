<?php

namespace App\Mail;

use App\Models\ScheduleRetest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RetestTodayAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    public function __construct(ScheduleRetest $schedule)
    {
        $this->schedule = $schedule;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 Action Required: Your BioVue Biomarker Retest is TODAY!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.retest_today',
        );
    }
}