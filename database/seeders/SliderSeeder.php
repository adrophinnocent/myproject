<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        Slider::create([
            'title' => 'Unforgettable Safaris',
            'subtitle' => 'Explore the wild heart of Africa',
            'image' => 'sliders/safari-hero.jpg',
            'page' => 'home',
            'cta_text' => 'Book Now',
            'cta_url' => '/tours',
            'order' => 1,
            'active' => true,
        ]);

        Slider::create([
            'title' => 'Mount Kilimanjaro',
            'subtitle' => 'Conquer the roof of Africa',
            'image' => 'sliders/kili-hero.jpg',
            'page' => 'home',
            'cta_text' => 'Plan Your Climb',
            'cta_url' => '/tours?category=kilimanjaro',
            'order' => 2,
            'active' => true,
        ]);
    }
}
