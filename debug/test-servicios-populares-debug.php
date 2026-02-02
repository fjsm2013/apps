<?php
session_start();
require_once '../lib/config.php';
require_once '../lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Debug: Servicios Populares</h2>";
echo "<p>Database: {$dbName}</p>";

// Parámetros de fecha
$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

echo "<p>Fecha Inicio: {$fechaInicio}</p>";
echo "<p>Fecha Fin: {$fechaFin}</p>";

// 1. Verificar qué servicios existen en la tabla servicios
echo "<h3>1. Servicios en tabla 'servicios':</h3>";
try {
    $servicios = CrearConsulta($conn, "SELECT ID, Descripcion FROM {$dbName}.servicios ORDER BY ID", [])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($servicios, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// 2. Verificar qué hay en orden_servicios
echo "<h3>2. Datos en tabla 'orden_servicios':</h3>";
try {
    $ordenServicios = CrearConsulta($conn, "SELECT * FROM {$dbName}.orden_servicios LIMIT 10", [])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($ordenServicios, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// 3. Verificar órdenes en el período
echo "<h3>3. Órdenes en el período:</h3>";
try {
    $ordenes = CrearConsulta($conn, 
        "SELECT ID, Estado, FechaIngreso, Monto FROM {$dbName}.ordenes 
         WHERE DATE(FechaIngreso) BETWEEN ? AND ? 
         ORDER BY ID DESC LIMIT 10", 
        [$fechaInicio, $fechaFin])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($ordenes, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// 4. Probar la consulta principal paso a paso
echo "<h3>4. Consulta principal (paso a paso):</h3>";

// Primero sin filtros de fecha
echo "<h4>4a. Todos los servicios en orden_servicios:</h4>";
try {
    $query1 = "SELECT 
        COALESCE(s.ID, 0) as ID,
        COALESCE(s.Descripcion, os.ServicioPersonalizado, 'Servicio Personalizado') as Descripcion,
        COUNT(DISTINCT os.OrdenID) as cantidad_ordenes,
        SUM(os.Precio) as ingresos_totales,
        AVG(os.Precio) as ticket_promedio
     FROM {$dbName}.orden_servicios os
     JOIN {$dbName}.ordenes o ON o.ID = os.OrdenID
     LEFT JOIN {$dbName}.servicios s ON s.ID = os.ServicioID
     WHERE o.Estado >= 3
     GROUP BY COALESCE(s.ID, 0), COALESCE(s.Descripcion, os.ServicioPersonalizado)
     ORDER BY cantidad_ordenes DESC";
    
    $result1 = CrearConsulta($conn, $query1, [])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($result1, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error en consulta 1: " . $e->getMessage() . "</p>";
}

// Con filtros de fecha
echo "<h4>4b. Con filtros de fecha:</h4>";
try {
    $query2 = "SELECT 
        COALESCE(s.ID, 0) as ID,
        COALESCE(s.Descripcion, os.ServicioPersonalizado, 'Servicio Personalizado') as Descripcion,
        COUNT(DISTINCT os.OrdenID) as cantidad_ordenes,
        SUM(os.Precio) as ingresos_totales,
        AVG(os.Precio) as ticket_promedio
     FROM {$dbName}.orden_servicios os
     JOIN {$dbName}.ordenes o ON o.ID = os.OrdenID
     LEFT JOIN {$dbName}.servicios s ON s.ID = os.ServicioID
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ? 
     AND o.Estado >= 3
     GROUP BY COALESCE(s.ID, 0), COALESCE(s.Descripcion, os.ServicioPersonalizado)
     ORDER BY cantidad_ordenes DESC";
    
    $result2 = CrearConsulta($conn, $query2, [$fechaInicio, $fechaFin])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($result2, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error en consulta 2: " . $e->getMessage() . "</p>";
}

// 5. Verificar si hay servicios personalizados
echo "<h3>5. Servicios personalizados:</h3>";
try {
    $personalizados = CrearConsulta($conn, 
        "SELECT ServicioPersonalizado, COUNT(*) as cantidad 
         FROM {$dbName}.orden_servicios 
         WHERE ServicioPersonalizado IS NOT NULL AND ServicioPersonalizado != ''
         GROUP BY ServicioPersonalizado", 
        [])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($personalizados, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// 6. Verificar estructura de tabla orden_servicios
echo "<h3>6. Estructura de tabla orden_servicios:</h3>";
try {
    $estructura = CrearConsulta($conn, "DESCRIBE {$dbName}.orden_servicios", [])->fetch_all(MYSQLI_ASSOC);
    echo "<pre>" . print_r($estructura, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>