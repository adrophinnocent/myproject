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
        Schema::create('ai_knowledge', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable()->comment('e.g. Serengeti, Company, Logistics');
            $table->string('topic')->comment('What is this fact about?');
            $table->text('content')->comment('The detailed information for the AI');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_knowledge');
    }
};
