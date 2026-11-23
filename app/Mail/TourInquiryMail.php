<?php

namespace App\Mail;

use App\Models\TourInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TourInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $name = null;

    public ?string $tourTitle = null;

    public ?string $adminUrl = null;

    public ?string $inquiryMessage = null;

    /**
     * Accepts either a TourInquiry model (tour inquiry form) or the raw
     * name/email/phone/message arguments used by the custom safari form.
     */
    public function __construct(
        public TourInquiry|string|null $inquiry = null,
        public ?string $email = null,
        public ?string $phone = null,
        ?string $message = null,
    ) {
        if ($inquiry instanceof TourInquiry) {
            $this->name = $inquiry->name;
            $this->email = $inquiry->email;
            $this->phone = $inquiry->phone;
            $this->inquiryMessage = $inquiry->message;
            $this->tourTitle = $inquiry->tour?->title;

            if ($inquiry->exists) {
                $this->adminUrl = route('admin.inquiries.show', $inquiry);
            }
        } else {
            $this->name = $inquiry;
            $this->inquiryMessage = $message;
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->tourTitle
                ? 'New Tour Inquiry: '.$this->tourTitle
                : 'New Safari Inquiry from '.($this->name ?? 'a customer'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tour-inquiry',
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
