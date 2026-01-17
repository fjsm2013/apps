<?php

class ServiciosManager
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
    public function all(): array
    {
        return CrearConsulta(
            $this->db,
            "SELECT ID, Descripcion, Detalles
             FROM {$this->dbName}.servicios
             ORDER BY ID ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       GET ONE
    ========================= */
    public function find(int $id): ?array
    {
        return ObtenerPrimerRegistro(
            $this->db,
            "SELECT ID, Descripcion, Detalles
             FROM {$this->dbName}.servicios
             WHERE ID = ?",
            [$id]
        );
    }

    /* =========================
       CREATE
    ========================= */
    public function create(string $descripcion, string $detalles = ''): int
    {
        return EjecutarSQL(
            $this->db,
            "INSERT INTO {$this->dbName}.servicios (Descripcion, Detalles)
             VALUES (?, ?)",
            [$descripcion, $detalles]
        );
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(int $id, string $descripcion, string $detalles = ''): void
    {
        EjecutarSQL(
            $this->db,
            "UPDATE {$this->dbName}.servicios
             SET Descripcion = ?, Detalles = ?
             WHERE ID = ?",
            [$descripcion, $detalles, $id]
        );
    }

    /* =========================
       DELETE
    ========================= */
    public function delete(int $id): void
    {
        EjecutarSQL(
            $this->db,
            "DELETE FROM {$this->dbName}.servicios
             WHERE ID = ?",
            [$id]
        );
    }
    public function allWithPricesByCategoria(int $categoriaId): array
    {
        return CrearConsulta(
            $this->db,
            "SELECT s.ID,
                s.Descripcion,
                s.Detalles,
                COALESCE(p.Precio, 0) AS Precio
         FROM {$this->dbName}.servicios s
         LEFT JOIN {$this->dbName}.precios p
           ON p.ServicioID = s.ID
          AND p.TipoCategoriaID = ?
         ORDER BY s.ID ASC",
            [$categoriaId]
        )->fetch_all(MYSQLI_ASSOC);
    }
    public function getServiciosConPrecioPorCategoria(int $categoriaId): array
    {
        return CrearConsulta(
            $this->db,
            "SELECT 
            s.ID,
            s.Descripcion,
            s.Detalles,
            IFNULL(p.Precio, 0) AS Precio
         FROM {$this->dbName}.servicios s
         LEFT JOIN {$this->dbName}.precios p
           ON p.ServicioID = s.ID
          AND p.TipoCategoriaID = ?
         ORDER BY s.ID ASC",
            [$categoriaId]
        )->fetch_all(MYSQLI_ASSOC);
    }
}