<?php

namespace Database\Seeders;

use App\Models\AiKnowledge;
use Illuminate\Database\Seeder;

class AiKnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facts = [
            [
                'category' => 'Serengeti',
                'topic' => 'Great Migration',
                'content' => 'The Great Migration is best viewed from June to October. It involves millions of wildebeests and zebras crossing the Mara River. Twina Safaris provides exclusive mobile camps to follow the herd.',
                'sort_order' => 1
            ],
            [
                'category' => 'Kilimanjaro',
                'topic' => 'Success Rate',
                'content' => 'Twina Safaris has a 98% summit success rate on the Machame and Lemosho routes due to our expert guides and longer acclimatization schedules.',
                'sort_order' => 2
            ],
            [
                'category' => 'Ngorongoro',
                'topic' => 'Black Rhino',
                'content' => 'The Ngorongoro Crater is the best place in East Africa to see the rare Black Rhino in its natural habitat. We recommend early morning game drives for the best sightings.',
                'sort_order' => 3
            ],
            [
                'category' => 'Company',
                'topic' => 'Luxury Standards',
                'content' => 'We use modified 4x4 Land Cruisers with pop-up roofs, charging points, and refrigerators. Our accommodations range from high-end luxury lodges to intimate tented camps.',
                'sort_order' => 4
            ]
        ];

        foreach ($facts as $fact) {
            AiKnowledge::create($fact);
        }
    }
}
