<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BiomarkerReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reports;
    public $user;
    public $pdf;

    public function __construct($reports, $user, $pdf)
    {
        $this->reports = $reports;
        $this->user = $user;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Your Biomarker Medical Report - ' . date('d M Y'))
                    ->view('emails.biomarker_report')
                    ->attachData($this->pdf->output(), 'medical_report.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}