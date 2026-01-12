<?php
session_start();

require_once '../../../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/VehiculosManager.php';

// Verificar autenticación
autoLoginFromCookie();
if (!isLoggedIn()) {
    jsonResponse(false, null, 'No autorizado', 401);
}

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(false, null, 'Método no permitido', 405);
}

// Obtener parámetros
$vehicleId = (int)($_GET['vehicle_id'] ?? 0);

if (!$vehicleId) {
    jsonResponse(false, null, 'ID de vehículo requerido', 400);
}

try {
    $user = userInfo();
    $dbName = $user['company']['db'];
    
    $manager = new VehiculosManager($conn, $dbName);
    $historial = $manager->getVehicleHistory($vehicleId);
    
    jsonResponse(true, [
        'historial' => $historial,
        'total' => count($historial)
    ], 'Historial cargado exitosamente');
    
} catch (Exception $e) {
    jsonResponse(false, null, 'Error interno del servidor: ' . $e->getMessage(), 500);
}
?>