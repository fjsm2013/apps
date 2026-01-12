<?php

require_once 'BaseManager.php';

class ClientesManager extends BaseManager
{
    protected array $fillable = [
        'NombreCompleto', 'Cedula', 'Empresa', 'Correo', 'Telefono',
        'Direccion', 'Distrito', 'Canton', 'Provincia', 'Pais', 'IVA', 'active'
    ];

    public function __construct(mysqli $conn, string $dbName)
    {
        parent::__construct($conn, $dbName, 'clientes');
    }

    /* =========================
       LIST WITH SEARCH AND VEHICLES INFO
    ========================= */
    public function getAllWithVehicles(string $search = '', string $status = 'all'): array
    {
        $whereConditions = [];
        $params = [];

        // Búsqueda por nombre o cédula
        if (!empty($search)) {
            $whereConditions[] = "(c.NombreCompleto LIKE ? OR c.Cedula LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        // Filtro por estado
        if ($status === 'active') {
            $whereConditions[] = "c.active = 1";
        } elseif ($status === 'inactive') {
            $whereConditions[] = "c.active = 0";
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        return $this->query(
            "SELECT c.*,
                    COUNT(DISTINCT v.ID) as total_vehiculos,
                    GROUP_CONCAT(DISTINCT CONCAT(v.Marca, ' ', v.Modelo, ' (', v.Placa, ')') SEPARATOR ', ') as vehiculos_principales,
                    MAX(o.FechaIngreso) as ultima_visita
             FROM {$this->dbName}.clientes c
             LEFT JOIN {$this->dbName}.vehiculos v ON v.ClienteID = c.ID
             LEFT JOIN {$this->dbName}.ordenes o ON o.ClienteID = c.ID AND o.Estado >= 3
             {$whereClause}
             GROUP BY c.ID
             ORDER BY c.NombreCompleto ASC",
            $params
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       GET STATISTICS
    ========================= */
    public function getStats(): array
    {
        $basicStats = $this->getBasicStats();
        
        $totalVehiculos = $this->queryValue(
            "SELECT COUNT(*) FROM {$this->dbName}.vehiculos"
        ) ?: 0;

        $nuevosEstesMes = $this->queryValue(
            "SELECT COUNT(*) FROM {$this->dbName}.clientes 
             WHERE DATE(FechaRegistro) >= DATE_FORMAT(NOW(), '%Y-%m-01') AND active = 1"
        ) ?: 0;

        return [
            'total_clientes' => $basicStats['total'],
            'clientes_activos' => $basicStats['active'],
            'total_vehiculos' => $totalVehiculos,
            'nuevos_mes' => $nuevosEstesMes
        ];
    }

    /* =========================
       GET CLIENT VEHICLES
    ========================= */
    public function getClientVehicles(int $clienteId): array
    {
        return $this->query(
            "SELECT v.*,
                    COUNT(DISTINCT o.ID) as total_ordenes,
                    MAX(o.FechaIngreso) as ultima_orden
             FROM {$this->dbName}.vehiculos v
             LEFT JOIN {$this->dbName}.ordenes o ON o.VehiculoID = v.ID AND o.Estado >= 3
             WHERE v.ClienteID = ?
             GROUP BY v.ID
             ORDER BY v.Placa ASC",
            [$clienteId]
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       FIND BY CEDULA
    ========================= */
    public function findByCedula(string $cedula): ?array
    {
        return $this->findWhere(['Cedula' => $cedula, 'active' => 1]);
    }
}