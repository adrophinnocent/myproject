<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $tours = [
            [
                'title' => 'Serengeti & Ngorongoro Classic Safari',
                'slug' => 'serengeti-ngorongoro-classic-safari',
                'category_id' => 1,
                'destination_id' => 1,
                'short_description' => 'Experience the best of the Serengeti and Ngorongoro Crater.',
                'price' => 5700,
                'duration_days' => 7,
                'duration_nights' => 6,
                'is_published' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Machame Route Kilimanjaro Trek',
                'slug' => 'machame-route-kilimanjaro-trek',
                'category_id' => 2,
                'destination_id' => 2,
                'short_description' => 'Climb Mount Kilimanjaro via the scenic Machame Route.',
                'price' => 3990,
                'duration_days' => 8,
                'duration_nights' => 7,
                'is_published' => true,
                'is_featured' => true,
            ],
        ];

        foreach ($tours as $tour) {
            Tour::create($tour);
        }
    }
}
