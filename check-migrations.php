<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Current migrations:\n";
$migrations = DB::table('migrations')->get()->pluck('migration')->toArray();
print_r($migrations);
