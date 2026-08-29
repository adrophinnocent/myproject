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
        Schema::table('trip_plans', function (Blueprint $table) {
            $table->string('trip_title')->nullable()->after('message');
            $table->date('end_date')->nullable()->after('travel_date');

            // Pricing
            $table->decimal('price_per_person', 10, 2)->default(0)->after('trip_title');
            $table->decimal('total_price', 10, 2)->default(0)->after('price_per_person');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_price');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('deposit_amount', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('balance_amount', 10, 2)->default(0)->after('deposit_amount');
            $table->string('currency')->default('USD')->after('balance_amount');

            // Detailed Content
            $table->json('itinerary_data')->nullable()->after('currency');
            $table->json('inclusions_data')->nullable()->after('itinerary_data');
            $table->json('exclusions_data')->nullable()->after('inclusions_data');
            $table->text('terms_conditions')->nullable()->after('exclusions_data');

            // Workflow Timestamps
            $table->timestamp('sent_at')->nullable()->after('status');
            $table->timestamp('viewed_at')->nullable()->after('sent_at');
            $table->timestamp('accepted_at')->nullable()->after('viewed_at');
            $table->timestamp('declined_at')->nullable()->after('accepted_at');

            // Payment tracking
            $table->string('payment_status')->default('unpaid')->after('status'); // unpaid, partial, paid, refunded
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_plans', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn([
                'trip_title', 'end_date', 'price_per_person', 'total_price',
                'discount_amount', 'tax_amount', 'deposit_amount', 'balance_amount',
                'currency', 'itinerary_data', 'inclusions_data', 'exclusions_data',
                'terms_conditions', 'sent_at', 'viewed_at', 'accepted_at',
                'declined_at', 'payment_status', 'booking_id'
            ]);
        });
    }
};
