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

echo "<h2>Testing Order Update System</h2>";
echo "<h3>Database: {$dbName}</h3>";

// Test data for order ID 23
$ordenId = 23;

echo "<h4>1. Current Order Data (ID: {$ordenId})</h4>";
$stmt = $conn->prepare("
    SELECT o.*, 
           c.NombreCompleto as ClienteNombre,
           v.Placa, v.Marca, v.Modelo
    FROM `{$dbName}`.ordenes o
    LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
    LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
    WHERE o.ID = ?
");

$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orden = $result->fetch_assoc();
    echo "<table border='1' style='border-collapse: collapse;'>";
    foreach ($orden as $key => $value) {
        echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value ?? 'NULL') . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>Order not found!</p>";
    exit;
}

echo "<h4>2. Current Order Services</h4>";
$stmt = $conn->prepare("
    SELECT os.*, s.Descripcion as ServicioNombre
    FROM `{$dbName}`.orden_servicios os
    LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
    WHERE os.OrdenID = ?
    ORDER BY os.ID
");

$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>ServicioID</th><th>Precio</th><th>ServicioPersonalizado</th><th>ServicioNombre</th></tr>";
    while ($servicio = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$servicio['ID']}</td>";
        echo "<td>{$servicio['ServicioID']}</td>";
        echo "<td>₡" . number_format($servicio['Precio'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($servicio['ServicioPersonalizado'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($servicio['ServicioNombre'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No services found for this order.</p>";
}

// Test adding a custom service
echo "<h4>3. Test Adding Custom Service</h4>";
if (isset($_POST['test_add'])) {
    try {
        $conn->begin_transaction();
        
        // Add a test custom service
        $stmt = $conn->prepare("
            INSERT INTO `{$dbName}`.orden_servicios 
            (OrdenID, ServicioID, Precio, ServicioPersonalizado) 
            VALUES (?, NULL, ?, ?)
        ");
        $testPrice = 15000;
        $testName = "Servicio de Prueba " . date('H:i:s');
        $stmt->bind_param("ids", $ordenId, $testPrice, $testName);
        $stmt->execute();
        
        $conn->commit();
        echo "<span style='color: green;'>✓ Successfully added test custom service: {$testName}</span><br>";
        echo "<a href=''>Refresh to see changes</a>";
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<span style='color: red;'>✗ Error: " . $e->getMessage() . "</span><br>";
    }
} else {
    echo "<form method='POST'>";
    echo "<button type='submit' name='test_add' style='padding: 10px; background: green; color: white; border: none;'>Add Test Custom Service</button>";
    echo "</form>";
}

echo "<h4>4. Check orden_servicios Table Structure</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while ($row = $result->fetch_assoc()) {
    $highlight = ($row['Field'] == 'ServicioPersonalizado') ? 'background: yellow;' : '';
    echo "<tr style='{$highlight}'>";
    echo "<td>{$row['Field']}</td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo "<h4>Test Links:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/editar-orden.php?id={$ordenId}' target='_blank'>Edit Order #{$ordenId}</a></li>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Orders List</a></li>";
echo "</ul>";
?>