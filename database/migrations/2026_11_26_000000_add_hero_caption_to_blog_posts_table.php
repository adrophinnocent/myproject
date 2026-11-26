<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_posts') && !Schema::hasColumn('blog_posts', 'hero_caption')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->string('hero_caption')->nullable()->after('excerpt');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('blog_posts') && Schema::hasColumn('blog_posts', 'hero_caption')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                $table->dropColumn('hero_caption');
            });
        }
    }
};
