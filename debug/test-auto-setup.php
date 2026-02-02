<?php
/**
 * Test Auto-Setup System
 * This page tests if the auto-setup is working correctly
 */

echo "<h1>FROSH Auto-Setup Test</h1>";
echo "<pre>";

// Check if setup complete file exists
$setupCompleteFile = __DIR__ . '/lib/.setup_complete';
$setupErrorFile = __DIR__ . '/lib/.setup_error';

echo "=== Setup Status ===\n";
echo "Setup Complete File: " . ($setupCompleteFile) . "\n";
echo "Exists: " . (file_exists($setupCompleteFile) ? "YES" : "NO") . "\n\n";

if (file_exists($setupCompleteFile)) {
    echo "Setup completed at: " . file_get_contents($setupCompleteFile) . "\n\n";
}

if (file_exists($setupErrorFile)) {
    echo "=== Setup Errors ===\n";
    echo file_get_contents($setupErrorFile) . "\n\n";
}

// Try to connect and check database
echo "=== Database Check ===\n";
require_once 'lib/config.php';

try {
    $result = $conn->query("SHOW DATABASES LIKE 'frosh_lavacar'");
    if ($result && $result->num_rows > 0) {
        echo "✓ Database 'frosh_lavacar' EXISTS\n\n";
        
        // Check tables
        $conn->select_db('frosh_lavacar');
        $tables = $conn->query("SHOW TABLES");
        echo "=== Tables in frosh_lavacar ===\n";
        while ($row = $tables->fetch_array()) {
            echo "  - " . $row[0] . "\n";
        }
    } else {
        echo "✗ Database 'frosh_lavacar' DOES NOT EXIST\n";
        echo "\nAttempting to create database...\n";
        
        // Force setup
        if (file_exists($setupCompleteFile)) {
            unlink($setupCompleteFile);
            echo "Removed setup complete flag\n";
        }
        
        // Reload page to trigger setup
        echo "\n<a href='test-auto-setup.php'>Click here to reload and trigger setup</a>\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</pre>";

echo "<hr>";
echo "<h2>Manual Setup</h2>";
echo "<p>If auto-setup is not working, you can manually create the database:</p>";
echo "<ol>";
echo "<li>Delete lib/.setup_complete file if it exists</li>";
echo "<li>Reload this page</li>";
echo "<li>Or run: CREATE DATABASE frosh_lavacar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</li>";
echo "</ol>";
?>
