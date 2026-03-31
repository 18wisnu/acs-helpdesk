<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'helpdesk-acs';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connect failed: " . $conn->connect_error);

$res = $conn->query("SHOW COLUMNS FROM devices LIKE 'ssid_2'");
if ($res && $res->num_rows > 0) {
    echo "CHECK_RESULT: COLUMN_FOUND\n";
} else {
    echo "CHECK_RESULT: COLUMN_MISSING\n";
}
$conn->close();
