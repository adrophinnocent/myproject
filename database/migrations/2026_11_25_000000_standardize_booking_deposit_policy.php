<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Standardise the public booking policy at 30% / 65 days. The booking flow
     * now uses Booking::DEPOSIT_PERCENTAGE directly; this keeps the stored
     * setting consistent for any other code that still reads it.
     */
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'deposit_percentage'],
            ['value' => '30', 'updated_at' => now(), 'created_at' => now()]
        );

        Cache::forget('setting_deposit_percentage');
    }

    public function down(): void
    {
        // Intentionally left blank: deposit policy changes are not reversible
        // once bookings have been created under the new terms.
    }
};
