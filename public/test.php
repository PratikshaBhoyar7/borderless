<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Laravel Configuration</h2>";

// Check storage paths
$paths = [
    '/storage' => 'Exists and writable',
    '/storage/framework' => 'Framework cache',
    '/storage/framework/cache' => 'Cache directory',
    '/storage/framework/cache/data' => 'Cache data',
    '/storage/framework/views' => 'View cache',
    '/storage/framework/sessions' => 'Sessions',
    '/storage/logs' => 'Logs',
    '/bootstrap/cache' => 'Bootstrap cache',
];

foreach ($paths as $path => $description) {
    $fullPath = __DIR__ . $path;
    
    if (!file_exists($fullPath)) {
        echo "<span style='color:red'>✗ Missing: $path ($description)</span><br>";
        if (mkdir($fullPath, 0755, true)) {
            echo "<span style='color:green'>  Created successfully</span><br>";
        }
    } else {
        $writable = is_writable($fullPath);
        $perms = substr(sprintf('%o', fileperms($fullPath)), -3);
        echo ($writable ? "✓" : "✗") . " $path: $perms " . ($writable ? "(writable)" : "(NOT writable)") . " - $description<br>";
        
        if (!$writable) {
            chmod($fullPath, 0755);
            echo "  → Changed to 755<br>";
        }
    }
}

echo "<hr>";

// Test Laravel bootstrap
putenv('COMPOSER_PLATFORM_CHECK=0');

try {
    require __DIR__ . '/vendor/autoload.php';
    echo "✓ Composer autoloader loaded<br>";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✓ Laravel application created<br>";
    
    // Check cache configuration
    $config = $app->make('config');
    echo "✓ Config loaded<br>";
    
    // Test cache
    $cache = $app->make('cache');
    $cache->put('test', 'works', 1);
    echo "✓ Cache system working: " . $cache->get('test') . "<br>";
    
    echo "<h2 style='color:green;'>✓ SUCCESS! Laravel is ready!</h2>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>ERROR:</h2>";
    echo $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "<br>";
}
?>