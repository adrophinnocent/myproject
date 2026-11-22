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

// AI Search Intelligence Tables
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('search_question_categories')) {
    echo "\nCreating search_question_categories table...\n";
    Schema::create('search_question_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->integer('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

if (!Schema::hasTable('search_questions')) {
    echo "Creating search_questions table...\n";
    Schema::create('search_questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('search_question_categories')->onDelete('cascade');
        $table->foreignId('tour_id')->nullable()->constrained('tours')->onDelete('set null');
        $table->text('question');
        $table->string('slug')->unique();
        $table->string('primary_keyword')->nullable();
        $table->json('secondary_keywords')->nullable();
        $table->string('search_intent')->default('informational');
        $table->string('target_country')->nullable();
        $table->string('suggested_content_type')->default('tour');
        $table->string('status')->default('draft');
        $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
        $table->softDeletes();
    });
}

if (!Schema::hasTable('search_answers')) {
    echo "Creating search_answers table...\n";
    Schema::create('search_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('search_question_id')->constrained('search_questions')->onDelete('cascade');
        $table->string('title')->nullable();
        $table->longText('content');
        $table->text('meta_description')->nullable();
        $table->json('suggested_links')->nullable();
        $table->json('related_questions')->nullable();
        $table->boolean('is_approved')->default(false);
        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamp('approved_at')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

if (!Schema::hasTable('search_content_assignments')) {
    echo "Creating search_content_assignments table...\n";
    Schema::create('search_content_assignments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('search_question_id')->constrained('search_questions')->onDelete('cascade');
        $table->string('target_type')->default('faq');
        $table->unsignedBigInteger('target_id')->nullable();
        $table->boolean('is_published')->default(false);
        $table->timestamp('published_at')->nullable();
        $table->timestamps();
    });
}

if (!Schema::hasTable('ai_visibility_checks')) {
    echo "Creating ai_visibility_checks table...\n";
    Schema::create('ai_visibility_checks', function (Blueprint $table) {
        $table->id();
        $table->text('query_text');
        $table->string('ai_platform');
        $table->boolean('is_brand_mentioned')->default(false);
        $table->boolean('is_source_cited')->default(false);
        $table->json('competitors_mentioned')->nullable();
        $table->text('source_link')->nullable();
        $table->date('check_date');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}

// Seed the 15 Search Question Categories if empty
if (DB::table('search_question_categories')->count() === 0) {
    echo "Seeding 15 search question categories...\n";
    $categories = [
        'Tanzania Safari',
        'Serengeti',
        'Ngorongoro',
        'Tarangire',
        'Lake Manyara',
        'Kilimanjaro Climbing',
        'Machame Route',
        'Lemosho Route',
        'Honeymoon Safaris',
        'Zanzibar Holidays',
        'Family Safaris',
        'Private Safaris',
        'Safari Pricing',
        'Booking and Travel Information',
        'Company Information',
    ];

    foreach ($categories as $index => $catName) {
        DB::table('search_question_categories')->insert([
            'name' => $catName,
            'slug' => \Illuminate\Support\Str::slug($catName),
            'description' => 'Tourist search questions regarding ' . $catName,
            'sort_order' => $index + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

echo "\nDone!\n";
