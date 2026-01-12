<?php
class CompanyRegistry {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Company Information Methods
    public function getCompanyInfo() {
        $query = "SELECT * FROM company_info ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCompanyInfo($data) {
        $query = "UPDATE company_info SET 
                  company_name = :company_name,
                  legal_name = :legal_name,
                  address = :address,
                  city = :city,
                  state = :state,
                  country = :country,
                  postal_code = :postal_code,
                  phone = :phone,
                  email = :email,
                  website = :website,
                  tax_id = :tax_id,
                  fiscal_year_start = :fiscal_year_start,
                  fiscal_year_end = :fiscal_year_end,
                  timezone = :timezone,
                  currency = :currency,
                  updated_at = NOW()
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    // Department Methods
    public function getDepartments($active_only = true) {
        $query = "SELECT d.*, 
                  m.first_name as manager_first, m.last_name as manager_last,
                  p.department_name as parent_department
                  FROM departments d
                  LEFT JOIN users m ON d.manager_id = m.id
                  LEFT JOIN departments p ON d.parent_department_id = p.id";
        
        if($active_only) {
            $query .= " WHERE d.is_active = 1";
        }
        
        $query .= " ORDER BY d.department_name";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDepartment($data) {
        $query = "INSERT INTO departments 
                  (department_name, department_code, description, manager_id, parent_department_id, is_active)
                  VALUES (:department_name, :department_code, :description, :manager_id, :parent_department_id, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    // System Methods
    public function getSystems($filters = []) {
        $query = "SELECT s.*,
          so.first_name as system_owner_first, so.last_name as system_owner_last,
          t_own.first_name as tech_owner_first, t_own.last_name as tech_owner_last,
          d.department_name
          FROM systems s
          LEFT JOIN users so ON s.system_owner_id = so.id
          LEFT JOIN users t_own ON s.technical_owner_id = t_own.id
          LEFT JOIN departments d ON s.department_id = d.id
          WHERE 1=1";
        
        if(isset($filters['status'])) {
            $query .= " AND s.status = :status";
        }
        if(isset($filters['department_id'])) {
            $query .= " AND s.department_id = :department_id";
        }
        
        $query .= " ORDER BY s.system_name";
        
        $stmt = $this->conn->prepare($query);
        
        if(isset($filters['status'])) {
            $stmt->bindParam(":status", $filters['status']);
        }
        if(isset($filters['department_id'])) {
            $stmt->bindParam(":department_id", $filters['department_id']);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Data Classification Methods
    public function getDataClassifications() {
        $query = "SELECT * FROM data_classifications ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compliance Framework Methods
    public function getComplianceFrameworks($filters = []) {
        $query = "SELECT * FROM compliance_frameworks WHERE 1=1";
        
        if(isset($filters['status'])) {
            $query .= " AND status = :status";
        }
        
        $query .= " ORDER BY framework_name";
        
        $stmt = $this->conn->prepare($query);
        
        if(isset($filters['status'])) {
            $stmt->bindParam(":status", $filters['status']);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Dashboard Statistics
    public function getRegistryStats() {
        $stats = [];
        
        // Department count
        $query = "SELECT COUNT(*) as count FROM departments WHERE is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['department_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // System count by status
        $query = "SELECT status, COUNT(*) as count FROM systems GROUP BY status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['system_counts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Framework count
        $query = "SELECT COUNT(*) as count FROM compliance_frameworks";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['framework_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return $stats;
    }
}
?>