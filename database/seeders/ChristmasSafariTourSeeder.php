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
        // Never overwrite an editor's tour. Only make sure the bookable package exists.
        $existing = Tour::withTrashed()->where('slug', self::SLUG)->first();
        if ($existing) {
            $this->ensurePackage($existing);

            return;
        }

        $category = Category::firstOrCreate(
            ['slug' => 'safari'],
            ['name' => 'Safari', 'is_active' => true]
        );

        $destination = Destination::firstOrCreate(
            ['slug' => 'serengeti'],
            ['name' => 'Serengeti National Park', 'is_active' => true, 'is_featured' => true]
        );

        $source = database_path('seeders/data/blog/christmas-safari');

        $hero = $this->storePhoto($source.'/hero.webp', 1600);
        $serengeti = $this->storePhoto($source.'/serengeti.webp', 1200, 800);
        $ngorongoro = $this->storePhoto($source.'/ngorongoro.webp', 1200, 800);
        $festive = $this->storePhoto($source.'/festive.jpg', 1200, 800);

        $tour = Tour::create([
            'title' => 'Christmas & New Year Safari in Tanzania 2026/2027',
            'slug' => self::SLUG,
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'short_description' => 'Celebrate the festive season on the southern Serengeti plains with green landscapes, migrating herds and a special dinner at camp. Seven days from Arusha to Ndutu, Ngorongoro and Tarangire.',
            'description' => $this->description(),
            'highlights' => $this->highlights(),
            'price' => 6450,
            'child_price' => 4520,
            'group_discount' => 350,
            'deposit_percent' => 30,
            'currency' => 'USD',
            'price_note' => 'Per person, based on two travelers. Festive supplements may apply; single supplement $1,250.',
            'duration_days' => 7,
            'duration_nights' => 6,
            'group_size_min' => 2,
            'group_size_max' => 8,
            'difficulty_level' => 'Easy',
            'tour_type' => 'safari',
            'accommodation_type' => 'Safari lodge & tented camp',
            'transport_type' => 'Private 4x4 Land Cruiser',
            'departure_location' => 'Arusha / Kilimanjaro International Airport',
            'departure_time' => '08:30',
            'meeting_point' => 'Arusha, Tanzania',
            'pickup_included' => true,
            'airport_pickup' => true,
            'transport_included' => true,
            'assigned_guide' => 'Professional English/Swahili safari guide',
            'languages_offered' => ['English', 'Swahili'],
            'special_notes' => 'The exact position of the wildebeest herds follows the rains. Your guide will adjust each day to give you the best chance of finding them.',
            'available_slots' => 8,
            'seasonal_tag' => 'Festive 2026/2027',
            'availability_notes' => 'Festive departures between 20 December 2026 and 5 January 2027. Christmas Day and New Year departures available on request. Limited to 8 travelers.',
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
            'featured_image' => $hero,
            'meta_title' => 'Christmas & New Year Safari Tanzania 2026/2027 | Twina Safaris',
            'meta_description' => 'Join our 7-day Christmas and New Year safari in Tanzania: southern Serengeti, Ndutu, Ngorongoro Crater and Tarangire, with a festive camp dinner and expert guides.',
            'meta_keywords' => 'Christmas safari Tanzania, New Year safari Tanzania, festive safari 2026, Serengeti December, Ndutu migration, Ngorongoro Crater, Tarangire, family safari, Twina Safaris',
            'is_published' => true,
            'is_featured' => true,
            'is_new' => true,
            'limited_offer' => true,
            'sort_order' => 1,
        ]);

        $gallery = [
            ['path' => $serengeti, 'alt' => 'Wildebeest herds spread across the southern Serengeti plains', 'order' => 1],
            ['path' => $ngorongoro, 'alt' => 'The floor of Ngorongoro Crater', 'order' => 2],
            ['path' => $festive, 'alt' => 'A festive evening at a safari camp', 'order' => 3],
        ];

        foreach ($gallery as $item) {
            TourImage::firstOrCreate(
                ['tour_id' => $tour->id, 'image_path' => $item['path']],
                ['alt_text' => $item['alt'], 'sort_order' => $item['order'], 'is_primary' => false]
            );
        }

        $this->ensurePackage($tour);
    }

    private function storePhoto(string $source, int $width, ?int $height = null): string
    {
        $image = (new ImageManager(new Driver))->decodePath($source);

        if ($height !== null) {
            $image->cover($width, $height);
        } else {
            $image->scale(width: $width);
        }

        $path = self::IMAGE_DIR.'/'.basename($source, pathinfo($source, PATHINFO_EXTENSION) === 'jpg' ? '.jpg' : '.webp').'.webp';
        Storage::disk('public')->put(
            $path,
            $image->encode(new WebpEncoder(quality: 80))->toString()
        );

        // Stored relative so the public URL stays valid if the domain changes.
        return $path;
    }

    private function ensurePackage(Tour $tour): void
    {
        TourPackage::firstOrCreate(
            ['tour_id' => $tour->id, 'name' => 'Christmas & New Year Safari – Classic Package'],
            [
                'slug' => 'christmas-new-year-safari-classic-2026-2027',
                'description' => 'Our classic festive package: 6 nights in safari lodges and tented camps, a private 4x4 vehicle, all park fees and a special festive dinner at camp.',
                'selling_price' => 6450,
                'adult_price' => 6450,
                'child_price' => 4520,
                'single_supplement' => 1250,
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
            ]
        );
    }

    private function description(): string
    {
        return <<<'HTML'
<p>Trade the usual holiday routine for lion cubs, endless plains and a sundowner under the African sky. This seven-day festive safari takes you from Arusha through Tarangire, Lake Manyara and the Ngorongoro Crater to the southern Serengeti and Ndutu, where the migration herds gather in December and the calving season begins in January.</p>
<p>Christmas 2026 falls on a Friday, and so does New Year’s Day 2027. The short rains have turned the landscape green, wildlife is spread across the plains, and the camps are ready for a relaxed, family-friendly celebration. Days are spent on game drives with an expert guide; evenings are spent around the fire, with a special festive dinner arranged by the camp.</p>
<p>We keep the pace comfortable, use a private 4x4 vehicle, and tailor each day to the wildlife reports. If you want to finish on the beach, we can add a Zanzibar extension after your last game drive.</p>
HTML;
    }

    private function highlights(): array
    {
        return [
            'Seven days across Tarangire, Lake Manyara, Ngorongoro and the southern Serengeti',
            'Christmas and New Year dinner at a safari camp',
            'Sunrise and sunset game drives on the Ndutu plains',
            'Great Migration herds arriving in the south',
            'Full day inside the wildlife-rich Ngorongoro Crater',
            'Private 4x4 vehicle with a professional English/Swahili guide',
            'Green, photogenic landscapes after the short rains',
            'Optional Zanzibar beach extension',
        ];
    }

    private function itinerary(): array
    {
        return [
            1 => [
                'title' => 'Arrival in Arusha & Safari Briefing',
                'description' => 'Arrive at Kilimanjaro International Airport. Your Twina Safaris guide meets you and transfers you to your hotel in Arusha. Relax, meet your fellow travelers and go through the route, packing and safety briefing before a relaxed dinner.',
                'accommodation' => 'Arusha lodge',
                'meals' => 'Dinner',
                'activities' => ['Airport pickup', 'Safari briefing', 'Welcome dinner'],
            ],
            2 => [
                'title' => 'Tarangire National Park',
                'description' => 'Drive to Tarangire, famous for its large elephant herds and ancient baobab trees. Enjoy a full day of game drives, with a picnic lunch under the shade and excellent birdwatching along the river.',
                'accommodation' => 'Tarangire lodge or tented camp',
                'meals' => 'Breakfast, Lunch, Dinner',
                'activities' => ['Elephant viewing', 'Baobab landscapes', 'Birdwatching'],
            ],
            3 => [
                'title' => 'Lake Manyara or Cultural Visit en Route to Ngorongoro',
                'description' => 'Choose a morning game drive in Lake Manyara National Park, known for its tree-climbing lions and flamingos, or a local cultural visit. Continue to the Ngorongoro highlands for the night.',
                'accommodation' => 'Ngorongoro highland lodge',
                'meals' => 'Breakfast, Lunch, Dinner',
                'activities' => ['Lake Manyara game drive', 'Cultural visit', 'Scenic transfer'],
            ],
            4 => [
                'title' => 'Full Day in Ngorongoro Crater',
                'description' => 'Descend early into the Crater for a full-day game drive on the floor, one of the best places in Africa to see the Big Five in a single morning. Picnic lunch at the crater, then climb back to the highlands.',
                'accommodation' => 'Ngorongoro highland lodge',
                'meals' => 'Breakfast, Lunch, Dinner',
                'activities' => ['Crater game drive', 'Big Five sightings', 'Picnic lunch'],
            ],
            5 => [
                'title' => 'Into the Southern Serengeti & Ndutu',
                'description' => 'Travel across the plains to Ndutu in the southern Serengeti. The landscape is green after the short rains and the migration herds are usually arriving. Afternoon game drive on the way to camp.',
                'accommodation' => 'Ndutu tented camp',
                'meals' => 'Breakfast, Lunch, Dinner',
                'activities' => ['Scenic drive', 'Migration tracking', 'Sunset game drive'],
            ],
            6 => [
                'title' => 'Festive Plains Day & Dinner at Camp',
                'description' => 'A full day in the southern Serengeti with an early sunrise game drive, a relaxed midday break and a sunset game drive. In the evening, enjoy a special festive dinner at camp with your guide and fellow travelers.',
                'accommodation' => 'Ndutu tented camp',
                'meals' => 'Breakfast, Lunch, Dinner',
                'activities' => ['Sunrise game drive', 'Wildlife photography', 'Festive camp dinner'],
            ],
            7 => [
                'title' => 'Return to Arusha or Fly to Zanzibar',
                'description' => 'One last short drive if time allows, then transfer to Arusha or Kilimanjaro airport for your onward flight. Prefer to keep celebrating? Extend with a three-night Zanzibar beach stay.',
                'accommodation' => 'Departure / optional Zanzibar extension',
                'meals' => 'Breakfast, Lunch',
                'activities' => ['Final game drive', 'Airport transfer', 'Zanzibar extension'],
            ],
        ];
    }

    private function inclusions(): array
    {
        return [
            'Airport pickup and all ground transfers',
            '6 nights in safari lodges and tented camps',
            'Private 4x4 Land Cruiser with pop-up roof',
            'Professional English/Swahili safari guide',
            'All national park and Ngorongoro Crater fees',
            'Daily game drives as per the itinerary',
            '6 breakfasts, 6 lunches and 6 dinners',
            'Bottled drinking water and tea/coffee on game drives',
            'Special festive dinner at camp, subject to lodge programme',
            'Pre-departure planning support from Twina Safaris',
        ];
    }

    private function exclusions(): array
    {
        return [
            'International and domestic flights',
            'Tanzania visa fees and travel insurance',
            'Tips and gratuities for guides and camp staff',
            'Personal expenses, soft drinks and alcohol',
            'Optional hot-air balloon safari in the Serengeti',
            'Zanzibar beach extension and extra nights',
            'Festive supplements charged by lodges where applicable',
        ];
    }

    private function faqs(): array
    {
        return [
            [
                'question' => 'Is December a good time for a safari in Tanzania?',
                'answer' => 'Yes. The landscapes are green after the short rains, wildlife is plentiful and the migration herds are typically arriving on the southern Serengeti plains.',
            ],
            [
                'question' => 'Will we see the Great Migration at Christmas?',
                'answer' => 'The herds are usually arriving around Ndutu and the southern plains at Christmas. Their exact position follows the rains, so no one can guarantee a sighting, but this is one of the best regions to be at that time.',
            ],
            [
                'question' => 'When is calving season?',
                'answer' => 'Calving begins in January, peaks from mid-January to the end of February and continues into March. A New Year trip that stretches into January gives you the best chance of seeing newborn calves.',
            ],
            [
                'question' => 'How do I pay for this festive safari?',
                'answer' => 'A 30% deposit secures your place and the balance is due 65 days before departure. Because festive camps fill early, we recommend booking as soon as your dates are confirmed.',
            ],
            [
                'question' => 'Is this safari suitable for children?',
                'answer' => 'Yes. We use a private vehicle, keep drive days comfortable and can arrange family rooms and child-friendly activities. Minimum age is five, with younger children considered on request.',
            ],
        ];
    }

    private function packingList(): array
    {
        return [
            'Binoculars and a camera with a zoom lens',
            'Power bank and spare batteries',
            'Light layers for warm days',
            'Warm fleece for cool early mornings',
            'Lightweight rain jacket',
            'Personal medications and a small first-aid kit',
            'Insect repellent and sunscreen',
            'Reusable water bottle',
            'Travel insurance documents',
            'Passport and visa documents',
        ];
    }

    private function goodToKnow(): array
    {
        return [
            'The herds move with the rains, so your guide may change the route to follow fresh wildlife reports.',
            'Christmas and New Year is peak season; good camps around Ndutu fill months ahead.',
            'Afternoon showers are normal in December and January. They usually clear quickly and leave the plains green.',
            'A 30% deposit secures your place; the balance is due 65 days before departure.',
            'A Zanzibar beach extension can be added before or after the safari.',
        ];
    }

    private function extras(): array
    {
        return [
            [
                'name' => 'Zanzibar beach extension (3 nights)',
                'description' => 'Finish your festive trip on the white sands of Zanzibar with flights, transfers and beach accommodation included.',
                'price' => 850,
                'price_type' => 'per_person',
            ],
            [
                'name' => 'Serengeti hot-air balloon safari',
                'description' => 'Sunrise balloon flight over the plains with a champagne breakfast after landing.',
                'price' => 620,
                'price_type' => 'per_person',
            ],
            [
                'name' => 'Private vehicle upgrade',
                'description' => 'Extra space and privacy for families or small groups travelling together.',
                'price' => 450,
                'price_type' => 'per_person',
            ],
        ];
    }

    private function accommodationOptions(): array
    {
        $galleryImage = '/storage/'.self::IMAGE_DIR.'/serengeti.webp';

        return [
            [
                'id' => 'acc_festive_standard',
                'name' => 'Standard Camp & Lodge',
                'price' => 6450,
                'hotels' => [
                    'day_0' => 'Arusha Planet Lodge',
                    'day_1' => 'Tarangire Safari Lodge',
                    'day_2' => 'Ngorongoro Highland Lodge',
                    'day_3' => 'Ngorongoro Highland Lodge',
                    'day_4' => 'Ndutu Safari Lodge',
                    'day_5' => 'Ndutu Safari Lodge',
                    'day_6' => 'Departure / Arusha',
                ],
                'image' => $galleryImage,
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'id' => 'acc_festive_comfort',
                'name' => 'Comfort Lodge',
                'price' => 7850,
                'hotels' => [
                    'day_0' => 'Kibo Palace Hotel',
                    'day_1' => 'Maramboi Tented Lodge',
                    'day_2' => 'Ang’ata Ngorongoro Camp',
                    'day_3' => 'Ang’ata Ngorongoro Camp',
                    'day_4' => 'Ndutu Kati Camp',
                    'day_5' => 'Ndutu Kati Camp',
                    'day_6' => 'Departure / Arusha',
                ],
                'image' => $galleryImage,
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'id' => 'acc_festive_luxury',
                'name' => 'Luxury Tented Camp',
                'price' => 10250,
                'hotels' => [
                    'day_0' => 'Gran Meliá Arusha',
                    'day_1' => 'Tarangire Treetops',
                    'day_2' => 'Ngorongoro Serena Safari Lodge',
                    'day_3' => 'Ngorongoro Serena Safari Lodge',
                    'day_4' => 'Serengeti Migration Camp',
                    'day_5' => 'Serengeti Migration Camp',
                    'day_6' => 'Departure / Arusha',
                ],
                'image' => $galleryImage,
                'is_active' => true,
                'is_default' => false,
            ],
        ];
    }
}
