<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/PasswordResetManager.php';

header('Content-Type: application/json');

// Clean output buffers
while (ob_get_level()) {
    ob_end_clean();
}

// Use MySQLi connection (same as the rest of the system)
$resetManager = new PasswordResetManager($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = filter_var(trim($input['email'] ?? ''), FILTER_SANITIZE_EMAIL);

// Validate email format
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Por favor, introduce un email válido.'
    ]);
    exit;
}

try {
    /*
     * IMPORTANT:
     * Always return success message to avoid account enumeration
     */
    $result = $resetManager->createResetRequest($email);
    
    echo json_encode([
        'success' => true,
        'message' => 'Si el email existe en nuestro sistema, hemos enviado un enlace de recuperación.'
    ]);
    
} catch (Exception $e) {
    error_log("Error en reset de contraseña: " . $e->getMessage());
    
    echo json_encode([
        'success' => true, // Always return success for security
        'message' => 'Si el email existe en nuestro sistema, hemos enviado un enlace de recuperación.'
    ]);
}