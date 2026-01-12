<?php
/**
 * FROSH Tenant Database Manager
 * Handles creation and management of tenant-specific databases
 */

class TenantDatabaseManager
{
    private $masterConn;
    private $dbHost;
    private $dbUser;
    private $dbPass;
    
    public function __construct($masterConnection, $host = 'localhost', $user = 'fmorgan', $pass = '4sf7xnah')
    {
        $this->masterConn = $masterConnection;
        $this->dbHost = $host;
        $this->dbUser = $user;
        $this->dbPass = $pass;
    }
    
    /**
     * Create a new tenant database from template
     */
    public function createTenantDatabase($companyId, $companyName)
    {
        try {
            $dbName = 'froshlav_' . $companyId;
            
            // Step 1: Create the database
            $this->createDatabase($dbName);
            
            // Step 2: Import the template schema
            $this->importTemplate($dbName);
            
            // Step 3: Insert initial data specific to this tenant
            $this->insertInitialData($dbName, $companyId, $companyName);
            
            // Step 4: Update the company record with the database name
            $this->updateCompanyDatabase($companyId, $dbName);
            
            return [
                'success' => true,
                'database' => $dbName,
                'message' => "Database created successfully for company: $companyName"
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => "Failed to create database for company: $companyName"
            ];
        }
    }
    
    /**
     * Create the physical database
     */
    private function createDatabase($dbName)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS `$dbName` 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_unicode_ci";
        
        if (!$this->masterConn->query($sql)) {
            throw new Exception("Failed to create database: " . $this->masterConn->error);
        }
    }
    
    /**
     * Import the template schema into the new database
     */
    private function importTemplate($dbName)
    {
        // Read the tenant template SQL file
        $templatePath = __DIR__ . '/schema/tenant.sql';
        
        if (!file_exists($templatePath)) {
            throw new Exception("Tenant template file not found: $templatePath");
        }
        
        $sql = file_get_contents($templatePath);
        
        if ($sql === false) {
            throw new Exception("Failed to read tenant template file");
        }
        
        // Connect to the new database
        $tenantConn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $dbName);
        
        if ($tenantConn->connect_error) {
            throw new Exception("Failed to connect to tenant database: " . $tenantConn->connect_error);
        }
        
        // Execute the SQL commands
        if (!$tenantConn->multi_query($sql)) {
            throw new Exception("Failed to import tenant template: " . $tenantConn->error);
        }
        
        // Process all results to avoid "Commands out of sync" error
        do {
            if ($result = $tenantConn->store_result()) {
                $result->free();
            }
        } while ($tenantConn->next_result());
        
        $tenantConn->close();
    }
    
    /**
     * Insert initial data specific to this tenant
     */
    private function insertInitialData($dbName, $companyId, $companyName)
    {
        // Connect to the tenant database
        $tenantConn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $dbName);
        
        if ($tenantConn->connect_error) {
            throw new Exception("Failed to connect to tenant database for initial data");
        }
        
        // Insert company information into the tenant database
        $stmt = $tenantConn->prepare("
            INSERT INTO empresas (id_empresa, nombre, estado, fecha_registro) 
            VALUES (?, ?, 'activo', NOW())
            ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)
        ");
        
        if ($stmt) {
            $stmt->bind_param("is", $companyId, $companyName);
            $stmt->execute();
            $stmt->close();
        }
        
        // Insert default categories for vehicle types
        $defaultCategories = [
            'Sedán',
            'SUV',
            'Pickup',
            'Hatchback',
            'Motocicleta',
            'Camión'
        ];
        
        $stmt = $tenantConn->prepare("INSERT INTO categoriavehiculo (TipoVehiculo) VALUES (?)");
        if ($stmt) {
            foreach ($defaultCategories as $category) {
                $stmt->bind_param("s", $category);
                $stmt->execute();
            }
            $stmt->close();
        }
        
        // Insert default service categories
        $defaultServices = [
            ['Lavado Básico', 'Lavado exterior básico'],
            ['Lavado Completo', 'Lavado exterior e interior'],
            ['Encerado', 'Aplicación de cera protectora'],
            ['Aspirado', 'Limpieza interior con aspiradora'],
            ['Detallado', 'Limpieza profunda y detallada']
        ];
        
        $stmt = $tenantConn->prepare("INSERT INTO categoriaservicios (Descripcion, Detalle) VALUES (?, ?)");
        if ($stmt) {
            foreach ($defaultServices as $service) {
                $stmt->bind_param("ss", $service[0], $service[1]);
                $stmt->execute();
            }
            $stmt->close();
        }
        
        $tenantConn->close();
    }
    
    /**
     * Update the company record in master database with the database name
     */
    private function updateCompanyDatabase($companyId, $dbName)
    {
        $stmt = $this->masterConn->prepare("
            UPDATE empresas 
            SET nombre_base_datos = ? 
            WHERE id_empresa = ?
        ");
        
        if ($stmt) {
            $stmt->bind_param("si", $dbName, $companyId);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Failed to update company database name");
        }
    }
    
    /**
     * Check if a tenant database exists
     */
    public function databaseExists($dbName)
    {
        $result = $this->masterConn->query("SHOW DATABASES LIKE '$dbName'");
        return $result && $result->num_rows > 0;
    }
    
    /**
     * Delete a tenant database (use with caution!)
     */
    public function deleteTenantDatabase($companyId)
    {
        $dbName = 'froshlav_' . $companyId;
        
        if ($this->databaseExists($dbName)) {
            $sql = "DROP DATABASE `$dbName`";
            
            if ($this->masterConn->query($sql)) {
                // Update company record to remove database name
                $stmt = $this->masterConn->prepare("
                    UPDATE empresas 
                    SET nombre_base_datos = NULL 
                    WHERE id_empresa = ?
                ");
                
                if ($stmt) {
                    $stmt->bind_param("i", $companyId);
                    $stmt->execute();
                    $stmt->close();
                }
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get tenant database connection
     */
    public function getTenantConnection($companyId)
    {
        $dbName = 'froshlav_' . $companyId;
        
        if (!$this->databaseExists($dbName)) {
            throw new Exception("Tenant database does not exist: $dbName");
        }
        
        $conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $dbName);
        
        if ($conn->connect_error) {
            throw new Exception("Failed to connect to tenant database: " . $conn->connect_error);
        }
        
        return $conn;
    }
}
?>