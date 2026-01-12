<?php
/**
 * Debug script for registration issues
 */
session_start();
require_once 'lib/config.php';

echo "<h2>Debug Registration System</h2>";

// Test database connection
echo "<h3>1. Database Connection Test</h3>";
if ($conn && $conn->ping()) {
    echo "✅ Database connection OK<br>";
} else {
    echo "❌ Database connection failed<br>";
    exit;
}

// Test EjecutarSQL function
echo "<h3>2. EjecutarSQL Function Test</h3>";
try {
    $testResult = EjecutarSQL($conn, "SELECT 1 as test");
    echo "✅ EjecutarSQL function works<br>";
} catch (Exception $e) {
    echo "❌ EjecutarSQL error: " . $e->getMessage() . "<br>";
}

// Test usuarios table structure
echo "<h3>3. Usuarios Table Structure</h3>";
$result = $conn->query("DESCRIBE usuarios");
if ($result) {
    echo "✅ Usuarios table exists<br>";
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "❌ Cannot access usuarios table<br>";
}

// Test empresas table
echo "<h3>4. Empresas Table Test</h3>";
$empresas = ObtenerRegistros($conn, "SELECT id_empresa, nombre, email, estado FROM empresas LIMIT 5");
if ($empresas) {
    echo "✅ Found " . count($empresas) . " companies<br>";
    foreach ($empresas as $empresa) {
        echo "- ID: {$empresa['id_empresa']}, Name: {$empresa['nombre']}, Email: {$empresa['email']}, Status: {$empresa['estado']}<br>";
    }
} else {
    echo "❌ No companies found<br>";
}

// Test planes table
echo "<h3>5. Plans Table Test</h3>";
$planes = ObtenerRegistros($conn, "SELECT * FROM planes WHERE estado = 'activo'");
if ($planes) {
    echo "✅ Found " . count($planes) . " active plans<br>";
    foreach ($planes as $plan) {
        echo "- ID: {$plan['id_plan']}, Name: {$plan['nombre']}, Price: \${$plan['precio_mensual']}<br>";
    }
} else {
    echo "❌ No active plans found<br>";
}

// Simulate user creation
echo "<h3>6. Simulate User Creation</h3>";
if (isset($_GET['test_user']) && $_GET['test_user'] === 'yes') {
    try {
        $testCompanyId = 3; // Use existing company
        $testName = "Test User " . time();
        $testEmail = "test" . time() . "@example.com";
        $testUsername = "testuser" . time();
        $testPassword = password_hash("TestPassword123!", PASSWORD_DEFAULT);
        
        echo "Attempting to create user:<br>";
        echo "- Company ID: $testCompanyId<br>";
        echo "- Name: $testName<br>";
        echo "- Email: $testEmail<br>";
        echo "- Username: $testUsername<br>";
        
        $userId = EjecutarSQL(
            $conn,
            "INSERT INTO usuarios (id_empresa, name, email, user_name, password, permiso, estado) 
             VALUES (?, ?, ?, ?, ?, 1, 'activo')",
            [$testCompanyId, $testName, $testEmail, $testUsername, $testPassword]
        );
        
        if ($userId) {
            echo "✅ User created successfully with ID: $userId<br>";
            
            // Clean up test user
            EjecutarSQL($conn, "DELETE FROM usuarios WHERE id = ?", [$userId]);
            echo "✅ Test user cleaned up<br>";
        } else {
            echo "❌ Failed to create user<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Error creating user: " . $e->getMessage() . "<br>";
    }
} else {
    echo "<a href='?test_user=yes'>Click here to test user creation</a><br>";
}

// Check session data
echo "<h3>7. Session Data</h3>";
if (isset($_SESSION['registration_company_id'])) {
    echo "✅ Registration session active<br>";
    echo "- Company ID: " . $_SESSION['registration_company_id'] . "<br>";
    echo "- Company Name: " . ($_SESSION['registration_company_name'] ?? 'Not set') . "<br>";
    echo "- DB Name: " . ($_SESSION['registration_db_name'] ?? 'Not set') . "<br>";
} else {
    echo "ℹ️ No registration session active<br>";
}

echo "<h3>8. Recent Registration Attempts</h3>";
$recentUsers = ObtenerRegistros($conn, "SELECT id, name, email, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC LIMIT 5");
if ($recentUsers) {
    echo "Recent users:<br>";
    foreach ($recentUsers as $user) {
        echo "- ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Created: {$user['fecha_creacion']}<br>";
    }
} else {
    echo "No recent users found<br>";
}

echo "<hr>";
echo "<p><a href='register.php'>Go to Registration Page</a></p>";
echo "<p><a href='test-registration.php'>Run Full Registration Test</a></p>";
?>