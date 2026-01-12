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

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

header('Content-Type: application/json');

try {
    $ordenId = (int)($_GET['id'] ?? 0);
    
    if (!$ordenId) {
        throw new Exception('ID de orden no válido');
    }
    
    $orden = $ordenManager->find($ordenId);
    if (!$orden) {
        throw new Exception('Orden no encontrada');
    }
    
    $servicios = $ordenManager->getServicios($ordenId);
    $totales = $ordenManager->getTotals($ordenId);
    
    // Formatear fechas
    $fechas = [
        'ingreso' => $orden['FechaIngreso'] ? date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) : null,
        'proceso' => $orden['FechaProceso'] ? date('d/m/Y H:i', strtotime($orden['FechaProceso'])) : null,
        'terminado' => $orden['FechaTerminado'] ? date('d/m/Y H:i', strtotime($orden['FechaTerminado'])) : null,
        'cierre' => $orden['FechaCierre'] ? date('d/m/Y H:i', strtotime($orden['FechaCierre'])) : null
    ];
    
    // Determinar siguiente estado posible
    $siguienteEstado = null;
    $siguienteEstadoTexto = '';
    $siguienteEstadoIcon = '';
    
    if ($orden['Estado'] < 4) {
        $siguienteEstado = $orden['Estado'] + 1;
        switch ($siguienteEstado) {
            case 2:
                $siguienteEstadoTexto = 'Iniciar Proceso';
                $siguienteEstadoIcon = 'fa-play';
                break;
            case 3:
                $siguienteEstadoTexto = 'Marcar Terminado';
                $siguienteEstadoIcon = 'fa-check';
                break;
            case 4:
                $siguienteEstadoTexto = 'Cerrar Orden';
                $siguienteEstadoIcon = 'fa-lock';
                break;
        }
    }
    
    echo json_encode([
        'success' => true,
        'orden' => $orden,
        'servicios' => $servicios,
        'totales' => $totales,
        'fechas' => $fechas,
        'siguiente_estado' => $siguienteEstado,
        'siguiente_estado_texto' => $siguienteEstadoTexto,
        'siguiente_estado_icon' => $siguienteEstadoIcon
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>