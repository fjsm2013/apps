<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Debug: Órdenes Activas</h2>";
echo "<p><strong>Usuario:</strong> " . htmlspecialchars($user['name']) . "</p>";
echo "<p><strong>Empresa:</strong> " . htmlspecialchars($user['company']['name']) . "</p>";
echo "<p><strong>Base de datos:</strong> " . htmlspecialchars($dbName) . "</p>";

// Verificar si la base de datos existe
try {
    $dbExistsQuery = "SHOW DATABASES LIKE '{$dbName}'";
    $dbExistsResult = $conn->query($dbExistsQuery);
    
    if ($dbExistsResult->num_rows > 0) {
        echo "<p>✅ Base de datos '{$dbName}' existe</p>";
        
        // Mostrar todas las tablas en la base de datos
        $tablesQuery = "SHOW TABLES FROM `{$dbName}`";
        $tablesResult = $conn->query($tablesQuery);
        
        echo "<h4>Tablas en la base de datos:</h4>";
        if ($tablesResult->num_rows > 0) {
            echo "<ul>";
            while ($table = $tablesResult->fetch_array()) {
                $tableName = $table[0];
                echo "<li><strong>{$tableName}</strong>";
                
                // Contar registros en cada tabla
                try {
                    $countQuery = "SELECT COUNT(*) as total FROM `{$dbName}`.`{$tableName}`";
                    $countResult = $conn->query($countQuery);
                    $count = $countResult->fetch_assoc()['total'];
                    echo " - {$count} registros";
                } catch (Exception $e) {
                    echo " - Error contando: " . $e->getMessage();
                }
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>❌ No hay tablas en la base de datos {$dbName}</p>";
        }
        
    } else {
        echo "<p>❌ Base de datos '{$dbName}' NO existe</p>";
        echo "<p>Esto significa que el tenant no ha sido inicializado correctamente.</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error verificando base de datos: " . $e->getMessage() . "</p>";
}

// Verificar si OrdenManager existe
echo "<h4>Verificación de OrdenManager:</h4>";
if (file_exists('lavacar/backend/OrdenManager.php')) {
    echo "<p>✅ Archivo OrdenManager.php existe</p>";
    
    try {
        require_once 'lavacar/backend/OrdenManager.php';
        $ordenManager = new OrdenManager($conn, $dbName);
        echo "<p>✅ OrdenManager se instanció correctamente</p>";
        
        $ordenes = $ordenManager->getActive();
        echo "<p><strong>Órdenes activas obtenidas por OrdenManager:</strong> " . count($ordenes) . "</p>";
        
        if (!empty($ordenes)) {
            echo "<h5>Primeras 3 órdenes activas:</h5>";
            echo "<pre>";
            print_r(array_slice($ordenes, 0, 3));
            echo "</pre>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Error al usar OrdenManager: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Archivo OrdenManager.php NO existe</p>";
}

echo "<hr>";
echo "<p><a href='lavacar/reportes/ordenes-activas.php'>Ir a Órdenes Activas</a></p>";
echo "<p><a href='lavacar/dashboard.php'>Volver al Dashboard</a></p>";
?>