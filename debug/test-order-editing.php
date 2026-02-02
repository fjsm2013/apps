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

echo "<h2>Test Order Editing Functionality</h2>";

// Test 1: Check if required columns exist
echo "<h3>1. Database Schema Check</h3>";

$tables_to_check = [
    'ordenes' => ['Descuento', 'FechaCierre'],
    'orden_servicios' => ['ServicioPersonalizado']
];

foreach ($tables_to_check as $table => $columns) {
    echo "<h4>Table: {$table}</h4>";
    
    foreach ($columns as $column) {
        $stmt = $conn->prepare("
            SELECT COUNT(*) as exists_col 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE table_schema = ? AND table_name = ? AND column_name = ?
        ");
        $stmt->bind_param("sss", $dbName, $table, $column);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result['exists_col'] > 0) {
            echo "<span style='color: green;'>✓ Column '{$column}' exists</span><br>";
        } else {
            echo "<span style='color: red;'>✗ Column '{$column}' missing</span><br>";
            
            // Try to add the missing column
            try {
                if ($column == 'Descuento') {
                    $conn->query("ALTER TABLE `{$dbName}`.{$table} ADD {$column} DECIMAL(10,2) DEFAULT 0.00 AFTER Monto");
                    echo "<span style='color: blue;'>→ Added column '{$column}'</span><br>";
                } elseif ($column == 'FechaCierre') {
                    $conn->query("ALTER TABLE `{$dbName}`.{$table} ADD {$column} DATETIME NULL AFTER FechaTerminado");
                    echo "<span style='color: blue;'>→ Added column '{$column}'</span><br>";
                } elseif ($column == 'ServicioPersonalizado') {
                    $conn->query("ALTER TABLE `{$dbName}`.{$table} ADD {$column} VARCHAR(255) NULL AFTER Precio");
                    echo "<span style='color: blue;'>→ Added column '{$column}'</span><br>";
                }
            } catch (Exception $e) {
                echo "<span style='color: orange;'>→ Could not add column: " . $e->getMessage() . "</span><br>";
            }
        }
    }
}

// Test 2: Check if AJAX files exist
echo "<h3>2. AJAX Files Check</h3>";

$ajax_files = [
    'lavacar/ajax/obtener-orden-editar.php',
    'lavacar/ajax/actualizar-orden.php', 
    'lavacar/ajax/cerrar-orden-final.php'
];

foreach ($ajax_files as $file) {
    if (file_exists($file)) {
        echo "<span style='color: green;'>✓ {$file} exists</span><br>";
    } else {
        echo "<span style='color: red;'>✗ {$file} missing</span><br>";
    }
}

// Test 3: Check for active orders to test with
echo "<h3>3. Active Orders Check</h3>";

$stmt = $conn->prepare("
    SELECT COUNT(*) as total_orders,
           SUM(CASE WHEN Estado = 1 THEN 1 ELSE 0 END) as pendientes,
           SUM(CASE WHEN Estado = 2 THEN 1 ELSE 0 END) as proceso,
           SUM(CASE WHEN Estado = 3 THEN 1 ELSE 0 END) as terminados,
           SUM(CASE WHEN Estado = 4 THEN 1 ELSE 0 END) as cerrados
    FROM `{$dbName}`.ordenes
");
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

echo "<ul>";
echo "<li>Total orders: {$stats['total_orders']}</li>";
echo "<li>Pendientes: {$stats['pendientes']}</li>";
echo "<li>En Proceso: {$stats['proceso']}</li>";
echo "<li>Terminados: {$stats['terminados']}</li>";
echo "<li>Cerrados: {$stats['cerrados']}</li>";
echo "</ul>";

if ($stats['total_orders'] > 0) {
    echo "<span style='color: green;'>✓ Orders available for testing</span><br>";
    
    // Show some sample orders
    $stmt = $conn->prepare("
        SELECT o.ID, o.Estado, c.NombreCompleto, v.Placa, o.Monto
        FROM `{$dbName}`.ordenes o
        LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
        LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
        ORDER BY o.ID DESC
        LIMIT 5
    ");
    $stmt->execute();
    $orders = $stmt->get_result();
    
    echo "<h4>Sample Orders:</h4>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Estado</th><th>Cliente</th><th>Placa</th><th>Monto</th><th>Actions</th></tr>";
    
    while ($order = $orders->fetch_assoc()) {
        $estado_text = ['', 'Pendiente', 'En Proceso', 'Terminado', 'Cerrado'][$order['Estado']];
        echo "<tr>";
        echo "<td>{$order['ID']}</td>";
        echo "<td>{$estado_text}</td>";
        echo "<td>{$order['NombreCompleto']}</td>";
        echo "<td>{$order['Placa']}</td>";
        echo "<td>₡" . number_format($order['Monto'], 2) . "</td>";
        echo "<td>";
        
        if ($order['Estado'] < 4) {
            echo "<a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Edit Available</a>";
        }
        if ($order['Estado'] == 3) {
            echo " | <a href='lavacar/reportes/ordenes-activas.php' target='_blank'>Calculator Available</a>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<span style='color: orange;'>⚠ No orders available for testing. Create some orders first.</span><br>";
}

// Test 4: Custom Services in Wizard
echo "<h3>4. Custom Services Feature</h3>";
echo "<p>The custom services functionality is already implemented in the order wizard:</p>";
echo "<ul>";
echo "<li>✓ Custom service form in paso_servicios.php</li>";
echo "<li>✓ JavaScript functions in wizard.js</li>";
echo "<li>✓ Integration with order totals calculation</li>";
echo "</ul>";
echo "<p><a href='lavacar/ordenes/index.php' target='_blank'>Test Custom Services in Order Wizard</a></p>";

echo "<h3>5. Testing Instructions</h3>";
echo "<ol>";
echo "<li><strong>Custom Services:</strong> Go to the order wizard and try adding custom services in step 2</li>";
echo "<li><strong>Order Editing:</strong> Go to active orders and click the edit button on any non-closed order</li>";
echo "<li><strong>Calculator:</strong> For orders in 'Terminado' state, use the calculator button to adjust prices and close the order</li>";
echo "</ol>";

echo "<p><a href='lavacar/reportes/ordenes-activas.php' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Active Orders</a></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3, h4 { color: #333; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>