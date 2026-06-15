<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $pdfPath;

    public function __construct(Booking $booking, $pdfPath = null)
    {
        $this->booking = $booking;
        $this->pdfPath = $pdfPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation: ' . $this->booking->tour->title . ' (Ref: ' . $this->booking->booking_reference . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-booking-confirmation',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $attachments[] = Attachment::fromPath($this->pdfPath)
                ->as('Itinerary-' . $this->booking->booking_reference . '.pdf')
                ->withMime('application/pdf');
        }
        return $attachments;
    }
}
