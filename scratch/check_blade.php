<?php
define("LARAVEL_START", microtime(true));
require __DIR__ . "/../vendor/autoload.php";
$app = require __DIR__ . "/../bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$blade = app("blade.compiler");
$source = file_get_contents(__DIR__ . "/../resources/views/livewire/project-monitor.blade.php");
echo "Source: " . strlen($source) . " bytes\n";
try {
    $compiled = $blade->compileString($source);
    echo "Blade compiled OK. Length: " . strlen($compiled) . "\n";
    $tmp = __DIR__ . "/compiled_out.php";
    file_put_contents($tmp, $compiled);
    $output = []; $code = 0;
    exec("php -l \"$tmp\" 2>&1", $output, $code);
    echo "Lint exit: $code\n";
    echo implode("\n", $output) . "\n";
    if ($code !== 0) {
        foreach ($output as $line) {
            if (preg_match("/on line (\d+)/", $line, $m)) {
                $errLine = (int)$m[1];
                $lines = explode("\n", $compiled);
                echo "\n--- Lines around error (line $errLine) ---\n";
                for ($i = max(0, $errLine-5); $i < min(count($lines), $errLine+5); $i++) {
                    echo ($i+1) . ": " . $lines[$i] . "\n";
                }
            }
        }
    }
} catch (Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }
