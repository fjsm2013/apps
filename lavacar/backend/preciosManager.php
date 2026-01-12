<?php

class PreciosManager
{
    private mysqli $db;
    private string $dbName;

    public function __construct(mysqli $conn, string $dbName)
    {
        $this->db = $conn;
        $this->dbName = $dbName;
    }

    /* =========================
       GET PRICES BY SERVICE
       (indexed by category ID)
    ========================= */
    public function byServicioIndexed(int $servicioId): array
    {
        $rows = CrearConsulta(
            $this->db,
            "SELECT TipoCategoriaID, Precio
             FROM {$this->dbName}.precios
             WHERE ServicioID = ?",
            [$servicioId]
        )->fetch_all(MYSQLI_ASSOC);

        $indexed = [];

        foreach ($rows as $r) {
            $indexed[(int)$r['TipoCategoriaID']] = (float)$r['Precio'];
        }

        return $indexed;
    }

    /* =========================
       SAVE / UPDATE PRICE
    ========================= */
    public function save(
        int $servicioId,
        int $categoriaId,
        float $precio
    ): void {
        EjecutarSQL(
            $this->db,
            "INSERT INTO {$this->dbName}.precios
                (ServicioID, TipoCategoriaID, Precio)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE
                Precio = VALUES(Precio)",
            [$servicioId, $categoriaId, $precio]
        );
    }

    /* =========================
       DELETE PRICES BY SERVICE
    ========================= */
    public function deleteByServicio(int $servicioId): void
    {
        EjecutarSQL(
            $this->db,
            "DELETE FROM {$this->dbName}.precios
             WHERE ServicioID = ?",
            [$servicioId]
        );
    }
}