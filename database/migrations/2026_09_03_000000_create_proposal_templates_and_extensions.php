<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Reusable Day Templates Table
        Schema::create('proposal_day_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('destination')->nullable();
            $table->string('starting_point')->nullable();
            $table->string('ending_point')->nullable();
            $table->string('route')->nullable();

            $table->text('description')->nullable();
            $table->text('activities')->nullable();
            $table->text('highlights')->nullable();
            $table->text('special_notes')->nullable();
            $table->text('optional_activities')->nullable();

            $table->string('distance')->nullable();
            $table->string('driving_time')->nullable();
            $table->string('transport_type')->nullable();
            $table->string('meals')->nullable();

            $table->string('accommodation_property')->nullable();
            $table->string('room_type')->nullable();
            $table->string('accommodation_location')->nullable();
            $table->integer('nights')->default(1);
            $table->string('meal_plan')->nullable();

            $table->string('cover_image')->nullable();
            $table->json('gallery_images')->nullable();

            $table->timestamps();
        });

        // 2. Complete Itinerary Templates Table
        Schema::create('proposal_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('duration_days')->default(1);
            $table->integer('duration_nights')->default(0);

            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->json('destinations')->nullable();
            $table->string('safari_style')->nullable();
            $table->string('accommodation_level')->nullable();
            $table->string('transport_type')->nullable();
            $table->boolean('is_private')->default(true);

            $table->json('highlights')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('optional_extras')->nullable();
            $table->json('accommodations')->nullable();

            $table->decimal('default_total_price', 12, 2)->default(0.00);
            $table->decimal('default_adult_price', 12, 2)->nullable();
            $table->decimal('default_child_price', 12, 2)->nullable();

            $table->text('payment_terms')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->json('internal_costing')->nullable();

            $table->timestamps();
        });

        // 3. Extend Proposals Table
        Schema::table('proposals', function (Blueprint $table) {
            $table->decimal('subtotal_price', 12, 2)->nullable()->after('currency');
            $table->decimal('discount_amount', 12, 2)->default(0.00)->after('subtotal_price');
            $table->decimal('adult_price', 12, 2)->nullable()->after('price_per_person');
            $table->decimal('child_price', 12, 2)->nullable()->after('adult_price');
            $table->string('child_age_range')->nullable()->after('child_price');
            $table->json('accommodations')->nullable()->after('optional_extras');
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_price', 'discount_amount', 'adult_price', 'child_price',
                'child_age_range', 'accommodations'
            ]);
        });

        Schema::dropIfExists('proposal_templates');
        Schema::dropIfExists('proposal_day_templates');
    }
};
