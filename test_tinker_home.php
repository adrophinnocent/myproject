
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Destination;
use App\Models\Testimonial;
use App\Models\Tour;
use Illuminate\Support\Facades\View;

echo "Testing test_home.blade.php...\n";
try {
    $featuredTours = Tour::where('is_published', true)->where('is_featured', true)->latest()->take(6)->get();
    $allPublishedTours = Tour::where('is_published', true)->count();
    $destinations = Destination::where('is_active', true)->where('is_featured', true)->take(6)->get();
    $testimonials = Testimonial::where('is_published', true)->latest()->take(6)->get();

    echo View::make('test_home', compact('featuredTours', 'allPublishedTours', 'destinations', 'testimonials'))->render();
} catch (Exception $e) {
    echo "Error in test_home.blade.php:\n";
    echo $e->getMessage()."\n";
    echo $e->getTraceAsString()."\n\n";
}
