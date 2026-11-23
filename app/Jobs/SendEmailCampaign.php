<?php

namespace App\Jobs;

use App\Mail\NewsletterCampaignMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 600;

    public function __construct(
        public string $subject,
        public string $content,
    ) {}

    public function handle(): void
    {
        $sent = 0;

        NewsletterSubscriber::where('active', true)
            ->orderBy('id')
            ->chunkById(100, function ($subscribers) use (&$sent) {
                foreach ($subscribers as $subscriber) {
                    try {
                        Mail::to($subscriber->email)->send(
                            new NewsletterCampaignMail($this->subject, $this->content, $subscriber->name, $subscriber->unsubscribe_url)
                        );
                        $sent++;
                    } catch (\Throwable $e) {
                        Log::error("Campaign mail failed for {$subscriber->email}: ".$e->getMessage());
                    }
                }
            });

        Log::info("Email campaign '{$this->subject}' sent to {$sent} subscribers.");
    }
}
