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

echo "<h2>Checking Ordenes Table Structure</h2>";
echo "<h3>Database: {$dbName}</h3>";

// Show ordenes table structure
echo "<h4>ordenes Table Columns:</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.ordenes");
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
    $highlight = '';
    if (in_array($row['Field'], ['Subtotal', 'Impuesto', 'Monto', 'Total'])) {
        $highlight = 'background: yellow;';
    }
    echo "<tr style='{$highlight}'>";
    echo "<td><strong>{$row['Field']}</strong></td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "<td>{$row['Extra']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h4>Column Analysis:</h4>";
$expectedColumns = ['Subtotal', 'Impuesto', 'Monto', 'Total'];
foreach ($expectedColumns as $col) {
    if (in_array($col, $columns)) {
        echo "<span style='color: green;'>✓ {$col} exists</span><br>";
    } else {
        echo "<span style='color: red;'>✗ {$col} missing</span><br>";
    }
}

// Show sample order data
echo "<h4>Sample Order Data:</h4>";
$result = $conn->query("SELECT * FROM `{$dbName}`.ordenes LIMIT 1");
if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo "<table border='1' style='border-collapse: collapse;'>";
    foreach ($order as $key => $value) {
        echo "<tr><td><strong>{$key}</strong></td><td>" . htmlspecialchars($value ?? 'NULL') . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found.</p>";
}

// Check what columns are commonly used for totals
echo "<h4>Possible Total Columns:</h4>";
$possibleTotalColumns = [];
foreach ($columns as $col) {
    if (stripos($col, 'total') !== false || 
        stripos($col, 'monto') !== false || 
        stripos($col, 'precio') !== false ||
        stripos($col, 'subtotal') !== false ||
        stripos($col, 'impuesto') !== false) {
        $possibleTotalColumns[] = $col;
        echo "<span style='color: blue;'>→ {$col}</span><br>";
    }
}

if (empty($possibleTotalColumns)) {
    echo "<p>No obvious total columns found. The table might use different column names.</p>";
}

echo "<hr>";
echo "<h4>Next Steps:</h4>";
echo "<p>Based on the columns found, we need to update the code to use the correct column names.</p>";
?>