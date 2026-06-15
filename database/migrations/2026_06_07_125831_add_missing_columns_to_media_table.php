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
        Schema::table('media', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('filename')->after('name');
            $table->string('path')->after('filename');
            $table->string('thumb')->nullable()->after('path');
            $table->string('type')->default('image')->after('thumb');
            $table->string('mime_type')->nullable()->after('type');
            $table->unsignedBigInteger('size')->default(0)->after('mime_type');
            $table->string('url')->after('size');
            $table->string('alt')->nullable()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'filename',
                'path',
                'thumb',
                'type',
                'mime_type',
                'size',
                'url',
                'alt',
            ]);
        });
    }
};
