<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Serengeti National Park',
                'slug' => 'serengeti',
                'country' => 'Tanzania',
                'description' => 'The Serengeti hosts the largest terrestrial mammal migration in the world.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mount Kilimanjaro',
                'slug' => 'kilimanjaro',
                'country' => 'Tanzania',
                'description' => 'Mount Kilimanjaro is a dormant volcano in Tanzania.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Zanzibar',
                'slug' => 'zanzibar',
                'country' => 'Tanzania',
                'description' => 'Zanzibar is an archipelago in the Indian Ocean.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ngorongoro Crater',
                'slug' => 'ngorongoro',
                'country' => 'Tanzania',
                'description' => 'The Ngorongoro Conservation Area is a protected area.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tarangire',
                'slug' => 'tarangire',
                'country' => 'Tanzania',
                'description' => 'Tarangire National Park is a national park in Tanzania.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
