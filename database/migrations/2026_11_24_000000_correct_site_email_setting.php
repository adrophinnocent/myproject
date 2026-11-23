<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The public contact address is twinasafaris@gmail.com. Older production
     * data still held info@twinasafaris.com, which was shown in the header,
     * footer and contact page while the schema.org email stayed correct.
     */
    public function up(): void
    {
        $current = Setting::get('site_email');

        if ($current === null || strtolower(trim((string) $current)) === 'info@twinasafaris.com') {
            Setting::set('site_email', 'twinasafaris@gmail.com');
        }
    }

    public function down(): void
    {
        // Do not restore an incorrect public contact address.
    }
};
