<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Safari Tours', 'slug' => 'safari-tours', 'description' => 'Big Five & Migration', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Kilimanjaro Trekking', 'slug' => 'kilimanjaro-trekking', 'description' => 'Roof of Africa', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Zanzibar Holidays', 'slug' => 'zanzibar-holidays', 'description' => 'Island paradise', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Luxury Safaris', 'slug' => 'luxury-safaris', 'description' => 'Ultra-premium escapes', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Family Safaris', 'slug' => 'family-safaris', 'description' => 'Memories for all ages', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Honeymoon Safaris', 'slug' => 'honeymoon-safaris', 'description' => 'Romantic adventures', 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Day Trips', 'slug' => 'day-trips', 'description' => 'Short but thrilling', 'is_active' => true, 'sort_order' => 7],
            ['name' => 'Group Tours', 'slug' => 'group-tours', 'description' => 'Shared adventures', 'is_active' => true, 'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
