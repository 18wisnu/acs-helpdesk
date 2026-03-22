<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new App\Http\Controllers\HelpdeskController();
$result = $controller->syncDevices();
echo "Sync result: " . session('success') . session('error') . "\n";

$devices = App\Models\Device::all(['id', 'genieacs_id', 'rx_power']);
foreach ($devices as $d) {
    echo "ID: {$d->id}, ACS: {$d->genieacs_id}, RX: '{$d->rx_power}'\n";
}
