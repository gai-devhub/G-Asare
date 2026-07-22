<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Storage::disk('s3')->put('test-upload.txt', 'hello from G-Base', 'public');
    echo 'SUCCESS: ' . Illuminate\Support\Facades\Storage::disk('s3')->url('test-upload.txt') . PHP_EOL;
} catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
