<?php

namespace App\Mail;

use App\Models\TripPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewTripPlanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $plan;

    public function __construct(TripPlan $plan)
    {
        $this->plan = $plan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Trip Plan Request from ' . $this->plan->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-trip-plan',
        );
    }
}
