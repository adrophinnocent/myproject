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
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if (! Schema::hasColumn('newsletter_subscribers', 'confirmation_token')) {
                $table->string('confirmation_token', 64)->nullable()->after('email');
            }
            if (! Schema::hasColumn('newsletter_subscribers', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('subscribed_at');
            }
            if (! Schema::hasColumn('newsletter_subscribers', 'unsubscribe_token')) {
                $table->string('unsubscribe_token', 64)->nullable()->after('confirmed_at');
            }
        });

        // Existing subscribers signed up before double opt-in existed: treat
        // active ones as already confirmed, and give everyone an unsubscribe link.
        DB::table('newsletter_subscribers')
            ->where('active', true)
            ->whereNull('confirmed_at')
            ->update(['confirmed_at' => now()]);

        DB::table('newsletter_subscribers')
            ->whereNull('unsubscribe_token')
            ->orWhere('unsubscribe_token', '')
            ->orderBy('id')
            ->each(function ($subscriber) {
                do {
                    $token = Str::random(48);
                } while (DB::table('newsletter_subscribers')->where('unsubscribe_token', $token)->exists());

                DB::table('newsletter_subscribers')
                    ->where('id', $subscriber->id)
                    ->update(['unsubscribe_token' => $token]);
            });

        if (! $this->hasIndex('newsletter_subscribers', 'confirmation_token')) {
            Schema::table('newsletter_subscribers', function (Blueprint $table) {
                $table->unique('confirmation_token');
            });
        }

        if (! $this->hasIndex('newsletter_subscribers', 'unsubscribe_token')) {
            Schema::table('newsletter_subscribers', function (Blueprint $table) {
                $table->unique('unsubscribe_token');
            });
        }
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropColumn(['confirmation_token', 'confirmed_at', 'unsubscribe_token']);
        });
    }

    private function hasIndex(string $table, string $column): bool
    {
        return collect(Schema::getIndexes($table))
            ->contains(fn (array $index) => in_array($column, $index['columns'], true));
    }
};
