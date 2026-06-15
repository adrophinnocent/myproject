<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Checking for columns in tours table...\n";

$columns = Schema::getColumnListing('tours');
print_r($columns);

echo "\nAdding missing columns...\n";

$needs = [
    'child_price' => 'decimal',
    'group_discount' => 'decimal',
    'deposit_percent' => 'integer',
    'currency' => 'string',
    'tour_type' => 'string',
    'transport_type' => 'string',
    'departure_time' => 'string',
    'meeting_point' => 'string',
    'pickup_included' => 'boolean',
    'airport_pickup' => 'boolean',
    'transport_included' => 'boolean',
    'map_location' => 'text',
    'assigned_guide' => 'string',
    'languages_offered' => 'json',
    'special_notes' => 'text',
    'start_date' => 'date',
    'end_date' => 'date',
    'available_slots' => 'integer',
    'seasonal_tag' => 'string',
    'seasonal_pricing' => 'json',
    'availability_dates' => 'json',
    'booking_deadline_days' => 'integer',
    'min_age' => 'integer',
    'max_age' => 'integer',
    'video_url' => 'string',
    'highlights' => 'json',
    'what_to_bring' => 'json',
    'good_to_know' => 'json',
    'meta_keywords' => 'text',
    'is_bestseller' => 'boolean',
    'is_new' => 'boolean',
    'limited_offer' => 'boolean',
    'availability_notes' => 'text',
];

foreach ($needs as $col => $type) {
    if (! in_array($col, $columns)) {
        echo "Adding $col...\n";
        switch ($type) {
            case 'decimal':
                DB::statement("ALTER TABLE tours ADD COLUMN $col DECIMAL(10, 2) DEFAULT NULL");
                break;
            case 'integer':
                DB::statement("ALTER TABLE tours ADD COLUMN $col INTEGER DEFAULT NULL");
                break;
            case 'string':
                DB::statement("ALTER TABLE tours ADD COLUMN $col VARCHAR DEFAULT NULL");
                break;
            case 'boolean':
                DB::statement("ALTER TABLE tours ADD COLUMN $col TINYINT(1) DEFAULT 0");
                break;
            case 'text':
                DB::statement("ALTER TABLE tours ADD COLUMN $col TEXT DEFAULT NULL");
                break;
            case 'date':
                DB::statement("ALTER TABLE tours ADD COLUMN $col DATE DEFAULT NULL");
                break;
            case 'json':
                DB::statement("ALTER TABLE tours ADD COLUMN $col TEXT DEFAULT NULL");
                break;
        }
    } else {
        echo "$col exists, skipping.\n";
    }
}

echo "\nDone!";
