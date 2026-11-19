<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

echo "Running Artisan Migration...\n";
try {
    Artisan::call('migrate', ['--force' => true]);
    echo Artisan::output();
} catch (\Throwable $e) {
    echo "Artisan migrate note: " . $e->getMessage() . "\n";
}

echo "\nChecking for columns in tours table...\n";
$columns = Schema::getColumnListing('tours');

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
    'pickup_locations' => 'json',
    'extras' => 'json',
];

foreach ($needs as $col => $type) {
    if (! in_array($col, $columns)) {
        echo "Adding $col to tours table...\n";
        switch ($type) {
            case 'decimal':
                DB::statement("ALTER TABLE tours ADD COLUMN $col DECIMAL(10, 2) DEFAULT NULL");
                break;
            case 'integer':
                DB::statement("ALTER TABLE tours ADD COLUMN $col INTEGER DEFAULT NULL");
                break;
            case 'string':
                DB::statement("ALTER TABLE tours ADD COLUMN $col VARCHAR(255) DEFAULT NULL");
                break;
            case 'boolean':
                DB::statement("ALTER TABLE tours ADD COLUMN $col TINYINT(1) DEFAULT 0");
                break;
            case 'text':
            case 'json':
                DB::statement("ALTER TABLE tours ADD COLUMN $col LONGTEXT DEFAULT NULL");
                break;
            case 'date':
                DB::statement("ALTER TABLE tours ADD COLUMN $col DATE DEFAULT NULL");
                break;
        }
    } else {
        echo "tours.$col exists, skipping.\n";
    }
}

if (Schema::hasTable('safaris')) {
    echo "\nChecking safaris table...\n";
    $safariColumns = Schema::getColumnListing('safaris');
    if (! in_array('extras', $safariColumns)) {
        echo "Adding extras to safaris table...\n";
        DB::statement("ALTER TABLE safaris ADD COLUMN extras LONGTEXT DEFAULT NULL");
    }
}

if (Schema::hasTable('bookings')) {
    echo "\nChecking bookings table...\n";
    $bookingColumns = Schema::getColumnListing('bookings');
    if (! in_array('selected_extras', $bookingColumns)) {
        echo "Adding selected_extras to bookings table...\n";
        DB::statement("ALTER TABLE bookings ADD COLUMN selected_extras LONGTEXT DEFAULT NULL");
    }
    if (! in_array('extras_total', $bookingColumns)) {
        echo "Adding extras_total to bookings table...\n";
        DB::statement("ALTER TABLE bookings ADD COLUMN extras_total DECIMAL(10, 2) DEFAULT 0.00");
    }
}

echo "\nDone!\n";
