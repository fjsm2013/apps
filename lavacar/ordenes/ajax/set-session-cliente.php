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

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!empty($data['cliente_id'])) {
        $_SESSION['orden']['cliente_id'] = (int)$data['cliente_id'];
        echo json_encode(['success' => true]);
    } else {
        unset($_SESSION['orden']['cliente_id']);
        echo json_encode(['success' => true]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>