<?php

require_once 'BaseManager.php';

class VehiculosManager extends BaseManager
{
    protected array $fillable = [
        'ClienteID', 'Marca', 'Modelo', 'Year', 'Placa',
        'CategoriaVehiculo', 'Color', 'Observaciones', 'active'
    ];

    public function __construct(mysqli $conn, string $dbName)
    {
        parent::__construct($conn, $dbName, 'vehiculos');
    }

    /* =========================
       GET ALL WITH RELATIONSHIPS
    ========================= */
    public function getAllWithRelations(string $search = '', string $status = 'all'): array
    {
        $whereConditions = [];
        $params = [];

        // Búsqueda por placa, marca, modelo o cliente
        if (!empty($search)) {
            $whereConditions[] = "(v.Placa LIKE ? OR v.Marca LIKE ? OR v.Modelo LIKE ? OR c.NombreCompleto LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        // Filtro por estado
        if ($status === 'active') {
            $whereConditions[] = "v.active = 1";
        } elseif ($status === 'inactive') {
            $whereConditions[] = "v.active = 0";
        }

        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

        return $this->query(
            "SELECT v.*,
                    c.NombreCompleto as cliente_nombre,
                    c.Telefono as cliente_telefono,
                    cv.TipoVehiculo as categoria_nombre,
                    COUNT(DISTINCT o.ID) as total_ordenes,
                    MAX(o.FechaIngreso) as ultima_orden,
                    SUM(CASE WHEN o.Estado >= 3 THEN o.Monto ELSE 0 END) as total_gastado
             FROM {$this->dbName}.vehiculos v
             LEFT JOIN {$this->dbName}.clientes c ON c.ID = v.ClienteID
             LEFT JOIN {$this->dbName}.categoriavehiculo cv ON cv.ID = v.CategoriaVehiculo
             LEFT JOIN {$this->dbName}.ordenes o ON o.VehiculoID = v.ID
             {$whereClause}
             GROUP BY v.ID
             ORDER BY v.Placa ASC",
            $params
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       FIND BY CLIENT
    ========================= */
    public function findByCliente(int $clienteId): array
    {
        return $this->query(
            "SELECT v.*, cv.TipoVehiculo as categoria_nombre
             FROM {$this->dbName}.vehiculos v
             LEFT JOIN {$this->dbName}.categoriavehiculo cv ON cv.ID = v.CategoriaVehiculo
             WHERE v.ClienteID = ? AND v.active = 1
             ORDER BY v.Placa ASC",
            [$clienteId]
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       FIND BY PLATE
    ========================= */
    public function findByPlaca(string $placa): ?array
    {
        return $this->queryFirst(
            "SELECT v.*, cv.TipoVehiculo as categoria_nombre, c.NombreCompleto as cliente_nombre
             FROM {$this->dbName}.vehiculos v
             LEFT JOIN {$this->dbName}.categoriavehiculo cv ON cv.ID = v.CategoriaVehiculo
             LEFT JOIN {$this->dbName}.clientes c ON c.ID = v.ClienteID
             WHERE v.Placa = ? AND v.active = 1",
            [$placa]
        );
    }

    /* =========================
       GET STATISTICS
    ========================= */
    public function getStats(): array
    {
        $basicStats = $this->getBasicStats();
        
        $conOrdenes = $this->queryValue(
            "SELECT COUNT(DISTINCT v.ID) FROM {$this->dbName}.vehiculos v
             JOIN {$this->dbName}.ordenes o ON o.VehiculoID = v.ID
             WHERE v.active = 1"
        ) ?: 0;

        $nuevosEstesMes = $this->queryValue(
            "SELECT COUNT(*) FROM {$this->dbName}.vehiculos 
             WHERE DATE(FechaRegistro) >= DATE_FORMAT(NOW(), '%Y-%m-01') AND active = 1"
        ) ?: 0;

        $categorias = $this->query(
            "SELECT cv.TipoVehiculo, COUNT(v.ID) as total
             FROM {$this->dbName}.categoriavehiculo cv
             LEFT JOIN {$this->dbName}.vehiculos v ON v.CategoriaVehiculo = cv.ID AND v.active = 1
             GROUP BY cv.ID, cv.TipoVehiculo
             ORDER BY total DESC
             LIMIT 5"
        )->fetch_all(MYSQLI_ASSOC);

        return [
            'total_vehiculos' => $basicStats['total'],
            'vehiculos_activos' => $basicStats['active'],
            'con_ordenes' => $conOrdenes,
            'nuevos_mes' => $nuevosEstesMes,
            'top_categorias' => $categorias
        ];
    }

    /* =========================
       GET VEHICLE HISTORY
    ========================= */
    public function getVehicleHistory(int $vehicleId): array
    {
        return $this->query(
            "SELECT o.*, 
                    c.NombreCompleto as cliente_nombre,
                    CASE 
                        WHEN o.Estado = 1 THEN 'Pendiente'
                        WHEN o.Estado = 2 THEN 'En Proceso'
                        WHEN o.Estado = 3 THEN 'Terminado'
                        ELSE 'Cancelado'
                    END as estado_texto
             FROM {$this->dbName}.ordenes o
             LEFT JOIN {$this->dbName}.clientes c ON c.ID = o.ClienteID
             WHERE o.VehiculoID = ?
             ORDER BY o.FechaIngreso DESC
             LIMIT 20",
            [$vehicleId]
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       GET CATEGORIES FOR DROPDOWN
    ========================= */
    public function getCategories(): array
    {
        return $this->query(
            "SELECT ID, TipoVehiculo as nombre FROM {$this->dbName}.categoriavehiculo 
             WHERE Estado = 1 ORDER BY TipoVehiculo ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       SOFT DELETE (LEGACY METHOD)
    ========================= */
    public function deactivate(int $id): bool
    {
        return $this->update($id, ['active' => 0]);
    }
}