<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Accommodations Table
        if (! Schema::hasTable('accommodations')) {
            Schema::create('accommodations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('type')->default('lodge'); // lodge, camp, hotel, resort, tent
                $table->string('category_level')->default('standard'); // budget, standard, mid_range, luxury, luxury_plus, custom
                $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
                $table->string('location_name')->nullable();
                $table->text('description')->nullable();
                $table->json('images')->nullable();
                $table->decimal('star_rating', 3, 1)->default(4.0);
                $table->decimal('supplier_cost_per_night', 10, 2)->nullable(); // PRIVATE ADMIN COST
                $table->json('amenities')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Room Types Table
        if (! Schema::hasTable('room_types')) {
            Schema::create('room_types', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Single, Double, Twin, Triple, Family Room, Custom
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->integer('max_occupants')->default(2);
                $table->decimal('default_supplement_price', 10, 2)->default(0.00);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 3. Tour Packages Table (Dynamic accommodation packages per tour)
        if (! Schema::hasTable('tour_packages')) {
            Schema::create('tour_packages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tour_id')->nullable()->constrained('tours')->onDelete('cascade');
                $table->foreignId('safari_id')->nullable()->constrained('safaris')->onDelete('cascade');
                $table->string('name'); // e.g. Budget Camping Option, Luxury Lodge Option, VIP Serengeti Package
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->decimal('selling_price', 10, 2)->default(0.00);
                $table->decimal('adult_price', 10, 2)->nullable();
                $table->decimal('child_price', 10, 2)->nullable();
                $table->decimal('single_supplement', 10, 2)->default(0.00);
                $table->decimal('deposit_percentage', 5, 2)->default(30.00);
                $table->string('currency', 10)->default('USD');
                $table->json('images')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);

                // INTERNAL PRIVATE ADMIN COSTING (Strictly hidden from guests)
                $table->decimal('supplier_hotel_cost', 10, 2)->default(0.00);
                $table->decimal('park_fees_cost', 10, 2)->default(0.00);
                $table->decimal('transport_cost', 10, 2)->default(0.00);
                $table->decimal('guide_cost', 10, 2)->default(0.00);
                $table->decimal('meals_cost', 10, 2)->default(0.00);
                $table->decimal('transfers_cost', 10, 2)->default(0.00);
                $table->decimal('other_costs', 10, 2)->default(0.00);

                $table->timestamps();
            });
        }

        // 4. Package Itinerary Days (Night-by-night hotel assignments)
        if (! Schema::hasTable('package_itinerary_days')) {
            Schema::create('package_itinerary_days', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tour_package_id')->constrained('tour_packages')->onDelete('cascade');
                $table->integer('day_number')->default(1);
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->foreignId('accommodation_id')->nullable()->constrained('accommodations')->nullOnDelete();
                $table->text('accommodation_notes')->nullable();
                $table->string('meals')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 5. Package Room Options
        if (! Schema::hasTable('package_room_options')) {
            Schema::create('package_room_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tour_package_id')->constrained('tour_packages')->onDelete('cascade');
                $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
                $table->decimal('supplement_price', 10, 2)->default(0.00);
                $table->boolean('is_available')->default(true);
                $table->timestamps();
            });
        }

        // 6. Extend Bookings Table with selected package and room options
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'tour_package_id')) {
                $table->foreignId('tour_package_id')->nullable()->constrained('tour_packages')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'room_type_id')) {
                $table->foreignId('room_type_id')->nullable()->constrained('room_types')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'package_name')) {
                $table->string('package_name')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'room_type_name')) {
                $table->string('room_type_name')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'room_supplement_amount')) {
                $table->decimal('room_supplement_amount', 10, 2)->default(0.00);
            }
            if (! Schema::hasColumn('bookings', 'selected_hotels_summary')) {
                $table->json('selected_hotels_summary')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_room_options');
        Schema::dropIfExists('package_itinerary_days');
        Schema::dropIfExists('tour_packages');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('accommodations');
    }
};
