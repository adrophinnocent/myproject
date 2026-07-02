<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaign_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('type'); // click, visit, whatsapp, conversion
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('platform')->nullable(); // fb, insta, tiktok, x, wa
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaign_stats');
    }
};
