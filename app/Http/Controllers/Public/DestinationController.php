<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Safari;
use App\Models\Tour;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Factual copy that complements, rather than replaces, the destination
     * description managed in the admin panel.
     *
     * @var array<string, array<string, mixed>>
     */
    public const FACTS = [
        'serengeti' => [
            'summary' => 'Plan a Serengeti safari: migration routes, big cats, the best months to visit and itineraries led by local guides.',
            'best_time' => 'June to October is the driest game-viewing window. January to March is best for calving on the southern plains around Ndutu.',
            'highlights' => ['Great Migration routes', 'Lions, cheetahs and leopards', 'Ndutu calving season', 'Endless short-grass plains'],
            'getting_there' => 'Fly to Seronera airstrip or drive from Arusha, usually five to seven hours depending on the gate and route.',
            'guide' => ['label' => 'Serengeti Safari Guide', 'url' => '/serengeti-safari'],
            'geo' => ['latitude' => -2.3333, 'longitude' => 34.8333],
        ],
        'kilimanjaro' => [
            'summary' => 'Plan a Mount Kilimanjaro trek: routes, acclimatisation, permits, the best season and local Moshi-based guides.',
            'best_time' => 'June to October and January to February are the driest trekking windows. March and November are quieter but wetter.',
            'highlights' => ['Machame and other classic routes', 'Montane forest and alpine desert', 'Sunrise over Uhuru Peak', 'Experienced local trek crews'],
            'getting_there' => 'Fly to Kilimanjaro International Airport and transfer to Moshi, roughly 45 minutes by road.',
            'guide' => ['label' => 'Kilimanjaro Trekking Company Guide', 'url' => '/kilimanjaro-trekking-company'],
            'geo' => ['latitude' => -3.0674, 'longitude' => 37.3556],
        ],
        'zanzibar' => [
            'summary' => 'Plan a Zanzibar holiday: Stone Town, spice farms, coral reefs, beach seasons and safari combinations.',
            'best_time' => 'June to October offers dry beach weather. December to February brings warm seas and good snorkelling conditions.',
            'highlights' => ['Stone Town history and architecture', 'Spice farms and local food', 'Coral reefs and dhow sunsets', 'Easy safari and beach combinations'],
            'getting_there' => 'Take a short flight from Dar es Salaam or Arusha, or a ferry from Dar es Salaam to Stone Town.',
            'guide' => ['label' => 'Zanzibar Holiday Packages Guide', 'url' => '/zanzibar-holiday-packages'],
            'geo' => ['latitude' => -6.1659, 'longitude' => 39.2026],
        ],
        'ngorongoro' => [
            'summary' => 'Plan an Ngorongoro Crater safari: crater-floor game drives, black rhino, conservation rules and routes from Arusha.',
            'best_time' => 'The crater can be visited year-round. June to October is driest and generally gives the clearest crater views.',
            'highlights' => ['Crater-floor game drives', 'Black rhino and large lion prides', 'Flamingos on Lake Magadi', 'UNESCO conservation landscape'],
            'getting_there' => 'Drive from Arusha in about three hours, usually as part of the northern safari circuit.',
            'guide' => ['label' => 'Ngorongoro Tour Guide', 'url' => '/ngorongoro-tour'],
            'geo' => ['latitude' => -3.25, 'longitude' => 35.5833],
        ],
        'tarangire' => [
            'summary' => 'Plan a Tarangire National Park safari: elephant herds, baobab landscapes, birdlife and the best time to visit.',
            'best_time' => 'June to October, when elephants gather along the Tarangire River and the trails are dry.',
            'highlights' => ['Large elephant herds', 'Ancient baobab trees', 'Silale swamp birdlife', 'Quiet northern-circuit game drives'],
            'getting_there' => 'About two hours by road from Arusha, commonly combined with Lake Manyara or Ngorongoro.',
            'guide' => ['label' => 'Best Time for a Tanzania Safari', 'url' => '/best-time-for-tanzania-safari'],
            'geo' => ['latitude' => -3.9, 'longitude' => 35.9333],
        ],
        'lake-manyara-national-park' => [
            'summary' => 'Plan a Lake Manyara safari: tree-climbing lions, flamingos, forest walks and Rift Valley scenery.',
            'best_time' => 'June to October is driest. November to March brings greener scenery and more migratory birds.',
            'highlights' => ['Tree-climbing lions', 'Flamingos and diverse birdlife', 'Forest canopy and hot springs', 'Great Rift Valley escarpment views'],
            'getting_there' => 'Around 90 minutes by road from Arusha, often combined with Tarangire or Ngorongoro.',
            'guide' => ['label' => 'Best Time for a Tanzania Safari', 'url' => '/best-time-for-tanzania-safari'],
            'geo' => ['latitude' => -3.3833, 'longitude' => 35.75],
        ],
    ];

    public function index()
    {
        $destinations = Destination::active()->get();

        return view('public.destinations.index', compact('destinations'));
    }

    public function show(string $slug)
    {
        $destination = Destination::active()->where('slug', $slug)->firstOrFail();

        $tours = Tour::published()
            ->where('destination_id', $destination->id)
            ->with('category')
            ->orderBy('title')
            ->get(['id', 'slug', 'title', 'short_description', 'price', 'duration_days', 'duration_nights', 'featured_image', 'destination_id']);

        $safaris = Safari::published()
            ->where('destination_id', $destination->id)
            ->orderBy('title')
            ->get(['id', 'slug', 'title', 'short_description', 'price', 'duration_days', 'duration_nights', 'featured_image', 'destination_id']);

        $faqs = Faq::active()->where('destination_id', $destination->id)->get();

        $name = $destination->translate('name') ?: $destination->name;
        $facts = self::FACTS[$destination->slug] ?? [
            'summary' => 'Explore '.$name.' with Twina Safaris and compare itineraries led by local guides.',
            'best_time' => 'Our local team can advise on the best season for your travel dates and interests.',
            'highlights' => ['Wildlife and scenery', 'Local expert guides', 'Tailor-made itineraries'],
            'getting_there' => 'Ask our team for the most practical route based on your arrival city and dates.',
            'guide' => ['label' => 'Plan a Custom Safari', 'url' => '/custom-safari'],
            'geo' => null,
        ];

        $title = Str::limit($name.' Safari & Tours | Twina Safaris', 60, '');
        $metaDescription = Str::limit($facts['summary'], 158, '');

        return view('public.destinations.show', compact(
            'destination',
            'tours',
            'safaris',
            'faqs',
            'facts',
            'name',
            'title',
            'metaDescription'
        ));
    }
}
