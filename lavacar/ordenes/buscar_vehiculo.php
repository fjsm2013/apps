<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
$user = userInfo();
$dbName = $user['company']['db'];

$placa = $_GET['placa'] ?? '';

$row = ObtenerPrimerRegistro(
    $conn,
    "SELECT v.ID, v.Placa, v.CategoriaVehiculo, c.TipoVehiculo,
            v.Marca, v.Modelo, v.Year, v.Color, v.ClienteID
     FROM {$dbName}.vehiculos v
     JOIN {$dbName}.categoriavehiculo c ON c.ID = v.CategoriaVehiculo
     WHERE v.Placa = ? AND v.active = 1",
    [$placa]
);

if (!$row) {
    echo json_encode(['ok' => false]);
    exit;
}

// Si el vehículo tiene cliente, también obtener datos del cliente
$cliente = null;
if (!empty($row['ClienteID'])) {
    $cliente = ObtenerPrimerRegistro(
        $conn,
        "SELECT ID, NombreCompleto, Correo FROM {$dbName}.clientes WHERE ID = ? AND active = 1",
        [$row['ClienteID']]
    );
}

echo json_encode([
    'ok' => true,
    'vehiculo' => $row,
    'cliente' => $cliente
]);