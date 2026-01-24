<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

if (!isLoggedIn()) {
    die("Not logged in");
}

$user = userInfo();
$dbName = $user['company']['db'];
$ordenId = 27;

echo "<h2>Fix Monto for Order #27</h2>";

// Calculate correct total from orden_servicios
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

echo "<h4>Calculated Totals:</h4>";
echo "<p><strong>Subtotal:</strong> ₡" . number_format($subtotal, 2) . "</p>";
echo "<p><strong>IVA (13%):</strong> ₡" . number_format($iva, 2) . "</p>";
echo "<p><strong>Total:</strong> ₡" . number_format($total, 2) . "</p>";

// Update the Monto
$stmt = $conn->prepare("
    UPDATE `{$dbName}`.ordenes 
    SET Monto = ?
    WHERE ID = ?
");
$stmt->bind_param("di", $total, $ordenId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<p style='color: green; font-size: 18px;'>✓ Monto updated successfully!</p>";
    echo "<p><strong>New Monto:</strong> ₡" . number_format($total, 2) . "</p>";
} else {
    echo "<p style='color: orange;'>⚠ No changes made (Monto was already correct)</p>";
}

// Verify
$stmt = $conn->prepare("SELECT Monto FROM `{$dbName}`.ordenes WHERE ID = ?");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();
$orden = $result->fetch_assoc();

echo "<h4>Verification:</h4>";
echo "<p><strong>Current Monto in database:</strong> ₡" . number_format($orden['Monto'], 2) . "</p>";

echo "<hr>";
echo "<h4>Next Steps:</h4>";
echo "<ol>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php?refresh=" . time() . "' target='_blank'>Open Órdenes Activas (should show ₡" . number_format($total, 2) . ")</a></li>";
echo "<li>Press Ctrl+F5 to hard refresh the page</li>";
echo "<li>Check if the Monto column shows the correct amount</li>";
echo "</ol>";
?>