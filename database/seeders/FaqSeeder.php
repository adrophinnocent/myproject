<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            ['question' => 'What is included in the safari package?', 'answer' => 'Our safari packages include professional English-speaking guides, 4x4 safari vehicles with pop-up roofs, all accommodation (luxury tented camps/lodges), all meals (breakfast, lunch, dinner), park entry fees, drinking water and soft drinks, cultural visits, airport transfers, and complimentary laundry service at select lodges.', 'order' => 1, 'is_active' => true],
            ['question' => 'What is not included in the safari price?', 'answer' => 'International flights, travel insurance (highly recommended), visa fees, gratuities for guide and staff (recommended $15–$20 per guest per day), optional activities (night game drives, hot air balloon safaris), alcoholic beverages, personal expenses, and souvenirs are not included.', 'order' => 2, 'is_active' => true],
            ['question' => 'Can I customize my safari itinerary?', 'answer' => 'Yes! We specialize in custom safari itineraries! Whether you want to add extra days, change destinations, or focus on specific activities (like photography or birdwatching), we can tailor your trip to your preferences! Contact us to create your perfect adventure!', 'order' => 3, 'is_active' => true],
            ['question' => 'What is the best time to visit Tanzania?', 'answer' => 'The best time for wildlife viewing is June–October (dry season) when vegetation is sparse and animals gather around waterholes. January–February is also great for calving season in the southern Serengeti. March–May has lush greenery and fewer crowds.', 'order' => 4, 'is_active' => true],
            ['question' => 'Do you offer private and group safaris?', 'answer' => 'Yes! We offer both private and group safaris! Private safaris are available at an extra cost and give you your own guide and vehicle, while our small group safaris (max 8 guests) are a more budget-friendly option.', 'order' => 5, 'is_active' => true],
            ['question' => 'What type of safari vehicles do you use?', 'answer' => 'We use custom-built 4x4 safari vehicles with pop-up roofs, window seats for everyone, USB charging ports, refrigerated storage, and comfortable seating! All vehicles are well-maintained and designed for wildlife viewing!', 'order' => 6, 'is_active' => true],
            ['question' => 'Is Tanzania safe for tourists?', 'answer' => 'Yes! Tanzania is one of the safest African destinations for tourists! We have been operating here for over 19 years, and our guides are trained to ensure your safety at all times! We also follow strict safety protocols and travel advisories!', 'order' => 7, 'is_active' => true],
            ['question' => 'How do I book a safari with Twina Safaris?', 'answer' => 'Booking is easy! 1. Browse our tours, 2. Contact us with your preferred dates, 3. Pay 30% deposit, 4. Receive your booking confirmation, 5. Prepare for your safari!', 'order' => 8, 'is_active' => true],
            ['question' => 'Can I combine a safari with a Zanzibar beach holiday?', 'answer' => 'Absolutely! Many of our guests love combining a wildlife safari with a relaxing beach holiday in Zanzibar! We offer packages that include both safari and Zanzibar beach stay!', 'order' => 9, 'is_active' => true],
            ['question' => 'Why should I choose Twina Safaris?', 'answer' => 'Twina Safaris is a family-owned business with 19+ years of experience, 15,000+ happy guests, 98% booking success, 24/7 local support, expert local guides, eco-friendly practices, and custom-tailored itineraries! We are committed to making your safari unforgettable!', 'order' => 10, 'is_active' => true],
        ];

        foreach ($faqs as $faqData) {
            Faq::firstOrCreate($faqData);
        }
    }
}
