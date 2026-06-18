<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Twina Safaris',
            'site_email' => 'info@twinasafaris.com',
            'site_phone' => '+255 795 482 197',
            'site_whatsapp' => '255795482197',
            'hero_title' => 'Explore Tanzania',
            'hero_subtitle' => 'Beyond Expectations',
            'hero_description' => 'Unforgettable luxury safaris designed specifically for you.',
            'hero_eyebrow' => 'Tanzania\'s #1 Boutique Safari Operator',
            'season_good_text' => 'June to October: The absolute best time for wildlife viewing.',
            'season_moderate_text' => 'Jan-Feb & Nov-Dec.',
            'season_low_text' => 'March to May: Long rains.',
            'home_footer_banner' => 'images/footer-banner.jpg',
            'contact_banner' => 'images/contact-banner.jpg',
            'featured_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
