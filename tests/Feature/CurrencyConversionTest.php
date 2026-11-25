<?php

namespace Tests\Feature;

use App\Helpers\Currency;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CurrencyConversionTest extends TestCase
{
    use RefreshDatabase;

    private function makeTour(): Tour
    {
        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Serengeti', 'slug' => 'serengeti']);

        return Tour::create([
            'title' => 'Serengeti Explorer',
            'slug' => 'serengeti-explorer',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'price' => 2500,
            'duration_days' => 5,
            'is_published' => true,
        ]);
    }

    public function test_prices_display_in_usd_by_default(): void
    {
        $tour = $this->makeTour();

        $this->assertSame('USD', Currency::current());
        $this->assertSame('$2,500.00', $tour->display_price_formatted);
    }

    public function test_prices_convert_to_selected_currency(): void
    {
        $tour = $this->makeTour();

        $this->withSession(['currency' => 'EUR']);

        $this->assertSame('EUR', Currency::current());
        // Default fallback rate: 0.92 EUR per USD.
        $this->assertSame(2300.0, $tour->display_price);
        $this->assertSame('€2,300.00', $tour->display_price_formatted);
    }

    public function test_admin_configured_rate_overrides_default(): void
    {
        Setting::set('currency_rate_EUR', 0.5);
        $tour = $this->makeTour();

        $this->withSession(['currency' => 'EUR']);

        $this->assertSame(1250.0, $tour->display_price);
    }

    public function test_whole_unit_currencies_round_to_zero_decimals(): void
    {
        $tour = $this->makeTour();

        $this->withSession(['currency' => 'TZS']);

        // Default fallback rate: 2600 TZS per USD.
        $this->assertSame(6500000.0, $tour->display_price);
        $this->assertSame('TSh 6,500,000', $tour->display_price_formatted);
    }

    public function test_unsupported_currency_falls_back_to_usd(): void
    {
        $this->withSession(['currency' => 'XYZ']);

        $this->assertSame('USD', Currency::current());
    }

    public function test_booking_is_charged_in_the_selected_currency(): void
    {
        Mail::fake();
        Storage::fake('public');

        $tour = $this->makeTour();

        $response = $this->withSession(['currency' => 'EUR'])->post(route('booking.store', $tour->slug), [
            'first_name' => 'Euro',
            'last_name' => 'Traveler',
            'email' => 'euro@example.com',
            'phone' => '+123456789',
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'travel_date' => now()->addDays(10)->format('Y-m-d'),
            'payment_method' => 'bank_transfer',
        ]);

        $booking = Booking::firstOrFail();
        $response->assertRedirect(route('booking.confirmation', $booking->token));

        // 2500 * 2 adults + 1750 child = 6750 USD -> 6210 EUR at 0.92.
        $this->assertSame('EUR', $booking->currency);
        $this->assertEquals(6210.0, (float) $booking->total_price);
        $this->assertEquals(1863.0, (float) $booking->deposit_required); // 30% of 6210 EUR
    }

    public function test_booking_defaults_to_usd_without_currency_selection(): void
    {
        Mail::fake();
        Storage::fake('public');

        $tour = $this->makeTour();

        $this->post(route('booking.store', $tour->slug), [
            'first_name' => 'Dollar',
            'last_name' => 'Traveler',
            'email' => 'dollar@example.com',
            'phone' => '+123456789',
            'number_of_adults' => 1,
            'number_of_children' => 0,
            'travel_date' => now()->addDays(10)->format('Y-m-d'),
            'payment_method' => 'bank_transfer',
        ]);

        $booking = Booking::firstOrFail();
        $this->assertSame('USD', $booking->currency);
        $this->assertEquals(2500.0, (float) $booking->total_price);
    }
}
