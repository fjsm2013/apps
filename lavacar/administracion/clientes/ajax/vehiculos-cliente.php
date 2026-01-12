<?php
session_start();

require_once '../../../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/ClientesManager.php';

// Verificar autenticación
autoLoginFromCookie();
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener parámetros
$clienteId = (int)($_GET['cliente_id'] ?? 0);

if (!$clienteId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de cliente requerido']);
    exit;
}

try {
    $user = userInfo();
    $dbName = $user['company']['db'];
    
    $manager = new ClientesManager($conn, $dbName);
    $vehiculos = $manager->getClientVehicles($clienteId);
    
    echo json_encode([
        'success' => true,
        'vehiculos' => $vehiculos,
        'total' => count($vehiculos)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error interno del servidor',
        'error' => $e->getMessage()
    ]);
}
?>