<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (! Schema::hasColumn('tours', 'extras')) {
                $table->json('extras')->nullable()->after('itinerary');
            }
        });

        Schema::table('safaris', function (Blueprint $table) {
            if (! Schema::hasColumn('safaris', 'extras')) {
                $table->json('extras')->nullable()->after('itinerary');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'selected_extras')) {
                $table->json('selected_extras')->nullable()->after('special_requests');
            }
            if (! Schema::hasColumn('bookings', 'extras_total')) {
                $table->decimal('extras_total', 10, 2)->default(0.00)->after('selected_extras');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('extras');
        });
        Schema::table('safaris', function (Blueprint $table) {
            $table->dropColumn('extras');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['selected_extras', 'extras_total']);
        });
    }
};
