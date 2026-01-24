<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $ordenId = $input['orden_id'] ?? null;
    
    if (!$ordenId) {
        echo json_encode(['success' => false, 'message' => 'ID de orden requerido']);
        exit;
    }
    
    // Obtener datos de la orden
    $stmt = $conn->prepare("
        SELECT o.*, 
               c.NombreCompleto as ClienteNombre,
               c.Correo as ClienteCorreo,
               v.Placa, v.Marca, v.Modelo, v.Year, v.Color,
               cv.TipoVehiculo
        FROM `{$dbName}`.ordenes o
        LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
        LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
        LEFT JOIN `{$dbName}`.categoriavehiculo cv ON v.CategoriaVehiculo = cv.ID
        WHERE o.ID = ?
    ");
    
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
        exit;
    }
    
    $orden = $result->fetch_assoc();
    
    // Obtener servicios de la orden
    $stmt = $conn->prepare("
        SELECT os.*, s.Descripcion as ServicioNombre
        FROM `{$dbName}`.orden_servicios os
        LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
        WHERE os.OrdenID = ?
        ORDER BY os.ID
    ");
    
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    $serviciosResult = $stmt->get_result();
    
    $servicios = [];
    while ($servicio = $serviciosResult->fetch_assoc()) {
        $servicios[] = [
            'id' => $servicio['ServicioID'] ?: 'custom_' . $servicio['ID'],
            'nombre' => ($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre'],
            'precio' => floatval($servicio['Precio']),
            'personalizado' => !empty($servicio['ServicioPersonalizado'] ?? '')
        ];
    }
    
    // Agregar servicios al JSON de la orden
    $orden['ServiciosJSON'] = json_encode($servicios);
    
    echo json_encode([
        'success' => true,
        'orden' => $orden
    ]);
    
} catch (Exception $e) {
    error_log("Error en obtener-orden-editar.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>