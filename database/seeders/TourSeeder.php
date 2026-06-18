<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $categories = \App\Models\Category::all();
        $destinations = \App\Models\Destination::all();

        foreach ($categories as $cat) {
            Tour::create([
                'title' => $cat->name . ' Adventure',
                'slug' => \Illuminate\Support\Str::slug($cat->name . ' Adventure'),
                'category_id' => $cat->id,
                'destination_id' => $destinations->random()->id ?? 1,
                'short_description' => 'A perfect ' . strtolower($cat->name) . ' experience designed for you.',
                'description' => 'Enjoy an unforgettable ' . strtolower($cat->name) . ' with Twina Safaris.',
                'price' => rand(1500, 5000),
                'duration_days' => rand(3, 10),
                'is_published' => true,
                'is_featured' => true,
                'difficulty_level' => 'moderate',
                'accommodation_type' => 'Luxury Lodge',
                'departure_location' => 'Moshi',
            ]);
        }
    }
}
