<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourImage;
use App\Models\TourPackage;
use Database\Seeders\ChristmasSafariTourSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChristmasSafariTourSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_the_three_day_christmas_tour_with_exact_accommodation_prices(): void
    {
        Storage::fake('public');

        $this->seed(ChristmasSafariTourSeeder::class);

        $tour = Tour::where('slug', 'christmas-new-year-tanzania-safari-2026-2027')->firstOrFail();

        $this->assertSame('Christmas & New Year Safari in Tanzania 2026/2027', $tour->title);
        $this->assertTrue($tour->is_published);
        $this->assertTrue($tour->is_featured);
        $this->assertSame('Safari', $tour->category->name);
        $this->assertSame('Ngorongoro Crater', $tour->destination->name);
        $this->assertSame('1185.00', (string) $tour->price);
        $this->assertSame('830.00', (string) $tour->child_price);
        $this->assertSame(3, $tour->duration_days);
        $this->assertSame(2, $tour->duration_nights);
        $this->assertCount(3, $tour->itinerary);
        $this->assertCount(6, $tour->highlights);
        $this->assertCount(5, $tour->faqs);

        $levels = $tour->accommodation_options;
        $this->assertCount(5, $levels);
        $this->assertSame([
            'Bei nafuu zaidi',
            'Cha kati',
            'Cha kati juu',
            'Hadhi ya juu',
            'Anasa zaidi',
        ], array_column($levels, 'name'));
        $this->assertSame([1185, 1283, 1370, 1603, 1925], array_column($levels, 'price'));

        $first = collect($levels)->firstWhere('is_default', true);
        $this->assertSame('acc_festive_1', $first['id']);
        $this->assertSame('Charity Hotel, Arusha', $first['hotels']['day_0']);
        $this->assertSame('Charity Hotel, Arusha', $first['hotels']['day_1']);
        $this->assertSame('Omega View Lodge', $first['hotels']['day_2']);

        $this->assertSame(3, TourImage::where('tour_id', $tour->id)->count());
        $package = TourPackage::where('tour_id', $tour->id)->firstOrFail();
        $this->assertSame('Christmas & New Year Safari – Classic Package', $package->name);
        $this->assertTrue($package->is_active);
        $this->assertSame('1185.00', (string) $package->selling_price);
        $this->assertSame('830.00', (string) $package->child_price);
        $this->assertSame('30.00', (string) $package->deposit_percentage);

        $files = Storage::disk('public')->allFiles('tours/christmas-safari');
        $this->assertCount(4, $files);

        $hero = getimagesize(Storage::disk('public')->path('tours/christmas-safari/hero.webp'));
        $this->assertSame(1600, $hero[0]);

        foreach (['serengeti.webp', 'ngorongoro.webp', 'festive.webp'] as $file) {
            $size = getimagesize(Storage::disk('public')->path('tours/christmas-safari/'.$file));
            $this->assertSame(1200, $size[0]);
            $this->assertSame(800, $size[1]);
        }

        $response = $this->get('/tours/tour/'.$tour->slug.'.html');
        $response->assertOk();
        $response->assertSee('Christmas &amp; New Year Safari in Tanzania 2026/2027', false);
        $response->assertSee('3 Days / 2 Nights', false);
        $response->assertSee('Bei nafuu zaidi');
        $response->assertSee('Charity Hotel, Arusha');
        $response->assertSee('Omega View Lodge');
        $response->assertSee('Gran Melia Arusha');
        $response->assertSee('Classic Package');
        $response->assertSee('Book This Safari');
        $response->assertSee('3 day Christmas safari Tanzania');
        $response->assertDontSee('7 Days / 6 Nights', false);

        $listing = $this->get('/tours');
        $listing->assertOk();
        $listing->assertSee('Christmas &amp; New Year Safari in Tanzania 2026/2027', false);

        // Re-running must update in place, never duplicate the tour, package or images.
        $this->seed(ChristmasSafariTourSeeder::class);
        $this->assertSame(1, Tour::where('slug', $tour->slug)->count());
        $this->assertSame(1, TourPackage::where('tour_id', $tour->id)->count());
        $this->assertSame(3, TourImage::where('tour_id', $tour->id)->count());
        $this->assertCount(4, Storage::disk('public')->allFiles('tours/christmas-safari'));
    }
}
