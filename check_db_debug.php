<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

if (Schema::hasColumn('devices', 'ssid_2')) {
    echo "COLUMN_EXISTS: YES\n";
} else {
    echo "COLUMN_EXISTS: NO\n";
}
