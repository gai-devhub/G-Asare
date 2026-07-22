<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $stream = fopen(__FILE__, 'r');
    $driver = Illuminate\Support\Facades\Storage::disk('s3')->getDriver();
    // Do not pass visibility!
    $driver->writeStream('profile_pic/test2.php', $stream, []);
    echo 'Result: SUCCESS without visibility!' . PHP_EOL;
} catch (\Throwable $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
}
