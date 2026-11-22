<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'tour_id')) {
                $table->foreignId('tour_id')->nullable()->constrained('tours')->onDelete('cascade');
            }
            if (!Schema::hasColumn('faqs', 'destination_id')) {
                $table->foreignId('destination_id')->nullable()->constrained('destinations')->onDelete('cascade');
            }
            if (!Schema::hasColumn('faqs', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            }
            if (!Schema::hasColumn('faqs', 'primary_keyword')) {
                $table->string('primary_keyword')->nullable();
            }
            if (!Schema::hasColumn('faqs', 'is_verified_traveler')) {
                $table->boolean('is_verified_traveler')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->dropForeign(['destination_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['tour_id', 'destination_id', 'category_id', 'primary_keyword', 'is_verified_traveler']);
        });
    }
};
