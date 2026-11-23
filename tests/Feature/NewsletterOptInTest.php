<?php

namespace Tests\Feature;

use App\Mail\NewsletterConfirmationMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterOptInTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_requires_email_confirmation_before_activation(): void
    {
        Mail::fake();

        $this->post(route('newsletter.subscribe'), [
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'jane@example.com')->firstOrFail();

        $this->assertFalse($subscriber->active);
        $this->assertNull($subscriber->confirmed_at);
        $this->assertNotEmpty($subscriber->confirmation_token);
        $this->assertNotEmpty($subscriber->unsubscribe_token);

        Mail::assertSent(NewsletterConfirmationMail::class, function ($mail) use ($subscriber) {
            return $mail->hasTo('jane@example.com')
                && str_contains($mail->render(), $subscriber->confirmation_token);
        });
    }

    public function test_clicking_confirmation_link_activates_subscription(): void
    {
        Mail::fake();

        $this->post(route('newsletter.subscribe'), [
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'jane@example.com')->firstOrFail();

        $response = $this->get(route('newsletter.confirm', $subscriber->confirmation_token));

        $response->assertOk();
        $subscriber->refresh();
        $this->assertTrue($subscriber->active);
        $this->assertNotNull($subscriber->confirmed_at);
        $this->assertNull($subscriber->confirmation_token);

        // The link cannot be reused.
        $this->get(route('newsletter.confirm', 'reused-token'))->assertRedirect(route('home'));
    }

    public function test_unsubscribe_link_deactivates_subscription(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'active' => true,
            'subscribed_at' => now(),
            'confirmed_at' => now(),
        ]);

        $response = $this->get(route('newsletter.unsubscribe', $subscriber->unsubscribe_token));

        $response->assertOk();
        $this->assertFalse($subscriber->refresh()->active);
    }

    public function test_invalid_unsubscribe_token_redirects_home(): void
    {
        $this->get(route('newsletter.unsubscribe', 'does-not-exist'))
            ->assertRedirect(route('home'));
    }

    public function test_already_confirmed_subscriber_is_not_emailed_again(): void
    {
        Mail::fake();

        NewsletterSubscriber::create([
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'active' => true,
            'subscribed_at' => now(),
            'confirmed_at' => now(),
        ]);

        $response = $this->post(route('newsletter.subscribe'), [
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
        ]);

        $response->assertSessionHas('success');
        Mail::assertNothingSent();
    }

    public function test_unconfirmed_subscriber_can_request_a_new_link(): void
    {
        Mail::fake();

        $this->post(route('newsletter.subscribe'), ['name' => 'Jane', 'email' => 'jane@example.com']);
        $firstToken = NewsletterSubscriber::where('email', 'jane@example.com')->value('confirmation_token');

        $this->post(route('newsletter.subscribe'), ['name' => 'Jane', 'email' => 'jane@example.com']);
        $secondToken = NewsletterSubscriber::where('email', 'jane@example.com')->value('confirmation_token');

        $this->assertNotSame($firstToken, $secondToken);
        $this->assertSame(1, NewsletterSubscriber::where('email', 'jane@example.com')->count());
        Mail::assertSent(NewsletterConfirmationMail::class, 2);
    }
}
