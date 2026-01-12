<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/PasswordResetManager.php';

header('Content-Type: application/json');

// Clean output buffers
while (ob_get_level()) {
    ob_end_clean();
}

$database = new Database();
$conn = $database->getConnection();
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

/*
 * IMPORTANT:
 * Always return success message to avoid account enumeration
 */
$resetManager->createResetRequest($email);

echo json_encode([
    'success' => true,
    'message' => 'Hemos enviado un enlace de recuperación a tu email.'
]);