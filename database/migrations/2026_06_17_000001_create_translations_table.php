<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type'); // e.g. App\Models\Tour
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale')->index(); // en, fr, de, etc.
            $table->string('field'); // title, content, etc.
            $table->text('text');
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'locale', 'field'], 'trans_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
    }
};
