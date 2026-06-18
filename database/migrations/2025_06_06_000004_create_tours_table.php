<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('child_price', 10, 2)->nullable();
            $table->decimal('group_discount', 10, 2)->nullable();
            $table->integer('deposit_percent')->nullable();
            $table->string('currency')->default('USD');
            $table->string('price_note')->nullable();
            $table->integer('duration_days');
            $table->integer('duration_nights')->nullable();
            $table->integer('group_size_min')->nullable();
            $table->integer('group_size_max')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->string('tour_type')->nullable();
            $table->string('accommodation_type')->nullable();
            $table->string('transport_type')->nullable();
            $table->string('departure_location')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('meeting_point')->nullable();
            $table->boolean('pickup_included')->default(false);
            $table->boolean('airport_pickup')->default(false);
            $table->boolean('transport_included')->default(false);
            $table->text('map_location')->nullable();
            $table->string('assigned_guide')->nullable();
            $table->json('languages_offered')->nullable();
            $table->text('special_notes')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('available_slots')->nullable();
            $table->string('seasonal_tag')->nullable();
            $table->json('seasonal_pricing')->nullable();
            $table->json('availability_dates')->nullable();
            $table->integer('booking_deadline_days')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('highlights')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('what_to_bring')->nullable();
            $table->json('good_to_know')->nullable();
            $table->json('faqs')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('limited_offer')->default(false);
            $table->text('availability_notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
