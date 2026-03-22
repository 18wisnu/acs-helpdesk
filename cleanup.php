<?php
$files = [
    "'required", "'required'", "count()", "delete()", "genieacs_id", 
    "get()", "password", "ssid", "validate([", 
    "check_devices.php", "cleanup_now.php", "run_sync.php"
];
foreach ($files as $f) {
    if (file_exists($f)) {
        if (unlink($f)) echo "Deleted $f\n";
        else echo "Failed to delete $f\n";
    } else {
        echo "Not found: $f\n";
    }
}
