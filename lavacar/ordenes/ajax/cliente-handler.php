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

require_once 'lavacar/backend/ClientesManager.php';
$clientesManager = new ClientesManager($conn, $dbName);

header('Content-Type: application/json');

try {
    // Determinar acción basada en método HTTP
    $action = '';
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? '';
    } else {
        $action = $_POST['action'] ?? '';
    }

    switch ($action) {
        case 'create':
            createCliente();
            break;
        case 'update':
            updateCliente();
            break;
        case 'get':
            getCliente();
            break;
        case 'searchByCedula':
            searchByCedula();
            break;
        case 'getVehiculos':
            getVehiculosCliente();
            break;
        default:
            throw new Exception('Acción no válida: ' . $action);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function createCliente()
{
    global $clientesManager;

    // Validar campos requeridos
    $required = ['nombreCompleto', 'correo', 'telefono'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("El campo {$field} es requerido");
        }
    }

    // Validar email
    if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El correo electrónico no es válido');
    }

    $data = [
        'NombreCompleto' => trim($_POST['nombreCompleto']),
        'Cedula' => trim($_POST['cedula']) ?: null,
        'Empresa' => trim($_POST['empresa']) ?: null,
        'Correo' => trim($_POST['correo']),
        'Telefono' => trim($_POST['telefono']),
        'Direccion' => trim($_POST['direccion']) ?: null,
        'Distrito' => trim($_POST['distrito']) ?: null,
        'Canton' => trim($_POST['canton']) ?: null,
        'Provincia' => trim($_POST['provincia']) ?: null,
        'Pais' => $_POST['pais'] ?? 'CR',
        'IVA' => (int)($_POST['iva'] ?? 13),
        'active' => 1
    ];

    $clientesManager->create($data);

    // Obtener el ID del cliente recién creado
    $clienteId = $GLOBALS['conn']->insert_id;

    // Obtener los datos completos del cliente
    $cliente = $clientesManager->find($clienteId);

    echo json_encode([
        'success' => true,
        'message' => 'Cliente creado exitosamente',
        'cliente' => $cliente
    ]);
}

function updateCliente()
{
    global $clientesManager;

    $clienteId = (int)($_POST['clienteId'] ?? 0);
    if (!$clienteId) {
        throw new Exception('ID de cliente no válido');
    }

    // Verificar que el cliente existe
    $existingCliente = $clientesManager->find($clienteId);
    if (!$existingCliente) {
        throw new Exception('Cliente no encontrado');
    }

    // Validar campos requeridos
    $required = ['nombreCompleto', 'correo', 'telefono'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("El campo {$field} es requerido");
        }
    }

    // Validar email
    if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El correo electrónico no es válido');
    }

    $data = [
        'NombreCompleto' => trim($_POST['nombreCompleto']),
        'Cedula' => trim($_POST['cedula']) ?: null,
        'Empresa' => trim($_POST['empresa']) ?: null,
        'Correo' => trim($_POST['correo']),
        'Telefono' => trim($_POST['telefono']),
        'Direccion' => trim($_POST['direccion']) ?: null,
        'Distrito' => trim($_POST['distrito']) ?: null,
        'Canton' => trim($_POST['canton']) ?: null,
        'Provincia' => trim($_POST['provincia']) ?: null,
        'Pais' => $_POST['pais'] ?? 'CR',
        'IVA' => (int)($_POST['iva'] ?? 13),
        'active' => $existingCliente['active'] // Mantener el estado actual
    ];

    $clientesManager->update($clienteId, $data);

    // Obtener los datos actualizados
    $cliente = $clientesManager->find($clienteId);

    echo json_encode([
        'success' => true,
        'message' => 'Cliente actualizado exitosamente',
        'cliente' => $cliente
    ]);
}

function getCliente()
{
    global $clientesManager;

    $clienteId = (int)($_GET['id'] ?? 0);
    if (!$clienteId) {
        throw new Exception('ID de cliente no válido');
    }

    $cliente = $clientesManager->find($clienteId);
    if (!$cliente) {
        throw new Exception('Cliente no encontrado');
    }

    echo json_encode([
        'success' => true,
        'cliente' => $cliente
    ]);
}

function searchByCedula()
{
    global $clientesManager;

    $cedula = trim($_GET['cedula'] ?? '');
    if (empty($cedula)) {
        throw new Exception('Cédula es requerida');
    }

    // Debug: Log the search
    error_log("Buscando cliente con cédula: " . $cedula);

    $cliente = $clientesManager->findByCedula($cedula);
    
    if ($cliente) {
        error_log("Cliente encontrado: " . json_encode($cliente));
        echo json_encode([
            'success' => true,
            'found' => true,
            'cliente' => $cliente
        ]);
    } else {
        error_log("Cliente no encontrado para cédula: " . $cedula);
        echo json_encode([
            'success' => true,
            'found' => false,
            'message' => 'Cliente no encontrado'
        ]);
    }
}

function getVehiculosCliente()
{
    global $conn, $user;
    
    $clienteId = (int)($_GET['clienteId'] ?? 0);
    if (!$clienteId) {
        throw new Exception('ID de cliente no válido');
    }

    $dbName = $user['company']['db'];
    require_once 'lavacar/backend/VehiculosManager.php';
    $vehiculosManager = new VehiculosManager($conn, $dbName);
    
    $vehiculos = $vehiculosManager->findByCliente($clienteId);
    
    echo json_encode([
        'success' => true,
        'vehiculos' => $vehiculos
    ]);
}
?>