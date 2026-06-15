<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            // Pricing
            $table->decimal('child_price', 10, 2)->nullable()->after('price');
            $table->decimal('group_discount', 10, 2)->nullable()->after('child_price');
            $table->integer('deposit_percent')->nullable()->after('group_discount');
            $table->string('currency')->default('USD')->after('deposit_percent');

            // Experience
            $table->string('tour_type')->nullable()->after('difficulty_level');
            $table->string('transport_type')->nullable()->after('accommodation_type');
            $table->string('departure_time')->nullable()->after('departure_location');
            $table->string('meeting_point')->nullable()->after('departure_time');

            // Logistics
            $table->boolean('pickup_included')->default(false)->after('meeting_point');
            $table->boolean('airport_pickup')->default(false)->after('pickup_included');
            $table->boolean('transport_included')->default(false)->after('airport_pickup');
            $table->text('map_location')->nullable()->after('transport_included');

            // Guide & Support
            $table->string('assigned_guide')->nullable()->after('map_location');
            $table->json('languages_offered')->nullable()->after('assigned_guide');
            $table->text('special_notes')->nullable()->after('languages_offered');

            // Availability
            $table->date('start_date')->nullable()->after('special_notes');
            $table->date('end_date')->nullable()->after('start_date');
            $table->integer('available_slots')->nullable()->after('end_date');
            $table->string('seasonal_tag')->nullable()->after('available_slots');
            $table->json('seasonal_pricing')->nullable()->after('seasonal_tag');
            $table->json('availability_dates')->nullable()->after('seasonal_pricing');
            $table->integer('booking_deadline_days')->nullable()->after('availability_dates');

            // Age limits
            $table->integer('min_age')->nullable()->after('booking_deadline_days');
            $table->integer('max_age')->nullable()->after('min_age');

            // Media
            $table->string('video_url')->nullable()->after('featured_image');

            // More fields
            $table->json('highlights')->nullable()->after('description');
            $table->json('what_to_bring')->nullable()->after('exclusions');
            $table->json('good_to_know')->nullable()->after('what_to_bring');
            $table->text('meta_keywords')->nullable()->after('meta_description');

            // Marketing flags
            $table->boolean('is_bestseller')->default(false)->after('is_featured');
            $table->boolean('is_new')->default(false)->after('is_bestseller');
            $table->boolean('limited_offer')->default(false)->after('is_new');

            // Availability notes
            $table->text('availability_notes')->nullable()->after('limited_offer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'child_price', 'group_discount', 'deposit_percent', 'currency',
                'tour_type', 'transport_type', 'departure_time', 'meeting_point',
                'pickup_included', 'airport_pickup', 'transport_included', 'map_location',
                'assigned_guide', 'languages_offered', 'special_notes',
                'start_date', 'end_date', 'available_slots', 'seasonal_tag',
                'seasonal_pricing', 'availability_dates', 'booking_deadline_days',
                'min_age', 'max_age', 'video_url', 'highlights',
                'what_to_bring', 'good_to_know', 'meta_keywords',
                'is_bestseller', 'is_new', 'limited_offer', 'availability_notes',
            ]);
        });
    }
};
