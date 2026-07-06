<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        $itemName = $this->booking->tour->title ?? $this->booking->safari->title ?? 'Reservation';

        return new Envelope(
            subject: 'ALERT: New Booking Received - ' . $itemName . ' (' . $this->booking->booking_reference . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-booking-notification',
        );
    }
}
