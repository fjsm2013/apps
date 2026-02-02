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
$ordenId = 27;

echo "<h2>Testing Monto Update for Order #27</h2>";
echo "<h3>Database: {$dbName}</h3>";

// 1. Check current Monto
echo "<h4>1. Current Order Data:</h4>";
$stmt = $conn->prepare("SELECT ID, Monto, Observaciones FROM `{$dbName}`.ordenes WHERE ID = ?");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();
$orden = $result->fetch_assoc();

echo "<p><strong>Current Monto:</strong> ₡" . number_format($orden['Monto'], 2) . "</p>";
echo "<p><strong>Observaciones:</strong> " . htmlspecialchars($orden['Observaciones'] ?? 'NULL') . "</p>";

// 2. Calculate from orden_servicios
echo "<h4>2. Calculate from orden_servicios table:</h4>";
$stmt = $conn->prepare("
    SELECT SUM(Precio) as Subtotal
    FROM `{$dbName}`.orden_servicios
    WHERE OrdenID = ?
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();
$calc = $result->fetch_assoc();

$subtotal = floatval($calc['Subtotal'] ?? 0);
$iva = $subtotal * 0.13;
$total = $subtotal + $iva;

echo "<p><strong>Calculated Subtotal:</strong> ₡" . number_format($subtotal, 2) . "</p>";
echo "<p><strong>Calculated IVA (13%):</strong> ₡" . number_format($iva, 2) . "</p>";
echo "<p><strong>Calculated Total:</strong> ₡" . number_format($total, 2) . "</p>";

// 3. Test UPDATE
echo "<h4>3. Test Manual Update:</h4>";
if (isset($_POST['update'])) {
    try {
        $newTotal = floatval($_POST['new_total']);
        $testObs = "Test update at " . date('Y-m-d H:i:s');
        
        $stmt = $conn->prepare("
            UPDATE `{$dbName}`.ordenes 
            SET Monto = ?, Observaciones = ?
            WHERE ID = ?
        ");
        $stmt->bind_param("dsi", $newTotal, $testObs, $ordenId);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            echo "<p style='color: green;'>✓ Update successful! Affected rows: " . $stmt->affected_rows . "</p>";
            echo "<p><a href=''>Refresh to see changes</a></p>";
        } else {
            echo "<p style='color: orange;'>⚠ Update executed but affected 0 rows</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<form method='POST'>";
    echo "<p>Update Monto to calculated total:</p>";
    echo "<input type='hidden' name='new_total' value='{$total}'>";
    echo "<button type='submit' name='update' class='btn btn-primary'>Update Monto to ₡" . number_format($total, 2) . "</button>";
    echo "</form>";
}

// 4. Show all services
echo "<h4>4. Services in orden_servicios:</h4>";
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
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $nombre = $row['ServicioPersonalizado'] ?: $row['ServicioNombre'];
        echo "<tr>";
        echo "<td>{$row['ID']}</td>";
        echo "<td>" . htmlspecialchars($nombre) . "</td>";
        echo "<td>₡" . number_format($row['Precio'], 2) . "</td>";
        echo "<td>{$row['Cantidad']}</td>";
        echo "<td>₡" . number_format($row['Subtotal'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No services found</p>";
}

echo "<hr>";
echo "<h4>Links:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/editar-orden-final.php?id=27'>Edit Order #27</a></li>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php'>Back to Orders</a></li>";
echo "<li><a href='test-update-monto.php'>Refresh This Test</a></li>";
echo "</ul>";
?>