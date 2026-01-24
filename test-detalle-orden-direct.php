<?php
// Direct test of the AJAX endpoint to see the actual error
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

if (!isLoggedIn()) {
    die("Not logged in");
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Direct Test of detalle-orden.php</h2>";
echo "<h3>Order ID: 27</h3>";

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

echo "<h4>Step 1: Find Order</h4>";
try {
    $orden = $ordenManager->find(27);
    if ($orden) {
        echo "<p style='color: green;'>✓ Order found</p>";
        echo "<pre>" . print_r($orden, true) . "</pre>";
    } else {
        echo "<p style='color: red;'>✗ Order not found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h4>Step 2: Get Servicios</h4>";
try {
    $servicios = $ordenManager->getServicios(27);
    echo "<p style='color: green;'>✓ Servicios retrieved: " . count($servicios) . "</p>";
    echo "<pre>" . print_r($servicios, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h4>Step 3: Get Totals</h4>";
try {
    $totales = $ordenManager->getTotals(27);
    echo "<p style='color: green;'>✓ Totals retrieved</p>";
    echo "<pre>" . print_r($totales, true) . "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h4>Step 4: Full AJAX Simulation</h4>";
try {
    $ordenId = 27;
    
    $orden = $ordenManager->find($ordenId);
    if (!$orden) {
        throw new Exception('Orden no encontrada');
    }
    
    $servicios = $ordenManager->getServicios($ordenId);
    $totales = $ordenManager->getTotals($ordenId);
    
    $fechas = [
        'ingreso' => $orden['FechaIngreso'] ? date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) : null,
        'proceso' => $orden['FechaProceso'] ? date('d/m/Y H:i', strtotime($orden['FechaProceso'])) : null,
        'terminado' => $orden['FechaTerminado'] ? date('d/m/Y H:i', strtotime($orden['FechaTerminado'])) : null,
        'cierre' => $orden['FechaCierre'] ? date('d/m/Y H:i', strtotime($orden['FechaCierre'])) : null
    ];
    
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
    
    $response = [
        'success' => true,
        'orden' => $orden,
        'servicios' => $servicios,
        'totales' => $totales,
        'fechas' => $fechas,
        'siguiente_estado' => $siguienteEstado,
        'siguiente_estado_texto' => $siguienteEstadoTexto,
        'siguiente_estado_icon' => $siguienteEstadoIcon
    ];
    
    echo "<p style='color: green;'>✓ Full simulation successful</p>";
    echo "<h5>JSON Response:</h5>";
    echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT) . "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h4>Now test the actual AJAX endpoint:</h4>";
echo "<p><a href='lavacar/reportes/ajax/detalle-orden.php?id=27' target='_blank'>Open AJAX endpoint directly</a></p>";
?>