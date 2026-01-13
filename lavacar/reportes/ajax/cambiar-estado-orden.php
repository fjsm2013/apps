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
require_once 'lavacar/ordenes/enviar_correo_estado.php';
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
    
    // Obtener datos de la orden antes del cambio para verificar si hay email
    $orden = $ordenManager->find($ordenId);
    if (!$orden) {
        throw new Exception('Orden no encontrada');
    }
    
    // Actualizar estado
    $ordenManager->updateStatus($ordenId, $estado);
    
    $estadoTexto = [
        1 => 'Pendiente',
        2 => 'En Proceso', 
        3 => 'Terminado',
        4 => 'Cerrado'
    ];
    
    // Enviar email de notificación si el cliente tiene correo
    $emailEnviado = false;
    if (!empty($orden['ClienteCorreo']) && $estado > 1) { // Solo enviar para estados > Pendiente
        try {
            $emailEnviado = enviarCorreoEstado($ordenId, $estado);
        } catch (Exception $e) {
            error_log("Error enviando email de estado: " . $e->getMessage());
            // No fallar la operación si el email falla
        }
    }
    
    $message = "Orden #{$ordenId} cambiada a estado: {$estadoTexto[$estado]}";
    if ($emailEnviado) {
        $message .= " - Notificacion enviada al cliente";
    } elseif (!empty($orden['ClienteCorreo'])) {
        $message .= " - Error enviando notificación";
    } else {
        $message .= " - Cliente sin email registrado";
    }
    
    echo json_encode([
        'success' => true,
        'message' => $message,
        'email_sent' => $emailEnviado
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>