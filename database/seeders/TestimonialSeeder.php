<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Mitchell',
                'location' => 'New York, USA',
                'content' => 'Absolutely life-changing experience! Our guide knew every animal\'s behavior, we saw all the Big Five.',
                'is_featured' => true,
            ],
            [
                'name' => 'Marco & Julia',
                'location' => 'Milan, Italy',
                'content' => 'Perfect honeymoon! Safari followed by Zanzibar beach time. The lodges were incredible.',
                'is_featured' => true,
            ],
            [
                'name' => 'Kim & Family',
                'location' => 'London, UK',
                'content' => 'Family-friendly, professional, and so much fun! Kids loved every minute.',
                'is_featured' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
