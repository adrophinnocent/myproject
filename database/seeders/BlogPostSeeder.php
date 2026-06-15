<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::first();
        $categories = BlogCategory::all()->keyBy('slug');

        $blogPosts = [
            [
                'author_id' => $adminUser?->id,
                'category_id' => $categories->get('safari-tips')?->id,
                'title' => 'Best Time to Visit the Serengeti',
                'slug' => 'best-time-to-visit-serengeti',
                'excerpt' => 'Discover the perfect season for your Serengeti safari adventure.',
                'content' => 'The Serengeti is amazing year-round, but the best time to visit depends on what you want to see. The Great Migration is a spectacular sight!',
                'tags' => ['safari', 'serengeti', 'travel tips'],
                'is_published' => true,
                'published_at' => now(),
                'views_count' => 150,
            ],
            [
                'author_id' => $adminUser?->id,
                'category_id' => $categories->get('kilimanjaro')?->id,
                'title' => 'Kilimanjaro Trekking Tips',
                'slug' => 'kilimanjaro-trekking-tips',
                'excerpt' => 'Essential tips for a successful Kilimanjaro summit attempt.',
                'content' => 'Climbing Mount Kilimanjaro is a challenging but rewarding experience. Here are our top tips!',
                'tags' => ['trekking', 'kilimanjaro', 'adventure'],
                'is_published' => true,
                'published_at' => now()->subWeek(),
                'views_count' => 230,
            ],
            [
                'author_id' => $adminUser?->id,
                'category_id' => $categories->get('zanzibar')?->id,
                'title' => 'Zanzibar: The Spice Island',
                'slug' => 'zanzibar-spice-island',
                'excerpt' => 'Explore the rich culture and natural beauty of Zanzibar.',
                'content' => 'Zanzibar is famous for its spices, history, and pristine beaches!',
                'tags' => ['zanzibar', 'beach', 'culture'],
                'is_published' => true,
                'published_at' => now()->subMonth(),
                'views_count' => 180,
            ],
        ];

        foreach ($blogPosts as $post) {
            BlogPost::create($post);
        }
    }
}
