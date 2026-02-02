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

echo "<h2>Debug: What's Being Sent When Saving Order #27</h2>";

// Show current services in database
echo "<h4>Current Services in Database:</h4>";
$stmt = $conn->prepare("
    SELECT os.ID, os.ServicioID, os.Precio, os.ServicioPersonalizado
    FROM `{$dbName}`.orden_servicios os
    WHERE os.OrdenID = ?
    ORDER BY os.ID
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>DB ID</th><th>ServicioID</th><th>Nombre</th><th>Precio</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['ID']}</td>";
    echo "<td>{$row['ServicioID']}</td>";
    echo "<td>" . htmlspecialchars($row['ServicioPersonalizado'] ?? 'NULL') . "</td>";
    echo "<td>₡" . number_format($row['Precio'], 2) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Show what would be in the form
echo "<h4>What Should Be in the Form:</h4>";
$stmt = $conn->prepare("
    SELECT os.ID as DbID, os.*, s.Descripcion as ServicioNombre
    FROM `{$dbName}`.orden_servicios os
    LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
    WHERE os.OrdenID = ?
    ORDER BY os.ID
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$serviciosResult = $stmt->get_result();

$servicios = [];
while ($servicio = $serviciosResult->fetch_assoc()) {
    $servicios[] = [
        'db_id' => $servicio['DbID'],
        'nombre' => ($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre'],
        'precio' => floatval($servicio['Precio']),
        'personalizado' => !empty($servicio['ServicioPersonalizado'] ?? '')
    ];
}

echo "<pre>";
print_r($servicios);
echo "</pre>";

echo "<h4>Simulated Form Data:</h4>";
echo "<p>When you save, the form should send:</p>";
echo "<pre>";
foreach ($servicios as $index => $servicio) {
    echo "servicios[{$index}][db_id] = {$servicio['db_id']}\n";
    echo "servicios[{$index}][nombre] = {$servicio['nombre']}\n";
    echo "servicios[{$index}][precio] = {$servicio['precio']}\n";
    echo "\n";
}
echo "</pre>";

echo "<hr>";
echo "<h4>Test the Edit Page:</h4>";
echo "<p><a href='lavacar/reportes/editar-orden-final.php?id=27' target='_blank'>Open Edit Page</a></p>";
echo "<p><strong>Instructions:</strong></p>";
echo "<ol>";
echo "<li>Open the edit page</li>";
echo "<li>Open browser console (F12)</li>";
echo "<li>Before clicking 'Guardar Cambios', check how many service rows are visible</li>";
echo "<li>Look at the HTML to see if all hidden db_id fields are present</li>";
echo "</ol>";
?>