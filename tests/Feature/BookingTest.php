<?php

namespace Tests\Feature;

use App\Mail\AdminNewBookingNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
            'payment_method' => 'bank_transfer',
        ];

        $response = $this->post(route('booking.store', $tour->slug), $bookingData);

        // 4. Assert response redirects to success page
        $booking = Booking::first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('booking.confirmation', $booking->token));

        // 5. Assert Booking is saved in database with correct values
        $this->assertDatabaseHas('bookings', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'tour_id' => $tour->id,
            'total_price' => 6750.00, // 2500 * (2 adults + 1 child at 70%)
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
            return $mail->hasTo('twinasafaris@gmail.com') && // Default settings email
                   $mail->booking->id === $booking->id;
        });

        // 8. Assert Admin Notification is logged in the database
        $this->assertDatabaseHas('admin_notifications', [
            'type' => 'booking',
            'title' => 'New Booking Request: '.$booking->booking_reference,
            'is_read' => 0,
        ]);
    }

    public function test_public_booking_uses_30_percent_deposit_and_65_day_balance_due_date()
    {
        Mail::fake();
        Storage::fake('public');

        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Serengeti', 'slug' => 'serengeti']);

        $tour = Tour::create([
            'title' => 'Deposit Policy Safari',
            'slug' => 'deposit-policy-safari',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'price' => 2500.00,
            'duration_days' => 5,
            'is_published' => true,
        ]);

        $travelDate = now()->addDays(100)->format('Y-m-d');

        $this->post(route('booking.store', $tour->slug), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '+123456789',
            'nationality' => 'American',
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'travel_date' => $travelDate,
            'payment_method' => 'bank_transfer',
        ]);

        $booking = Booking::first();

        $this->assertSame(30.0, (float) $booking->deposit_percentage);
        $this->assertSame(2025.0, (float) $booking->deposit_required); // 30% of $6,750
        $this->assertSame(4725.0, (float) $booking->balance_amount);   // 70% of $6,750
        $this->assertSame(
            Carbon::parse($travelDate)->subDays(65)->format('Y-m-d'),
            $booking->balance_due_date->format('Y-m-d')
        );
    }

    public function test_selected_tier_and_extras_are_added_to_the_booking_total()
    {
        Mail::fake();
        Storage::fake('public');

        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Tarangire', 'slug' => 'tarangire']);

        $tour = Tour::create([
            'title' => 'Tier And Extras Safari',
            'slug' => 'tier-and-extras-safari',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'price' => 1000.00,
            'duration_days' => 3,
            'is_published' => true,
            'accommodation_options' => [
                ['id' => 'acc_tc50se', 'name' => 'Tier 50 SE', 'price' => 1150.00],
            ],
            'extras' => [
                ['name' => 'Balloon Safari', 'price' => 200.00, 'price_type' => 'per_person'],
            ],
        ]);

        $this->post(route('booking.store', $tour->slug), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+123456789',
            'nationality' => 'American',
            'number_of_adults' => 2,
            'number_of_children' => 0,
            'travel_date' => now()->addDays(100)->format('Y-m-d'),
            'payment_method' => 'bank_transfer',
            'tier' => 'acc_tc50se',
            'selected_extras_qty' => ['ext_0' => 2],
        ]);

        $booking = Booking::first();

        // 2 x $1,150 tier price + 2 x $200 per-person extra = $2,700.
        $this->assertSame(2700.0, (float) $booking->total_price);
        $this->assertSame(400.0, (float) $booking->extras_total);
        $this->assertSame('Balloon Safari', $booking->selected_extras[0]['name']);
        $this->assertSame('acc_tc50se', $booking->selected_accommodation_tier['id']);
        $this->assertSame(810.0, (float) $booking->deposit_required); // 30% of $2,700
        $this->assertSame(1890.0, (float) $booking->balance_amount);  // 70% of $2,700
    }

    public function test_live_price_summary_and_extra_controls_share_one_alpine_scope()
    {
        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Tarangire', 'slug' => 'tarangire']);

        $tour = Tour::create([
            'title' => 'Live Estimate Safari',
            'slug' => 'live-estimate-safari',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'price' => 1150.00,
            'duration_days' => 3,
            'is_published' => true,
            'extras' => [
                ['name' => 'Balloon Safari', 'price' => 200.00, 'price_type' => 'per_person'],
            ],
        ]);

        $html = $this->get(route('booking.create', $tour->slug))
            ->assertOk()
            ->getContent();

        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($dom);

        $totalLabel = $xpath->query('//*[normalize-space(text())="Estimated Total"]')->item(0);
        $extraToggle = $xpath->query('//button[@*[contains(., "isExtraSelected")]]')->item(0);

        $this->assertNotNull($totalLabel, 'Estimated Total is missing from the booking summary.');
        $this->assertNotNull($extraToggle, 'The add-extra control is missing from the booking form.');

        $summaryScope = $xpath->query('ancestor::*[@x-data][1]', $totalLabel)->item(0);
        $extraScope = $xpath->query('ancestor::*[@x-data][last()]', $extraToggle)->item(0);

        $this->assertNotNull($summaryScope, 'Estimated Total must be inside an Alpine component.');
        $this->assertNotNull($extraScope, 'The extra control must be inside an Alpine component.');
        $this->assertSame(
            $summaryScope->getAttribute('x-data'),
            $extraScope->getAttribute('x-data'),
            'The price summary and the extra controls must share the same Alpine state so the total updates live.'
        );

        $this->assertStringContainsString('Deposit Required', $html);
        $this->assertStringContainsString('depositPct: 30', $html);
        $this->assertStringContainsString('selectedExtraLines', $html);
    }

    public function test_tour_detail_page_shows_the_30_percent_deposit_note()
    {
        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Tarangire', 'slug' => 'tarangire']);

        $tour = Tour::create([
            'title' => '3 Days Tarangire National park & Ngorongoro Crater & Maasai',
            'slug' => '3-days-tarangire-national-park-ngorongoro-crater-maasai',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'price' => 1150.00,
            'duration_days' => 3,
            'is_published' => true,
        ]);

        $bufferLevel = ob_get_level();
        $response = $this->get('/tours/tour/'.$tour->slug.'.html');
        while (ob_get_level() > $bufferLevel) {
            ob_end_clean();
        }

        $response->assertOk()
            ->assertSee('Pay only 30% today.', false)
            ->assertSee('The rest is due 65 days before departure.', false)
            ->assertSee('30% deposit today · balance 65 days before departure', false)
            ->assertDontSee('Pay only 20% today.', false);
    }
}
