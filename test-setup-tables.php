<?php
/**
 * Test Setup Tables Creation
 * Verifica que las tablas del setup wizard se crean correctamente
 */

// Mock basic requirements
session_start();

// Mock user data for testing
$_SESSION['user_data'] = [
    'Usuarios' => 1,
    'company' => [
        'id' => 1,
        'db' => 'test_lavacar_db'
    ]
];

// Include required files
require_once 'lib/config.php';

// Mock functions if they don't exist
if (!function_exists('userInfo')) {
    function userInfo() {
        return $_SESSION['user_data'] ?? null;
    }
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_data']);
    }
}

echo "🧪 Testing Setup Tables Creation...\n\n";

// Include the setup tables function
require_once 'lavacar/setup-wizard/setup-tables.php';

$user = userInfo();
$dbName = $user['company']['db'];

echo "📋 Base de datos de prueba: $dbName\n\n";

echo "🔧 Verificando tablas existentes antes del setup:\n";
$tables = ['configuracion_sistema', 'configuracion_empresa'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES FROM `$dbName` LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "   ✅ $table - YA EXISTE\n";
    } else {
        echo "   ❌ $table - NO EXISTE\n";
    }
}

echo "\n🚀 Ejecutando createSetupTables()...\n";
$result = createSetupTables($conn, $dbName);

if ($result['success']) {
    echo "✅ " . $result['message'] . "\n\n";
} else {
    echo "❌ " . $result['message'] . "\n\n";
}

echo "🔍 Verificando tablas después del setup:\n";
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES FROM `$dbName` LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "   ✅ $table - EXISTE\n";
        
        // Verificar estructura de configuracion_sistema
        if ($table === 'configuracion_sistema') {
            $columns = $conn->query("DESCRIBE `$dbName`.`$table`");
            if ($columns) {
                echo "      Columnas: ";
                $columnNames = [];
                while ($col = $columns->fetch_assoc()) {
                    $columnNames[] = $col['Field'];
                }
                echo implode(', ', $columnNames) . "\n";
            }
        }
    } else {
        echo "   ❌ $table - NO EXISTE\n";
    }
}

echo "\n🧪 Probando inserción en configuracion_sistema:\n";
try {
    $sql = "INSERT INTO `{$dbName}`.`configuracion_sistema` (clave, valor) 
            VALUES ('test_key', 'test_value') 
            ON DUPLICATE KEY UPDATE valor = 'test_value'";
    
    if ($conn->query($sql)) {
        echo "   ✅ Inserción exitosa\n";
        
        // Verificar que se insertó
        $check = $conn->query("SELECT * FROM `{$dbName}`.`configuracion_sistema` WHERE clave = 'test_key'");
        if ($check && $check->num_rows > 0) {
            echo "   ✅ Datos verificados correctamente\n";
            
            // Limpiar datos de prueba
            $conn->query("DELETE FROM `{$dbName}`.`configuracion_sistema` WHERE clave = 'test_key'");
            echo "   🧹 Datos de prueba limpiados\n";
        } else {
            echo "   ❌ No se pudieron verificar los datos\n";
        }
    } else {
        echo "   ❌ Error en inserción: " . $conn->error . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Excepción: " . $e->getMessage() . "\n";
}

echo "\n🎯 Resultado del test:\n";
if ($result['success']) {
    echo "✅ Setup tables funcionando correctamente\n";
    echo "✅ Tabla configuracion_sistema creada y funcional\n";
    echo "✅ Step 4 del wizard debería funcionar ahora\n";
} else {
    echo "❌ Hay problemas con la creación de tablas\n";
    echo "🔧 Revisar permisos de base de datos\n";
    echo "🔧 Verificar que la base de datos existe\n";
}

echo "\n🚀 Test completado!\n";
?>