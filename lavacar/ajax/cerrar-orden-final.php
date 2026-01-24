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
    $servicios = $input['servicios'] ?? [];
    $descuento = floatval($input['descuento'] ?? 0);
    $observaciones = $input['observaciones'] ?? '';
    
    if (!$ordenId) {
        echo json_encode(['success' => false, 'message' => 'ID de orden requerido']);
        exit;
    }
    
    // Verificar que la orden existe y está en estado Terminado
    $stmt = $conn->prepare("SELECT Estado FROM `{$dbName}`.ordenes WHERE ID = ?");
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Orden no encontrada']);
        exit;
    }
    
    $orden = $result->fetch_assoc();
    if ($orden['Estado'] != 3) {
        echo json_encode(['success' => false, 'message' => 'Solo se pueden cerrar órdenes terminadas']);
        exit;
    }
    
    $conn->begin_transaction();
    
    try {
        // Eliminar servicios existentes
        $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        
        // Insertar servicios finales
        $subtotal = 0;
        foreach ($servicios as $servicio) {
            $precio = floatval($servicio['precio']);
            $subtotal += $precio;
            
            if ($servicio['personalizado']) {
                // Servicio personalizado
                $stmt = $conn->prepare("
                    INSERT INTO `{$dbName}`.orden_servicios 
                    (OrdenID, ServicioID, Precio, ServicioPersonalizado) 
                    VALUES (?, NULL, ?, ?)
                ");
                $stmt->bind_param("ids", $ordenId, $precio, $servicio['nombre']);
            } else {
                // Servicio regular
                $stmt = $conn->prepare("
                    INSERT INTO `{$dbName}`.orden_servicios 
                    (OrdenID, ServicioID, Precio) 
                    VALUES (?, ?, ?)
                ");
                $stmt->bind_param("iid", $ordenId, $servicio['id'], $precio);
            }
            $stmt->execute();
        }
        
        // Aplicar descuento
        $subtotalConDescuento = $subtotal - $descuento;
        $impuesto = $subtotalConDescuento * 0.13;
        $total = $subtotalConDescuento + $impuesto;
        
        // Actualizar orden con estado cerrado
        $fechaCierre = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("
            UPDATE `{$dbName}`.ordenes 
            SET Estado = 4, 
                Subtotal = ?, 
                Impuesto = ?, 
                Monto = ?, 
                Descuento = ?,
                Observaciones = ?,
                FechaCierre = ?
            WHERE ID = ?
        ");
        $stmt->bind_param("ddddsi", $subtotal, $impuesto, $total, $descuento, $observaciones, $fechaCierre, $ordenId);
        $stmt->execute();
        
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Orden cerrada exitosamente',
            'totales' => [
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'impuesto' => $impuesto,
                'total' => $total
            ]
        ]);
        
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("Error en cerrar-orden-final.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al cerrar la orden']);
}
?>