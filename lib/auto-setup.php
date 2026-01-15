<?php
/**
 * FROSH Auto-Setup System
 * Automatically creates the master database if it doesn't exist
 * This runs only once when the system is first deployed
 */

// Prevent direct access
if (!defined('FROSH_SETUP_RUNNING')) {
    die('Direct access not permitted');
}

class AutoSetup {
    private $conn;
    private $masterDbName = 'frosh_lavacar';
    private $setupCompleteFile = __DIR__ . '/.setup_complete';
    
    public function __construct($dbHost, $dbUser, $dbPass) {
        // Connect without selecting a database
        $this->conn = new mysqli($dbHost, $dbUser, $dbPass);
        
        if ($this->conn->connect_error) {
            throw new Exception("Database connection failed: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }
    
    /**
     * Check if setup has already been completed
     */
    public function isSetupComplete() {
        return file_exists($this->setupCompleteFile);
    }
    
    /**
     * Check if master database exists
     */
    public function masterDatabaseExists() {
        $result = $this->conn->query("SHOW DATABASES LIKE '{$this->masterDbName}'");
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Create master database and tables
     */
    public function createMasterDatabase() {
        try {
            // Create database
            $sql = "CREATE DATABASE IF NOT EXISTS `{$this->masterDbName}` 
                    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            
            if (!$this->conn->query($sql)) {
                throw new Exception("Error creating database: " . $this->conn->error);
            }
            
            // Select the database
            $this->conn->select_db($this->masterDbName);
            
            // Read and execute schema
            $schemaFile = __DIR__ . '/schema/master.sql';
            if (!file_exists($schemaFile)) {
                throw new Exception("Schema file not found: {$schemaFile}");
            }
            
            $schema = file_get_contents($schemaFile);
            
            // Remove MySQL dump comments and settings
            $schema = preg_replace('/^\/\*!.*?\*\/;?\s*$/m', '', $schema);
            $schema = preg_replace('/^--.*$/m', '', $schema);
            $schema = preg_replace('/^LOCK TABLES.*?UNLOCK TABLES;/ms', '', $schema);
            
            // Split into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $schema)),
                function($stmt) {
                    return !empty($stmt) && 
                           !preg_match('/^(SET|DELIMITER|\/\*|DROP TABLE IF EXISTS)/i', $stmt);
                }
            );
            
            // Execute each statement
            foreach ($statements as $statement) {
                if (!empty(trim($statement))) {
                    if (!$this->conn->query($statement)) {
                        // Log error but continue (some statements might fail if tables exist)
                        error_log("Warning executing statement: " . $this->conn->error);
                    }
                }
            }
            
            // Create essential tables if they don't exist
            $this->createEssentialTables();
            
            // Mark setup as complete
            $this->markSetupComplete();
            
            return true;
            
        } catch (Exception $e) {
            error_log("Auto-setup error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create essential tables with minimal structure
     */
    private function createEssentialTables() {
        $tables = [
            // Empresas table
            "CREATE TABLE IF NOT EXISTS `empresas` (
                `id_empresa` int NOT NULL AUTO_INCREMENT,
                `nombre` varchar(100) NOT NULL,
                `ruc_identificacion` varchar(20) DEFAULT NULL,
                `telefono` varchar(20) DEFAULT NULL,
                `email` varchar(100) NOT NULL,
                `pais` varchar(50) DEFAULT NULL,
                `ciudad` varchar(50) DEFAULT NULL,
                `nombre_base_datos` varchar(50) DEFAULT NULL,
                `estado` enum('activo','inactivo','pendiente') DEFAULT 'pendiente',
                `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_empresa`),
                UNIQUE KEY `email` (`email`),
                UNIQUE KEY `nombre_base_datos` (`nombre_base_datos`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Users table
            "CREATE TABLE IF NOT EXISTS `users` (
                `id` int NOT NULL AUTO_INCREMENT,
                `empresa_id` int DEFAULT '0',
                `username` varchar(100) NOT NULL,
                `email` varchar(100) NOT NULL,
                `password_hash` varchar(100) NOT NULL,
                `first_name` varchar(100) DEFAULT NULL,
                `last_name` varchar(100) DEFAULT NULL,
                `role` int DEFAULT '1',
                `department` varchar(100) DEFAULT NULL,
                `is_active` tinyint(1) DEFAULT '1',
                `last_login` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `username` (`username`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Planes table
            "CREATE TABLE IF NOT EXISTS `planes` (
                `id_plan` int NOT NULL AUTO_INCREMENT,
                `nombre` varchar(50) NOT NULL,
                `descripcion` text,
                `precio_mensual` decimal(10,2) NOT NULL,
                `precio_anual` decimal(10,2) DEFAULT NULL,
                `moneda` varchar(3) DEFAULT 'USD',
                `max_vehiculos` int DEFAULT NULL,
                `max_usuarios` int DEFAULT '1',
                `max_sucursales` int DEFAULT '1',
                `tipo_base_datos` enum('compartida','dedicada') DEFAULT 'compartida',
                `limite_almacenamiento_gb` int DEFAULT '1',
                `incluye_soporte_prioritario` tinyint(1) DEFAULT '0',
                `incluye_api_acceso` tinyint(1) DEFAULT '0',
                `incluye_reportes_avanzados` tinyint(1) DEFAULT '0',
                `estado` enum('activo','inactivo') DEFAULT 'activo',
                `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_plan`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Suscripciones table
            "CREATE TABLE IF NOT EXISTS `suscripciones` (
                `id_suscripcion` int NOT NULL AUTO_INCREMENT,
                `id_empresa` int NOT NULL,
                `id_plan` int NOT NULL,
                `ciclo_facturacion` enum('mensual','anual') DEFAULT 'mensual',
                `fecha_inicio` date NOT NULL,
                `fecha_fin` date NOT NULL,
                `fecha_proximo_pago` date DEFAULT NULL,
                `estado` enum('activa','pendiente','cancelada','vencida') DEFAULT 'pendiente',
                `metodo_pago` enum('tarjeta','transferencia','paypal') DEFAULT NULL,
                `precio_actual` decimal(10,2) NOT NULL,
                `renovacion_automatica` tinyint(1) DEFAULT '1',
                PRIMARY KEY (`id_suscripcion`),
                KEY `id_empresa` (`id_empresa`),
                KEY `id_plan` (`id_plan`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Password reset tokens table
            "CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
                `id` int NOT NULL AUTO_INCREMENT,
                `user_id` int NOT NULL,
                `token` varchar(64) NOT NULL,
                `expires_at` datetime NOT NULL,
                `used` tinyint(1) DEFAULT '0',
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `token` (`token`),
                KEY `user_id` (`user_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Roles table
            "CREATE TABLE IF NOT EXISTS `roles` (
                `ID` int NOT NULL AUTO_INCREMENT,
                `Descripcion` varchar(90) DEFAULT '0',
                `Reportes` tinyint DEFAULT '0',
                `Usuarios` tinyint DEFAULT '0',
                `Clientes` tinyint DEFAULT '0',
                `Ordenes` tinyint DEFAULT '0',
                `Actividad` tinyint DEFAULT '0',
                `Financiero` tinyint DEFAULT '0',
                PRIMARY KEY (`ID`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"
        ];
        
        foreach ($tables as $sql) {
            if (!$this->conn->query($sql)) {
                error_log("Error creating table: " . $this->conn->error);
            }
        }
        
        // Insert default plans if they don't exist
        $this->insertDefaultPlans();
        
        // Insert default roles if they don't exist
        $this->insertDefaultRoles();
    }
    
    /**
     * Insert default subscription plans
     */
    private function insertDefaultPlans() {
        $checkPlans = $this->conn->query("SELECT COUNT(*) as count FROM planes");
        if ($checkPlans && $checkPlans->fetch_assoc()['count'] == 0) {
            $plans = [
                ['Bronce', 'Plan básico para pequeños negocios', 29.99, 299.99, 50, 2, 1],
                ['Plata', 'Plan para negocios en crecimiento', 79.99, 799.99, 200, 5, 5],
                ['Oro', 'Plan premium para empresas', 199.99, 1999.99, 1000, 20, 20]
            ];
            
            $stmt = $this->conn->prepare(
                "INSERT INTO planes (nombre, descripcion, precio_mensual, precio_anual, max_vehiculos, max_usuarios, limite_almacenamiento_gb) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            
            foreach ($plans as $plan) {
                $stmt->bind_param('ssddiis', ...$plan);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
    
    /**
     * Insert default roles
     */
    private function insertDefaultRoles() {
        $checkRoles = $this->conn->query("SELECT COUNT(*) as count FROM roles");
        if ($checkRoles && $checkRoles->fetch_assoc()['count'] == 0) {
            $roles = [
                ['Administrador', 1, 1, 1, 1, 1, 1],
                ['Asistente', 0, 0, 1, 1, 1, 0],
                ['Operador', 0, 0, 0, 1, 1, 0]
            ];
            
            $stmt = $this->conn->prepare(
                "INSERT INTO roles (Descripcion, Reportes, Usuarios, Clientes, Ordenes, Actividad, Financiero) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            
            foreach ($roles as $role) {
                $stmt->bind_param('siiiiii', ...$role);
                $stmt->execute();
            }
            $stmt->close();
        }
    }
    
    /**
     * Mark setup as complete
     */
    public function markSetupComplete() {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->setupCompleteFile, $timestamp);
    }
    
    /**
     * Get setup status
     */
    public function getSetupStatus() {
        return [
            'setup_complete' => $this->isSetupComplete(),
            'master_db_exists' => $this->masterDatabaseExists(),
            'setup_file' => $this->setupCompleteFile
        ];
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
