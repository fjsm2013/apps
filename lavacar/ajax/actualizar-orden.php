<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

header('Content-Type: application/json');

// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Log the received data for debugging
    error_log("actualizar-orden.php received data: " . json_encode($input));
    
    $ordenId = $input['orden_id'] ?? null;
    $servicios = $input['servicios'] ?? [];
    $observaciones = $input['observaciones'] ?? '';
    
    if (!$ordenId) {
        echo json_encode(['success' => false, 'message' => 'ID de orden requerido']);
        exit;
    }
    
    // Log services count
    error_log("actualizar-orden.php processing " . count($servicios) . " services for order {$ordenId}");
    
    // Verificar que la orden existe y no está cerrada
    $stmt = $conn->prepare("SELECT Estado FROM `{$dbName}`.ordenes WHERE ID = ?");
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
        exit;
    }
    
    $orden = $result->fetch_assoc();
    if ($orden['Estado'] == 4) {
        echo json_encode(['success' => false, 'message' => 'No se puede editar una orden cerrada']);
        exit;
    }
    
    $conn->begin_transaction();
    
    try {
        // Eliminar servicios existentes
        $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        error_log("actualizar-orden.php deleted existing services for order {$ordenId}");
        
        // Insertar servicios actualizados
        $subtotal = 0;
        foreach ($servicios as $index => $servicio) {
            $precio = floatval($servicio['precio']);
            $cantidad = intval($servicio['cantidad'] ?? 1);
            $servicioSubtotal = $precio * $cantidad;
            $subtotal += $servicioSubtotal;
            
            error_log("actualizar-orden.php processing service {$index}: " . json_encode($servicio));
            
            if ($servicio['personalizado']) {
                // Servicio personalizado
                $stmt = $conn->prepare("
                    INSERT INTO `{$dbName}`.orden_servicios 
                    (OrdenID, ServicioID, Descripcion, Precio, ServicioPersonalizado, Cantidad, Subtotal) 
                    VALUES (?, 0, ?, ?, ?, ?, ?)
                ");
                $descripcion = $servicio['nombre'];
                $stmt->bind_param("isdsid", $ordenId, $descripcion, $precio, $servicio['nombre'], $cantidad, $servicioSubtotal);
                error_log("actualizar-orden.php inserting custom service: {$servicio['nombre']} - ₡{$precio}");
            } else {
                // Servicio regular - extraer ID numérico
                $servicioID = intval(str_replace('service_', '', $servicio['id']));
                $stmt = $conn->prepare("
                    INSERT INTO `{$dbName}`.orden_servicios 
                    (OrdenID, ServicioID, Precio, Cantidad, Subtotal) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("iidid", $ordenId, $servicioID, $precio, $cantidad, $servicioSubtotal);
                error_log("actualizar-orden.php inserting regular service: ID {$servicioID} - ₡{$precio}");
            }
            $stmt->execute();
        }
        
        // Calcular totales
        $impuesto = $subtotal * 0.13;
        $total = $subtotal + $impuesto;
        
        // Update order (update Observaciones and ServiciosJson)
        $serviciosJsonData = json_encode($servicios);
        
        $stmt = $conn->prepare("
            UPDATE `{$dbName}`.ordenes 
            SET Observaciones = ?, ServiciosJson = ?
            WHERE ID = ?
        ");
        $stmt->bind_param("ssi", $observaciones, $serviciosJsonData, $ordenId);
        $stmt->execute();
        
        error_log("actualizar-orden.php updated order {$ordenId} totals: Subtotal={$subtotal}, Total={$total}");
        
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Orden actualizada exitosamente',
            'debug' => [
                'services_processed' => count($servicios),
                'subtotal' => $subtotal,
                'total' => $total
            ],
            'totales' => [
                'subtotal' => $subtotal,
                'impuesto' => $impuesto,
                'total' => $total
            ]
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("actualizar-orden.php transaction error: " . $e->getMessage());
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("actualizar-orden.php error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la orden: ' . $e->getMessage()]);
}
?>