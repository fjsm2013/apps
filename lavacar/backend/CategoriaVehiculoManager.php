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
       DELETE
       (safe: prevents deleting if in use)
    ========================= */
    public function delete(int $id): void
    {
        // Prevent delete if category is in use
        $used = ObtenerPrimerRegistro(
            $this->db,
            "SELECT ID
             FROM {$this->dbName}.precios
             WHERE TipoCategoriaID = ?
             LIMIT 1",
            [$id]
        );

        if ($used) {
            throw new Exception("No se puede eliminar la categoría porque está en uso.");
        }

        EjecutarSQL(
            $this->db,
            "DELETE FROM {$this->dbName}.categoriavehiculo
             WHERE ID = ?",
            [$id]
        );
    }
}