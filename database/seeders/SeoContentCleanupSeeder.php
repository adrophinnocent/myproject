<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Repairs known thin or broken SEO fields without overwriting valid edits made
 * in the admin panel. Safe to run repeatedly on an existing production database.
 */
class SeoContentCleanupSeeder extends Seeder
{
    private const SITE_TITLE = 'Twina Safaris | Tanzania Safaris, Kilimanjaro & Zanzibar';

    private const SITE_DESCRIPTION = 'Twina Safaris plans tailored Tanzania safaris, Kilimanjaro treks and Zanzibar holidays with local guides, clear prices and private itineraries.';

    /**
     * Slugs that were generated before a title spelling fix. The published URL
     * stays on the original slug even when the visible title is corrected.
     *
     * @var array<string, string>
     */
    private const SLUG_ALIASES = [
        'serengeto-wildlife-safari-experience' => 'serengeti-wildlife-safari-experience',
        'serengeti-wildlife-safari-experience' => 'serengeto-wildlife-safari-experience',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    private const TOURS = [
        'serengeti-ngorongoro-classic-safari' => [
            'title' => 'Serengeti & Ngorongoro Classic Safari',
            'short_description' => 'Seven days across Tarangire, the Serengeti and Ngorongoro Crater with a private 4x4 and local guide.',
            'description' => '<p>A relaxed seven-day northern-circuit safari that combines three of Tanzania’s most rewarding wildlife areas: Tarangire, the Serengeti and Ngorongoro Crater. Travel with a private 4x4 and an experienced local guide, with park fees and game drives included.</p><p>This itinerary suits first-time visitors who want strong wildlife viewing without rushing between parks. Accommodation can be adjusted to match your budget, from comfortable camps to premium lodges.</p>',
            'meta_title' => 'Serengeti & Ngorongoro Classic Safari | 7 Days',
            'meta_description' => 'Seven-day Serengeti and Ngorongoro Crater safari with Tarangire game drives, private 4x4 transport, park fees and local guides.',
            'duration_days' => 7,
            'duration_nights' => 6,
            'price' => 5700,
            'highlights' => ['Tarangire elephant herds', 'Serengeti plains', 'Ngorongoro Crater game drive', 'Private 4x4 safari vehicle'],
            'itinerary' => [
                ['title' => 'Arrival in Arusha', 'description' => 'Meet your Twina Safaris guide, review the route and overnight in Arusha.'],
                ['title' => 'Arusha to Tarangire', 'description' => 'Game drives among baobabs and elephant herds, then continue to your camp.'],
                ['title' => 'Tarangire to Serengeti', 'description' => 'Travel north through the Rift Valley and enter the Serengeti for afternoon game viewing.'],
                ['title' => 'Full day in the Serengeti', 'description' => 'Explore the central and southern plains, with picnic lunch and flexible drive times.'],
                ['title' => 'Serengeti to Ngorongoro', 'description' => 'Morning game drive, then transfer to the crater highlands for the night.'],
                ['title' => 'Ngorongoro Crater', 'description' => 'Descend to the crater floor for a full game drive, then return to the highlands.'],
                ['title' => 'Return to Arusha', 'description' => 'Transfer back to Arusha for your flight or onward travel.'],
            ],
            'inclusions' => ['Private 4x4 safari vehicle and driver-guide', 'All park and conservation fees', 'Accommodation and daily meals', 'Bottled water during game drives'],
            'exclusions' => ['International and domestic flights', 'Travel insurance', 'Personal expenses and gratuities'],
        ],
        'machame-route-kilimanjaro-trek' => [
            'title' => 'Machame Route Kilimanjaro Trek',
            'short_description' => 'Eight days on the scenic Machame Route with acclimatisation days and a fully supported trekking crew.',
            'description' => '<p>The Machame Route is one of the most popular ways to climb Mount Kilimanjaro because of its varied scenery and strong acclimatisation profile. This eight-day itinerary moves through rainforest, heath and alpine desert before the summit push.</p><p>Your trek includes guides, porters, park fees, camping or mountain huts, and all meals on the mountain. We brief every climber on pacing, hydration and responsible trekking before departure.</p>',
            'meta_title' => 'Machame Route Kilimanjaro Trek | 8 Days',
            'meta_description' => 'Eight-day Machame Route Kilimanjaro trek with acclimatisation days, expert guides, porters, park fees and summit support.',
            'duration_days' => 8,
            'duration_nights' => 7,
            'price' => 990,
            'highlights' => ['Rainforest and alpine desert scenery', 'Lava Tower acclimatisation', 'Barranco Wall', 'Sunrise summit attempt'],
            'itinerary' => [
                ['title' => 'Moshi arrival and briefing', 'description' => 'Gear check, route briefing and overnight in Moshi.'],
                ['title' => 'Machame Gate to Machame Camp', 'description' => 'Enter the rainforest and walk to the first camp.'],
                ['title' => 'Machame Camp to Shira', 'description' => 'Climb above the forest into the heath and moorland zone.'],
                ['title' => 'Shira to Barranco', 'description' => 'Acclimatisation day via Lava Tower, then descend to Barranco.'],
                ['title' => 'Barranco to Karanga', 'description' => 'Negotiate the Barranco Wall and continue toward the summit cone.'],
                ['title' => 'Karanga to Barafu', 'description' => 'Final high camp with an early rest before the summit push.'],
                ['title' => 'Summit attempt and descent', 'description' => 'Leave before dawn for Uhuru Peak, then descend to Mweka Camp.'],
                ['title' => 'Mweka to Moshi', 'description' => 'Return to the gate, collect certificates and transfer back to Moshi.'],
            ],
            'inclusions' => ['Professional guides and support crew', 'All Kilimanjaro park fees', 'Mountain accommodation and meals', 'Transfer from Moshi to the gate and back'],
            'exclusions' => ['Personal trekking gear', 'Travel insurance', 'Tips for the trekking crew'],
        ],
        'serengeto-wildlife-safari-experience' => [
            'title' => 'Serengeti Wildlife Safari Experience',
            'short_description' => 'Four focused days in the Serengeti with flexible game drives and time to follow the wildlife.',
            'description' => '<p>This four-day safari focuses on the Serengeti ecosystem and gives you time to follow the wildlife rather than tick off parks. Your guide chooses the route each day based on recent sightings, season and your interests.</p><p>It is a good option for travellers with limited time who still want a genuine Serengeti experience. Combine it with Zanzibar or Ngorongoro if you have extra days.</p>',
            'meta_title' => 'Serengeti Wildlife Safari Experience | 4 Days',
            'meta_description' => 'Four-day Serengeti wildlife safari with flexible full-day game drives, local guides, park fees and comfortable safari accommodation.',
            'duration_days' => 4,
            'duration_nights' => 3,
            'price' => 1200,
            'highlights' => ['Flexible full-day game drives', 'Big cats and resident wildlife', 'Seasonal migration areas', 'Private vehicle option'],
            'itinerary' => [
                ['title' => 'Arrival and transfer', 'description' => 'Meet your guide and transfer to your safari camp or lodge.'],
                ['title' => 'First Serengeti game drive', 'description' => 'Explore the nearby plains and settle into the rhythm of the bush.'],
                ['title' => 'Full-day wildlife search', 'description' => 'Follow current sightings with a picnic lunch in the park.'],
                ['title' => 'Morning drive and departure', 'description' => 'Final game drive before your onward transfer.'],
            ],
            'inclusions' => ['Safari vehicle and driver-guide', 'Serengeti park fees', 'Accommodation and meals', 'Bottled water on game drives'],
            'exclusions' => ['Flights', 'Travel insurance', 'Personal expenses and gratuities'],
        ],
        'climb-africas-highest-peak' => [
            'meta_title' => 'Climb Mount Kilimanjaro | 7-Day Machame Trek',
            'meta_description' => 'Seven-day Machame Route trek up Mount Kilimanjaro with acclimatisation, expert local guides, porters, park fees and summit support.',
        ],
        'stone-town-cultural-tour' => [
            'meta_title' => 'Stone Town Cultural Tour Zanzibar | 2 Days',
            'meta_description' => 'Two-day Stone Town cultural tour in Zanzibar: historic streets, spice farms, local food and a dhow sunset with a local guide.',
            'duration_days' => 2,
            'duration_nights' => 1,
            'price' => 45,
        ],
        'christmas-new-year-tanzania-safari-2026-2027' => [
            'meta_title' => 'Christmas & New Year Tanzania Safari 2026/27',
            'meta_description' => 'Three-day Christmas and New Year safari from Arusha to Lake Manyara and Ngorongoro Crater, with five accommodation levels from $1,185.',
        ],
    ];

    /**
     * @var array<string, array<string, string>>
     */
    private const BLOGS = [
        'best-time-to-visit-serengeti' => [
            'meta_title' => 'Best Time to Visit the Serengeti | Twina Safaris',
            'meta_description' => 'Month-by-month guide to the Serengeti: dry-season game viewing, Ndutu calving, migration routes and what each season means for your safari.',
            'excerpt' => 'The Serengeti is rewarding all year, but the right month changes what you see. Here is how to choose.',
            'content' => '<p>The Serengeti is a year-round destination, but the right month changes the wildlife pattern and the feel of the safari. This guide focuses specifically on the Serengeti ecosystem. For Tanzania-wide planning, read our <a href="/best-time-for-tanzania-safari">best time for a Tanzania safari guide</a>.</p><h2>Dry season: June to October</h2><p>This is the most popular window. Grass is short, animals gather near rivers and waterholes, and game viewing is generally excellent. The northern Serengeti often holds the migration late in this period, while the central plains stay busy with resident wildlife. Nights are cool and days are warm and clear.</p><h2>Green season: November to March</h2><p>The short rains arrive in November, but showers are usually brief. By January and February the southern plains and Ndutu are green, and this is the heart of calving season. Thousands of young animals attract predators, which makes the area exceptional for photographers. Accommodation is often better value too.</p><h2>Long rains: March to May</h2><p>March can still be excellent, especially for calving and birdlife. From April into May, some camps close and tracks can become challenging. Prices fall sharply, but you should plan with a flexible operator and allow extra travel time.</p><h2>Matching the season to your interests</h2><ul><li>For big cats and general game viewing, choose June to October.</li><li>For newborn animals and predator action, choose January or February around Ndutu.</li><li>For birdwatching and green landscapes, choose November or March.</li><liFor lower prices and fewer visitors, consider April or May with flexible dates.</li></ul><p>Exact wildlife movements always depend on the rains. Tell us your dates and priorities and we will suggest the part of the ecosystem that gives you the best chance.</p>',
        ],
        'kilimanjaro-trekking-tips' => [
            'meta_title' => 'Kilimanjaro Trekking Tips | Preparation & Acclimatisation',
            'meta_description' => 'Practical Kilimanjaro trekking tips on acclimatisation, pacing, training, gear and route choice from a local Moshi-based trekking team.',
            'excerpt' => 'A successful summit is mostly about preparation, pacing and sensible acclimatisation. Here is what our guides recommend.',
            'content' => '<p>Climbing Mount Kilimanjaro is demanding even though it does not require technical climbing. Most unsuccessful attempts fail because of altitude or poor pacing, not lack of fitness alone. These tips come from our Moshi-based trekking team. For help choosing an operator, see our <a href="/kilimanjaro-trekking-company">Kilimanjaro trekking company guide</a>.</p><h2>Choose the right route and length</h2><p>Short routes give your body less time to adjust. Where schedules allow, prefer eight or nine days over five or six. Machame, Lemosho and Northern Circuit routes generally offer better acclimatisation profiles than the shortest options.</p><h2>Walk slowly and drink often</h2><p>Your guide will set a deliberately slow pace. It can feel frustrating on day one, but it is the safest way to reach the summit. Drink water regularly and tell your guide about headaches, nausea or unusual tiredness immediately.</p><h2>Train for long, steady days</h2><p>Build up to repeated hikes with a light pack on varied terrain. Cardiovascular fitness helps, but time on your feet with a daypack is more useful than gym work alone. Practice descending too, because the descent is hard on knees.</p><h2>Pack for three seasons in one day</h2><p>Bring a warm sleeping bag, insulated jacket, rain shell, sun protection, headlamp and broken-in boots. Layers are more practical than one heavy garment. Keep electronics and spare batteries close to your body at high altitude.</p><h2>Respect the mountain and the crew</h2><p>Use established trails, carry out your litter, and treat your guides and porters as partners. Fair wages and sensible loads are part of a responsible climb. Ask us about our crew policy before you book.</p>',
        ],
        'christmas-new-year-safari-tanzania-2026-2027' => [
            'meta_title' => 'Christmas & New Year Safari Tanzania 2026/27 | Twina Safaris',
            'meta_description' => 'Plan a Christmas and New Year safari in Tanzania 2026/27: Serengeti herds, Ndutu calving, Ngorongoro and expert planning tips.',
        ],
        'zanzibar-spice-island' => [
            'meta_title' => 'Zanzibar: The Spice Island | Culture & History',
            'meta_description' => 'Explore Zanzibar’s Spice Island history: Stone Town, clove plantations, Swahili food and daily life, plus how to combine culture with a safari.',
            'excerpt' => 'Zanzibar is more than beaches. Its spice routes, Stone Town streets and food tell a much deeper story.',
            'content' => '<p>Zanzibar earned the name Spice Island because cloves, nutmeg, cardamom and black pepper shaped its trade for centuries. The islands sit at the meeting point of African, Arab, Indian and European histories, and that mixture is still visible in the architecture, food and daily rhythms. For beach seasons and package options, see our <a href="/zanzibar-holiday-packages">Zanzibar holiday packages guide</a>.</p><h2>Stone Town is the starting point</h2><p>The old town is a dense maze of carved doors, narrow lanes, mosques and former merchant houses. It is best explored slowly, with a local guide who can explain the slave trade, the sultanate and the families that still live there. Many tours finish at the waterfront as the dhows return.</p><h2>How the spice tours work</h2><p>A spice farm visit is not a museum. Guides walk you through planted plots, let you smell and taste fresh spices, and explain how each one reached the islands. Most tours include a local meal, so come hungry and ask about vegetarian options in advance.</p><h2>Beyond the tour</h2><p>Look for Swahili breakfasts, coconut curries, pilau and fresh seafood. Visit a local market early in the morning, take a dhow sunset trip, or combine a few days in Stone Town with a quieter beach on the east or north coast.</p><h2>Adding a safari</h2><p>Zanzibar pairs naturally with a northern-circuit safari. Fly from the mainland after your game drives, or begin on the coast and finish in the Serengeti. We can build the transfer into a single itinerary so your dates and budget stay clear.</p>',
        ],
    ];

    public function run(): void
    {
        $this->settings();
        $this->tours();
        $this->blogs();
    }

    private function settings(): void
    {
        $settings = [
            'seo_title' => self::SITE_TITLE,
            'meta_description' => self::SITE_DESCRIPTION,
        ];

        foreach ($settings as $key => $value) {
            $current = trim((string) Setting::get($key));

            if ($current === '' || $current === 'Kilimanjaro' || strlen($current) > 158) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                Cache::forget('setting_'.$key);
                Cache::forget('all_settings');
            }
        }
    }

    private function tours(): void
    {
        foreach (self::TOURS as $slug => $data) {
            $tour = Tour::withTrashed()->where('slug', $slug)->first();

            if (! $tour && isset(self::SLUG_ALIASES[$slug])) {
                $tour = Tour::withTrashed()->where('slug', self::SLUG_ALIASES[$slug])->first();
            }

            if (! $tour || $tour->trashed()) {
                continue;
            }

            // HasSlug regenerates the slug whenever the title changes. Pin the
            // existing slug first so an admin typo fix never changes a live URL.
            $updates = ['slug' => $tour->slug];

            if ($this->invalidTitle($tour->title)) {
                $updates['title'] = $data['title'] ?? $tour->title;
            }

            if ($this->invalidMeta($tour->meta_title, 60)) {
                $updates['meta_title'] = Str::limit($data['meta_title'], 60, '');
            }

            if ($this->invalidMeta($tour->meta_description, 158)) {
                $updates['meta_description'] = Str::limit($data['meta_description'], 158, '');
            }

            if (isset($data['short_description']) && strlen(trim(strip_tags((string) $tour->short_description))) < 25) {
                $updates['short_description'] = $data['short_description'];
            }

            if (isset($data['description']) && strlen(trim(strip_tags((string) $tour->description))) < 60) {
                $updates['description'] = $data['description'];
            }

            foreach (['highlights', 'itinerary', 'inclusions', 'exclusions'] as $field) {
                if (! isset($data[$field])) {
                    continue;
                }

                $current = $tour->{$field};
                $isEmpty = is_array($current)
                    ? $current === []
                    : trim((string) $current) === '';

                if ($isEmpty) {
                    $updates[$field] = $data[$field];
                }
            }

            if (isset($data['duration_days']) && (int) $tour->duration_days <= 0) {
                $updates['duration_days'] = $data['duration_days'];
            }

            if (isset($data['duration_nights']) && (int) $tour->duration_nights <= 0) {
                $updates['duration_nights'] = $data['duration_nights'];
            }

            if (isset($data['price']) && (float) $tour->price <= 0) {
                $updates['price'] = $data['price'];
            }

            if ($updates !== []) {
                $tour->update($updates);
            }
        }
    }

    private function blogs(): void
    {
        foreach (self::BLOGS as $slug => $data) {
            $post = BlogPost::where('slug', $slug)->first();

            if (! $post) {
                continue;
            }

            $updates = [];

            if ($this->invalidMeta($post->meta_title, 60)) {
                $updates['meta_title'] = Str::limit($data['meta_title'], 60, '');
            }

            if ($this->invalidMeta($post->meta_description, 158)) {
                $updates['meta_description'] = Str::limit($data['meta_description'], 158, '');
            }

            if (strlen(trim(strip_tags((string) $post->content))) < 250) {
                $updates['content'] = $data['content'];
            }

            if (strlen(trim((string) $post->excerpt)) < 40) {
                $updates['excerpt'] = $data['excerpt'];
            }

            if ($updates !== []) {
                $post->update($updates);
            }
        }
    }

    private function invalidMeta(?string $value, int $maxLength): bool
    {
        $value = strtolower(trim((string) $value));

        return $value === ''
            || $value === 'kilimanjaro'
            || $value === 'dfghjkl'
            || strlen($value) > $maxLength;
    }

    private function invalidTitle(?string $value): bool
    {
        $value = trim((string) $value);

        return $value === ''
            || strtolower($value) === 'dfghjkl'
            || stripos($value, 'serengeto') !== false
            || stripos($value, 'derengeto') !== false;
    }
}
