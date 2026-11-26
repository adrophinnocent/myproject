<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            DestinationSeeder::class,
            CategorySeeder::class,
            BlogCategorySeeder::class,
            TourSeeder::class,
            ChristmasSafariTourSeeder::class,
            TourImageSeeder::class,
            BookingSeeder::class,
            // ReviewSeeder::class,
            TestimonialSeeder::class,
            BlogPostSeeder::class,
            ChristmasSafariArticleSeeder::class,
            SliderSeeder::class,
            SettingSeeder::class,
            // Repairs known thin/broken SEO fields without overwriting valid edits.
            SeoContentCleanupSeeder::class,
        ]);
    }
}
