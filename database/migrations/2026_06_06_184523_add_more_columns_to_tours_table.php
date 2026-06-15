<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->json('highlights')->nullable();
            $table->decimal('child_price', 10, 2)->nullable();
            $table->decimal('group_discount', 10, 2)->nullable();
            $table->string('departure_time')->nullable();
            $table->json('what_to_bring')->nullable();
            $table->text('good_to_know')->nullable();
            $table->string('video_url')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_new')->default(false);
            $table->text('availability_notes')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('languages_offered')->nullable();
            $table->string('tour_type')->nullable();
            $table->json('seasonal_pricing')->nullable();
            $table->json('availability_dates')->nullable();
            $table->integer('booking_deadline_days')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'highlights',
                'child_price',
                'group_discount',
                'departure_time',
                'what_to_bring',
                'good_to_know',
                'video_url',
                'meta_keywords',
                'is_bestseller',
                'is_new',
                'availability_notes',
                'min_age',
                'max_age',
                'languages_offered',
                'tour_type',
                'seasonal_pricing',
                'availability_dates',
                'booking_deadline_days',
            ]);
        });
    }
};
