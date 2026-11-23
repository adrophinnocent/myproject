<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\TripPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_reference_is_not_a_valid_public_token(): void
    {
        $booking = Booking::create([
            'booking_reference' => 'TWN-2026-ABCD12',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'total_price' => 1000.00,
            'status' => 'approved',
            'payment_status' => 'unpaid',
        ]);

        $this->get('/booking/status/'.$booking->booking_reference)->assertNotFound();
        $this->get('/booking/status/'.$booking->token)->assertOk();
    }

    public function test_trip_plan_id_is_not_a_valid_public_token(): void
    {
        $plan = TripPlan::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'nationality' => 'German',
            'adults' => 2,
            'children' => 0,
            'status' => 'sent',
        ]);

        $this->get('/trip-plan/'.$plan->id)->assertNotFound();
        $this->get('/trip-plan/'.$plan->token)->assertOk();
    }

    public function test_non_admin_email_allowlist_is_ignored(): void
    {
        $user = User::create([
            'name' => 'Impersonator',
            'email' => 'twinasafaris@gmail.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_repeated_invalid_booking_tokens_are_rate_limited(): void
    {
        $lastStatus = null;

        for ($i = 0; $i < 32; $i++) {
            $response = $this->get('/booking/status/invalid-token-'.$i);
            $lastStatus = $response->getStatusCode();
        }

        $this->assertSame(429, $lastStatus);
    }
}
