<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing fields to bookings table if they don't exist
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'token')) {
                $table->string('token', 64)->nullable()->after('booking_reference');
            }
            if (! Schema::hasColumn('bookings', 'country')) {
                $table->string('country', 100)->nullable()->after('nationality');
            }
            if (! Schema::hasColumn('bookings', 'currency')) {
                $table->string('currency', 10)->default('USD')->after('total_price');
            }
            if (! Schema::hasColumn('bookings', 'deposit_percentage')) {
                $table->decimal('deposit_percentage', 5, 2)->default(30.00)->after('currency');
            }
            if (! Schema::hasColumn('bookings', 'deposit_required')) {
                $table->decimal('deposit_required', 10, 2)->nullable()->after('deposit_percentage');
            }
            if (! Schema::hasColumn('bookings', 'deposit_paid')) {
                $table->decimal('deposit_paid', 10, 2)->default(0.00)->after('deposit_required');
            }
            if (! Schema::hasColumn('bookings', 'balance_amount')) {
                $table->decimal('balance_amount', 10, 2)->nullable()->after('deposit_paid');
            }
            if (! Schema::hasColumn('bookings', 'balance_due_date')) {
                $table->date('balance_due_date')->nullable()->after('balance_amount');
            }
            if (! Schema::hasColumn('bookings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('confirmed_at');
            }
            if (! Schema::hasColumn('bookings', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('admin_notes');
            }
        });

        // 2. Create booking_payments table for transaction tracking
        if (! Schema::hasTable('booking_payments')) {
            Schema::create('booking_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained()->onDelete('cascade');
                $table->string('transaction_reference')->unique();
                $table->string('gateway'); // pesapal, flutterwave, paypal, stripe, bank_transfer, mpesa, tigopesa
                $table->string('payment_type'); // deposit, balance, full
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('USD');
                $table->string('status')->default('pending'); // pending, completed, failed, refunded
                $table->string('payer_name')->nullable();
                $table->string('payer_email')->nullable();
                $table->string('gateway_reference')->nullable();
                $table->json('gateway_response')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
