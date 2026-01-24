<?php
session_start();
require_once '../../../lib/config.php';
require_once 'lib/Auth.php';

header('Content-Type: application/json');

// Verificar autenticación
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

try {
    // Obtener datos del POST
    $input = json_decode(file_get_contents('php://input'), true);
    $ordenId = intval($input['orden_id'] ?? 0);
    
    if (!$ordenId) {
        throw new Exception('ID de orden inválido');
    }
    
    // Verificar que la orden existe
    $stmt = $conn->prepare("SELECT ID FROM `{$dbName}`.ordenes WHERE ID = ?");
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Orden no encontrada');
    }
    
    // Iniciar transacción
    $conn->begin_transaction();
    
    // Eliminar servicios asociados
    $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    
    // Eliminar la orden
    $stmt = $conn->prepare("DELETE FROM `{$dbName}`.ordenes WHERE ID = ?");
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    
    // Confirmar transacción
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Orden eliminada exitosamente'
    ]);
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($conn) {
        $conn->rollback();
    }
    
    error_log("Error en eliminar-orden.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
