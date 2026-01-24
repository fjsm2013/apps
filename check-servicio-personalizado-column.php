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

echo "<h2>Checking ServicioPersonalizado Column</h2>";
echo "<h3>Database: {$dbName}</h3>";

// Check if ServicioPersonalizado column exists in orden_servicios table
$result = $conn->query("
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = '{$dbName}' 
      AND TABLE_NAME = 'orden_servicios' 
      AND COLUMN_NAME = 'ServicioPersonalizado'
");

if ($result->num_rows > 0) {
    echo "<span style='color: green;'>✓ Column 'ServicioPersonalizado' exists in orden_servicios table</span><br>";
} else {
    echo "<span style='color: red;'>✗ Column 'ServicioPersonalizado' missing in orden_servicios table</span><br>";
    echo "<p>Adding the missing column...</p>";
    
    try {
        $conn->query("ALTER TABLE `{$dbName}`.orden_servicios ADD ServicioPersonalizado VARCHAR(255) NULL AFTER Precio");
        echo "<span style='color: blue;'>→ Successfully added 'ServicioPersonalizado' column</span><br>";
    } catch (Exception $e) {
        echo "<span style='color: red;'>✗ Error adding column: " . $e->getMessage() . "</span><br>";
    }
}

// Show current table structure
echo "<h4>Current orden_servicios Table Structure:</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['Field']}</td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "<td>{$row['Extra']}</td>";
    echo "</tr>";
}
echo "</table>";

// Test sample data
echo "<h4>Sample Order Services Data:</h4>";
$result = $conn->query("SELECT * FROM `{$dbName}`.orden_servicios LIMIT 3");
if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    $first = true;
    while ($row = $result->fetch_assoc()) {
        if ($first) {
            echo "<tr>";
            foreach (array_keys($row) as $key) {
                echo "<th>{$key}</th>";
            }
            echo "</tr>";
            $first = false;
        }
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No order services data found.</p>";
}

echo "<hr>";
echo "<h4>Test Links:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Test Order Editing</a></li>";
echo "<li><a href='test-order-editing-clean.php' target='_blank'>Run Order System Test</a></li>";
echo "</ul>";
?>