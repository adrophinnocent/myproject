<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('duration');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_limited')->default(false);
            $table->integer('max_group_size')->nullable();
            $table->string('difficulty')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
