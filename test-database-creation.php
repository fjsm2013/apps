<?php
/**
 * Test Tenant Database Creation
 */
require_once 'lib/config.php';
require_once 'lib/TenantDatabaseManager.php';

echo "<h2>Test Tenant Database Creation</h2>";

// Test database connection
echo "<h3>1. Master Database Connection Test</h3>";
if ($conn && $conn->ping()) {
    echo "✅ Master database connection OK<br>";
} else {
    echo "❌ Master database connection failed<br>";
    exit;
}

// Test template file
echo "<h3>2. Template File Test</h3>";
$templatePath = __DIR__ . '/lib/schema/tenant.sql';
if (file_exists($templatePath)) {
    echo "✅ Tenant template file exists: $templatePath<br>";
    $templateSize = filesize($templatePath);
    echo "Template size: " . number_format($templateSize) . " bytes<br>";
} else {
    echo "❌ Tenant template file not found: $templatePath<br>";
    exit;
}

// Initialize database manager
echo "<h3>3. Database Manager Test</h3>";
try {
    $dbManager = new TenantDatabaseManager($conn);
    echo "✅ Database manager initialized<br>";
} catch (Exception $e) {
    echo "❌ Failed to initialize database manager: " . $e->getMessage() . "<br>";
    exit;
}

// Test database creation (if requested)
if (isset($_GET['create_test']) && $_GET['create_test'] === 'yes') {
    echo "<h3>4. Creating Test Database</h3>";
    
    $testCompanyId = 999; // Use a test ID
    $testCompanyName = "Test Company " . date('Y-m-d H:i:s');
    
    echo "Creating database for:<br>";
    echo "- Company ID: $testCompanyId<br>";
    echo "- Company Name: $testCompanyName<br>";
    
    try {
        $result = $dbManager->createTenantDatabase($testCompanyId, $testCompanyName);
        
        if ($result['success']) {
            echo "✅ Database created successfully!<br>";
            echo "Database name: " . $result['database'] . "<br>";
            echo "Message: " . $result['message'] . "<br>";
            
            // Test connection to new database
            echo "<h4>Testing Connection to New Database</h4>";
            try {
                $tenantConn = $dbManager->getTenantConnection($testCompanyId);
                echo "✅ Successfully connected to tenant database<br>";
                
                // Test some queries
                $tables = $tenantConn->query("SHOW TABLES");
                if ($tables) {
                    echo "Tables in database:<br>";
                    while ($row = $tables->fetch_array()) {
                        echo "- " . $row[0] . "<br>";
                    }
                }
                
                $tenantConn->close();
                
            } catch (Exception $e) {
                echo "❌ Failed to connect to tenant database: " . $e->getMessage() . "<br>";
            }
            
            // Cleanup option
            echo "<br><a href='?cleanup_test=yes&company_id=$testCompanyId' onclick='return confirm(\"Delete test database?\")'>🗑️ Delete Test Database</a><br>";
            
        } else {
            echo "❌ Database creation failed<br>";
            echo "Error: " . $result['error'] . "<br>";
            echo "Message: " . $result['message'] . "<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Exception during database creation: " . $e->getMessage() . "<br>";
    }
}

// Cleanup test database
if (isset($_GET['cleanup_test']) && $_GET['cleanup_test'] === 'yes' && isset($_GET['company_id'])) {
    echo "<h3>5. Cleaning Up Test Database</h3>";
    
    $companyId = (int)$_GET['company_id'];
    
    try {
        if ($dbManager->deleteTenantDatabase($companyId)) {
            echo "✅ Test database deleted successfully<br>";
        } else {
            echo "❌ Failed to delete test database<br>";
        }
    } catch (Exception $e) {
        echo "❌ Exception during cleanup: " . $e->getMessage() . "<br>";
    }
}

// Show existing databases
echo "<h3>6. Existing Tenant Databases</h3>";
$result = $conn->query("SHOW DATABASES LIKE 'froshlav_%'");
if ($result && $result->num_rows > 0) {
    echo "Found tenant databases:<br>";
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
} else {
    echo "No tenant databases found<br>";
}

// Show companies in master database
echo "<h3>7. Companies in Master Database</h3>";
$companies = CrearConsulta($conn, "SELECT id_empresa, nombre, nombre_base_datos, estado FROM empresas ORDER BY id_empresa DESC LIMIT 10", [])->fetch_all(MYSQLI_ASSOC);
if ($companies) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Database</th><th>Status</th></tr>";
    foreach ($companies as $company) {
        echo "<tr>";
        echo "<td>{$company['id_empresa']}</td>";
        echo "<td>{$company['nombre']}</td>";
        echo "<td>{$company['nombre_base_datos']}</td>";
        echo "<td>{$company['estado']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No companies found<br>";
}

echo "<hr>";
if (!isset($_GET['create_test'])) {
    echo "<p><a href='?create_test=yes' onclick='return confirm(\"Create a test database?\")'>🧪 Create Test Database</a></p>";
}
echo "<p><a href='register.php'>Go to Registration</a></p>";
echo "<p><a href='debug-registration.php'>Debug Registration</a></p>";
?>