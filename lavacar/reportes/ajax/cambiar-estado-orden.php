<?php
session_start();
require_once '../../../lib/config.php';
require_once 'lib/Auth.php';

// Verificar autenticación
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['orden_id']) || !isset($data['estado'])) {
        throw new Exception('Datos incompletos');
    }
    
    $ordenId = (int)$data['orden_id'];
    $estado = (int)$data['estado'];
    
    // Validar estado
    if ($estado < 1 || $estado > 4) {
        throw new Exception('Estado no válido');
    }
    
    $ordenManager->updateStatus($ordenId, $estado);
    
    $estadoTexto = [
        1 => 'Pendiente',
        2 => 'En Proceso', 
        3 => 'Terminado',
        4 => 'Cerrado'
    ];
    
    echo json_encode([
        'success' => true,
        'message' => "Orden #{$ordenId} cambiada a estado: {$estadoTexto[$estado]}"
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>