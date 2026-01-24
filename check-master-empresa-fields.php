<?php
/**
 * Script para verificar y agregar campos faltantes en las bases de datos de empresas
 * Ejecuta automáticamente las actualizaciones necesarias en todas las bases de datos tenant
 */

require_once 'lib/config.php';

echo "🔧 FROSH - Verificación y Actualización de Campos en Bases de Datos\n";
echo "=" . str_repeat("=", 70) . "\n\n";

try {
    // Obtener todas las empresas registradas
    $empresasQuery = "SELECT id_empresa, nombre_empresa, nombre_base_datos FROM empresas WHERE nombre_base_datos IS NOT NULL";
    $result = $conn->query($empresasQuery);
    
    if (!$result) {
        throw new Exception("Error al obtener empresas: " . $conn->error);
    }
    
    $empresas = $result->fetch_all(MYSQLI_ASSOC);
    echo "📊 Empresas encontradas: " . count($empresas) . "\n\n";
    
    $actualizacionesRealizadas = 0;
    $errores = [];
    
    foreach ($empresas as $empresa) {
        $dbName = $empresa['nombre_base_datos'];
        $nombreEmpresa = $empresa['nombre_empresa'];
        
        echo "🏢 Procesando: {$nombreEmpresa} (DB: {$dbName})\n";
        
        try {
            // Verificar si la base de datos existe
            $checkDb = $conn->query("SHOW DATABASES LIKE '{$dbName}'");
            if ($checkDb->num_rows == 0) {
                echo "   ⚠️  Base de datos no existe, saltando...\n";
                continue;
            }
            
            // Conectar a la base de datos de la empresa
            $tenantConn = new mysqli(DB_HOST, DB_USER, DB_PASS, $dbName);
            if ($tenantConn->connect_error) {
                throw new Exception("Error de conexión: " . $tenantConn->connect_error);
            }
            
            // Verificar si la tabla servicios existe
            $checkTable = $tenantConn->query("SHOW TABLES LIKE 'servicios'");
            if ($checkTable->num_rows == 0) {
                echo "   ⚠️  Tabla servicios no existe, saltando...\n";
                $tenantConn->close();
                continue;
            }
            
            // Verificar si el campo Detalles ya existe
            $checkColumn = $tenantConn->query("SHOW COLUMNS FROM servicios LIKE 'Detalles'");
            
            if ($checkColumn->num_rows == 0) {
                // Agregar el campo Detalles
                $alterQuery = "ALTER TABLE servicios ADD COLUMN Detalles TEXT NULL AFTER Descripcion";
                
                if ($tenantConn->query($alterQuery)) {
                    echo "   ✅ Campo 'Detalles' agregado exitosamente\n";
                    $actualizacionesRealizadas++;
                } else {
                    throw new Exception("Error al agregar campo Detalles: " . $tenantConn->error);
                }
            } else {
                echo "   ℹ️  Campo 'Detalles' ya existe\n";
            }
            
            // Verificar la estructura actualizada
            $estructura = $tenantConn->query("DESCRIBE servicios");
            echo "   📋 Estructura actual de servicios:\n";
            while ($campo = $estructura->fetch_assoc()) {
                echo "      - {$campo['Field']} ({$campo['Type']})\n";
            }
            
            $tenantConn->close();
            echo "   ✅ Procesamiento completado\n\n";
            
        } catch (Exception $e) {
            $error = "Error en {$nombreEmpresa}: " . $e->getMessage();
            $errores[] = $error;
            echo "   ❌ {$error}\n\n";
        }
    }
    
    // Resumen final
    echo "=" . str_repeat("=", 70) . "\n";
    echo "📊 RESUMEN DE ACTUALIZACIONES\n";
    echo "=" . str_repeat("=", 70) . "\n";
    echo "✅ Empresas procesadas: " . count($empresas) . "\n";
    echo "🔧 Actualizaciones realizadas: {$actualizacionesRealizadas}\n";
    echo "❌ Errores encontrados: " . count($errores) . "\n\n";
    
    if (!empty($errores)) {
        echo "🚨 ERRORES DETALLADOS:\n";
        foreach ($errores as $i => $error) {
            echo ($i + 1) . ". {$error}\n";
        }
        echo "\n";
    }
    
    if ($actualizacionesRealizadas > 0) {
        echo "🎉 ¡Actualizaciones completadas exitosamente!\n";
        echo "💡 Las bases de datos ahora tienen el campo 'Detalles' en la tabla servicios.\n";
    } else {
        echo "ℹ️  No se requirieron actualizaciones.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error crítico: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n🏁 Proceso completado.\n";
?>