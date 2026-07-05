<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Explicitly register the banners compression command
Artisan::command('banners:compress', function () {
    $this->call(\App\Console\Commands\CompressBanners::class);
})->purpose('Compress and convert all banner images to WebP format');
