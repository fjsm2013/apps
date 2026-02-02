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

echo "<h2>Adding ServicioPersonalizado Column</h2>";
echo "<h3>Database: {$dbName}</h3>";

try {
    // Check if column exists
    $result = $conn->query("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = '{$dbName}' 
          AND TABLE_NAME = 'orden_servicios' 
          AND COLUMN_NAME = 'ServicioPersonalizado'
    ");

    if ($result->num_rows > 0) {
        echo "<span style='color: green;'>✓ Column 'ServicioPersonalizado' already exists</span><br>";
    } else {
        echo "<span style='color: orange;'>⚠ Column 'ServicioPersonalizado' missing. Adding it now...</span><br>";
        
        // Add the column
        $sql = "ALTER TABLE `{$dbName}`.orden_servicios ADD ServicioPersonalizado VARCHAR(255) NULL AFTER Precio";
        $conn->query($sql);
        
        echo "<span style='color: green;'>✓ Successfully added 'ServicioPersonalizado' column</span><br>";
    }

    // Show current table structure
    echo "<h4>Current orden_servicios Table Structure:</h4>";
    $result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios");
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $highlight = ($row['Field'] == 'ServicioPersonalizado') ? 'background: yellow;' : '';
        echo "<tr style='{$highlight}'>";
        echo "<td><strong>{$row['Field']}</strong></td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h4>Test: Insert Custom Service</h4>";
    
    // Test inserting a custom service
    $testOrderId = 23; // Using order 23 as mentioned
    $testPrice = 17000;
    $testName = "Servicio Personalizado de Prueba";
    
    $stmt = $conn->prepare("
        INSERT INTO `{$dbName}`.orden_servicios 
        (OrdenID, ServicioID, Precio, ServicioPersonalizado) 
        VALUES (?, NULL, ?, ?)
    ");
    $stmt->bind_param("ids", $testOrderId, $testPrice, $testName);
    
    if ($stmt->execute()) {
        echo "<span style='color: green;'>✓ Successfully inserted test custom service</span><br>";
        echo "<p><strong>Test Service:</strong> {$testName} - ₡" . number_format($testPrice, 2) . "</p>";
    } else {
        echo "<span style='color: red;'>✗ Error inserting test service: " . $conn->error . "</span><br>";
    }

    // Show services for order 23
    echo "<h4>Services for Order #23:</h4>";
    $stmt = $conn->prepare("
        SELECT os.*, s.Descripcion as ServicioNombre
        FROM `{$dbName}`.orden_servicios os
        LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
        WHERE os.OrdenID = ?
        ORDER BY os.ID DESC
    ");
    $stmt->bind_param("i", $testOrderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>ServicioID</th><th>Precio</th><th>ServicioPersonalizado</th><th>ServicioNombre</th></tr>";
        while ($servicio = $result->fetch_assoc()) {
            $highlight = !empty($servicio['ServicioPersonalizado']) ? 'background: lightgreen;' : '';
            echo "<tr style='{$highlight}'>";
            echo "<td>{$servicio['ID']}</td>";
            echo "<td>{$servicio['ServicioID']}</td>";
            echo "<td>₡" . number_format($servicio['Precio'], 2) . "</td>";
            echo "<td><strong>" . htmlspecialchars($servicio['ServicioPersonalizado'] ?? 'NULL') . "</strong></td>";
            echo "<td>" . htmlspecialchars($servicio['ServicioNombre'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No services found for order #23.</p>";
    }

} catch (Exception $e) {
    echo "<span style='color: red;'>✗ Error: " . $e->getMessage() . "</span><br>";
}

echo "<hr>";
echo "<h4>Next Steps:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/editar-orden.php?id=23' target='_blank'>Test Edit Order #23</a></li>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Back to Orders List</a></li>";
echo "</ul>";
?>