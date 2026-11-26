<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\TourImage;
use App\Models\TourPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ChristmasSafariTourSeeder extends Seeder
{
    private const SLUG = 'christmas-new-year-tanzania-safari-2026-2027';

    private const IMAGE_DIR = 'tours/christmas-safari';

    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'safari'],
            ['name' => 'Safari', 'is_active' => true]
        );

        $destination = Destination::firstOrCreate(
            ['slug' => 'ngorongoro'],
            ['name' => 'Ngorongoro Crater', 'is_active' => true, 'is_featured' => true]
        );

        // Always (re)produce the same compressed images so the gallery and hero
        // stay correct after a fresh deployment.
        $source = database_path('seeders/data/blog/christmas-safari');
        $this->storePhoto($source.'/hero.webp', 1600);
        $this->storePhoto($source.'/serengeti.webp', 1200, 800);
        $this->storePhoto($source.'/ngorongoro.webp', 1200, 800);
        $this->storePhoto($source.'/festive.jpg', 1200, 800);

        $tourData = $this->tourData($category->id, $destination->id);

        $tour = Tour::withTrashed()->where('slug', self::SLUG)->first();

        if ($tour) {
            // This is our own seeded record; bring it up to date without
            // changing its id, slug, creation date or view count.
            $tour->update($tourData);
        } else {
            $tour = Tour::create($tourData);
        }

        $this->syncGallery($tour);
        $this->ensurePackage($tour);
    }

    private function tourData(int $categoryId, int $destinationId): array
    {
        return [
            'title' => 'Christmas & New Year Safari in Tanzania 2026/2027',
            'slug' => self::SLUG,
            'category_id' => $categoryId,
            'destination_id' => $destinationId,
            'short_description' => 'A festive three-day Arusha, Lake Manyara & Ngorongoro safari for Christmas and New Year 2026/2027, with five accommodation levels from $1,185 and private 4x4 guiding.',
            'description' => $this->description(),
            'highlights' => $this->highlights(),
            'price' => 1185,
            'child_price' => 830,
            'group_discount' => 0,
            'deposit_percent' => 30,
            'currency' => 'USD',
            'price_note' => 'Per person, based on two travelers. Choose your accommodation level below.',
            'duration_days' => 3,
            'duration_nights' => 2,
            'group_size_min' => 2,
            'group_size_max' => 8,
            'difficulty_level' => 'Easy',
            'tour_type' => 'safari',
            'accommodation_type' => 'Lodge & tented camp',
            'transport_type' => 'Private 4x4 Land Cruiser',
            'departure_location' => 'Arusha / Kilimanjaro International Airport',
            'departure_time' => '08:30',
            'meeting_point' => 'Arusha, Tanzania',
            'pickup_included' => true,
            'airport_pickup' => true,
            'transport_included' => true,
            'assigned_guide' => 'Professional English/Swahili safari guide',
            'languages_offered' => ['English', 'Swahili'],
            'special_notes' => 'This short northern route focuses on the resident wildlife of Lake Manyara and Ngorongoro; the Great Migration is not part of this itinerary.',
            'available_slots' => 8,
            'seasonal_tag' => 'Festive 3-Day 2026/2027',
            'availability_notes' => 'Festive departures between 20 December 2026 and 5 January 2027. Five accommodation levels available. Limited space in peak season.',
            'booking_deadline_days' => 30,
            'min_age' => 5,
            'max_age' => 75,
            'itinerary' => $this->itinerary(),
            'inclusions' => $this->inclusions(),
            'exclusions' => $this->exclusions(),
            'faqs' => $this->faqs(),
            'what_to_bring' => $this->packingList(),
            'good_to_know' => $this->goodToKnow(),
            'extras' => $this->extras(),
            'accommodation_options' => $this->accommodationOptions(),
            'featured_image' => self::IMAGE_DIR.'/hero.webp',
            'meta_title' => 'Christmas & New Year Tanzania Safari 2026/27',
            'meta_description' => 'Three-day Christmas and New Year safari from Arusha to Lake Manyara and Ngorongoro Crater, with five accommodation levels from $1,185.',
            'meta_keywords' => '3 day Christmas safari Tanzania, New Year safari Tanzania, Arusha Lake Manyara Ngorongoro, Christmas safari 2026, festive safari 2027, Ngorongoro Christmas tour, Lake Manyara festive safari, affordable Tanzania safari, luxury festive safari, family Christmas safari, short safari Tanzania, Twina Safaris',
            'is_published' => true,
            'is_featured' => true,
            'is_new' => true,
            'limited_offer' => true,
            'sort_order' => 1,
        ];
    }

    private function syncGallery(Tour $tour): void
    {
        $gallery = [
            ['path' => self::IMAGE_DIR.'/serengeti.webp', 'alt' => 'Wildlife and birdlife along the Lake Manyara and Ngorongoro route', 'order' => 1],
            ['path' => self::IMAGE_DIR.'/ngorongoro.webp', 'alt' => 'Ngorongoro Crater floor', 'order' => 2],
            ['path' => self::IMAGE_DIR.'/festive.webp', 'alt' => 'A festive evening at a safari camp', 'order' => 3],
        ];

        foreach ($gallery as $item) {
            TourImage::updateOrCreate(
                ['tour_id' => $tour->id, 'image_path' => $item['path']],
                ['alt_text' => $item['alt'], 'sort_order' => $item['order'], 'is_primary' => false]
            );
        }
    }

    private function storePhoto(string $source, int $width, ?int $height = null): string
    {
        $image = (new ImageManager(new Driver))->decodePath($source);

        if ($height !== null) {
            $image->cover($width, $height);
        } else {
            $image->scale(width: $width);
        }

        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $path = self::IMAGE_DIR.'/'.basename($source, '.'.$extension).'.webp';

        Storage::disk('public')->put(
            $path,
            $image->encode(new WebpEncoder(quality: 80))->toString()
        );

        // Relative path keeps the public URL valid if the domain changes.
        return $path;
    }

    private function ensurePackage(Tour $tour): void
    {
        $data = [
            'name' => 'Christmas & New Year Safari – Classic Package',
            'slug' => 'christmas-new-year-safari-classic-2026-2027',
            'description' => 'Our classic festive package: two nights in carefully selected lodges, a private 4x4 vehicle, all park fees and a relaxed Christmas or New Year dinner at camp.',
            'selling_price' => 1185,
            'adult_price' => 1185,
            'child_price' => 830,
            'single_supplement' => 0,
            'deposit_percentage' => 30.00,
            'currency' => 'USD',
            'images' => [],
            'sort_order' => 1,
            'is_active' => true,
            'supplier_hotel_cost' => 0,
            'park_fees_cost' => 0,
            'transport_cost' => 0,
            'guide_cost' => 0,
            'meals_cost' => 0,
            'transfers_cost' => 0,
            'other_costs' => 0,
        ];

        $existing = TourPackage::where('tour_id', $tour->id)
            ->where(function ($query) {
                $query->where('name', 'like', 'Christmas%')
                    ->orWhere('slug', 'like', 'christmas%');
            })
            ->orderBy('id')
            ->get();

        $package = $existing->shift();

        if ($package) {
            $package->update($data);
        } else {
            $package = TourPackage::create(array_merge(['tour_id' => $tour->id], $data));
        }

        // Remove stale duplicates left by an earlier version of this seeder.
        foreach ($existing as $duplicate) {
            $duplicate->delete();
        }
    }

    private function description(): string
    {
        return <<<'HTML'
<p>Make this Christmas and New Year unforgettable with a compact three-day safari from Arusha to Lake Manyara and the Ngorongoro Crater. Designed for travelers who want big wildlife moments without a long expedition, this festive escape combines flamingo-lined lakes, tree-climbing lions and the Big Five on the Crater floor.</p>
<p>Arrive in Arusha for Christmas 2026 or New Year 2027, settle into one of five carefully chosen accommodation levels, and let your Twina Safaris guide handle every detail. The short rains leave the landscapes green, the holiday mood is everywhere, and each day ends with a relaxed dinner under the African sky.</p>
<p>Choose the level that suits you: Charity Hotel with Omega View Lodge at $1,185, Summit Lodge with Olea Farm Lodge at $1,283, Kibo Palace Hotel with Heart and Soul Lodge at $1,370, Arusha Serena with Manyara Serena Safari Lodge at $1,603, or Gran Meliá Arusha with Manyara Best View Hotel at $1,925. Every option includes a private 4x4 safari vehicle, expert guiding, park fees and festive service.</p>
<p>This is one of the most searched short festive safaris in Tanzania: a genuine Christmas safari, a New Year wildlife break, a Lake Manyara birdwatching trip and a Ngorongoro Crater game drive in one smooth three-day plan. Book early — festive lodges fill quickly in December and January.</p>
HTML;
    }

    private function highlights(): array
    {
        return [
            'Three-day festive safari from Arusha',
            'Lake Manyara flamingos and tree-climbing lions',
            'Ngorongoro Crater Big Five game drive',
            'Five accommodation levels from budget to luxury',
            'Christmas and New Year dinner in the wild',
            'Private 4x4 vehicle with an expert local guide',
        ];
    }

    private function itinerary(): array
    {
        return [
            1 => [
                'title' => 'Arrival in Arusha & Festive Welcome',
                'description' => 'Arrive at Kilimanjaro International Airport. Your Twina Safaris guide meets you and transfers you to your Arusha hotel for the first night. Briefing, pack check and a relaxed welcome dinner to start the festive safari.',
                'accommodation' => 'Arusha hotel — choose your level below',
                'meals' => 'Dinner',
                'activities' => ['Airport pickup', 'Safari briefing', 'Welcome dinner'],
            ],
            2 => [
                'title' => 'Lake Manyara Game Drive & Transfer to Ngorongoro',
                'description' => 'Drive to Lake Manyara National Park for a morning game drive. Look for flamingos, hippos, baboons and the famous tree-climbing lions. After lunch, continue through the Rift Valley to your lodge near the Ngorongoro highlands.',
                'accommodation' => 'Lake Manyara / Ngorongoro lodge — choose your level below',
                'meals' => 'Breakfast, Lunch, Dinner',
                'activities' => ['Lake Manyara game drive', 'Birdwatching', 'Rift Valley transfer'],
            ],
            3 => [
                'title' => 'Ngorongoro Crater & Return to Arusha',
                'description' => 'Descend early into the Ngorongoro Crater for a game drive on the floor, home to lions, elephants, buffalo, rhino and leopard. Picnic lunch, then return to Arusha or Kilimanjaro airport for your onward flight.',
                'accommodation' => 'Departure / Arusha airport',
                'meals' => 'Breakfast, Lunch',
                'activities' => ['Crater game drive', 'Big Five sightings', 'Airport transfer'],
            ],
        ];
    }

    private function inclusions(): array
    {
        return [
            'Airport pickup and all ground transfers',
            '2 nights in selected lodge or hotel',
            'Private 4x4 Land Cruiser with pop-up roof',
            'Professional English/Swahili safari guide',
            'Lake Manyara and Ngorongoro Crater fees',
            'Game drives as per the itinerary',
            '2 breakfasts, 2 lunches and 2 dinners',
            'Bottled drinking water on game drives',
            'Festive dinner arrangement at camp, subject to lodge programme',
        ];
    }

    private function exclusions(): array
    {
        return [
            'International and domestic flights',
            'Tanzania visa fees and travel insurance',
            'Tips and gratuities for guides and lodge staff',
            'Personal expenses, soft drinks and alcohol',
            'Optional extra nights or Zanzibar extension',
            'Festive supplements charged by lodges where applicable',
        ];
    }

    private function faqs(): array
    {
        return [
            [
                'question' => 'Is a three-day safari enough for Christmas?',
                'answer' => 'Yes. This short festive route covers two of northern Tanzania’s best wildlife areas — Lake Manyara and the Ngorongoro Crater — with comfortable pacing and two nights away from the holiday rush.',
            ],
            [
                'question' => 'Will we see the Great Migration?',
                'answer' => 'Not on this itinerary. The migration is in the southern Serengeti and Ndutu at this time. This three-day trip focuses on resident wildlife, birdlife and the Big Five in the Ngorongoro Crater.',
            ],
            [
                'question' => 'How do I choose an accommodation level?',
                'answer' => 'Pick the level that fits your budget and style. Prices range from $1,185 with Charity Hotel and Omega View Lodge to $1,925 with Gran Meliá Arusha and Manyara Best View Hotel. All levels include the same safari service.',
            ],
            [
                'question' => 'How do I pay for this festive safari?',
                'answer' => 'A 30% deposit secures your place and the balance is due 65 days before departure. Because festive lodges fill early, we recommend booking as soon as your dates are confirmed.',
            ],
            [
                'question' => 'Is this safari suitable for children?',
                'answer' => 'Yes. We use a private vehicle and keep the days comfortable. Minimum age is five, with younger children considered on request.',
            ],
        ];
    }

    private function packingList(): array
    {
        return [
            'Light layers for warm days',
            'Warm fleece for cool Ngorongoro mornings',
            'Lightweight rain jacket',
            'Binoculars and a camera',
            'Personal medications',
            'Insect repellent and sunscreen',
            'Reusable water bottle',
            'Passport, visa and insurance documents',
        ];
    }

    private function goodToKnow(): array
    {
        return [
            'This is a short, comfortable safari — packed but not rushed.',
            'The Ngorongoro rim is much cooler than Arusha, especially at sunrise.',
            'Christmas and New Year is peak season; the best lodges fill months ahead.',
            'A 30% deposit secures your place; the balance is due 65 days before departure.',
        ];
    }

    private function extras(): array
    {
        return [
            [
                'name' => 'Extra night in Arusha',
                'description' => 'Arrive a day early or depart a day later with an extra comfortable night in Arusha.',
                'price' => 180,
                'price_type' => 'per_person',
            ],
            [
                'name' => 'Zanzibar beach extension (3 nights)',
                'description' => 'Finish your festive trip on the white sands of Zanzibar with flights, transfers and beach accommodation included.',
                'price' => 850,
                'price_type' => 'per_person',
            ],
            [
                'name' => 'Private vehicle upgrade',
                'description' => 'Extra space and privacy for families or small groups travelling together.',
                'price' => 250,
                'price_type' => 'per_person',
            ],
        ];
    }

    private function accommodationOptions(): array
    {
        $levels = [
            ['name' => 'Bei nafuu zaidi', 'price' => 1185, 'arusha' => 'Charity Hotel, Arusha', 'second' => 'Omega View Lodge'],
            ['name' => 'Cha kati', 'price' => 1283, 'arusha' => 'Summit Lodge', 'second' => 'Olea Farm Lodge'],
            ['name' => 'Cha kati juu', 'price' => 1370, 'arusha' => 'Kibo Palace Hotel', 'second' => 'Heart and Soul Lodge'],
            ['name' => 'Hadhi ya juu', 'price' => 1603, 'arusha' => 'Arusha Serena', 'second' => 'Manyara Serena Safari Lodge'],
            ['name' => 'Anasa zaidi', 'price' => 1925, 'arusha' => 'Gran Melia Arusha', 'second' => 'Manyara Best View Hotel'],
        ];

        $options = [];

        foreach ($levels as $index => $level) {
            $options[] = [
                'id' => 'acc_festive_'.($index + 1),
                'name' => $level['name'],
                'price' => $level['price'],
                'hotels' => [
                    'day_0' => $level['arusha'],
                    'day_1' => $level['arusha'],
                    'day_2' => $level['second'],
                ],
                'image' => '/storage/'.self::IMAGE_DIR.'/ngorongoro.webp',
                'is_active' => true,
                'is_default' => $index === 0,
            ];
        }

        return $options;
    }
}
