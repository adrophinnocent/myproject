<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('safari'); // safari, kilimanjaro, tour
            $table->text('description')->nullable();
            $table->longText('itinerary')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('status')->default('draft'); // draft, published, scheduled
            $table->string('tracking_id')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};
