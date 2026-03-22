<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Device;

$count = Device::where(function($q) {
    $q->where('brand', 'Unknown')
      ->orWhere('sn', '-')
      ->orWhere('sn', '')
      ->orWhereNull('sn')
      ->orWhereNull('brand');
})->delete();

echo "SUCCESS: Kicked $count discovery ONT devices from the database.\n";
