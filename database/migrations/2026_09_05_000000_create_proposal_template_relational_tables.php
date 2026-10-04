<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_template_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_template_id')->constrained('proposal_templates')->cascadeOnDelete();
            $table->integer('day_number')->default(1);
            $table->string('title')->nullable();
            $table->string('destination')->nullable();
            $table->string('starting_point')->nullable();
            $table->string('ending_point')->nullable();
            $table->string('route')->nullable();
            $table->text('description')->nullable();
            $table->text('activities')->nullable();
            $table->text('optional_activities')->nullable();
            $table->string('driving_time')->nullable();
            $table->string('meals')->nullable();
            $table->string('accommodation_property')->nullable();
            $table->string('room_type')->nullable();
            $table->text('cover_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('proposal_template_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_template_id')->constrained('proposal_templates')->cascadeOnDelete();
            $table->string('property_name');
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            $table->string('room_type')->nullable();
            $table->integer('nights')->default(1);
            $table->string('meal_plan')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('website_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('proposal_template_inclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_template_id')->constrained('proposal_templates')->cascadeOnDelete();
            $table->string('item');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('proposal_template_exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_template_id')->constrained('proposal_templates')->cascadeOnDelete();
            $table->string('item');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('proposal_template_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_template_id')->constrained('proposal_templates')->cascadeOnDelete();
            $table->string('currency')->default('USD');
            $table->decimal('subtotal_price', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('total_price', 12, 2)->default(0.00);
            $table->decimal('adult_price', 12, 2)->nullable();
            $table->decimal('child_price', 12, 2)->nullable();
            $table->decimal('deposit_required', 12, 2)->nullable();
            $table->decimal('deposit_percentage', 5, 2)->nullable()->default(30.00);
            $table->decimal('accommodation_cost', 12, 2)->default(0.00);
            $table->decimal('park_fees', 12, 2)->default(0.00);
            $table->decimal('vehicle_cost', 12, 2)->default(0.00);
            $table->decimal('guide_cost', 12, 2)->default(0.00);
            $table->decimal('meals_cost', 12, 2)->default(0.00);
            $table->decimal('transfers_cost', 12, 2)->default(0.00);
            $table->decimal('flights_cost', 12, 2)->default(0.00);
            $table->decimal('activities_cost', 12, 2)->default(0.00);
            $table->decimal('government_fees', 12, 2)->default(0.00);
            $table->decimal('other_costs', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_template_prices');
        Schema::dropIfExists('proposal_template_exclusions');
        Schema::dropIfExists('proposal_template_inclusions');
        Schema::dropIfExists('proposal_template_accommodations');
        Schema::dropIfExists('proposal_template_days');
    }
};
