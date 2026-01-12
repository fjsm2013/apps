<?php
/**
 * Debug Login System
 */
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

echo "<h2>Debug Login System</h2>";

// Test database connection
echo "<h3>1. Database Connection Test</h3>";
if ($conn && $conn->ping()) {
    echo "✅ Database connection OK (Database: " . $conn->query("SELECT DATABASE()")->fetch_row()[0] . ")<br>";
} else {
    echo "❌ Database connection failed<br>";
    exit;
}

// Check if user is logged in
echo "<h3>2. Login Status</h3>";
if (isLoggedIn()) {
    echo "✅ User is logged in<br>";
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
} else {
    echo "❌ User is not logged in<br>";
}

// Get user info
echo "<h3>3. User Information</h3>";
$user = currentUser();
if ($user) {
    echo "✅ User data loaded<br>";
    echo "<pre>" . print_r($user, true) . "</pre>";
} else {
    echo "❌ Could not load user data<br>";
}

// Get company info
echo "<h3>4. Company Information</h3>";
$company = currentCompany();
if ($company) {
    echo "✅ Company data loaded<br>";
    echo "<pre>" . print_r($company, true) . "</pre>";
} else {
    echo "❌ Could not load company data<br>";
}

// Test user info function
echo "<h3>5. Combined User Info</h3>";
$userInfo = userInfo();
if ($userInfo) {
    echo "✅ Combined user info loaded<br>";
    echo "<pre>" . print_r($userInfo, true) . "</pre>";
} else {
    echo "❌ Could not load combined user info<br>";
}

// Check recent users
echo "<h3>6. Recent Users in Database</h3>";
$recentUsers = ObtenerRegistros($conn, "SELECT id, name, email, id_empresa, estado FROM usuarios ORDER BY fecha_creacion DESC LIMIT 5");
if ($recentUsers) {
    echo "Recent users:<br>";
    foreach ($recentUsers as $u) {
        echo "- ID: {$u['id']}, Name: {$u['name']}, Email: {$u['email']}, Company: {$u['id_empresa']}, Status: {$u['estado']}<br>";
    }
} else {
    echo "No users found<br>";
}

// Check companies
echo "<h3>7. Recent Companies</h3>";
$companies = ObtenerRegistros($conn, "SELECT id_empresa, nombre, email, nombre_base_datos, estado FROM empresas ORDER BY fecha_registro DESC LIMIT 5");
if ($companies) {
    echo "Recent companies:<br>";
    foreach ($companies as $c) {
        echo "- ID: {$c['id_empresa']}, Name: {$c['nombre']}, Email: {$c['email']}, DB: {$c['nombre_base_datos']}, Status: {$c['estado']}<br>";
    }
} else {
    echo "No companies found<br>";
}

// Session data
echo "<h3>8. Session Data</h3>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

echo "<hr>";
echo "<p><a href='login.php'>Go to Login</a></p>";
echo "<p><a href='lavacar/dashboard.php'>Go to Dashboard</a></p>";
?>