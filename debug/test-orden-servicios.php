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

$ordenId = $_GET['id'] ?? 27;

echo "<h2>Testing Order Services Data</h2>";
echo "<h3>Order ID: {$ordenId}</h3>";
echo "<h3>Database: {$dbName}</h3>";

// 1. Check orden_servicios table for this order
echo "<h4>1. Services from orden_servicios table (OrdenID = {$ordenId}):</h4>";
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
    echo "<tr><th>ID</th><th>OrdenID</th><th>ServicioID</th><th>Precio</th><th>ServicioPersonalizado</th><th>ServicioNombre</th><th>Cantidad</th><th>Subtotal</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['ID']}</td>";
        echo "<td><strong>{$row['OrdenID']}</strong></td>";
        echo "<td>{$row['ServicioID']}</td>";
        echo "<td>₡" . number_format($row['Precio'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($row['ServicioPersonalizado'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['ServicioNombre'] ?? 'NULL') . "</td>";
        echo "<td>{$row['Cantidad']}</td>";
        echo "<td>₡" . number_format($row['Subtotal'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>No services found in orden_servicios for this order.</p>";
}

// 2. Check ServiciosJson from ordenes table
echo "<h4>2. Services from ordenes.ServiciosJson:</h4>";
$stmt = $conn->prepare("SELECT ServiciosJson FROM `{$dbName}`.ordenes WHERE ID = ?");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orden = $result->fetch_assoc();
    $serviciosJson = $orden['ServiciosJson'];
    
    if ($serviciosJson) {
        echo "<pre>" . htmlspecialchars($serviciosJson) . "</pre>";
        
        $servicios = json_decode($serviciosJson, true);
        if ($servicios && is_array($servicios)) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Personalizado</th></tr>";
            foreach ($servicios as $servicio) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($servicio['id'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($servicio['nombre'] ?? 'N/A') . "</td>";
                echo "<td>₡" . number_format($servicio['precio'] ?? 0, 2) . "</td>";
                echo "<td>" . (($servicio['personalizado'] ?? false) ? 'Sí' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: orange;'>ServiciosJson is NULL or empty.</p>";
    }
} else {
    echo "<p style='color: red;'>Order not found!</p>";
}

// 3. Check ALL services in orden_servicios (to see if other orders are affected)
echo "<h4>3. ALL Services in orden_servicios table (all orders):</h4>";
$result = $conn->query("
    SELECT os.ID, os.OrdenID, os.ServicioID, os.Precio, os.ServicioPersonalizado, os.Cantidad
    FROM `{$dbName}`.orden_servicios os
    ORDER BY os.OrdenID, os.ID
    LIMIT 50
");

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>OrdenID</th><th>ServicioID</th><th>Precio</th><th>ServicioPersonalizado</th><th>Cantidad</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $highlight = ($row['OrdenID'] == $ordenId) ? 'background: yellow;' : '';
        echo "<tr style='{$highlight}'>";
        echo "<td>{$row['ID']}</td>";
        echo "<td><strong>{$row['OrdenID']}</strong></td>";
        echo "<td>{$row['ServicioID']}</td>";
        echo "<td>₡" . number_format($row['Precio'], 2) . "</td>";
        echo "<td>" . htmlspecialchars($row['ServicioPersonalizado'] ?? 'NULL') . "</td>";
        echo "<td>{$row['Cantidad']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No services found in orden_servicios table.</p>";
}

// 4. Recommendation
echo "<hr>";
echo "<h4>Recommendation:</h4>";
echo "<div style='background: #f0f0f0; padding: 15px; border-left: 4px solid #007bff;'>";
echo "<p><strong>Best Practice:</strong> Use <code>orden_servicios</code> table as the single source of truth.</p>";
echo "<ul>";
echo "<li>✅ <strong>orden_servicios table</strong> - Normalized, relational, proper database design</li>";
echo "<li>⚠️ <strong>ServiciosJson</strong> - Keep for backward compatibility but sync from orden_servicios</li>";
echo "</ul>";
echo "<p><strong>When editing:</strong></p>";
echo "<ol>";
echo "<li>Read services from <code>orden_servicios</code> table (filtered by OrdenID)</li>";
echo "<li>Update/Insert/Delete in <code>orden_servicios</code> table (with OrdenID check)</li>";
echo "<li>Rebuild <code>ServiciosJson</code> from <code>orden_servicios</code> data</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<h4>Test Links:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/editar-orden-smart.php?id={$ordenId}'>Edit Order #{$ordenId} (Smart)</a></li>";
echo "<li><a href='test-orden-servicios.php?id={$ordenId}'>Refresh This Test</a></li>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php'>Back to Orders</a></li>";
echo "</ul>";
?>