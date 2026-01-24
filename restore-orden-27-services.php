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

echo "<h2>Restore Services for Order #27</h2>";

if (isset($_POST['restore'])) {
    try {
        $conn->begin_transaction();
        
        // Delete current services
        $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        
        // Restore original services
        $services = [
            ['nombre' => 'Lavado Exteriores', 'precio' => 5000],
            ['nombre' => 'Lavado Chasis', 'precio' => 2000],
            ['nombre' => 'PERSONAL SERVICE', 'precio' => 25000]
        ];
        
        foreach ($services as $service) {
            $stmt = $conn->prepare("
                INSERT INTO `{$dbName}`.orden_servicios 
                (OrdenID, ServicioID, Precio, ServicioPersonalizado, Subtotal, Cantidad) 
                VALUES (?, NULL, ?, ?, ?, 1)
            ");
            $stmt->bind_param("idsd", $ordenId, $service['precio'], $service['nombre'], $service['precio']);
            $stmt->execute();
        }
        
        // Update order totals
        $subtotal = 32000;
        $total = 36160;
        $serviciosJson = json_encode([
            ['id' => 'custom_1', 'nombre' => 'Lavado Exteriores', 'precio' => 5000, 'personalizado' => true],
            ['id' => 'custom_2', 'nombre' => 'Lavado Chasis', 'precio' => 2000, 'personalizado' => true],
            ['id' => 'custom_3', 'nombre' => 'PERSONAL SERVICE', 'precio' => 25000, 'personalizado' => true]
        ]);
        
        $stmt = $conn->prepare("UPDATE `{$dbName}`.ordenes SET Monto = ?, ServiciosJson = ? WHERE ID = ?");
        $stmt->bind_param("dsi", $total, $serviciosJson, $ordenId);
        $stmt->execute();
        
        $conn->commit();
        
        echo "<p style='color: green; font-size: 18px;'>✓ Services restored successfully!</p>";
        echo "<p><a href='lavacar/reportes/editar-orden-final.php?id=27'>Open Edit Page</a></p>";
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>This will restore the original 3 services to order #27:</p>";
    echo "<ul>";
    echo "<li>Lavado Exteriores - ₡5,000</li>";
    echo "<li>Lavado Chasis - ₡2,000</li>";
    echo "<li>PERSONAL SERVICE - ₡25,000</li>";
    echo "</ul>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='restore' class='btn btn-primary'>Restore Services</button>";
    echo "</form>";
}
?>