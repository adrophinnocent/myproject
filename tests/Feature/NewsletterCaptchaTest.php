<?php

namespace Tests\Feature;

use App\Mail\NewsletterConfirmationMail;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterCaptchaTest extends TestCase
{
    use RefreshDatabase;

    public function test_captcha_cannot_be_bypassed_by_omitting_the_token(): void
    {
        Setting::set('recaptcha_secret_key', 'test-secret');

        $response = $this->post(route('newsletter.subscribe'), [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
        ]);

        $response->assertSessionHasErrors('captcha');
        $this->assertDatabaseMissing('newsletter_subscribers', ['email' => 'bot@example.com']);
    }

    public function test_valid_captcha_creates_pending_subscription(): void
    {
        Mail::fake();
        Setting::set('recaptcha_secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true, 'score' => 0.9]),
        ]);

        $response = $this->post(route('newsletter.subscribe'), [
            'name' => 'Real Traveler',
            'email' => 'traveler@example.com',
            'g-recaptcha-response' => 'valid-token',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'traveler@example.com',
            'active' => false,
        ]);
        Mail::assertSent(NewsletterConfirmationMail::class);
    }

    public function test_failed_captcha_blocks_subscription(): void
    {
        Setting::set('recaptcha_secret_key', 'test-secret');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false]),
        ]);

        $response = $this->post(route('newsletter.subscribe'), [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'g-recaptcha-response' => 'bad-token',
        ]);

        $response->assertSessionHasErrors('captcha');
        $this->assertDatabaseMissing('newsletter_subscribers', ['email' => 'bot@example.com']);
    }

    public function test_subscription_works_when_captcha_is_not_configured(): void
    {
        Mail::fake();

        $response = $this->post(route('newsletter.subscribe'), [
            'name' => 'Real Traveler',
            'email' => 'traveler@example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'traveler@example.com']);
    }
}
