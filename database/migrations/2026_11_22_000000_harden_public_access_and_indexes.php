<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Every operation is guarded by table/column/index existence so a
        // partially migrated production database can safely re-run this file.
        if (Schema::hasTable('trip_plans')) {
            if (! Schema::hasColumn('trip_plans', 'token')) {
                Schema::table('trip_plans', function (Blueprint $table) {
                    $table->string('token', 64)->nullable()->after('id');
                });
            }

            DB::table('trip_plans')
                ->whereNull('token')
                ->orWhere('token', '')
                ->orderBy('id')
                ->each(function ($plan) {
                    DB::table('trip_plans')
                        ->where('id', $plan->id)
                        ->update(['token' => Str::random(48)]);
                });

            if (! $this->hasIndex('trip_plans', 'token')) {
                Schema::table('trip_plans', function (Blueprint $table) {
                    $table->unique('token');
                });
            }
        }

        if (Schema::hasTable('reviews') && ! Schema::hasColumn('reviews', 'trip_date')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->date('trip_date')->nullable()->after('content');
            });
        }

        // Public lookup columns need indexes; sequential scans on these tables are hot paths.
        foreach ([
            ['bookings', 'email'],
            ['bookings', 'status'],
            ['bookings', 'travel_date'],
            ['booking_payments', 'status'],
            ['reviews', 'is_approved'],
            ['faqs', 'is_active'],
            ['trip_plans', 'status'],
        ] as [$table, $column]) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column) && ! $this->hasIndex($table, $column)) {
                Schema::table($table, function (Blueprint $blueprint) use ($column) {
                    $blueprint->index($column);
                });
            }
        }

        // Legacy bookings were created before tokens existed; backfill before
        // adding the unique index or it fails on duplicate empty values.
        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'token')) {
            DB::table('bookings')
                ->whereNull('token')
                ->orWhere('token', '')
                ->orderBy('id')
                ->each(function ($booking) {
                    do {
                        $token = Str::random(48);
                    } while (DB::table('bookings')->where('token', $token)->exists());

                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update(['token' => $token]);
                });

            if (! $this->hasIndex('bookings', 'token')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->unique('token');
                });
            }
        }
    }

    public function down(): void
    {
        // Data-preserving migration; indexes are intentionally left in place.
    }

    private function hasIndex(string $table, string $column): bool
    {
        if (! Schema::hasTable($table)) {
            return false;
        }

        return collect(Schema::getIndexes($table))
            ->contains(fn (array $index) => in_array($column, $index['columns'], true));
    }
};
