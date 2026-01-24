<?php
/**
 * Script de prueba para verificar el cálculo de tiempo desde FechaProceso
 */

require_once 'lib/config.php';
require_once 'lib/Auth.php';

echo "🕒 TESTING: Cálculo de Tiempo desde FechaProceso\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Simular usuario logueado para prueba
session_start();
if (!isLoggedIn()) {
    echo "❌ Necesitas estar logueado para ejecutar esta prueba\n";
    echo "💡 Ve a login.php primero\n";
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

try {
    // Obtener órdenes en proceso con FechaProceso
    $query = "SELECT ID, Estado, FechaIngreso, FechaProceso, 
                     COALESCE(v.Placa, 'Sin placa') as Placa
              FROM {$dbName}.ordenes o
              LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
              WHERE o.Estado = 2 
              ORDER BY o.ID DESC 
              LIMIT 5";
              
    $result = CrearConsulta($conn, $query, []);
    $ordenes = $result->fetch_all(MYSQLI_ASSOC);
    
    echo "📊 Órdenes en proceso encontradas: " . count($ordenes) . "\n\n";
    
    if (empty($ordenes)) {
        echo "ℹ️  No hay órdenes en proceso para probar\n";
        echo "💡 Cambia alguna orden a estado 'En Proceso' desde órdenes activas\n";
        exit;
    }
    
    foreach ($ordenes as $orden) {
        echo "🚗 Orden #{$orden['ID']} - Placa: {$orden['Placa']}\n";
        echo "   📅 Fecha Ingreso: {$orden['FechaIngreso']}\n";
        echo "   🔧 Fecha Proceso: " . ($orden['FechaProceso'] ?? 'NULL') . "\n";
        
        if (!empty($orden['FechaProceso'])) {
            $fechaProceso = new DateTime($orden['FechaProceso']);
            $ahora = new DateTime();
            $tiempoTranscurrido = $fechaProceso->diff($ahora);
            
            // Formato de tiempo
            $tiempoTexto = '';
            if ($tiempoTranscurrido->h > 0) {
                $tiempoTexto = $tiempoTranscurrido->format('%h:%I horas');
            } else {
                $tiempoTexto = $tiempoTranscurrido->format('%i minutos');
            }
            
            echo "   ⏱️  Tiempo trabajando: {$tiempoTexto}\n";
        } else {
            echo "   ⚠️  FechaProceso es NULL - usando FechaIngreso como fallback\n";
            
            $fechaIngreso = new DateTime($orden['FechaIngreso']);
            $ahora = new DateTime();
            $tiempoTranscurrido = $fechaIngreso->diff($ahora);
            
            $tiempoTexto = '';
            if ($tiempoTranscurrido->h > 0) {
                $tiempoTexto = $tiempoTranscurrido->format('%h:%I horas');
            } else {
                $tiempoTexto = $tiempoTranscurrido->format('%i minutos');
            }
            
            echo "   ⏱️  Tiempo desde ingreso: {$tiempoTexto}\n";
        }
        
        echo "\n";
    }
    
    echo "✅ Prueba completada exitosamente\n";
    echo "💡 El panel de trabajo ahora mostrará el tiempo correcto desde que inició el proceso\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>