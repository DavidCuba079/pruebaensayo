<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$logFile = __DIR__ . '/../storage/logs/laravel.log';

if (file_exists($logFile)) {
    header('Content-Type: text/plain');
    echo file_get_contents($logFile);
} else {
    echo "Log file not found at: " . $logFile;
}
