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
            TourImageSeeder::class,
            BookingSeeder::class,
            // ReviewSeeder::class,
            TestimonialSeeder::class,
            BlogPostSeeder::class,
            ChristmasSafariArticleSeeder::class,
            SliderSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
