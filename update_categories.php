<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;

$updates = [
    1 => 'Safari Tours',
    2 => 'Kilimanjaro Trekking',
    3 => 'Zanzibar Holidays',
    6 => 'Honeymoon Safaris',
];

foreach ($updates as $id => $name) {
    $cat = Category::find($id);
    if ($cat) {
        $cat->name = $name;
        $cat->save();
        echo "Updated category {$id} to '{$name}'\n";
    }
}

echo "All done!\n";
