
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\View;

echo "Testing test.blade.php...\n";
try {
    echo View::make('test')->render();
} catch (Exception $e) {
    echo "Error in test.blade.php:\n";
    echo $e->getMessage()."\n";
    echo $e->getTraceAsString();
}
