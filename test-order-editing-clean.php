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

echo "<h2>Testing Order Editing System</h2>";
echo "<h3>Database: {$dbName}</h3>";

// Test 1: Check if required tables exist
echo "<h4>1. Checking Required Tables</h4>";
$requiredTables = ['ordenes', 'orden_servicios', 'clientes', 'vehiculos', 'categoriavehiculo'];

foreach ($requiredTables as $table) {
    $result = $conn->query("SHOW TABLES FROM `{$dbName}` LIKE '{$table}'");
    if ($result->num_rows > 0) {
        echo "<span style='color: green;'>✓ Table '{$table}' exists</span><br>";
    } else {
        echo "<span style='color: red;'>✗ Table '{$table}' missing</span><br>";
    }
}

// Test 2: Check if required columns exist in ordenes table
echo "<h4>2. Checking Ordenes Table Structure</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.ordenes");
$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

$requiredColumns = ['ID', 'ClienteID', 'VehiculoID', 'Estado', 'FechaIngreso', 'Observaciones', 'Descuento'];
foreach ($requiredColumns as $col) {
    if (in_array($col, $columns)) {
        echo "<span style='color: green;'>✓ Column '{$col}' exists</span><br>";
    } else {
        echo "<span style='color: red;'>✗ Column '{$col}' missing</span><br>";
    }
}

// Test 3: Check sample order data
echo "<h4>3. Sample Order Data</h4>";
$stmt = $conn->prepare("
    SELECT o.*, 
           c.NombreCompleto as ClienteNombre,
           v.Placa, v.Marca, v.Modelo,
           cv.TipoVehiculo
    FROM `{$dbName}`.ordenes o
    LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
    LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
    LEFT JOIN `{$dbName}`.categoriavehiculo cv ON v.CategoriaVehiculo = cv.ID
    WHERE o.Estado < 4
    LIMIT 3
");

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Cliente</th><th>Vehículo</th><th>Estado</th><th>Fecha</th><th>Actions</th></tr>";
    
    while ($orden = $result->fetch_assoc()) {
        $estados = [1 => 'Pendiente', 2 => 'En Proceso', 3 => 'Terminado', 4 => 'Cerrado'];
        $estadoText = $estados[$orden['Estado']] ?? 'Desconocido';
        
        echo "<tr>";
        echo "<td>{$orden['ID']}</td>";
        echo "<td>" . htmlspecialchars($orden['ClienteNombre']) . "</td>";
        echo "<td>" . htmlspecialchars($orden['Placa']) . " - " . htmlspecialchars($orden['Marca']) . " " . htmlspecialchars($orden['Modelo']) . "</td>";
        echo "<td>{$estadoText}</td>";
        echo "<td>" . date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) . "</td>";
        echo "<td>";
        
        // Edit button (for orders not closed)
        if ($orden['Estado'] < 4) {
            echo "<a href='lavacar/reportes/editar-orden.php?id={$orden['ID']}' target='_blank' style='margin-right: 10px; color: blue;'>✏️ Editar</a>";
        }
        
        // Calculator button (for finished orders)
        if ($orden['Estado'] == 3) {
            echo "<a href='lavacar/reportes/calculadora-cierre.php?id={$orden['ID']}' target='_blank' style='color: green;'>🧮 Calculadora</a>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found for testing.</p>";
}

// Test 4: Check orden_servicios structure
echo "<h4>4. Checking Orden Servicios Table</h4>";
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.orden_servicios");
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>{$row['Field']} ({$row['Type']})</li>";
}
echo "</ul>";

// Test 5: Sample services for an order
echo "<h4>5. Sample Order Services</h4>";
$stmt = $conn->prepare("
    SELECT os.*, s.Descripcion as ServicioNombre
    FROM `{$dbName}`.orden_servicios os
    LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
    LIMIT 5
");

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>OrdenID</th><th>ServicioID</th><th>Nombre</th><th>Precio</th><th>Personalizado</th></tr>";
    
    while ($servicio = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$servicio['OrdenID']}</td>";
        echo "<td>{$servicio['ServicioID']}</td>";
        echo "<td>" . htmlspecialchars($servicio['ServicioPersonalizado'] ?: $servicio['ServicioNombre']) . "</td>";
        echo "<td>₡" . number_format($servicio['Precio'], 2) . "</td>";
        echo "<td>" . (!empty($servicio['ServicioPersonalizado']) ? 'Sí' : 'No') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No order services found.</p>";
}

echo "<hr>";
echo "<h4>Test Links:</h4>";
echo "<ul>";
echo "<li><a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Órdenes Activas (Main Page)</a></li>";
echo "<li><a href='lavacar/ordenes/index.php' target='_blank'>Create New Order</a></li>";
echo "</ul>";
?>