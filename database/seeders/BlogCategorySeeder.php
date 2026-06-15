<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Safari Tips', 'slug' => 'safari-tips'],
            ['name' => 'Kilimanjaro', 'slug' => 'kilimanjaro'],
            ['name' => 'Zanzibar', 'slug' => 'zanzibar'],
            ['name' => 'Travel Stories', 'slug' => 'travel-stories'],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
