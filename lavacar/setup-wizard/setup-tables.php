<?php
/**
 * Setup Wizard Database Tables
 * Creates the necessary tables for the setup wizard to work with the existing schema
 */

function createSetupTables($conn, $dbName) {
    try {
        // Create configuracion_sistema table first (most important for wizard)
        $sql = "CREATE TABLE IF NOT EXISTS `{$dbName}`.`configuracion_sistema` (
            `id` int NOT NULL AUTO_INCREMENT,
            `clave` varchar(100) NOT NULL,
            `valor` text DEFAULT NULL,
            `descripcion` text DEFAULT NULL,
            `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `clave_unique` (`clave`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if (!$conn->query($sql)) {
            throw new Exception("Error creating configuracion_sistema table: " . $conn->error);
        }
        
        // Create configuracion_empresa table
        $sql = "CREATE TABLE IF NOT EXISTS `{$dbName}`.`configuracion_empresa` (
            `id` int NOT NULL AUTO_INCREMENT,
            `nombre` varchar(150) NOT NULL,
            `eslogan` varchar(200) DEFAULT NULL,
            `telefono` varchar(50) NOT NULL,
            `email` varchar(100) DEFAULT NULL,
            `direccion` text DEFAULT NULL,
            `hora_apertura` time DEFAULT '08:00:00',
            `hora_cierre` time DEFAULT '18:00:00',
            `dias_laborales` varchar(100) DEFAULT 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            `capacidad_maxima` int DEFAULT 50,
            `tiempo_promedio` int DEFAULT 30,
            `moneda` varchar(10) DEFAULT 'CRC',
            `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
            `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        if (!$conn->query($sql)) {
            throw new Exception("Error creating configuracion_empresa table: " . $conn->error);
        }
        
        // Verify the table was created
        $checkTable = $conn->query("SHOW TABLES FROM `{$dbName}` LIKE 'configuracion_sistema'");
        if (!$checkTable || $checkTable->num_rows === 0) {
            throw new Exception("configuracion_sistema table was not created successfully");
        }
        
        return ['success' => true, 'message' => 'Tablas de configuración creadas correctamente'];
        
    } catch (Exception $e) {
        error_log("Setup tables error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error creando tablas: ' . $e->getMessage()];
    }
}

// Auto-run if called directly
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    session_start();
    require_once '../../lib/config.php';
    require_once '../lib/Auth.php';
    
    if (!isLoggedIn()) {
        die('No autorizado');
    }
    
    $user = userInfo();
    $dbName = $user['company']['db'];
    
    $result = createSetupTables($conn, $dbName);
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
?>