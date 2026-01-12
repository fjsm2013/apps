<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/ServiciosManager.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$categoriaId = (int)($data['categoria_id'] ?? 0);

if ($categoriaId === 0) {
    echo json_encode([]);
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

$manager = new ServiciosManager($conn, $dbName);

/*
  ESTE MÉTODO DEBE EXISTIR
  allWithPricesByCategoria($categoriaId)
*/
$servicios = $manager->allWithPricesByCategoria($categoriaId);

// 🔴 ASEGURAR JSON LIMPIO
header('Content-Type: application/json');
echo json_encode($servicios);
exit;