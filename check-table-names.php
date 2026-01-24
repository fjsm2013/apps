<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Database Table Structure Check</h2>";
echo "<h3>Database: {$dbName}</h3>";

// Get all tables in the database
$result = $conn->query("SHOW TABLES FROM `{$dbName}`");

echo "<h4>Available Tables:</h4>";
echo "<ul>";
while ($row = $result->fetch_array()) {
    $tableName = $row[0];
    echo "<li><strong>{$tableName}</strong>";
    
    // Check if it's related to categories or vehicles
    if (stripos($tableName, 'categoria') !== false || stripos($tableName, 'vehiculo') !== false) {
        echo " <span style='color: green;'>← VEHICLE/CATEGORY RELATED</span>";
        
        // Show columns for this table
        $columnsResult = $conn->query("SHOW COLUMNS FROM `{$dbName}`.`{$tableName}`");
        echo "<ul>";
        while ($column = $columnsResult->fetch_assoc()) {
            echo "<li>{$column['Field']} ({$column['Type']})</li>";
        }
        echo "</ul>";
    }
    echo "</li>";
}
echo "</ul>";

// Also check what's in the ordenes table to see the structure
echo "<h4>Ordenes Table Structure:</h4>";
$columnsResult = $conn->query("SHOW COLUMNS FROM `{$dbName}`.ordenes");
echo "<ul>";
while ($column = $columnsResult->fetch_assoc()) {
    echo "<li>{$column['Field']} ({$column['Type']})</li>";
}
echo "</ul>";

// Check vehiculos table structure
echo "<h4>Vehiculos Table Structure:</h4>";
$columnsResult = $conn->query("SHOW COLUMNS FROM `{$dbName}`.vehiculos");
echo "<ul>";
while ($column = $columnsResult->fetch_assoc()) {
    echo "<li>{$column['Field']} ({$column['Type']})</li>";
}
echo "</ul>";

// Let's also check a sample order to see the actual data structure
echo "<h4>Sample Order Data:</h4>";
$stmt = $conn->prepare("
    SELECT o.*, v.* 
    FROM `{$dbName}`.ordenes o
    LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
    LIMIT 1
");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo "<pre>";
    foreach ($order as $key => $value) {
        echo "{$key}: {$value}\n";
    }
    echo "</pre>";
} else {
    echo "<p>No orders found to analyze structure.</p>";
}
?>