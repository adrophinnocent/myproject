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
            if (!Schema::hasColumn('tours', 'location_name')) {
                $table->string('location_name')->nullable()->after('meeting_point');
            }
            if (!Schema::hasColumn('tours', 'latitude')) {
                $table->string('latitude')->nullable()->after('location_name');
            }
            if (!Schema::hasColumn('tours', 'longitude')) {
                $table->string('longitude')->nullable()->after('latitude');
            }
        });

        Schema::table('safaris', function (Blueprint $table) {
            if (!Schema::hasColumn('safaris', 'location_name')) {
                $table->string('location_name')->nullable()->after('departure_location');
            }
            if (!Schema::hasColumn('safaris', 'latitude')) {
                $table->string('latitude')->nullable()->after('location_name');
            }
            if (!Schema::hasColumn('safaris', 'longitude')) {
                $table->string('longitude')->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['location_name', 'latitude', 'longitude']);
        });

        Schema::table('safaris', function (Blueprint $table) {
            $table->dropColumn(['location_name', 'latitude', 'longitude']);
        });
    }
};
