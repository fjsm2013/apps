<?php
/**
 * Test Login Database Creation
 * Tests the automatic tenant database creation during login
 */
require_once 'lib/config.php';
require_once 'lib/AuthManager.php';

echo "<h2>Test Login Database Creation</h2>";

// Test user data (simulating a user from company 8)
$testUser = [
    'id' => 1,
    'id_empresa' => 8,
    'empresa_nombre' => 'Interpal',
    'nombre_base_datos' => 'froshlav_8',
    'name' => 'Test User',
    'email' => 'test@interpal.com'
];

echo "<h3>1. Test User Data</h3>";
echo "<pre>" . print_r($testUser, true) . "</pre>";

echo "<h3>2. Testing ensureTenantDatabase Method</h3>";

try {
    $authManager = new AuthManager($conn);
    
    // Use reflection to access the private method for testing
    $reflection = new ReflectionClass($authManager);
    $method = $reflection->getMethod('ensureTenantDatabase');
    $method->setAccessible(true);
    
    $result = $method->invoke($authManager, $testUser);
    
    echo "<h4>Result:</h4>";
    echo "<pre>" . print_r($result, true) . "</pre>";
    
    if ($result['success']) {
        echo "✅ Database check/creation successful<br>";
        
        if (isset($result['created']) && $result['created']) {
            echo "🆕 New database was created<br>";
            echo "Database: " . ($result['database'] ?? 'N/A') . "<br>";
        } else {
            echo "✅ Database already exists<br>";
        }
        
        if (isset($result['message'])) {
            echo "Message: " . $result['message'] . "<br>";
        }
    } else {
        echo "❌ Database check/creation failed<br>";
        echo "Error: " . ($result['message'] ?? 'Unknown error') . "<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Exception during test: " . $e->getMessage() . "<br>";
}

echo "<h3>3. Verify Database Exists</h3>";

// Check if the database exists
$dbName = 'froshlav_8';
$result = $conn->query("SHOW DATABASES LIKE '$dbName'");

if ($result && $result->num_rows > 0) {
    echo "✅ Database '$dbName' exists<br>";
    
    // Test connection to the database
    try {
        $testConn = new mysqli('localhost', 'fmorgan', '4sf7xnah', $dbName);
        
        if ($testConn->connect_error) {
            echo "❌ Cannot connect to database: " . $testConn->connect_error . "<br>";
        } else {
            echo "✅ Successfully connected to tenant database<br>";
            
            // Check tables
            $tables = $testConn->query("SHOW TABLES");
            if ($tables && $tables->num_rows > 0) {
                echo "Tables in database:<br>";
                while ($row = $tables->fetch_array()) {
                    echo "- " . $row[0] . "<br>";
                }
            } else {
                echo "⚠️ No tables found in database<br>";
            }
            
            $testConn->close();
        }
        
    } catch (Exception $e) {
        echo "❌ Error testing database connection: " . $e->getMessage() . "<br>";
    }
    
} else {
    echo "❌ Database '$dbName' does not exist<br>";
}

echo "<hr>";
echo "<p><a href='test-database-creation.php'>Back to Database Creation Test</a></p>";
echo "<p><a href='login.php'>Test Login</a></p>";
?>