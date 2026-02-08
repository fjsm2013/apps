<?php

class CategoriaVehiculoManager
{
    private mysqli $db;
    private string $dbName;

    public function __construct(mysqli $conn, string $dbName)
    {
        $this->db = $conn;
        $this->dbName = $dbName;
    }

    /* =========================
       LIST
    ========================= */
    public function all(bool $onlyActive = true): array
    {
        $where = $onlyActive ? "WHERE Estado = 1" : "";

        return CrearConsulta(
            $this->db,
            "SELECT ID, TipoVehiculo, Imagen, Estado, OrdenClasificacion
             FROM {$this->dbName}.categoriavehiculo
             $where
             ORDER BY OrdenClasificacion ASC, ID ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       GET ONE
    ========================= */
    public function find(int $id): ?array
    {
        return ObtenerPrimerRegistro(
            $this->db,
            "SELECT ID, TipoVehiculo, Imagen, Estado, OrdenClasificacion
             FROM {$this->dbName}.categoriavehiculo
             WHERE ID = ?",
            [$id]
        );
    }

    /* =========================
       CREATE
    ========================= */
    public function create(
        string $tipoVehiculo,
        string $imagen,
        int $estado = 1,
        int $orden = 0
    ): void {
        EjecutarSQL(
            $this->db,
            "INSERT INTO {$this->dbName}.categoriavehiculo
             (TipoVehiculo, Imagen, Estado, OrdenClasificacion)
             VALUES (?, ?, ?, ?)",
            [$tipoVehiculo, $imagen, $estado, $orden]
        );
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(
        int $id,
        string $tipoVehiculo,
        string $imagen,
        int $estado,
        int $orden
    ): void {
        EjecutarSQL(
            $this->db,
            "UPDATE {$this->dbName}.categoriavehiculo
             SET TipoVehiculo = ?,
                 Imagen = ?,
                 Estado = ?,
                 OrdenClasificacion = ?
             WHERE ID = ?",
            [$tipoVehiculo, $imagen, $estado, $orden, $id]
        );
    }

    /* =========================
       CHANGE STATUS
    ========================= */
    public function toggleStatus(int $id): void
    {
        EjecutarSQL(
            $this->db,
            "UPDATE {$this->dbName}.categoriavehiculo
             SET Estado = IF(Estado = 1, 0, 1)
             WHERE ID = ?",
            [$id]
        );
    }

    /* =========================
       CHECK IF CAN DELETE
    ========================= */
    public function canDelete(int $id): array
    {
        $result = [
            'can_delete' => true,
            'reason' => '',
            'details' => []
        ];

        // Check precios
        $preciosCount = ObtenerPrimerRegistro(
            $this->db,
            "SELECT COUNT(*) as total
             FROM {$this->dbName}.precios
             WHERE TipoCategoriaID = ?",
            [$id]
        )['total'] ?? 0;

        if ($preciosCount > 0) {
            $result['can_delete'] = false;
            $result['reason'] = 'Tiene precios asociados';
            $result['details'][] = "$preciosCount precio(s) configurado(s)";
        }

        // Check vehiculos
        $vehiculosCount = ObtenerPrimerRegistro(
            $this->db,
            "SELECT COUNT(*) as total
             FROM {$this->dbName}.vehiculos
             WHERE CategoriaVehiculo = ?",
            [$id]
        )['total'] ?? 0;

        if ($vehiculosCount > 0) {
            $result['can_delete'] = false;
            $result['reason'] = 'Tiene vehículos registrados';
            $result['details'][] = "$vehiculosCount vehículo(s) registrado(s)";
        }

        // Check ordenes
        $ordenesCount = ObtenerPrimerRegistro(
            $this->db,
            "SELECT COUNT(DISTINCT o.ID) as total
             FROM {$this->dbName}.ordenes o
             INNER JOIN {$this->dbName}.vehiculos v ON o.VehiculoID = v.ID
             WHERE v.CategoriaVehiculo = ?",
            [$id]
        )['total'] ?? 0;

        if ($ordenesCount > 0) {
            $result['can_delete'] = false;
            $result['reason'] = 'Tiene órdenes asociadas';
            $result['details'][] = "$ordenesCount orden(es) asociada(s)";
        }

        return $result;
    }

    /* =========================
       DELETE
       (safe: prevents deleting if in use)
    ========================= */
    public function delete(int $id): void
    {
        // Check if category is used in precios
        $usedInPrecios = ObtenerPrimerRegistro(
            $this->db,
            "SELECT ID
             FROM {$this->dbName}.precios
             WHERE TipoCategoriaID = ?
             LIMIT 1",
            [$id]
        );

        if ($usedInPrecios) {
            throw new Exception("No se puede eliminar: la categoría tiene precios asociados.");
        }

        // Check if category is used in vehiculos
        $usedInVehiculos = ObtenerPrimerRegistro(
            $this->db,
            "SELECT ID
             FROM {$this->dbName}.vehiculos
             WHERE CategoriaVehiculo = ?
             LIMIT 1",
            [$id]
        );

        if ($usedInVehiculos) {
            throw new Exception("No se puede eliminar: existen vehículos registrados con esta categoría.");
        }

        // Check if category is used in ordenes (through vehiculos)
        $usedInOrdenes = ObtenerPrimerRegistro(
            $this->db,
            "SELECT o.ID
             FROM {$this->dbName}.ordenes o
             INNER JOIN {$this->dbName}.vehiculos v ON o.VehiculoID = v.ID
             WHERE v.CategoriaVehiculo = ?
             LIMIT 1",
            [$id]
        );

        if ($usedInOrdenes) {
            throw new Exception("No se puede eliminar: existen órdenes asociadas a vehículos de esta categoría.");
        }

        // Safe to delete
        EjecutarSQL(
            $this->db,
            "DELETE FROM {$this->dbName}.categoriavehiculo
             WHERE ID = ?",
            [$id]
        );
    }
}