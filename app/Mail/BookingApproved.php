<?php

namespace App\Mail;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Great News! Your Safari Booking is Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-approved',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.itinerary-pdf', ['booking' => $this->booking]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'itinerary-' . $this->booking->booking_reference . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
