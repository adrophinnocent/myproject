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

    public function test_it_seeds_a_publishable_christmas_safari_tour_and_package_once(): void
    {
        Storage::fake('public');

        $this->seed(ChristmasSafariTourSeeder::class);

        $tour = Tour::where('slug', 'christmas-new-year-tanzania-safari-2026-2027')->firstOrFail();

        $this->assertSame('Christmas & New Year Safari in Tanzania 2026/2027', $tour->title);
        $this->assertTrue($tour->is_published);
        $this->assertTrue($tour->is_featured);
        $this->assertSame('Safari', $tour->category->name);
        $this->assertSame('Serengeti National Park', $tour->destination->name);
        $this->assertCount(7, $tour->itinerary);
        $this->assertCount(8, $tour->highlights);
        $this->assertCount(5, $tour->faqs);
        $this->assertStringContainsString('sunset game drive', $tour->itinerary[6]['description']);
        $this->assertSame('acc_festive_standard', collect($tour->accommodation_options)->firstWhere('is_default', true)['id']);

        $this->assertSame(3, TourImage::where('tour_id', $tour->id)->count());
        $package = TourPackage::where('tour_id', $tour->id)->firstOrFail();
        $this->assertSame('Christmas & New Year Safari – Classic Package', $package->name);
        $this->assertTrue($package->is_active);
        $this->assertSame('6450.00', (string) $package->selling_price);
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
        $response->assertSee('Classic Package');
        $response->assertSee('Festive Plains Day', false);
        $response->assertSee('Book This Safari');
        $response->assertSee('Christmas safari Tanzania');

        // A second run must not duplicate the tour, package or images.
        $this->seed(ChristmasSafariTourSeeder::class);
        $this->assertSame(1, Tour::where('slug', $tour->slug)->count());
        $this->assertSame(1, TourPackage::where('tour_id', $tour->id)->count());
        $this->assertCount(4, Storage::disk('public')->allFiles('tours/christmas-safari'));
    }
}
