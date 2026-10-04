<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\ProposalTemplate;
use App\Models\ProposalTemplateDay;
use App\Models\ProposalTemplateAccommodation;
use App\Models\ProposalTemplateInclusion;
use App\Models\ProposalTemplateExclusion;
use App\Models\ProposalTemplatePrice;

return new class extends Migration
{
    public function up(): void
    {
        $templatesData = [
            [
                'title' => '7 Days Tanzania Comfort Safari',
                'subtitle' => '2 Adults · Private 4x4 Safari · Comfort Lodges',
                'duration_days' => 7,
                'duration_nights' => 6,
                'start_location' => 'Arusha',
                'end_location' => 'Arusha',
                'route_summary' => 'Arusha → Tarangire → Lake Manyara → Serengeti → Ngorongoro → Arusha',
                'destinations' => ['Arusha', 'Tarangire', 'Lake Manyara', 'Serengeti', 'Ngorongoro'],
                'safari_style' => 'Private 4x4 Safari',
                'accommodation_level' => 'Comfort',
                'transport_type' => '4x4 Land Cruiser',
                'is_private' => true,
                'highlights' => ['Tarangire Elephants', 'Serengeti Great Migration', 'Ngorongoro Crater Floor', 'Lake Manyara Flamingos'],
                'default_total_price' => 3200.00,
                'default_adult_price' => 1600.00,
                'default_child_price' => 800.00,
                'default_deposit_percentage' => 30.00,
                'payment_terms' => "A 30% deposit is required upon booking confirmation. The remaining balance is due 30 days prior to departure date.",
                'terms_conditions' => "Prices are based on private 4x4 safari and subject to park fee regulations.",
                'cancellation_policy' => "Cancellations 60+ days prior to arrival receive a full refund minus administrative bank charges.",
                'status' => 'active',
                'inclusions_list' => [
                    'All national park entry fees & conservation fees',
                    'Private 4x4 Safari Land Cruiser with pop-up roof',
                    'Professional English-speaking driver/guide',
                    'Full board accommodation at comfort lodges/tented camps',
                    'Unlimited bottled drinking water in vehicle',
                    'All government taxes and VAT'
                ],
                'exclusions_list' => [
                    'International flights & visas',
                    'Travel and medical insurance',
                    'Tips for driver/guide and lodge staff',
                    'Personal expenses and laundry',
                    'Optional hot air balloon safari ($550/person)'
                ],
                'accommodations_list' => [
                    ['property_name' => 'Arusha Planet Lodge', 'location' => 'Arusha', 'category' => 'Comfort', 'room_type' => 'Standard Room', 'nights' => 1, 'meal_plan' => 'Bed & Breakfast'],
                    ['property_name' => 'Tarangire Safari Lodge', 'location' => 'Tarangire', 'category' => 'Comfort', 'room_type' => 'Luxury Tent', 'nights' => 1, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Serengeti Heritage Tented Camp', 'location' => 'Central Serengeti', 'category' => 'Comfort', 'room_type' => 'Luxury Safari Tent', 'nights' => 2, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Rhino Lodge', 'location' => 'Ngorongoro Crater Rim', 'category' => 'Comfort', 'room_type' => 'Standard Room', 'nights' => 1, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Marera Valley Lodge', 'location' => 'Karatu', 'category' => 'Comfort', 'room_type' => 'Cottage Room', 'nights' => 1, 'meal_plan' => 'Full Board'],
                ],
                'days_list' => [
                    [
                        'day_number' => 1,
                        'title' => 'Arrival in Arusha & Safari Briefing',
                        'destination' => 'Arusha',
                        'starting_point' => 'JRO Airport',
                        'ending_point' => 'Arusha Planet Lodge',
                        'route' => 'Kilimanjaro Airport → Arusha',
                        'description' => 'Upon arrival at Kilimanjaro International Airport (JRO), you will be met by your private Twina Safaris driver-guide and transferred to your lodge in Arusha. Evening briefing about your upcoming safari adventure.',
                        'activities' => 'Airport pickup, hotel transfer, safari briefing',
                        'driving_time' => '50 km / 1 hr',
                        'meals' => 'Dinner',
                        'accommodation_property' => 'Arusha Planet Lodge',
                        'room_type' => 'Standard Room',
                        'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 2,
                        'title' => 'Tarangire National Park Game Drive',
                        'destination' => 'Tarangire',
                        'starting_point' => 'Arusha',
                        'ending_point' => 'Tarangire Safari Lodge',
                        'route' => 'Arusha → Tarangire National Park',
                        'description' => 'Depart Arusha after breakfast for Tarangire National Park, famous for its massive elephant herds and majestic baobab trees. Enjoy a full day game drive exploring the Tarangire River circuit with a picnic lunch.',
                        'activities' => 'Game drive, elephant tracking, picnic lunch',
                        'driving_time' => '120 km / 2.5 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Tarangire Safari Lodge',
                        'room_type' => 'Luxury Tent',
                        'cover_image' => 'https://images.unsplash.com/photo-1534567153574-2b12153a87f0?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 3,
                        'title' => 'Lake Manyara to Central Serengeti',
                        'destination' => 'Serengeti',
                        'starting_point' => 'Tarangire',
                        'ending_point' => 'Serengeti Heritage Tented Camp',
                        'route' => 'Tarangire → Ngorongoro Highlands → Central Serengeti',
                        'description' => 'Journey through the scenic Ngorongoro Conservation Area highlands into the endless plains of Serengeti National Park. Afternoon game drive in Seronera Valley searching for lions, cheetahs, and leopards.',
                        'activities' => 'Scenic drive, afternoon Serengeti game drive',
                        'driving_time' => '220 km / 5 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Serengeti Heritage Tented Camp',
                        'room_type' => 'Luxury Safari Tent',
                        'cover_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 4,
                        'title' => 'Full Day Serengeti Wildlife Exploration',
                        'destination' => 'Serengeti',
                        'starting_point' => 'Serengeti Heritage Camp',
                        'ending_point' => 'Serengeti Heritage Camp',
                        'route' => 'Central Serengeti Plains Circuit',
                        'description' => 'A full day dedicated to game viewing in Serengeti National Park. Early morning game drive when predators are most active, followed by breakfast and a full day exploring wildebeest herds and riverine habitats.',
                        'activities' => 'Full day game drive, predator tracking',
                        'driving_time' => 'Game drives throughout the day',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Serengeti Heritage Tented Camp',
                        'room_type' => 'Luxury Safari Tent',
                        'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 5,
                        'title' => 'Serengeti to Ngorongoro Crater Rim',
                        'destination' => 'Ngorongoro',
                        'starting_point' => 'Serengeti',
                        'ending_point' => 'Rhino Lodge',
                        'route' => 'Central Serengeti → Ngorongoro Crater Rim',
                        'description' => 'Morning game drive as you exit Serengeti National Park. Drive towards the Ngorongoro Crater Rim, enjoying panoramic views over the volcanic caldera upon arrival in the late afternoon.',
                        'activities' => 'Morning game drive, rim transfer & scenic view',
                        'driving_time' => '140 km / 3.5 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Rhino Lodge',
                        'room_type' => 'Standard Room',
                        'cover_image' => 'https://images.unsplash.com/photo-1534567153574-2b12153a87f0?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 6,
                        'title' => 'Ngorongoro Crater Floor Tour & Karatu',
                        'destination' => 'Ngorongoro / Karatu',
                        'starting_point' => 'Ngorongoro Crater Rim',
                        'ending_point' => 'Marera Valley Lodge',
                        'route' => 'Crater Floor → Karatu',
                        'description' => 'Descend 600 meters into the Ngorongoro Crater for a breathtaking 5-hour game drive. Spot endangered black rhinos, flamingos, hippos, and dense lion prides. Afternoon drive to Karatu.',
                        'activities' => 'Crater floor game drive, picnic lunch near hippo pool',
                        'driving_time' => '50 km / 2 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Marera Valley Lodge',
                        'room_type' => 'Cottage Room',
                        'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 7,
                        'title' => 'Lake Manyara Tour & Return to Arusha',
                        'destination' => 'Lake Manyara / Arusha',
                        'starting_point' => 'Karatu',
                        'ending_point' => 'Arusha / JRO Airport',
                        'route' => 'Karatu → Lake Manyara → Arusha',
                        'description' => 'Enjoy a morning game drive in Lake Manyara National Park, famous for tree-climbing lions and flamingo-filled shores. In the afternoon, return to Arusha or JRO Airport for your onward flight.',
                        'activities' => 'Lake Manyara game drive, airport transfer',
                        'driving_time' => '150 km / 3 hrs',
                        'meals' => 'Breakfast, Lunch',
                        'accommodation_property' => 'Day Use / Departure',
                        'room_type' => 'N/A',
                        'cover_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=80',
                    ],
                ]
            ],
            [
                'title' => '8 Days Tanzania Luxury Safari',
                'subtitle' => '2 Adults · Luxury Tented Lodges · Fly-in Option',
                'duration_days' => 8,
                'duration_nights' => 7,
                'start_location' => 'Arusha',
                'end_location' => 'Arusha / JRO',
                'route_summary' => 'Arusha → Tarangire → Serengeti → Ngorongoro → Lake Manyara → Arusha',
                'destinations' => ['Arusha', 'Tarangire', 'Serengeti', 'Ngorongoro Crater', 'Lake Manyara'],
                'safari_style' => 'Luxury Safari',
                'accommodation_level' => 'Luxury',
                'transport_type' => '4x4 Luxury Land Cruiser',
                'is_private' => true,
                'highlights' => ['Four Seasons / Elewana Lodges', 'Serengeti Balloon Safari Option', 'Ngorongoro Crater Private Tour'],
                'default_total_price' => 5800.00,
                'default_adult_price' => 2900.00,
                'default_child_price' => 1450.00,
                'default_deposit_percentage' => 30.00,
                'payment_terms' => "30% deposit required at booking. Balance 30 days prior to travel.",
                'terms_conditions' => "Luxury safari package including premium accommodations.",
                'cancellation_policy' => "Cancellation policies follow luxury lodge rules.",
                'status' => 'active',
                'inclusions_list' => [
                    'All park fees and luxury crater service fees',
                    'Private luxury 4x4 Land Cruiser with fridge & Wi-Fi',
                    'Top-tier professional safari guide',
                    'Luxury lodge accommodations on Full Board & drinks',
                    'All airport transfers'
                ],
                'exclusions_list' => [
                    'International flights & visas',
                    'Hot air balloon safari ($550 per person)',
                    'Gratuities and tips'
                ],
                'accommodations_list' => [
                    ['property_name' => 'Gran Melia Arusha', 'location' => 'Arusha', 'category' => 'Luxury', 'room_type' => 'Executive Suite', 'nights' => 1, 'meal_plan' => 'Bed & Breakfast'],
                    ['property_name' => 'Tarangire Treetops by Elewana', 'location' => 'Tarangire', 'category' => 'Luxury', 'room_type' => 'Treehouse Suite', 'nights' => 1, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Four Seasons Safari Lodge', 'location' => 'Serengeti', 'category' => 'Luxury', 'room_type' => 'Savannah Room', 'nights' => 3, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Ngorongoro Crater Lodge', 'location' => 'Ngorongoro', 'category' => 'Luxury', 'room_type' => 'Suite', 'nights' => 2, 'meal_plan' => 'Full Board'],
                ],
                'days_list' => [
                    [
                        'day_number' => 1,
                        'title' => 'VIP Airport Arrival & Luxury Transfer to Gran Melia',
                        'destination' => 'Arusha',
                        'starting_point' => 'JRO Airport',
                        'ending_point' => 'Gran Melia Arusha',
                        'route' => 'JRO → Arusha',
                        'description' => 'VIP meet & assist at Kilimanjaro Airport. Private transfer to Gran Melia Arusha for dinner and leisure.',
                        'activities' => 'VIP transfer, welcome dinner',
                        'driving_time' => '50 km / 1 hr',
                        'meals' => 'Dinner',
                        'accommodation_property' => 'Gran Melia Arusha',
                        'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 2,
                        'title' => 'Tarangire Game Drive & Night Safari at Treetops',
                        'destination' => 'Tarangire',
                        'starting_point' => 'Arusha',
                        'ending_point' => 'Tarangire Treetops',
                        'route' => 'Arusha → Tarangire Treetops',
                        'description' => 'Drive to Tarangire Treetops private reserve. Afternoon game drive followed by a night game drive.',
                        'activities' => 'Game drive, night safari',
                        'driving_time' => '120 km / 2.5 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Tarangire Treetops by Elewana',
                        'cover_image' => 'https://images.unsplash.com/photo-1534567153574-2b12153a87f0?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 3,
                        'title' => 'Fly or Drive to Four Seasons Serengeti',
                        'destination' => 'Serengeti',
                        'starting_point' => 'Tarangire',
                        'ending_point' => 'Four Seasons Safari Lodge',
                        'route' => 'Tarangire → Central Serengeti',
                        'description' => 'Arrive in central Serengeti and check into Four Seasons Safari Lodge. Relax by the infinity pool overlooking the waterhole.',
                        'activities' => 'Game drive & lodge relaxation',
                        'driving_time' => '4 hrs or 45 mins flight',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Four Seasons Safari Lodge',
                        'cover_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=80',
                    ],
                ]
            ],
            [
                'title' => '5 Days Zanzibar Escape',
                'subtitle' => 'Beach Resort & Stone Town Cultural Experience',
                'duration_days' => 5,
                'duration_nights' => 4,
                'start_location' => 'Zanzibar Airport',
                'end_location' => 'Zanzibar Airport',
                'route_summary' => 'Stone Town → Nungwi Beach → Safari Blue → Zanzibar Airport',
                'destinations' => ['Stone Town', 'Nungwi', 'Safari Blue Lagoon'],
                'safari_style' => 'Beach & Culture',
                'accommodation_level' => 'Luxury Resort',
                'transport_type' => 'Private AC Minivan',
                'is_private' => true,
                'highlights' => ['Stone Town Tour', 'Spice Farm Tour', 'Safari Blue Boat Cruise', 'Nungwi White Sand Beaches'],
                'default_total_price' => 2400.00,
                'default_adult_price' => 1200.00,
                'default_child_price' => 600.00,
                'default_deposit_percentage' => 30.00,
                'payment_terms' => "30% deposit required upon confirmation.",
                'terms_conditions' => "Beach package in Zanzibar.",
                'cancellation_policy' => "Full refund up to 30 days before arrival.",
                'status' => 'active',
                'inclusions_list' => [
                    'Private airport & hotel transfers in Zanzibar',
                    'Accommodations in 5-star beachfront resort',
                    'Guided Stone Town & Spice Plantation tour',
                    'Safari Blue full day dhow cruise with seafood lunch'
                ],
                'exclusions_list' => [
                    'Flights into Zanzibar',
                    'Zanzibar infrastructure tax ($5/night/person)',
                    'Tips and personal items'
                ],
                'accommodations_list' => [
                    ['property_name' => 'The Park Hyatt Stone Town', 'location' => 'Stone Town', 'category' => 'Luxury', 'room_type' => 'Ocean Front Room', 'nights' => 1, 'meal_plan' => 'Bed & Breakfast'],
                    ['property_name' => 'Riu Palace Zanzibar', 'location' => 'Nungwi', 'category' => 'Luxury', 'room_type' => 'Junior Suite', 'nights' => 3, 'meal_plan' => 'All Inclusive'],
                ],
                'days_list' => [
                    [
                        'day_number' => 1,
                        'title' => 'Arrival in Zanzibar & Stone Town Sunset Tour',
                        'destination' => 'Stone Town',
                        'starting_point' => 'Zanzibar Airport',
                        'ending_point' => 'Park Hyatt Stone Town',
                        'route' => 'ZNZ Airport → Stone Town',
                        'description' => 'Airport pickup and check in at Park Hyatt Stone Town. Afternoon walking tour through Stone Town historical streets and Forodhani Gardens sunset.',
                        'activities' => 'Stone Town walking tour, sunset watching',
                        'driving_time' => '15 km / 20 mins',
                        'meals' => 'Dinner',
                        'accommodation_property' => 'Park Hyatt Stone Town',
                        'cover_image' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=80',
                    ],
                    [
                        'day_number' => 2,
                        'title' => 'Spice Tour & Transfer to Nungwi Beach Resort',
                        'destination' => 'Nungwi',
                        'starting_point' => 'Stone Town',
                        'ending_point' => 'Riu Palace Zanzibar',
                        'route' => 'Stone Town → Spice Farm → Nungwi',
                        'description' => 'Morning spice tour experiencing tropical cloves, vanilla, and spices. Afternoon transfer to Riu Palace Beach Resort in Nungwi.',
                        'activities' => 'Spice farm tour, beach resort check-in',
                        'driving_time' => '60 km / 1.5 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Riu Palace Zanzibar',
                        'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
                    ],
                ]
            ],
            [
                'title' => '7 Days Safari + Zanzibar',
                'subtitle' => '4 Days Northern Safari + 3 Days Zanzibar Beach',
                'duration_days' => 7,
                'duration_nights' => 6,
                'start_location' => 'Arusha',
                'end_location' => 'Zanzibar',
                'route_summary' => 'Arusha → Tarangire → Ngorongoro → Flight to Zanzibar → Nungwi Beach',
                'destinations' => ['Tarangire', 'Ngorongoro', 'Zanzibar Beach'],
                'safari_style' => 'Bush & Beach Combo',
                'accommodation_level' => 'Comfort',
                'transport_type' => '4x4 Land Cruiser & Flight to ZNZ',
                'is_private' => true,
                'highlights' => ['Tarangire Elephants', 'Ngorongoro Crater Floor', 'Flight to Zanzibar', 'Relaxation in Zanzibar'],
                'default_total_price' => 3950.00,
                'default_adult_price' => 1975.00,
                'default_child_price' => 987.50,
                'default_deposit_percentage' => 30.00,
                'payment_terms' => "30% deposit to secure safari and domestic flight.",
                'terms_conditions' => "Bush and beach combo itinerary.",
                'cancellation_policy' => "Standard cancellation policy applies.",
                'status' => 'active',
                'inclusions_list' => [
                    'All park entrance fees & crater fees',
                    'Private 4x4 Land Cruiser safari',
                    'Domestic flight from Arusha/Seronera to Zanzibar',
                    'Comfort lodge & beach resort accommodations',
                    'All airport and ferry transfers'
                ],
                'exclusions_list' => [
                    'International flights & visas',
                    'Zanzibar infrastructure tax',
                    'Tips and personal expenses'
                ],
                'accommodations_list' => [
                    ['property_name' => 'Tarangire Safari Lodge', 'location' => 'Tarangire', 'category' => 'Comfort', 'room_type' => 'Luxury Tent', 'nights' => 1, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Marera Valley Lodge', 'location' => 'Karatu', 'category' => 'Comfort', 'room_type' => 'Cottage', 'nights' => 2, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Ocean Paradise Resort', 'location' => 'Zanzibar East Coast', 'category' => 'Comfort', 'room_type' => 'Superior Room', 'nights' => 3, 'meal_plan' => 'Half Board'],
                ],
                'days_list' => [
                    [
                        'day_number' => 1,
                        'title' => 'Arusha to Tarangire Elephant Safari',
                        'destination' => 'Tarangire',
                        'starting_point' => 'Arusha',
                        'ending_point' => 'Tarangire Safari Lodge',
                        'route' => 'Arusha → Tarangire',
                        'description' => 'Morning pickup in Arusha and drive to Tarangire for full day game viewing.',
                        'activities' => 'Game drive, elephant watching',
                        'driving_time' => '120 km / 2.5 hrs',
                        'meals' => 'Breakfast, Lunch, Dinner',
                        'accommodation_property' => 'Tarangire Safari Lodge',
                        'cover_image' => 'https://images.unsplash.com/photo-1534567153574-2b12153a87f0?auto=format&fit=crop&w=1200&q=80',
                    ],
                ]
            ],
            [
                'title' => '10 Days Tanzania Safari',
                'subtitle' => 'Complete Northern Circuit: Tarangire, Manyara, Serengeti & Ngorongoro',
                'duration_days' => 10,
                'duration_nights' => 9,
                'start_location' => 'Arusha',
                'end_location' => 'Arusha / JRO',
                'route_summary' => 'Arusha → Tarangire → Lake Manyara → Central Serengeti → North Serengeti → Ngorongoro → Arusha',
                'destinations' => ['Tarangire', 'Lake Manyara', 'Central Serengeti', 'North Serengeti', 'Ngorongoro'],
                'safari_style' => 'Comprehensive Wilderness Safari',
                'accommodation_level' => 'Comfort',
                'transport_type' => '4x4 Land Cruiser',
                'is_private' => true,
                'highlights' => ['Full Serengeti Circuit', 'Mara River Crossing Option', 'Ngorongoro Crater', 'Hadza Bushmen Cultural Visit'],
                'default_total_price' => 4800.00,
                'default_adult_price' => 2400.00,
                'default_child_price' => 1200.00,
                'default_deposit_percentage' => 30.00,
                'payment_terms' => "30% deposit required at booking.",
                'terms_conditions' => "10-day comprehensive safari.",
                'cancellation_policy' => "Standard cancellation policy.",
                'status' => 'active',
                'inclusions_list' => [
                    'All national park & crater fees for 10 days',
                    'Private 4x4 Land Cruiser with unlimited mileage',
                    'Professional safari guide',
                    'Full board accommodation at comfort lodges',
                    'Unlimited drinking water in safari vehicle'
                ],
                'exclusions_list' => [
                    'International flights',
                    'Visas & travel insurance',
                    'Tips for driver guide'
                ],
                'accommodations_list' => [
                    ['property_name' => 'Arusha Planet Lodge', 'location' => 'Arusha', 'category' => 'Comfort', 'room_type' => 'Standard', 'nights' => 1, 'meal_plan' => 'Bed & Breakfast'],
                    ['property_name' => 'Tarangire Safari Lodge', 'location' => 'Tarangire', 'category' => 'Comfort', 'room_type' => 'Tent', 'nights' => 1, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Serengeti Heritage Camp', 'location' => 'Central Serengeti', 'category' => 'Comfort', 'room_type' => 'Luxury Tent', 'nights' => 3, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Mara Heritage Camp', 'location' => 'North Serengeti', 'category' => 'Comfort', 'room_type' => 'Luxury Tent', 'nights' => 2, 'meal_plan' => 'Full Board'],
                    ['property_name' => 'Rhino Lodge', 'location' => 'Ngorongoro', 'category' => 'Comfort', 'room_type' => 'Standard', 'nights' => 2, 'meal_plan' => 'Full Board'],
                ],
                'days_list' => [
                    [
                        'day_number' => 1,
                        'title' => 'Arrival in Arusha',
                        'destination' => 'Arusha',
                        'starting_point' => 'JRO Airport',
                        'ending_point' => 'Arusha Planet Lodge',
                        'route' => 'JRO → Arusha',
                        'description' => 'Arrival and welcome transfer to Arusha Planet Lodge.',
                        'activities' => 'Transfer, briefing',
                        'driving_time' => '50 km / 1 hr',
                        'meals' => 'Dinner',
                        'accommodation_property' => 'Arusha Planet Lodge',
                        'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
                    ],
                ]
            ]
        ];

        foreach ($templatesData as $tData) {
            $existing = ProposalTemplate::where('title', $tData['title'])->first();
            if ($existing) {
                continue;
            }

            $template = ProposalTemplate::create([
                'title' => $tData['title'],
                'subtitle' => $tData['subtitle'],
                'duration_days' => $tData['duration_days'],
                'duration_nights' => $tData['duration_nights'],
                'start_location' => $tData['start_location'],
                'end_location' => $tData['end_location'],
                'route_summary' => $tData['route_summary'],
                'destinations' => $tData['destinations'],
                'safari_style' => $tData['safari_style'],
                'accommodation_level' => $tData['accommodation_level'],
                'transport_type' => $tData['transport_type'],
                'is_private' => $tData['is_private'],
                'highlights' => $tData['highlights'],
                'itinerary' => $tData['days_list'],
                'inclusions' => $tData['inclusions_list'],
                'exclusions' => $tData['exclusions_list'],
                'accommodations' => $tData['accommodations_list'],
                'default_total_price' => $tData['default_total_price'],
                'default_adult_price' => $tData['default_adult_price'],
                'default_child_price' => $tData['default_child_price'],
                'default_deposit_percentage' => $tData['default_deposit_percentage'],
                'payment_terms' => $tData['payment_terms'],
                'terms_conditions' => $tData['terms_conditions'],
                'cancellation_policy' => $tData['cancellation_policy'],
                'status' => $tData['status'],
                'internal_costing' => [
                    'accommodation_cost' => 1200,
                    'park_fees' => 700,
                    'vehicle_cost' => 450,
                    'guide_cost' => 250,
                    'total_cost' => 2600,
                    'profit' => $tData['default_total_price'] - 2600,
                    'profit_margin' => round((($tData['default_total_price'] - 2600) / $tData['default_total_price']) * 100, 2),
                ],
            ]);

            // Save Days in relational table
            foreach ($tData['days_list'] as $dayData) {
                ProposalTemplateDay::create([
                    'proposal_template_id' => $template->id,
                    'day_number' => $dayData['day_number'],
                    'title' => $dayData['title'],
                    'destination' => $dayData['destination'] ?? null,
                    'starting_point' => $dayData['starting_point'] ?? null,
                    'ending_point' => $dayData['ending_point'] ?? null,
                    'route' => $dayData['route'] ?? null,
                    'description' => $dayData['description'] ?? null,
                    'activities' => $dayData['activities'] ?? null,
                    'driving_time' => $dayData['driving_time'] ?? null,
                    'meals' => $dayData['meals'] ?? null,
                    'accommodation_property' => $dayData['accommodation_property'] ?? null,
                    'room_type' => $dayData['room_type'] ?? null,
                    'cover_image' => $dayData['cover_image'] ?? null,
                ]);
            }

            // Save Accommodations in relational table
            foreach ($tData['accommodations_list'] as $accData) {
                ProposalTemplateAccommodation::create([
                    'proposal_template_id' => $template->id,
                    'property_name' => $accData['property_name'],
                    'location' => $accData['location'] ?? null,
                    'category' => $accData['category'] ?? null,
                    'room_type' => $accData['room_type'] ?? null,
                    'nights' => $accData['nights'] ?? 1,
                    'meal_plan' => $accData['meal_plan'] ?? null,
                ]);
            }

            // Save Inclusions in relational table
            foreach ($tData['inclusions_list'] as $inc) {
                ProposalTemplateInclusion::create([
                    'proposal_template_id' => $template->id,
                    'item' => $inc,
                ]);
            }

            // Save Exclusions in relational table
            foreach ($tData['exclusions_list'] as $exc) {
                ProposalTemplateExclusion::create([
                    'proposal_template_id' => $template->id,
                    'item' => $exc,
                ]);
            }

            // Save Price in relational table
            ProposalTemplatePrice::create([
                'proposal_template_id' => $template->id,
                'currency' => 'USD',
                'subtotal_price' => $tData['default_total_price'],
                'discount_amount' => 0.00,
                'total_price' => $tData['default_total_price'],
                'adult_price' => $tData['default_adult_price'],
                'child_price' => $tData['default_child_price'],
                'deposit_required' => round(($tData['default_total_price'] * 0.3), 2),
                'deposit_percentage' => 30.00,
                'accommodation_cost' => 1200.00,
                'park_fees' => 700.00,
                'vehicle_cost' => 450.00,
                'guide_cost' => 250.00,
            ]);
        }
    }

    public function down(): void
    {
    }
};
