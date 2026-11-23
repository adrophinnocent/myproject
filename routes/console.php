<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// App\Console\Commands\CompressBanners is auto-discovered; no manual
// registration needed here (it previously shadowed the real command).
