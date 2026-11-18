<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tour = App\Models\Tour::where('slug', '3-day-tanzania-nature-wildlife-group-safari')->first()
        ?? App\Models\Safari::where('slug', '3-day-tanzania-nature-wildlife-group-safari')->first();

if (!$tour) {
    echo "TOUR NOT FOUND\n";
    exit;
}

echo "TOUR TITLE: " . $tour->title . "\n";
echo "ITINERARY TYPE: " . gettype($tour->itinerary) . "\n";
echo "ITINERARY RAW DATA:\n";
print_r($tour->itinerary);
