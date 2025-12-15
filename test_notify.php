<?php
echo "FILE NotificationService.php LOADED<br>";
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/app/NotificationService.php";

echo "<pre>";
echo "Declared classes:\n";
print_r(get_declared_classes());
echo "</pre>";

echo "<br>class_exists: ";
var_dump(class_exists("NotificationService"));

echo "Current dir: " . __DIR__ . "<br>";
echo "Check file: " . __DIR__ . "/app/NotificationService.php<br>";

if (file_exists(__DIR__ . "/app/NotificationService.php")) {
    echo "FOUND<br>";
} else {
    echo "NOT FOUND<br>";
}

$svc = new NotificationService($koneksi);
$ok = $svc->logNotification(1, "Test Notif", "Ini pesan percobaan", "sent");

echo $ok ? "<br>NOTIF BERHASIL INSERT" : "<br>GAGAL INSERT";