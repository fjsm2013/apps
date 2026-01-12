<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Test de Datos para Reporte de Vehículos</h2>";
echo "<p><strong>Base de datos:</strong> {$dbName}</p>";

// Verificar tablas disponibles
echo "<h3>Tablas disponibles:</h3>";
$tablas = CrearConsulta($conn, "SHOW TABLES FROM {$dbName}", array())->fetch_all(MYSQLI_ASSOC);
foreach ($tablas as $tabla) {
    $nombreTabla = $tabla["Tables_in_{$dbName}"];
    echo "<li>{$nombreTabla}</li>";
}

// Verificar datos en ordenes
echo "<h3>Datos en tabla ordenes:</h3>";
$ordenes = CrearConsulta($conn, "SELECT COUNT(*) as total FROM {$dbName}.ordenes", array())->fetch_all(MYSQLI_ASSOC);
echo "<p>Total órdenes: " . ($ordenes[0]['total'] ?? 0) . "</p>";

if ($ordenes[0]['total'] > 0) {
    $ordenesCompletas = CrearConsulta($conn, "SELECT COUNT(*) as total FROM {$dbName}.ordenes WHERE Estado >= 3", array())->fetch_all(MYSQLI_ASSOC);
    echo "<p>Órdenes completadas: " . ($ordenesCompletas[0]['total'] ?? 0) . "</p>";
    
    $ordenesConVehiculo = CrearConsulta($conn, "SELECT COUNT(*) as total FROM {$dbName}.ordenes WHERE VehiculoID IS NOT NULL", array())->fetch_all(MYSQLI_ASSOC);
    echo "<p>Órdenes con vehículo: " . ($ordenesConVehiculo[0]['total'] ?? 0) . "</p>";
}

// Verificar datos en vehiculos
echo "<h3>Datos en tabla vehiculos:</h3>";
$vehiculos = CrearConsulta($conn, "SELECT COUNT(*) as total FROM {$dbName}.vehiculos", array())->fetch_all(MYSQLI_ASSOC);
echo "<p>Total vehículos: " . ($vehiculos[0]['total'] ?? 0) . "</p>";

// Verificar datos en categoriaservicio
echo "<h3>Datos en tabla categoriaservicio:</h3>";
$categorias = CrearConsulta($conn, "SELECT * FROM {$dbName}.categoriaservicio LIMIT 5", array())->fetch_all(MYSQLI_ASSOC);
echo "<p>Categorías disponibles:</p>";
foreach ($categorias as $cat) {
    echo "<li>ID: {$cat['ID']}, Descripción: {$cat['Descripcion']}</li>";
}

// Test de la consulta principal
echo "<h3>Test de consulta principal:</h3>";
$fechaInicio = date('Y-m-01');
$fechaFin = date('Y-m-d');

$testQuery = "SELECT 
    CASE 
        WHEN cs.Descripcion IS NOT NULL THEN cs.Descripcion
        WHEN o.Categoria = 1 THEN 'Categoría 1'
        WHEN o.Categoria = 2 THEN 'Categoría 2'
        WHEN o.Categoria = 3 THEN 'Categoría 3'
        ELSE 'Sin categoría'
    END as categoria,
    COUNT(DISTINCT o.ID) as total_ordenes,
    COUNT(DISTINCT COALESCE(v.ID, o.VehiculoID)) as vehiculos_unicos,
    SUM(o.Monto) as ingresos_totales,
    AVG(o.Monto) as ticket_promedio
 FROM {$dbName}.ordenes o
 LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
 LEFT JOIN {$dbName}.categoriaservicio cs ON cs.ID = o.Categoria
 WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
 AND o.Estado >= 3
 GROUP BY 
    CASE 
        WHEN cs.Descripcion IS NOT NULL THEN cs.Descripcion
        WHEN o.Categoria = 1 THEN 'Categoría 1'
        WHEN o.Categoria = 2 THEN 'Categoría 2'
        WHEN o.Categoria = 3 THEN 'Categoría 3'
        ELSE 'Sin categoría'
    END
 ORDER BY total_ordenes DESC";

try {
    $resultado = CrearConsulta($conn, $testQuery, array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);
    echo "<p>Resultado de consulta:</p>";
    echo "<pre>" . print_r($resultado, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error en consulta: " . $e->getMessage() . "</p>";
}

echo "<p><a href='vehiculos-atendidos.php'>← Volver al reporte</a></p>";
?>