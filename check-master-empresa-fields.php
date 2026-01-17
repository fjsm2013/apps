<?php
/**
 * Verificar campos disponibles en la tabla empresas de la base padre
 */

session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo "❌ Error: Usuario no autenticado. Inicia sesión primero.\n";
    exit;
}

$user = userInfo();
echo "🔍 Verificando estructura de la tabla 'empresas' en la base padre\n";
echo "👤 Usuario: {$user['nombre']}\n";
echo "🏢 Empresa ID: {$user['company']['id']}\n\n";

try {
    // Obtener estructura de la tabla empresas
    echo "📋 Estructura de la tabla 'empresas':\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    $result = $conn->query("DESCRIBE empresas");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $nullable = $row['Null'] === 'YES' ? '(nullable)' : '(required)';
            $default = $row['Default'] ? " [default: {$row['Default']}]" : '';
            echo sprintf("%-20s %-15s %s%s\n", 
                $row['Field'], 
                $row['Type'], 
                $nullable,
                $default
            );
        }
    }
    
    echo "\n📊 Datos actuales de tu empresa:\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    // Obtener datos actuales de la empresa
    $stmt = $conn->prepare("SELECT * FROM empresas WHERE id_empresa = ?");
    $stmt->bind_param("i", $user['company']['id']);
    $stmt->execute();
    $empresaData = $stmt->get_result()->fetch_assoc();
    
    if ($empresaData) {
        foreach ($empresaData as $campo => $valor) {
            $valorMostrar = $valor ? $valor : '(vacío)';
            if (strlen($valorMostrar) > 50) {
                $valorMostrar = substr($valorMostrar, 0, 47) . '...';
            }
            echo sprintf("%-20s: %s\n", $campo, $valorMostrar);
        }
    } else {
        echo "❌ No se encontraron datos de la empresa\n";
    }
    
    echo "\n💡 Campos útiles para pre-llenar el wizard:\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    $camposUtiles = [
        // Información básica
        'nombre' => 'Nombre del lavadero',
        'telefono' => 'Teléfono principal', 
        'email' => 'Email de contacto',
        'ciudad' => 'Ciudad (para dirección)',
        'pais' => 'País (para dirección)',
        'ruc_identificacion' => 'RUC/Identificación',
        // Campos operativos nuevos
        'hora_apertura_default' => 'Hora de apertura por defecto',
        'hora_cierre_default' => 'Hora de cierre por defecto',
        'dias_laborales_default' => 'Días laborales por defecto',
        'capacidad_maxima_default' => 'Capacidad máxima por defecto',
        'tiempo_promedio_default' => 'Tiempo promedio por defecto',
        'moneda_default' => 'Moneda por defecto',
        'tipo_negocio' => 'Tipo de negocio'
    ];
    
    foreach ($camposUtiles as $campo => $descripcion) {
        $disponible = isset($empresaData[$campo]) && !empty($empresaData[$campo]);
        $icono = $disponible ? '✅' : '❌';
        $valor = $disponible ? " -> '{$empresaData[$campo]}'" : ' (no disponible)';
        echo "{$icono} {$descripcion}: {$campo}{$valor}\n";
    }
    
    echo "\n🎯 Recomendaciones:\n";
    echo "=" . str_repeat("=", 50) . "\n";
    
    if (empty($empresaData['telefono'])) {
        echo "📞 Considera agregar el campo 'telefono' a la tabla empresas\n";
    }
    
    if (empty($empresaData['email'])) {
        echo "📧 Considera agregar el campo 'email' a la tabla empresas\n";
    }
    
    if (empty($empresaData['ciudad']) || empty($empresaData['pais'])) {
        echo "🌍 Considera agregar campos 'ciudad' y 'pais' para ubicación\n";
    }
    
    // Nuevas recomendaciones para campos operativos
    if (empty($empresaData['hora_apertura_default'])) {
        echo "🕐 Considera agregar 'hora_apertura_default' para horarios inteligentes\n";
    }
    
    if (empty($empresaData['capacidad_maxima_default'])) {
        echo "🚗 Considera agregar 'capacidad_maxima_default' para configuración automática\n";
    }
    
    if (empty($empresaData['tipo_negocio'])) {
        echo "🏢 Considera agregar 'tipo_negocio' para configuraciones inteligentes\n";
    }
    
    echo "\n🎉 Beneficios de los campos operativos:\n";
    echo "   ✅ Configuración más rápida de nuevos lavaderos\n";
    echo "   ✅ Defaults inteligentes según tipo de negocio\n";
    echo "   ✅ Consistencia entre lavaderos de la empresa\n";
    echo "   ✅ Menos campos manuales que llenar\n";
    
    echo "\n✅ El wizard ya está configurado para usar todos los campos disponibles!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>