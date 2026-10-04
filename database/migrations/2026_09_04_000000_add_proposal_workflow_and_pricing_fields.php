<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->date('valid_until')->nullable()->after('end_date');
            $table->string('route_summary')->nullable()->after('end_location');

            $table->decimal('deposit_percentage', 5, 2)->nullable()->default(30.00)->after('deposit_required');
            $table->decimal('balance_amount', 12, 2)->nullable()->after('deposit_percentage');
            $table->date('balance_due_date')->nullable()->after('balance_amount');

            $table->text('payment_methods')->nullable()->after('payment_terms');
            $table->text('payment_instructions')->nullable()->after('payment_methods');
            $table->text('refund_policy')->nullable()->after('cancellation_policy');

            $table->timestamp('first_viewed_at')->nullable()->after('viewed_at');
            $table->timestamp('last_viewed_at')->nullable()->after('first_viewed_at');
            $table->integer('view_count')->default(0)->after('last_viewed_at');

            $table->string('signature_name')->nullable()->after('accepted_at');
            $table->string('accepted_email')->nullable()->after('signature_name');
        });

        Schema::table('proposal_templates', function (Blueprint $table) {
            $table->string('route_summary')->nullable()->after('end_location');
            $table->decimal('default_deposit_percentage', 5, 2)->nullable()->default(30.00)->after('default_child_price');
            $table->text('payment_methods')->nullable()->after('payment_terms');
            $table->text('payment_instructions')->nullable()->after('payment_methods');
            $table->text('refund_policy')->nullable()->after('cancellation_policy');
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn([
                'valid_until', 'route_summary', 'deposit_percentage', 'balance_amount',
                'balance_due_date', 'payment_methods', 'payment_instructions', 'refund_policy',
                'first_viewed_at', 'last_viewed_at', 'view_count', 'signature_name', 'accepted_email'
            ]);
        });

        Schema::table('proposal_templates', function (Blueprint $table) {
            $table->dropColumn([
                'route_summary', 'default_deposit_percentage', 'payment_methods',
                'payment_instructions', 'refund_policy'
            ]);
        });
    }
};
