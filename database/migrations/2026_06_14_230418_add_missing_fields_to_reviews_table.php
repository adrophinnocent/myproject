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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('reviews', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('reviews', 'country')) {
                $table->string('country')->nullable()->after('email');
            }
            if (!Schema::hasColumn('reviews', 'trip_date')) {
                $table->date('trip_date')->nullable()->after('content');
            }
            if (!Schema::hasColumn('reviews', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('trip_date');
            }
            if (!Schema::hasColumn('reviews', 'helpful_count')) {
                $table->integer('helpful_count')->default(0)->after('is_approved');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'country', 'trip_date', 'is_approved', 'helpful_count']);
        });
    }
};
