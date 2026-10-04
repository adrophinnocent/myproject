<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('tour_id')->nullable()->constrained('tours')->nullOnDelete();

            // Client details
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();

            // Proposal Overview
            $table->string('title');
            $table->text('welcome_message')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration_days')->default(1);
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->string('currency')->default('USD');

            // Client Pricing & Terms
            $table->decimal('total_price', 12, 2)->default(0.00);
            $table->decimal('price_per_person', 12, 2)->nullable();
            $table->decimal('deposit_required', 12, 2)->nullable();

            // Structured Itinerary, Inclusions, Exclusions
            $table->json('itinerary')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();

            // Terms and payment details
            $table->text('payment_terms')->nullable();
            $table->text('terms_conditions')->nullable();

            // INTERNAL COSTING (ADMIN ONLY)
            $table->json('internal_costing')->nullable();

            // Status & Timestamps
            $table->string('status')->default('draft');
            $table->text('client_feedback')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
