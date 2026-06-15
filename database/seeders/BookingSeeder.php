<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $tours = Tour::all();

        if ($tours->isNotEmpty()) {
            Booking::create([
                'tour_id' => $tours->first()->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'nationality' => 'United States',
                'number_of_adults' => 2,
                'number_of_children' => 0,
                'travel_date' => now()->addMonths(2),
                'status' => 'pending',
                'total_price' => 11400,
            ]);
        }
    }
}
