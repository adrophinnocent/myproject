<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $tours = Tour::all();

        $reviews = [
            [
                'tour_id' => $tours->first()?->id ?? 1,
                'user_id' => $user->id ?? 1,
                'rating' => 5,
                'title' => 'Absolutely Life-Changing!',
                'content' => 'Absolutely life-changing experience! Our guide knew every animal\'s behavior, we saw all the Big Five, and the Kilimanjaro summit was unforgettable. Already planning our return!',
                'is_featured' => true,
            ],
            [
                'tour_id' => $tours->get(2)?->id ?? 3,
                'user_id' => $user->id ?? 1,
                'rating' => 5,
                'title' => 'Perfect Honeymoon',
                'content' => 'Perfect honeymoon! Safari followed by Zanzibar beach time. The lodges were incredible, service impeccable, and the memories priceless.',
                'is_featured' => true,
            ],
            [
                'tour_id' => $tours->first()?->id ?? 1,
                'user_id' => $user->id ?? 1,
                'rating' => 5,
                'title' => 'Family-Friendly & Professional',
                'content' => 'Family-friendly, professional, and so much fun! Kids loved every minute, especially the wildebeest migration. Thank you!',
                'is_featured' => true,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
