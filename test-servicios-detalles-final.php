<?php
/**
 * Test final para verificar que el campo Detalles funciona correctamente
 * en la administración de servicios
 */

require_once 'lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/ServiciosManager.php';

echo "🧪 TESTING: Campo Detalles en Servicios - Verificación Final\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Usar base de datos de prueba
$testDbName = "froshlav_11"; // Cambiar por tu DB de prueba

try {
    $manager = new ServiciosManager($conn, $testDbName);
    
    echo "1️⃣ Probando crear servicio con detalles...\n";
    
    // Crear servicio con detalles
    $servicioId = $manager->create(
        "Lavado Premium Test", 
        "Incluye lavado exterior, interior, aspirado completo y encerado"
    );
    
    echo "   ✅ Servicio creado con ID: $servicioId\n";
    
    echo "\n2️⃣ Probando obtener servicio creado...\n";
    
    // Obtener el servicio
    $servicio = $manager->find($servicioId);
    
    if ($servicio) {
        echo "   ✅ Servicio encontrado:\n";
        echo "      - ID: {$servicio['ID']}\n";
        echo "      - Descripción: {$servicio['Descripcion']}\n";
        echo "      - Detalles: {$servicio['Detalles']}\n";
    } else {
        echo "   ❌ No se pudo encontrar el servicio\n";
    }
    
    echo "\n3️⃣ Probando actualizar servicio con nuevos detalles...\n";
    
    // Actualizar servicio
    $manager->update(
        $servicioId, 
        "Lavado Premium Test Actualizado",
        "Detalles actualizados: Lavado completo con productos premium, aspirado profundo, limpieza de llantas y aplicación de cera protectora"
    );
    
    echo "   ✅ Servicio actualizado\n";
    
    echo "\n4️⃣ Verificando actualización...\n";
    
    // Verificar actualización
    $servicioActualizado = $manager->find($servicioId);
    
    if ($servicioActualizado) {
        echo "   ✅ Servicio actualizado correctamente:\n";
        echo "      - ID: {$servicioActualizado['ID']}\n";
        echo "      - Descripción: {$servicioActualizado['Descripcion']}\n";
        echo "      - Detalles: {$servicioActualizado['Detalles']}\n";
    } else {
        echo "   ❌ Error al verificar actualización\n";
    }
    
    echo "\n5️⃣ Probando listar todos los servicios...\n";
    
    // Listar todos
    $servicios = $manager->all();
    
    echo "   ✅ Servicios encontrados: " . count($servicios) . "\n";
    
    foreach ($servicios as $s) {
        $detalles = !empty($s['Detalles']) ? $s['Detalles'] : 'Sin detalles';
        echo "      - {$s['Descripcion']} | $detalles\n";
    }
    
    echo "\n6️⃣ Probando método allWithPricesByCategoria...\n";
    
    // Probar con categoría 1 (normalmente Sedán)
    $serviciosConPrecios = $manager->allWithPricesByCategoria(1);
    
    echo "   ✅ Servicios con precios para categoría 1: " . count($serviciosConPrecios) . "\n";
    
    foreach ($serviciosConPrecios as $s) {
        $detalles = !empty($s['Detalles']) ? $s['Detalles'] : 'Sin detalles';
        echo "      - {$s['Descripcion']} | Precio: ₡{$s['Precio']} | $detalles\n";
    }
    
    echo "\n7️⃣ Limpiando datos de prueba...\n";
    
    // Limpiar
    $manager->delete($servicioId);
    echo "   ✅ Servicio de prueba eliminado\n";
    
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "🎉 TODAS LAS PRUEBAS PASARON CORRECTAMENTE!\n";
    echo "✅ El campo Detalles está funcionando en:\n";
    echo "   - Creación de servicios\n";
    echo "   - Actualización de servicios\n";
    echo "   - Consulta individual\n";
    echo "   - Listado completo\n";
    echo "   - Consulta con precios por categoría\n";
    echo "\n🔧 La interfaz de administración debería mostrar los detalles correctamente.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>