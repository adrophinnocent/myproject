<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Serengeti & Ngorongoro Classic',
                'slug' => 'serengeti-ngorongoro-classic',
                'category' => 'safari',
                'duration' => '7 Days',
                'price' => 5700,
                'description' => 'Experience the best of the Serengeti and Ngorongoro Crater.',
                'is_bestseller' => true,
                'max_group_size' => 8,
                'difficulty' => 'Easy',
            ],
            [
                'name' => 'Machame Route Trek',
                'slug' => 'machame-route-trek',
                'category' => 'kilimanjaro',
                'duration' => '8 Days',
                'price' => 3990,
                'description' => 'Climb Mount Kilimanjaro via the scenic Machame Route.',
                'is_popular' => true,
                'difficulty' => 'Challenging',
            ],
            [
                'name' => 'Ultimate Safari & Beach',
                'slug' => 'ultimate-safari-beach',
                'category' => 'combo',
                'duration' => '12 Days',
                'price' => 8900,
                'description' => 'Combine safari with Zanzibar beach relaxation.',
                'is_new' => true,
                'max_group_size' => 6,
            ],
            [
                'name' => 'Zanzibar Luxury Escape',
                'slug' => 'zanzibar-luxury-escape',
                'category' => 'zanzibar',
                'duration' => '5 Days',
                'price' => 2950,
                'description' => 'Relax on Zanzibar\'s pristine beaches.',
            ],
            [
                'name' => 'Northern Circuit Highlights',
                'slug' => 'northern-circuit-highlights',
                'category' => 'safari',
                'duration' => '4 Days',
                'price' => 3200,
                'description' => 'Quick tour of the northern circuit.',
            ],
            [
                'name' => 'Marangu Route Trek',
                'slug' => 'marangu-route-trek',
                'category' => 'kilimanjaro',
                'duration' => '6 Days',
                'price' => 3150,
                'description' => 'The classic Coca-Cola route.',
                'is_limited' => true,
                'difficulty' => 'Moderate',
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
