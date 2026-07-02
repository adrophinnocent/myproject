<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tour;

$tours = Tour::all();
echo "Total Tours: " . $tours->count() . "\n";

$missingTitle = $tours->filter(fn($t) => empty($t->meta_title))->count();
$missingDesc = $tours->filter(fn($t) => empty($t->meta_description))->count();
$missingKeywords = $tours->filter(fn($t) => empty($t->meta_keywords))->count();

echo "Missing Meta Title: $missingTitle\n";
echo "Missing Meta Description: $missingDesc\n";
echo "Missing Meta Keywords: $missingKeywords\n";

foreach($tours as $t) {
    if (empty($t->meta_title) || empty($t->meta_description)) {
        echo "- {$t->id}: {$t->title} (Title: " . ($t->meta_title ? 'OK' : 'MISSING') . ", Desc: " . ($t->meta_description ? 'OK' : 'MISSING') . ")\n";
    }
}
