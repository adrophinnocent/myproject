<?php

namespace Tests\Feature;

use App\Jobs\SendEmailCampaign;
use App\Mail\BookingStatusChangedMail;
use App\Mail\ContactAcknowledgmentMail;
use App\Mail\TourInquiryMail;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\NewsletterSubscriber;
use App\Models\Tour;
use App\Models\TourInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EmailContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_tour_inquiry_mail_contains_customer_details(): void
    {
        $category = Category::create(['name' => 'Trekking', 'slug' => 'trekking']);
        $destination = Destination::create(['name' => 'Kilimanjaro', 'slug' => 'kilimanjaro']);
        $tour = Tour::create([
            'title' => 'Machame Route 7 Days',
            'slug' => 'machame-route-7-days',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'price' => 2000,
            'duration_days' => 7,
            'is_published' => true,
        ]);

        $inquiry = TourInquiry::create([
            'tour_id' => $tour->id,
            'name' => 'Alice Walker',
            'email' => 'alice@example.com',
            'phone' => '+255711111111',
            'message' => 'I would like to book the Machame route in June.',
            'status' => 'unread',
        ]);

        $html = (new TourInquiryMail($inquiry))->render();

        $this->assertStringContainsString('Alice Walker', $html);
        $this->assertStringContainsString('alice@example.com', $html);
        $this->assertStringContainsString('Machame route', $html);
        $this->assertStringContainsString('Machame Route 7 Days', $html);
    }

    public function test_custom_safari_inquiry_mail_contains_details(): void
    {
        $html = (new TourInquiryMail(
            'Bob Safari',
            'bob@example.com',
            '+255722222222',
            'Custom Safari Request: 5 Days Honeymoon'
        ))->render();

        $this->assertStringContainsString('Bob Safari', $html);
        $this->assertStringContainsString('bob@example.com', $html);
        $this->assertStringContainsString('Honeymoon', $html);
    }

    public function test_contact_acknowledgment_contains_customer_name_and_subject(): void
    {
        $html = (new ContactAcknowledgmentMail([
            'name' => 'Carol Danvers',
            'email' => 'carol@example.com',
            'subject' => 'Zanzibar honeymoon question',
            'message' => 'Do you offer beach extensions?',
        ]))->render();

        $this->assertStringContainsString('Carol Danvers', $html);
        $this->assertStringContainsString('Zanzibar honeymoon question', $html);
    }

    public function test_booking_status_mail_contains_reference_and_status(): void
    {
        $booking = Booking::create([
            'first_name' => 'Dave',
            'last_name' => 'Jones',
            'email' => 'dave@example.com',
            'number_of_adults' => 1,
            'number_of_children' => 0,
            'travel_date' => now()->addDays(20)->format('Y-m-d'),
            'total_price' => 1500,
            'status' => Booking::STATUS_DEPOSIT_PAID,
            'payment_status' => Booking::PAYMENT_DEPOSIT_PAID,
            'deposit_paid' => 300,
            'balance_amount' => 1200,
        ]);

        $html = (new BookingStatusChangedMail($booking))->render();

        $this->assertStringContainsString($booking->booking_reference, $html);
        $this->assertStringContainsString('Deposit Received', $html);
        $this->assertStringContainsString(route('booking.status', $booking->token), $html);
    }

    public function test_email_campaign_actually_dispatches_sending_job(): void
    {
        Queue::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        NewsletterSubscriber::create([
            'name' => 'Subscriber',
            'email' => 'sub@example.com',
            'active' => true,
            'subscribed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.email-campaigns.send'), [
            'subject' => 'Migration season is here!',
            'content' => '<p>Book your Serengeti safari now.</p>',
        ]);

        $response->assertSessionHas('success');
        Queue::assertPushed(SendEmailCampaign::class, function ($job) {
            return $job->subject === 'Migration season is here!';
        });
    }

    public function test_email_campaign_with_no_subscribers_does_not_dispatch(): void
    {
        Queue::fake();

        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.email-campaigns.send'), [
            'subject' => 'Hello',
            'content' => '<p>Hi</p>',
        ]);

        $response->assertSessionHas('error');
        Queue::assertNothingPushed();
    }
}
