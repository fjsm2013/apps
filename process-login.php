<?php
session_start();

require_once 'lib/config.php';
require_once 'lib/Auth.php';
require_once 'lib/AuthManager.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$username   = trim($input['username'] ?? '');
$password   = (string)($input['password'] ?? '');
$rememberMe = !empty($input['rememberMe']);

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son requeridos']);
    exit;
}

$authManager = new AuthManager($conn);
$result = $authManager->login($username, $password, $rememberMe);

echo json_encode($result);
exit;