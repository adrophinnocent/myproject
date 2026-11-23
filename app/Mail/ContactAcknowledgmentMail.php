<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAcknowledgmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name?: string, email?: string, subject?: string, message?: string}  $contact
     */
    public function __construct(public array $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your message - '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-acknowledgment',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
