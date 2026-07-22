<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $file = new \Illuminate\Http\UploadedFile(__FILE__, 'test.php', 'text/plain', null, true);
    $res = Illuminate\Support\Facades\Storage::disk('s3')->putFile('profile_pic', $file);
    echo 'Result: ' . var_export($res, true) . PHP_EOL;
} catch (\Throwable $e) {
    echo 'Exception: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
