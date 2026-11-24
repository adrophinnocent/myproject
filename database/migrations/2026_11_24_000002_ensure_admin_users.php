<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Production databases can miss the is_admin column or have the primary
     * admin account without the flag, which sends valid logins back home.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('email_verified_at');
            });
        }

        User::whereIn('email', [
            'twinasafaris@gmail.com',
            'admin@twinasafaris.com',
            'info@twinasafaris.com',
            'admin@tourism.com',
            'bellainnos@gmail.com',
        ])->update(['is_admin' => true]);
    }

    public function down(): void
    {
        // Intentionally blank: rolling back must not revoke admin access.
    }
};
