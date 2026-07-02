<?php

namespace Tests\Feature;

use App\Mail\AdminNewBookingNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\AdminNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_booking_form_successfully()
    {
        // 1. Fake the Mail and Storage to prevent sending actual emails and writing real files
        Mail::fake();
        Storage::fake('public');

        // 2. Create prerequisite data
        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Serengeti', 'slug' => 'serengeti']);
        
        $tour = Tour::create([
            'title' => 'Luxury Serengeti Safari',
            'slug' => 'luxury-serengeti-safari',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'short_description' => 'A perfect experience.',
            'description' => 'Enjoy an unforgettable experience.',
            'price' => 2500.00,
            'duration_days' => 5,
            'is_published' => true,
            'is_featured' => true,
            'difficulty_level' => 'moderate',
            'accommodation_type' => 'Luxury Lodge',
            'departure_location' => 'Moshi',
        ]);

        // 3. Make POST request to the booking store route
        $bookingData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+123456789',
            'nationality' => 'American',
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'travel_date' => now()->addDays(10)->format('Y-m-d'),
            'special_requests' => 'Vegetarian meals preferred.',
            'accommodation_preference' => 'luxury',
            'payment_method' => 'paypal',
        ];

        $response = $this->post(route('booking.store', $tour), $bookingData);

        // 4. Assert response redirects to success page
        $booking = Booking::first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('booking.success', $booking->booking_reference));

        // 5. Assert Booking is saved in database with correct values
        $this->assertDatabaseHas('bookings', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'tour_id' => $tour->id,
            'total_price' => 7500.00, // 2500 * (2 adults + 1 child) = 7500
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // 6. Assert confirmation email is sent to the Guest
        Mail::assertSent(CustomerBookingConfirmation::class, function ($mail) use ($booking) {
            return $mail->hasTo('john.doe@example.com') &&
                   $mail->booking->id === $booking->id;
        });

        // 7. Assert notification email is sent to the Admin
        Mail::assertSent(AdminNewBookingNotification::class, function ($mail) use ($booking) {
            return $mail->hasTo('info@twinasafaris.com') && // Default settings email
                   $mail->booking->id === $booking->id;
        });

        // 8. Assert Admin Notification is logged in the database
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'booking',
            'title' => 'New Booking: ' . $booking->booking_reference,
            'is_read' => 0,
        ]);
    }
}
