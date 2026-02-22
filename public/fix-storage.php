<?php
echo "<h2>Fixing Laravel Storage Structure</h2>";

// Define all required storage directories
$directories = [
    '/storage/framework',
    '/storage/framework/cache',
    '/storage/framework/cache/data',
    '/storage/framework/sessions',
    '/storage/framework/views',
    '/storage/framework/testing',
    '/storage/logs',
    '/storage/app',
    '/storage/app/public',
    '/storage/framework/testing/sessions',
    '/storage/framework/testing/views',
    '/storage/framework/testing/cache',
    '/bootstrap/cache',
];

$basePath = __DIR__;

foreach ($directories as $dir) {
    $fullPath = $basePath . $dir;
    
    if (!file_exists($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            echo "Created: $dir<br>";
        } else {
            echo "<span style='color:red'>Failed to create: $dir</span><br>";
        }
    } else {
        echo "Exists: $dir<br>";
    }
    
    // Set permissions
    if (file_exists($fullPath)) {
        chmod($fullPath, 0755);
    }
}

// Create .gitignore files in cache directories
$gitignoreContent = "*\n!.gitignore\n";
file_put_contents($basePath . '/storage/framework/cache/.gitignore', $gitignoreContent);
file_put_contents($basePath . '/storage/framework/cache/data/.gitignore', $gitignoreContent);
file_put_contents($basePath . '/bootstrap/cache/.gitignore', $gitignoreContent);

echo "<h3>Setting permissions...</h3>";

// Set special permissions
chmod($basePath . '/storage', 0755);
chmod($basePath . '/bootstrap/cache', 0755);

echo "<h3 style='color:green'>✓ Storage structure fixed!</h3>";

// Test if Laravel can write
$testFile = $basePath . '/storage/test-write.txt';
if (file_put_contents($testFile, 'Test write at ' . date('Y-m-d H:i:s'))) {
    echo "✓ Can write to storage<br>";
    unlink($testFile);
} else {
    echo "<span style='color:red'>✗ Cannot write to storage</span><br>";
}

// Show current permissions
echo "<h3>Current Permissions:</h3>";
foreach (['/storage', '/storage/framework', '/storage/framework/cache', '/bootstrap/cache'] as $dir) {
    if (file_exists($basePath . $dir)) {
        $perms = substr(sprintf('%o', fileperms($basePath . $dir)), -3);
        echo "$dir: $perms<br>";
    }
}
?>