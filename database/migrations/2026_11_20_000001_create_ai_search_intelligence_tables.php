<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Production databases can already contain some of these tables from a
     * partially applied deploy. Each create is guarded so a re-run never
     * drops, replaces or deletes existing production data.
     */
    public function up(): void
    {
        // 1. Search Question Categories
        if (! Schema::hasTable('search_question_categories')) {
            Schema::create('search_question_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Search Questions
        if (! Schema::hasTable('search_questions')) {
            Schema::create('search_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('search_question_categories')->onDelete('cascade');
                $table->foreignId('tour_id')->nullable()->constrained('tours')->onDelete('set null');
                $table->text('question');
                $table->string('slug')->unique();
                $table->string('primary_keyword')->nullable();
                $table->json('secondary_keywords')->nullable();
                $table->string('search_intent')->default('informational'); // informational, commercial, transactional, navigational
                $table->string('target_country')->nullable(); // USA, UK, Germany, France, Norway, etc.
                $table->string('suggested_content_type')->default('tour'); // tour, destination, blog, faq, company
                $table->string('status')->default('draft'); // draft, approved, published, archived
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 3. Search Answers
        if (! Schema::hasTable('search_answers')) {
            Schema::create('search_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('search_question_id')->constrained('search_questions')->onDelete('cascade');
                $table->string('title')->nullable(); // Answer title / Meta title
                $table->longText('content'); // Comprehensive answer
                $table->text('meta_description')->nullable();
                $table->json('suggested_links')->nullable();
                $table->json('related_questions')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 4. Search Content Assignments
        if (! Schema::hasTable('search_content_assignments')) {
            Schema::create('search_content_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('search_question_id')->constrained('search_questions')->onDelete('cascade');
                $table->string('target_type')->default('faq'); // tour, destination, blog, faq, company
                $table->unsignedBigInteger('target_id')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. AI Visibility Checks (Brand Mentions in ChatGPT, Gemini, Perplexity)
        if (! Schema::hasTable('ai_visibility_checks')) {
            Schema::create('ai_visibility_checks', function (Blueprint $table) {
                $table->id();
                $table->text('query_text');
                $table->string('ai_platform'); // ChatGPT, Gemini, Perplexity, Copilot, etc.
                $table->boolean('is_brand_mentioned')->default(false);
                $table->boolean('is_source_cited')->default(false);
                $table->json('competitors_mentioned')->nullable();
                $table->text('source_link')->nullable();
                $table->date('check_date');
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_visibility_checks');
        Schema::dropIfExists('search_content_assignments');
        Schema::dropIfExists('search_answers');
        Schema::dropIfExists('search_questions');
        Schema::dropIfExists('search_question_categories');
    }
};
