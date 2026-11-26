<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ChristmasSafariArticleSeeder extends Seeder
{
    private const SLUG = 'christmas-new-year-safari-tanzania-2026-2027';

    public function run(): void
    {
        // Never overwrite an editor's work on a site that already has the article.
        if (BlogPost::where('slug', self::SLUG)->exists()) {
            return;
        }

        $author = User::where('name', 'Admin Twina')->first()
            ?: User::firstOrCreate(
                ['email' => 'admin.twina@twinasafaris.com'],
                [
                    'name' => 'Admin Twina',
                    'password' => Hash::make(Str::random(24)),
                    'is_admin' => true,
                ]
            );

        $category = BlogCategory::firstOrCreate(
            ['slug' => 'safari'],
            ['name' => 'Safari']
        );

        $source = database_path('seeders/data/blog/christmas-safari');

        $hero = $this->storePhoto($source.'/hero.webp', 'blog/cover', 1600);
        $serengeti = $this->storePhoto($source.'/serengeti.webp', 'blog/content', 1200, 800);
        $ngorongoro = $this->storePhoto($source.'/ngorongoro.webp', 'blog/content', 1200, 800);
        $festive = $this->storePhoto($source.'/festive.jpg', 'blog/content', 1200, 800);

        $content = $this->content($serengeti, $ngorongoro, $festive);

        BlogPost::create([
            'title' => 'Christmas and New Year Safari in Tanzania 2026/2027',
            'slug' => self::SLUG,
            'category_id' => $category->id,
            'author_id' => $author->id,
            'excerpt' => 'Trade the usual holiday routine for lion cubs, endless plains and a sundowner under the African sky. Here is everything you need to plan a Christmas and New Year safari in Tanzania.',
            'hero_caption' => 'Sunrise over the southern Serengeti plains',
            'content' => $content,
            'featured_image' => $hero,
            'meta_title' => 'Christmas & New Year Safari Tanzania 2026/27 | Twina Safaris',
            'meta_description' => 'Plan a Christmas and New Year safari in Tanzania 2026/2027: Serengeti herds, Ndutu calving, Ngorongoro, Zanzibar and expert tips.',
            'tags' => [
                'Christmas safari Tanzania',
                'New Year safari Tanzania',
                'Tanzania safari 2026',
                'Tanzania safari 2027',
                'Serengeti migration December',
                'Ndutu calving season',
                'Ngorongoro Crater',
                'Zanzibar beach holiday',
                'Festive safari',
                'Family safari Tanzania',
                'Twina Safaris',
                'Tanzania holiday packages',
            ],
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    private function storePhoto(string $source, string $directory, int $width, ?int $height = null): string
    {
        $image = (new ImageManager(new Driver))->decodePath($source);

        if ($height !== null) {
            $image->cover($width, $height);
        } else {
            $image->scale(width: $width);
        }

        $path = $directory.'/'.Str::lower(Str::random(20)).'.webp';
        Storage::disk('public')->put(
            $path,
            $image->encode(new WebpEncoder(quality: 80))->toString()
        );

        // Relative URLs stay valid when the site domain changes.
        return '/storage/'.$path;
    }

    private function content(string $serengeti, string $ngorongoro, string $festive): string
    {
        return <<<HTML
<p>Christmas 2026 falls on a Friday, and so does New Year’s Day 2027, which makes the festive break a natural window for a longer trip. Tanzania is one of the best places in the world to spend it. The landscapes are green after the short rains, the Great Migration herds are gathering in the southern Serengeti, and the weather is warm enough to enjoy a beach finish on Zanzibar.</p>
<p>This guide covers what wildlife to expect, where to go, a sample itinerary, and what to know before you book.</p>

<h2 class="article-heading-lg">1. Why Spend Christmas and New Year in Tanzania?</h2>
<p>Festive safaris are about doing something unforgettable together. Instead of crowded shopping centres, you wake up to birdsong, spend the day on game drives, and end it around a campfire. Many lodges and camps also arrange special festive dinners and bush celebrations, so you still get the holiday atmosphere with a wild twist.</p>
<ul class="article-check-list">
  <li>Green, photogenic landscapes after the short rains</li>
  <li>Migration herds arriving in the southern Serengeti</li>
  <li>Warm weather, perfect for a beach extension</li>
  <li>Family-friendly safari options</li>
  <li>A holiday you will talk about for years</li>
</ul>

<figure class="image article-photo">
  <img src="{$serengeti}" alt="Serengeti plains with wildebeest" loading="lazy" decoding="async">
  <figcaption>Wildebeest herds spread across the southern plains</figcaption>
</figure>

<h2 class="article-heading-lg">2. What Wildlife Will You See?</h2>
<p>December is a special month in the Serengeti ecosystem. The herds typically arrive on the southern plains, which turn lush and green after the November rains. Calving season begins in January, peaks from mid-January to the end of February, and runs into March. By February, thousands of calves can be born in a single day.</p>
<p>All those newborns attract predators, so sightings of lions, cheetahs, hyenas and other hunters are common during this period. The exact position of the herds depends on the rains, so no guide can promise it, but the southern Serengeti and Ndutu area are the best places to look.</p>
<div class="article-note article-note--info">
  <strong>ℹ️ Good to know</strong>
  <p>At Christmas the herds are usually arriving in the south, while the main calving wave comes in January and February. A New Year trip that stretches into January gives you the best chance of seeing newborn calves.</p>
</div>

<h2 class="article-heading-lg">3. Where to Go</h2>
<ul class="article-bullet-list">
  <li>Serengeti National Park, especially the southern plains and Ndutu area</li>
  <li>Ngorongoro Crater, for its dense wildlife in a single caldera</li>
  <li>Tarangire National Park, famous for elephants and baobab trees</li>
  <li>Lake Manyara National Park, for birdlife and tree-climbing lions</li>
  <li>Zanzibar, for a relaxing beach finish</li>
</ul>
<blockquote class="article-quote"><p>“Jambo!” is how Tanzania greets you, and how every safari begins.</p></blockquote>

<h2 class="article-heading-lg">4. Sample 7-Day Christmas Safari Itinerary</h2>
<p>This is a sample only. A good operator will adjust the route to your dates, budget and interests.</p>
<ol class="article-steps">
  <li><span class="article-step-num">1</span>Arrive at Kilimanjaro International Airport and transfer to Arusha</li>
  <li><span class="article-step-num">2</span>Game drive in Tarangire National Park</li>
  <li><span class="article-step-num">3</span>Transfer toward Ngorongoro, with a stop for Lake Manyara or a cultural visit</li>
  <li><span class="article-step-num">4</span>Descend into Ngorongoro Crater for a full-day game drive</li>
  <li><span class="article-step-num">5</span>Drive to Ndutu and the southern Serengeti, with game drives along the way</li>
  <li><span class="article-step-num">6</span>Full day exploring the plains, ideally including sunrise and sunset drives</li>
  <li><span class="article-step-num">7</span>Return to Arusha and onward flight home, or extend to Zanzibar</li>
</ol>

<figure class="image article-photo">
  <img src="{$ngorongoro}" alt="Ngorongoro Crater floor" loading="lazy" decoding="async">
  <figcaption>The floor of Ngorongoro Crater</figcaption>
</figure>

<h2 class="article-heading-lg">5. Weather in December and January</h2>
<p>Expect warm days across most of northern Tanzania, with occasional showers because this is the short-rains period. Rain often falls in the afternoon and leaves the air clear, the dust settled and the scenery vivid. Higher places such as the Ngorongoro rim are noticeably cooler, especially at night and in the early morning.</p>
<ul class="article-bullet-list">
  <li>Light layers for warm days</li>
  <li>A warm fleece or jacket for chilly mornings</li>
  <li>A lightweight rain jacket</li>
  <li>Hat, sunglasses and sunscreen</li>
  <li>Closed shoes and neutral-coloured clothes</li>
</ul>

<h2 class="article-heading-lg">6. Book Early</h2>
<div class="article-note article-note--warning">
  <strong>⚠️ Peak season</strong>
  <p>Christmas and New Year is one of the busiest times of the year for travel to Tanzania. Good lodges and the seasonal camps around Ndutu fill up well ahead, and many properties add festive supplements. Start planning as early as you can.</p>
</div>
<p>Since it is already late November, availability for this year’s holidays may be limited. Contact an operator soon, or consider a slightly later date in January, when the calving season is at its best.</p>

<h2 class="article-heading-lg">7. Add a Beach: Zanzibar</h2>
<p>After days of early starts and dusty game drives, a few nights on Zanzibar’s white-sand beaches make a perfect ending. Many travellers fly from Arusha or Kilimanjaro directly to the island, which keeps the transfer simple.</p>

<h2 class="article-heading-lg">8. What to Pack</h2>
<ul class="article-check-list">
  <li>Binoculars</li>
  <li>Camera with a zoom lens</li>
  <li>Power bank and spare batteries</li>
  <li>Personal medications</li>
  <li>Insect repellent</li>
  <li>Reusable water bottle</li>
  <li>Travel insurance documents</li>
  <li>Passport and visa documents</li>
</ul>

<h2 class="article-heading-lg">9. Travel Responsibly</h2>
<ul class="article-check-list">
  <li>Stay inside the vehicle unless your guide says otherwise</li>
  <li>Keep a respectful distance from wildlife</li>
  <li>Carry out all your litter</li>
  <li>Avoid single-use plastics</li>
  <li>Support local communities and ethical operators</li>
</ul>

<figure class="image article-photo">
  <img src="{$festive}" alt="Festive dinner at a safari camp" loading="lazy" decoding="async">
  <figcaption>A festive evening at a safari camp</figcaption>
</figure>

<h2 class="article-heading-lg">Final Thoughts</h2>
<p>A Christmas and New Year safari in Tanzania gives you more than a holiday. It gives you shared stories, wild encounters and a fresh start to the year in one of Africa’s most beautiful places. Plan early, choose an experienced operator, and let your guide’s knowledge do the rest.</p>

<h2 class="article-heading-lg">Frequently Asked Questions</h2>
<details class="article-faq"><summary>Is December a good time for a safari in Tanzania?</summary><div class="article-faq-answer"><p>Yes. The landscapes are green, wildlife is plentiful, and the migration herds are typically arriving in the southern Serengeti. Expect some rain, which usually comes in short showers.</p></div></details>
<details class="article-faq"><summary>Will I see the Great Migration at Christmas?</summary><div class="article-faq-answer"><p>The herds are usually arriving on the southern plains and around Ndutu at Christmas. Their exact position follows the rains, so nobody can guarantee a sighting, but this is one of the best regions to be at that time.</p></div></details>
<details class="article-faq"><summary>When is calving season?</summary><div class="article-faq-answer"><p>Calving begins in January, peaks from mid-January to the end of February and continues into March. Extending a New Year trip into January gives you the best chance of seeing newborn calves.</p></div></details>
<details class="article-faq"><summary>How early should I book a Christmas safari?</summary><div class="article-faq-answer"><p>As early as you can. Festive lodges and the seasonal camps around Ndutu fill months ahead, and by late November availability for the holidays can already be limited.</p></div></details>
<details class="article-faq"><summary>Is a Christmas safari suitable for children?</summary><div class="article-faq-answer"><p>Yes. Many camps offer family rooms, child-friendly game drives and festive activities. A private vehicle and a relaxed route with shorter drive days work best.</p></div></details>

<div class="article-cta">
  <h3>Ready for a Christmas and New Year Safari?</h3>
  <p>Tell us your dates, group size and interests, and we will design a safari that fits you, from the Serengeti plains to the beaches of Zanzibar.</p>
  <a class="btn-gold" href="/tours">Start Planning</a>
</div>
HTML;
    }
}
