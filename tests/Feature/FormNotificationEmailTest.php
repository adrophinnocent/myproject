<?php

namespace Tests\Feature;

use App\Mail\AdminNewTripPlanMail;
use App\Mail\ContactNotification;
use App\Mail\TourInquiryMail;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormNotificationEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::set('site_email', 'twinasafaris@gmail.com');
    }

    public function test_contact_form_sends_notification_to_twinasafaris_gmail(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.send'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '+1234567890',
            'subject' => 'Safari Inquiry',
            'message' => 'Hello, I want to book a safari.',
        ]);

        $response->assertRedirect();
        Mail::assertSent(ContactNotification::class, function ($mail) {
            return $mail->hasTo('twinasafaris@gmail.com');
        });
    }

    public function test_plan_my_trip_form_sends_notification_to_twinasafaris_gmail(): void
    {
        Mail::fake();

        $response = $this->post(route('trip-plan.store'), [
            'name' => 'Jane Smith',
            'email' => 'janesmith@example.com',
            'phone' => '+1987654321',
            'nationality' => 'German',
            'adults' => 2,
            'children' => 0,
            'message' => 'Plan a 7-day trip for us.',
        ]);

        $response->assertRedirect();
        Mail::assertSent(AdminNewTripPlanMail::class, function ($mail) {
            return $mail->hasTo('twinasafaris@gmail.com');
        });
    }

    public function test_custom_safari_form_sends_notification_to_twinasafaris_gmail(): void
    {
        Mail::fake();

        $response = $this->post(route('custom-safari.store'), [
            'full_name' => 'Alex Brown',
            'email' => 'alex@example.com',
            'phone' => '+1555000111',
            'adults' => 2,
            'children' => 1,
            'duration_days' => 5,
            'trip_type' => 'Safari & Beach',
            'special_requests' => 'Customized honeymoon trip',
        ]);

        $response->assertRedirect();
        Mail::assertSent(TourInquiryMail::class, function ($mail) {
            return $mail->hasTo('twinasafaris@gmail.com');
        });
    }
}
