<?php
session_start();

// Configurar zona horaria de Costa Rica
date_default_timezone_set("America/Costa_Rica");

require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        throw new Exception('Datos no válidos');
    }
    
    // Validar datos requeridos
    if (empty($data['vehiculo']['categoria_id'])) {
        throw new Exception('Categoría de vehículo requerida');
    }
    
    if (empty($data['servicios']) || !is_array($data['servicios'])) {
        throw new Exception('Servicios requeridos');
    }
    
    if (empty($data['cliente']['id'])) {
        throw new Exception('Cliente requerido');
    }
    
    // Iniciar transacción
    $conn->autocommit(false);
    
    // 1. Manejar vehículo (crear o actualizar)
    $vehiculoId = null;
    
    if (!empty($data['vehiculo']['id']) && $data['vehiculo']['existente']) {
        // Vehículo existente por ID
        $vehiculoId = $data['vehiculo']['id'];
        
        // Actualizar datos si es necesario
        if (!empty($data['vehiculo']['marca'])) {
            EjecutarSQL(
                $conn,
                "UPDATE {$dbName}.vehiculos SET 
                    Marca = ?, Modelo = ?, Year = ?, Color = ?
                 WHERE ID = ?",
                [
                    $data['vehiculo']['marca'],
                    $data['vehiculo']['modelo'],
                    $data['vehiculo']['year'],
                    $data['vehiculo']['color'],
                    $vehiculoId
                ]
            );
        }
    } else {
        // Verificar si existe vehículo con esta placa
        $vehiculoExistente = ObtenerPrimerRegistro(
            $conn,
            "SELECT ID FROM {$dbName}.vehiculos WHERE Placa = ? AND active = 1",
            [$data['vehiculo']['placa']]
        );
        
        if ($vehiculoExistente) {
            // Actualizar vehículo existente por placa
            $vehiculoId = $vehiculoExistente['ID'];
            EjecutarSQL(
                $conn,
                "UPDATE {$dbName}.vehiculos SET 
                    CategoriaVehiculo = ?, Marca = ?, Modelo = ?, Year = ?, Color = ?, ClienteID = ?
                 WHERE ID = ?",
                [
                    $data['vehiculo']['categoria_id'],
                    $data['vehiculo']['marca'],
                    $data['vehiculo']['modelo'],
                    $data['vehiculo']['year'],
                    $data['vehiculo']['color'],
                    $data['cliente']['id'],
                    $vehiculoId
                ]
            );
        } else {
            // Crear nuevo vehículo
            EjecutarSQL(
                $conn,
                "INSERT INTO {$dbName}.vehiculos 
                (Placa, CategoriaVehiculo, Marca, Modelo, Year, Color, ClienteID)
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    $data['vehiculo']['placa'],
                    $data['vehiculo']['categoria_id'],
                    $data['vehiculo']['marca'] ?? '',
                    $data['vehiculo']['modelo'] ?? '',
                    $data['vehiculo']['year'] ?? '',
                    $data['vehiculo']['color'] ?? '',
                    $data['cliente']['id']
                ]
            );
            $vehiculoId = $conn->insert_id;
        }
    }
    
    // 2. Crear orden usando OrdenManager
    require_once 'lavacar/backend/OrdenManager.php';
    $ordenManager = new OrdenManager($conn, $dbName);
    
    $ordenData = [
        'cliente_id' => $data['cliente']['id'],
        'vehiculo_id' => $vehiculoId,
        'categoria_id' => $data['vehiculo']['categoria_id'],
        'servicios' => $data['servicios'],
        'observaciones' => $data['observaciones'] ?? '',
        'descuento' => 0.00,
        'estado' => 1,
        'tipo_servicio' => 1
    ];
    
    $ordenId = $ordenManager->create($ordenData);
    
    // 3. Agregar servicios a la tabla orden_servicios (si existe) - para compatibilidad
    foreach ($data['servicios'] as $servicio) {
        try {
            if (isset($servicio['personalizado']) && $servicio['personalizado']) {
                // Servicio personalizado
                EjecutarSQL(
                    $conn,
                    "INSERT INTO {$dbName}.orden_servicios 
                    (OrdenID, ServicioID, Precio, ServicioPersonalizado)
                     VALUES (?, NULL, ?, ?)",
                    [
                        $ordenId,
                        $servicio['precio'],
                        $servicio['nombre']
                    ]
                );
            } else {
                // Servicio regular
                EjecutarSQL(
                    $conn,
                    "INSERT INTO {$dbName}.orden_servicios 
                    (OrdenID, ServicioID, Precio)
                     VALUES (?, ?, ?)",
                    [
                        $ordenId,
                        $servicio['id'],
                        $servicio['precio']
                    ]
                );
            }
        } catch (Exception $e) {
            // Si la tabla orden_servicios no existe, continuar
            // Los servicios ya están guardados en ServiciosJSON
            error_log("Info: orden_servicios table might not exist: " . $e->getMessage());
        }
    }
    
    // Confirmar transacción
    $conn->commit();
    $conn->autocommit(true);
    
    // Enviar correo de confirmación (opcional)
    if (!empty($data['cliente']['email'])) {
        try {
            require_once 'enviar_correo_orden.php';
            
            // Obtener nombre del cliente
            require_once 'lavacar/backend/ClientesManager.php';
            $clientesManager = new ClientesManager($conn, $dbName);
            $cliente = $clientesManager->find($data['cliente']['id']);
            $nombreCliente = $cliente['NombreCompleto'] ?? 'Cliente';
            
            $emailEnviado = enviarCorreoOrden($data['cliente']['email'], $nombreCliente, $ordenId);
            
            if (!$emailEnviado) {
                error_log("Advertencia: No se pudo enviar correo de confirmación para orden #{$ordenId}");
            }
        } catch (Exception $e) {
            // Log error but don't fail the order
            error_log("Error enviando correo: " . $e->getMessage());
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Orden creada exitosamente',
        'orden_id' => $ordenId
    ]);
    
} catch (Exception $e) {
    // Rollback en caso de error
    $conn->rollback();
    $conn->autocommit(true);
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
