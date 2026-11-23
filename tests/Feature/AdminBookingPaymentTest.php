<?php

namespace Tests\Feature;

use App\Mail\PaymentConfirmation;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminBookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    private function makeBooking(array $attributes = []): Booking
    {
        return Booking::create(array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '+255700000000',
            'number_of_adults' => 2,
            'number_of_children' => 0,
            'travel_date' => now()->addDays(30)->format('Y-m-d'),
            'total_price' => 1000,
            'deposit_required' => 200,
            'deposit_paid' => 0,
            'balance_amount' => 1000,
            'status' => Booking::STATUS_APPROVED,
            'payment_status' => Booking::PAYMENT_UNPAID,
        ], $attributes));
    }

    public function test_valid_deposit_payment_is_recorded(): void
    {
        $booking = $this->makeBooking();

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.record-payment', $booking), [
            'amount' => 200,
            'gateway' => 'bank_transfer',
            'payment_type' => 'deposit',
        ]);

        $response->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals(200.0, (float) $booking->deposit_paid);
        $this->assertEquals(800.0, (float) $booking->balance_amount);
        $this->assertSame(Booking::STATUS_DEPOSIT_PAID, $booking->status);
        $this->assertSame(Booking::PAYMENT_DEPOSIT_PAID, $booking->payment_status);

        $this->assertDatabaseHas('booking_payments', [
            'booking_id' => $booking->id,
            'amount' => 200,
            'status' => 'completed',
        ]);

        Mail::assertSent(PaymentConfirmation::class);
    }

    public function test_payment_exceeding_balance_is_rejected(): void
    {
        $booking = $this->makeBooking();

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.record-payment', $booking), [
            'amount' => 1500,
            'gateway' => 'cash',
            'payment_type' => 'full',
        ]);

        $response->assertSessionHasErrors('amount');

        $booking->refresh();
        $this->assertEquals(0.0, (float) $booking->deposit_paid);
        $this->assertSame(Booking::STATUS_APPROVED, $booking->status);
        $this->assertDatabaseCount('booking_payments', 0);
    }

    public function test_tiny_payment_does_not_mark_deposit_paid_when_deposit_is_higher(): void
    {
        $booking = $this->makeBooking();

        $this->actingAs($this->admin)->post(route('admin.bookings.record-payment', $booking), [
            'amount' => 1,
            'gateway' => 'cash',
            'payment_type' => 'deposit',
        ]);

        $booking->refresh();
        // A partial payment is tracked, but the booking is only "deposit paid"
        // once the required deposit has actually been covered.
        $this->assertEquals(1.0, (float) $booking->deposit_paid);
        $this->assertEquals(999.0, (float) $booking->balance_amount);
        $this->assertSame(Booking::STATUS_APPROVED, $booking->status);
    }

    public function test_full_payment_confirms_booking(): void
    {
        $booking = $this->makeBooking();

        $this->actingAs($this->admin)->post(route('admin.bookings.record-payment', $booking), [
            'amount' => 1000,
            'gateway' => 'bank_transfer',
            'payment_type' => 'full',
        ]);

        $booking->refresh();
        $this->assertSame(Booking::STATUS_CONFIRMED, $booking->status);
        $this->assertSame(Booking::PAYMENT_FULLY_PAID, $booking->payment_status);
        $this->assertNotNull($booking->confirmed_at);
    }

    public function test_payment_on_cancelled_booking_is_rejected(): void
    {
        $booking = $this->makeBooking(['status' => Booking::STATUS_CANCELLED]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.record-payment', $booking), [
            'amount' => 200,
            'gateway' => 'cash',
            'payment_type' => 'deposit',
        ]);

        $response->assertSessionHasErrors('amount');
        $this->assertDatabaseCount('booking_payments', 0);
    }

    public function test_cancelled_booking_cannot_be_approved(): void
    {
        $booking = $this->makeBooking(['status' => Booking::STATUS_CANCELLED]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.approve', $booking));

        $response->assertSessionHasErrors('status');
        $this->assertSame(Booking::STATUS_CANCELLED, $booking->refresh()->status);
    }

    public function test_fully_paid_booking_cannot_be_rejected_without_refund(): void
    {
        $booking = $this->makeBooking([
            'status' => Booking::STATUS_CONFIRMED,
            'payment_status' => Booking::PAYMENT_FULLY_PAID,
            'deposit_paid' => 1000,
            'balance_amount' => 0,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.bookings.reject', $booking), [
            'cancellation_reason' => 'Customer changed plans',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertSame(Booking::STATUS_CONFIRMED, $booking->refresh()->status);
    }

    public function test_completed_booking_cannot_change_status(): void
    {
        $booking = $this->makeBooking(['status' => Booking::STATUS_COMPLETED]);

        $response = $this->actingAs($this->admin)->patch(route('admin.bookings.update-status', $booking), [
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertSame(Booking::STATUS_COMPLETED, $booking->refresh()->status);
    }

    public function test_total_price_cannot_be_lower_than_amount_paid(): void
    {
        $booking = $this->makeBooking([
            'status' => Booking::STATUS_DEPOSIT_PAID,
            'payment_status' => Booking::PAYMENT_DEPOSIT_PAID,
            'deposit_paid' => 500,
            'balance_amount' => 500,
        ]);

        $response = $this->actingAs($this->admin)->patch(route('admin.bookings.update-status', $booking), [
            'status' => Booking::STATUS_DEPOSIT_PAID,
            'total_price' => 100,
        ]);

        $response->assertSessionHasErrors('total_price');
        $this->assertEquals(1000.0, (float) $booking->refresh()->total_price);
    }
}
