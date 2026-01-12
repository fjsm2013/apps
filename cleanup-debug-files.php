<?php
/**
 * Cleanup Debug Files
 * Removes temporary debug and test files
 */

$debugFiles = [
    'debug-registration.php',
    'debug-login.php',
    'test-registration.php',
    'test-step2.php',
    'register-simple.php',
    'test-database-creation.php'
];

echo "<h2>Cleanup Debug Files</h2>";

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    echo "<h3>Removing Debug Files...</h3>";
    
    foreach ($debugFiles as $file) {
        if (file_exists($file)) {
            if (unlink($file)) {
                echo "✅ Deleted: $file<br>";
            } else {
                echo "❌ Failed to delete: $file<br>";
            }
        } else {
            echo "ℹ️ Not found: $file<br>";
        }
    }
    
    echo "<br>✅ Cleanup completed!<br>";
    echo "<p><a href='register.php'>Go to Registration</a></p>";
    
} else {
    echo "<p>This will delete the following debug files:</p>";
    echo "<ul>";
    foreach ($debugFiles as $file) {
        if (file_exists($file)) {
            echo "<li>✅ $file</li>";
        } else {
            echo "<li>❌ $file (not found)</li>";
        }
    }
    echo "</ul>";
    
    echo "<p><strong>Are you sure you want to delete these files?</strong></p>";
    echo "<p>";
    echo "<a href='?confirm=yes' onclick='return confirm(\"Delete all debug files?\")' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>🗑️ Yes, Delete All</a> ";
    echo "<a href='register.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Cancel</a>";
    echo "</p>";
}
?>