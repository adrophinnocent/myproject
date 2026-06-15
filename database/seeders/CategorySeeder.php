<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Safari',
                'slug' => 'safari',
                'description' => 'Experience the best African wildlife safaris',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kilimanjaro',
                'slug' => 'kilimanjaro',
                'description' => 'Climb the highest peak in Africa',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Zanzibar',
                'slug' => 'zanzibar',
                'description' => 'Relax on paradise beaches',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Combo',
                'slug' => 'combo',
                'description' => 'Combine safari with beach relaxation',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
