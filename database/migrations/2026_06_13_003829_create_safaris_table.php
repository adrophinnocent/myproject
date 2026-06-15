<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('price_note')->nullable();
            $table->integer('duration_days');
            $table->integer('duration_nights')->nullable();
            $table->integer('group_size_min')->nullable();
            $table->integer('group_size_max')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->string('accommodation_type')->nullable();
            $table->string('departure_location')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('faqs')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->json('highlights')->nullable();
            $table->decimal('child_price', 10, 2)->nullable();
            $table->decimal('group_discount', 10, 2)->nullable();
            $table->string('departure_time')->nullable();
            $table->json('what_to_bring')->nullable();
            $table->json('good_to_know')->nullable();
            $table->string('video_url')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_new')->default(false);
            $table->text('availability_notes')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('languages_offered')->nullable();
            $table->string('safari_type')->nullable();
            $table->json('seasonal_pricing')->nullable();
            $table->json('availability_dates')->nullable();
            $table->integer('booking_deadline_days')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safaris');
    }
};
