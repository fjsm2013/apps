<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

if (!isLoggedIn()) {
    die("Not logged in");
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Fix ServicioID Column to Allow NULL</h2>";
echo "<h3>Database: {$dbName}</h3>";

// Check current structure
echo "<h4>1. Current orden_servicios Structure:</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while ($row = $result->fetch_assoc()) {
    $highlight = ($row['Field'] == 'ServicioID') ? 'background: yellow;' : '';
    echo "<tr style='{$highlight}'>";
    echo "<td><strong>{$row['Field']}</strong></td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "</tr>";
}
echo "</table>";

// Fix the column
echo "<h4>2. Fixing ServicioID to Allow NULL:</h4>";
try {
    $sql = "ALTER TABLE `{$dbName}`.orden_servicios MODIFY ServicioID INT NULL";
    $conn->query($sql);
    echo "<p style='color: green;'>✓ Successfully modified ServicioID to allow NULL values</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

// Verify the change
echo "<h4>3. Verification - Updated Structure:</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios WHERE Field = 'ServicioID'");
$row = $result->fetch_assoc();

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
echo "<tr>";
echo "<td><strong>{$row['Field']}</strong></td>";
echo "<td>{$row['Type']}</td>";
echo "<td style='background: " . ($row['Null'] == 'YES' ? 'lightgreen' : 'lightcoral') . ";'><strong>{$row['Null']}</strong></td>";
echo "<td>{$row['Key']}</td>";
echo "<td>{$row['Default']}</td>";
echo "</tr>";
echo "</table>";

if ($row['Null'] == 'YES') {
    echo "<p style='color: green; font-size: 18px;'>✓ ServicioID now allows NULL values!</p>";
    echo "<p>You can now add custom services (servicios personalizados) without errors.</p>";
} else {
    echo "<p style='color: red;'>✗ ServicioID still does not allow NULL. Manual intervention may be needed.</p>";
}

echo "<hr>";
echo "<h4>Next Steps:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/editar-orden-final.php?id=27'>Test Adding Custom Service to Order #27</a></li>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php'>Back to Orders</a></li>";
echo "</ul>";
?>