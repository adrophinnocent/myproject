<?php

namespace Tests\Feature;

use App\Models\Accommodation;
use App\Models\PackageRoomOption;
use App\Models\RoomType;
use App\Models\Tour;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniversalPackageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_accommodation_hotel_record(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@tourism.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.accommodations.store'), [
            'name' => 'Serengeti Serena Safari Lodge',
            'type' => 'lodge',
            'category_level' => 'luxury',
            'location_name' => 'Serengeti National Park',
            'description' => 'Luxury safari lodge with valley views',
            'star_rating' => 5.0,
            'supplier_cost_per_night' => 250.00,
            'is_active' => '1',
        ]);

        $this->assertDatabaseHas('accommodations', [
            'name' => 'Serengeti Serena Safari Lodge',
            'type' => 'lodge',
            'category_level' => 'luxury',
            'supplier_cost_per_night' => 250.00,
        ]);

        $response->assertRedirect(route('admin.accommodations.index'));
    }

    public function test_admin_can_create_tour_package_with_nightly_hotels_and_private_costing(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@tourism.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $tour = Tour::create([
            'title' => '5 Days Serengeti Wildlife Safari',
            'slug' => '5-days-serengeti-wildlife-safari',
            'price' => 2200.00,
            'duration_days' => 5,
            'is_published' => true,
        ]);

        $lodge = Accommodation::create([
            'name' => 'Gran Melia Arusha',
            'type' => 'hotel',
            'category_level' => 'luxury',
            'supplier_cost_per_night' => 200.00,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.tour-packages.store'), [
            'name' => 'VIP Luxury Safari Option',
            'tour_id' => $tour->id,
            'selling_price' => 3500.00,
            'child_price' => 2450.00,
            'single_supplement' => 450.00,
            'currency' => 'USD',
            'supplier_hotel_cost' => 1000.00,
            'park_fees_cost' => 500.00,
            'transport_cost' => 400.00,
            'guide_cost' => 200.00,
            'meals_cost' => 150.00,
            'transfers_cost' => 100.00,
            'is_active' => '1',
            'itinerary_days' => [
                ['title' => 'Day 1: Arusha Arrival', 'accommodation_id' => $lodge->id, 'accommodation_notes' => 'Bed & Breakfast'],
            ]
        ]);

        $this->assertDatabaseHas('tour_packages', [
            'name' => 'VIP Luxury Safari Option',
            'tour_id' => $tour->id,
            'selling_price' => 3500.00,
            'supplier_hotel_cost' => 1000.00,
        ]);

        $package = TourPackage::where('name', 'VIP Luxury Safari Option')->first();
        $this->assertEquals(2350.00, $package->total_internal_cost); // 1000+500+400+200+150+100
        $this->assertEquals(1150.00, $package->expected_profit); // 3500 - 2350

        $response->assertRedirect(route('admin.tour-packages.index'));
    }

    public function test_guest_can_select_package_and_room_type_on_booking_page(): void
    {
        $tour = Tour::create([
            'title' => '7 Days Machame Route',
            'slug' => '7-days-machame-route',
            'price' => 2000.00,
            'duration_days' => 7,
            'is_published' => true,
        ]);

        $pkg = TourPackage::create([
            'tour_id' => $tour->id,
            'name' => 'Luxury Lodge Package',
            'selling_price' => 2800.00,
            'child_price' => 1960.00,
            'currency' => 'USD',
            'is_active' => true,
        ]);

        $roomType = RoomType::create([
            'name' => 'Single Room',
            'max_occupants' => 1,
            'default_supplement_price' => 300.00,
            'is_active' => true,
        ]);

        PackageRoomOption::create([
            'tour_package_id' => $pkg->id,
            'room_type_id' => $roomType->id,
            'supplement_price' => 300.00,
            'is_available' => true,
        ]);

        $response = $this->post(route('booking.store', $tour->slug), [
            'first_name' => 'Sarah',
            'last_name' => 'Connor',
            'email' => 'sarah@example.com',
            'phone' => '+1987654321',
            'country' => 'United Kingdom',
            'number_of_adults' => 1,
            'number_of_children' => 0,
            'travel_date' => date('Y-m-d', strtotime('+40 days')),
            'tour_package_id' => $pkg->id,
            'room_type_id' => $roomType->id,
            'payment_method' => 'bank_transfer',
        ]);

        $this->assertDatabaseHas('bookings', [
            'first_name' => 'Sarah',
            'last_name' => 'Connor',
            'email' => 'sarah@example.com',
            'tour_package_id' => $pkg->id,
            'package_name' => 'Luxury Lodge Package',
            'room_type_id' => $roomType->id,
            'room_type_name' => 'Single Room',
            'total_price' => 3100.00, // 2800 package + 300 single room supplement
        ]);

        $response->assertRedirect();
    }
}
