<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_booking_request_and_receives_unique_reference(): void
    {
        $tour = Tour::create([
            'title' => '7 Days Kilimanjaro Trek',
            'slug' => '7-days-kilimanjaro-trek',
            'price' => 2000.00,
            'duration_days' => 7,
            'is_published' => true,
        ]);

        $response = $this->post(route('booking.store', $tour->slug), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'country' => 'United States',
            'number_of_adults' => 2,
            'number_of_children' => 0,
            'travel_date' => date('Y-m-d', strtotime('+30 days')),
            'special_requests' => 'Vegetarian food preferred',
            'payment_method' => 'bank_transfer',
        ]);

        $this->assertDatabaseHas('bookings', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'total_price' => 4000.00,
            'deposit_percentage' => 20.00,
            'deposit_required' => 800.00,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $booking = Booking::where('email', 'john@example.com')->first();
        $this->assertNotNull($booking->booking_reference);
        $this->assertStringStartsWith('TWN-', $booking->booking_reference);
        $this->assertNotNull($booking->token);

        $response->assertRedirect(route('booking.confirmation', $booking->token));
    }

    public function test_admin_can_approve_booking_and_set_deposit(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $booking = Booking::create([
            'booking_reference' => 'TWN-2026-001',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'number_of_adults' => 1,
            'total_price' => 1500.00,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.bookings.approve', $booking), [
            'deposit_percentage' => 25.00,
            'admin_notes' => 'Lodges confirmed for Jane',
        ]);

        $booking->refresh();

        $this->assertEquals('approved', $booking->status);
        $this->assertEquals(25.00, $booking->deposit_percentage);
        $this->assertEquals(375.00, $booking->deposit_required);

        $response->assertRedirect();
    }

    public function test_manual_payment_is_submitted_and_verified_by_admin(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $booking = Booking::create([
            'booking_reference' => 'TWN-2026-002',
            'first_name' => 'Alice',
            'last_name' => 'Walker',
            'email' => 'alice@example.com',
            'total_price' => 2000.00,
            'deposit_required' => 400.00,
            'deposit_paid' => 0.00,
            'status' => 'approved',
            'payment_status' => 'unpaid',
        ]);

        // Client submits a payment request with a bogus amount; server ignores it.
        $response = $this->post(route('booking.payment.process', $booking->token), [
            'gateway' => 'bank_transfer',
            'payment_type' => 'deposit',
            'amount' => 9999,
            'transaction_notes' => 'Bank slip #123',
        ]);

        $response->assertRedirect(route('booking.status', $booking->token));

        $payment = BookingPayment::where('booking_id', $booking->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(400.00, (float) $payment->amount);
        $this->assertEquals('pending', $payment->status);

        $booking->refresh();
        $this->assertEquals('unpaid', $booking->payment_status);

        // Admin verifies the payment.
        $adminResponse = $this->actingAs($admin)->post(route('admin.bookings.record-payment', $booking), [
            'amount' => 400.00,
            'gateway' => 'bank_transfer',
            'payment_type' => 'deposit',
            'notes' => 'Verified from bank statement',
        ]);

        $adminResponse->assertRedirect();

        $booking->refresh();
        $payment->refresh();

        $this->assertEquals('completed', $payment->status);
        $this->assertEquals(400.00, (float) $booking->deposit_paid);
        $this->assertEquals(1600.00, (float) $booking->balance_amount);
        $this->assertEquals('deposit_paid', $booking->status);
        $this->assertEquals('deposit_paid', $booking->payment_status);
    }
}
