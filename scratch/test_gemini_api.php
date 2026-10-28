<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$extractor = new App\Services\GeminiDocumentTaskExtractor();
$reflection = new ReflectionClass($extractor);
$method = $reflection->getMethod('credentials');
$method->setAccessible(true);
[$key, $model] = $method->invoke($extractor);

echo "API Key Length: " . strlen($key) . "\n";
echo "Model: " . $model . "\n";
if (strlen($key) > 6) {
    echo "API Key Prefix: " . substr($key, 0, 6) . "...\n";
}

// Let's test a simple ping to Gemini
$res = Illuminate\Support\Facades\Http::withHeaders(['x-goog-api-key' => $key])
    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
        'contents' => [['parts' => [['text' => 'ping']]]]
    ]);

echo "HTTP Status: " . $res->status() . "\n";
echo "Response Body: " . substr($res->body(), 0, 300) . "\n";
