<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tours') && !Schema::hasColumn('tours', 'accommodation_options')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->json('accommodation_options')->nullable()->after('extras');
            });
        }

        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'selected_accommodation_tier')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->json('selected_accommodation_tier')->nullable()->after('total_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tours') && Schema::hasColumn('tours', 'accommodation_options')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->dropColumn('accommodation_options');
            });
        }

        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'selected_accommodation_tier')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('selected_accommodation_tier');
            });
        }
    }
};
