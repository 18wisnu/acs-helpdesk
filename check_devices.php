<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$devices = App\Models\Device::all(['id', 'genieacs_id', 'rx_power']);
file_put_contents('tmp_device_data.json', json_encode($devices, JSON_PRETTY_PRINT));
echo "Dumped " . $devices->count() . " devices.\n";
