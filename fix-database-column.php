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

echo "<h2>Database Column Fix</h2>";
echo "<h3>Database: {$dbName}</h3>";

try {
    // Check if ServicioPersonalizado column exists
    $result = $conn->query("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = '{$dbName}' 
          AND TABLE_NAME = 'orden_servicios' 
          AND COLUMN_NAME = 'ServicioPersonalizado'
    ");

    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ ServicioPersonalizado column already exists</p>";
    } else {
        echo "<p style='color: orange;'>Adding ServicioPersonalizado column...</p>";
        
        $sql = "ALTER TABLE `{$dbName}`.orden_servicios ADD ServicioPersonalizado VARCHAR(255) NULL AFTER Precio";
        if ($conn->query($sql)) {
            echo "<p style='color: green;'>✓ Successfully added ServicioPersonalizado column</p>";
        } else {
            echo "<p style='color: red;'>✗ Error: " . $conn->error . "</p>";
        }
    }

    // Show table structure
    echo "<h4>orden_servicios Table Structure:</h4>";
    $result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $highlight = ($row['Field'] == 'ServicioPersonalizado') ? 'background: yellow;' : '';
        echo "<tr style='{$highlight}'>";
        echo "<td><strong>{$row['Field']}</strong></td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<p style='color: green; font-weight: bold;'>✓ Database is ready for order editing!</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h4>Test the New Simple Editor:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/editar-orden-simple.php?id=23' target='_blank'>Test Simple Editor (Order #23)</a></li>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Back to Orders List</a></li>";
echo "</ul>";
?>