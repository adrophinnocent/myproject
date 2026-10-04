<?php

namespace App\Mail;

use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ClientProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    public Proposal $proposal;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Personalized Safari Proposal: ' . $this->proposal->title . ' - Twina Safaris',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-proposal',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('proposals.pdf', ['proposal' => $this->proposal])
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Safari-Proposal-' . Str::slug($this->proposal->client_name) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
