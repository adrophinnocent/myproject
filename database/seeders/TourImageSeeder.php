<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Database\Seeder;

class TourImageSeeder extends Seeder
{
    public function run(): void
    {
        $tours = Tour::all();

        foreach ($tours as $tour) {
            TourImage::create([
                'tour_id' => $tour->id,
                'image_path' => 'tour-placeholder.jpg',
                'alt_text' => $tour->title,
                'sort_order' => 1,
                'is_primary' => true,
            ]);
        }
    }
}
