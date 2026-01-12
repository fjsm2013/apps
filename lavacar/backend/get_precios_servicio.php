<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/preciosManager.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$user = userInfo();
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'User info not found']);
    exit;
}

// Get tenant database name
$dbName = $user['company']['db'] ?? 'froshlav_' . $user['id_empresa'];

$serviceId = (int)($_GET['id'] ?? 0);

if ($serviceId <= 0) {
    echo json_encode([]);
    exit;
}

$preciosManager = new PreciosManager($conn, $dbName);
$precios = $preciosManager->byServicioIndexed($serviceId);

echo json_encode($precios);