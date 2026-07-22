<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $stream = fopen(__FILE__, 'r');
    $driver = Illuminate\Support\Facades\Storage::disk('s3')->getDriver();
    $driver->writeStream('profile_pic/test.php', $stream, ['visibility' => 'public']);
    echo 'Result: SUCCESS' . PHP_EOL;
} catch (\Throwable $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL;
}
