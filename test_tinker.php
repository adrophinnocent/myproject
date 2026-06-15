<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;

$controller = new HomeController;
$response = $controller->index(Request::create('/', 'GET'));

echo $response->render();
