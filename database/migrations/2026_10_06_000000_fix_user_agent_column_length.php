<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('campaign_stats')) {
            Schema::table('campaign_stats', function (Blueprint $table) {
                $table->text('user_agent')->nullable()->change();
                $table->text('referrer')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('campaign_stats')) {
            Schema::table('campaign_stats', function (Blueprint $table) {
                $table->string('user_agent', 255)->nullable()->change();
                $table->string('referrer', 255)->nullable()->change();
            });
        }
    }
};
