<?php

// Simple test file to verify PHP-FPM and Nginx configuration
echo '<h1>Filament Docker Environment Test</h1>';
echo '<p>If you can see this message, your PHP-FPM and Nginx setup is working correctly.</p>';
echo '<p>Please follow the instructions in the README.md to set up Laravel and Filament.</p>';
echo '<p>Once installed, you can access Filament at: <a href="/admin">/admin</a></p>';

// Display PHP information for verification
phpinfo();

// Test MySQL connection
try {
    $dsn = 'mysql:host=db;dbname=filament';
    $username = 'filament';
    $password = 'secret';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    echo '<p style="color: green;">MySQL connection successful!</p>';
} catch (PDOException $e) {
    echo '<p style="color: red;">MySQL connection failed: ' . $e->getMessage() . '</p>';
}

// Test Valkey/Redis connection
try {
    $redis = new Redis();
    $redis->connect('valkey', 6379);
    $redis->set('test_key', 'Hello from Valkey!');
    $value = $redis->get('test_key');
    echo '<p style="color: green;">Valkey connection successful! Retrieved value: ' . $value . '</p>';
} catch (Exception $e) {
    echo '<p style="color: red;">Valkey connection failed: ' . $e->getMessage() . '</p>';
}