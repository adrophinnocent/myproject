<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tours', 'pickup_locations')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->json('pickup_locations')->nullable();
            });
        }

        Schema::table('safaris', function (Blueprint $table) {
            if (!Schema::hasColumn('safaris', 'pickup_locations')) {
                $table->json('pickup_locations')->nullable();
            }
            if (!Schema::hasColumn('safaris', 'meeting_point')) {
                $table->string('meeting_point')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('pickup_locations');
        });
        Schema::table('safaris', function (Blueprint $table) {
            $table->dropColumn(['pickup_locations', 'meeting_point']);
        });
    }
};
