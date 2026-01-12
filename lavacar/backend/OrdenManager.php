<?php

class OrdenManager
{
    private mysqli $db;
    private string $dbName;

    public function __construct(mysqli $conn, string $dbName)
    {
        $this->db = $conn;
        $this->dbName = $dbName;
    }

    /* =========================
       CREATE ORDER
    ========================= */
    public function create(array $data): int
    {
        // Calcular totales
        $subtotal = 0;
        $serviciosData = [];
        
        foreach ($data['servicios'] as $servicio) {
            $subtotal += $servicio['precio'];
            $serviciosData[] = [
                'id' => $servicio['id'],
                'precio' => $servicio['precio'],
                'nombre' => $servicio['nombre'] ?? ''
            ];
        }
        
        $impuesto = $subtotal * 0.13; // 13% IVA
        $total = $subtotal + $impuesto;
        
        return EjecutarSQL(
            $this->db,
            "INSERT INTO {$this->dbName}.ordenes 
            (ClienteID, VehiculoID, Monto, Descuento, Impuesto, Estado, 
             FechaIngreso, Categoria, TipoServicio, ServiciosJSON, Observaciones)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)",
            [
                $data['cliente_id'],
                $data['vehiculo_id'],
                $total,
                $data['descuento'] ?? 0.00,
                $impuesto,
                $data['estado'] ?? 1,
                $data['categoria_id'],
                $data['tipo_servicio'] ?? 1,
                json_encode($serviciosData),
                $data['observaciones'] ?? ''
            ]
        );
    }

    /* =========================
       GET ACTIVE ORDERS
    ========================= */
    public function getActive(): array
    {
        $ordenes = CrearConsulta(
            $this->db,
            "SELECT o.*, 
                    COALESCE(c.NombreCompleto, 'Cliente no asignado') as ClienteNombre, 
                    COALESCE(v.Placa, 'Sin placa') as Placa
             FROM {$this->dbName}.ordenes o
             LEFT JOIN {$this->dbName}.clientes c ON c.ID = o.ClienteID
             LEFT JOIN {$this->dbName}.vehiculos v ON v.ID = o.VehiculoID
             WHERE o.Estado IN (1, 2, 3)
             ORDER BY o.FechaIngreso DESC",
            []
        )->fetch_all(MYSQLI_ASSOC);
        
        // Asegurar que los valores críticos no sean null
        foreach ($ordenes as &$orden) {
            $orden['ClienteNombre'] = $orden['ClienteNombre'] ?? 'Cliente no asignado';
            $orden['Placa'] = $orden['Placa'] ?? 'Sin placa';
            $orden['Monto'] = $orden['Monto'] ?? 0.00;
        }
        
        return $ordenes;
    }

    /* =========================
       GET ORDER BY ID
    ========================= */
    public function find(int $id): ?array
    {
        $orden = ObtenerPrimerRegistro(
            $this->db,
            "SELECT o.*, 
                    COALESCE(c.NombreCompleto, 'Cliente no asignado') as ClienteNombre, 
                    COALESCE(c.Correo, '') as ClienteCorreo,
                    COALESCE(v.Placa, 'Sin placa') as Placa, 
                    COALESCE(v.Marca, '') as Marca, 
                    COALESCE(v.Modelo, '') as Modelo, 
                    COALESCE(v.Year, '') as Year, 
                    COALESCE(v.Color, '') as Color,
                    COALESCE(cat.TipoVehiculo, 'Sin categoría') as TipoVehiculo
             FROM {$this->dbName}.ordenes o
             LEFT JOIN {$this->dbName}.clientes c ON c.ID = o.ClienteID
             LEFT JOIN {$this->dbName}.vehiculos v ON v.ID = o.VehiculoID
             LEFT JOIN {$this->dbName}.categoriavehiculo cat ON cat.ID = v.CategoriaVehiculo
             WHERE o.ID = ?",
            [$id]
        );
        
        if ($orden) {
            // Asegurar que los valores críticos no sean null
            $orden['ClienteNombre'] = $orden['ClienteNombre'] ?? 'Cliente no asignado';
            $orden['ClienteCorreo'] = $orden['ClienteCorreo'] ?? '';
            $orden['Placa'] = $orden['Placa'] ?? 'Sin placa';
            $orden['Marca'] = $orden['Marca'] ?? '';
            $orden['Modelo'] = $orden['Modelo'] ?? '';
            $orden['Year'] = $orden['Year'] ?? '';
            $orden['Color'] = $orden['Color'] ?? '';
            $orden['TipoVehiculo'] = $orden['TipoVehiculo'] ?? 'Sin categoría';
            $orden['Observaciones'] = $orden['Observaciones'] ?? '';
        }
        
        return $orden;
    }

    /* =========================
       UPDATE ORDER STATUS
    ========================= */
    public function updateStatus(int $id, int $estado): void
    {
        $fechaCampo = '';
        switch ($estado) {
            case 2: // En proceso
                $fechaCampo = ', FechaProceso = NOW()';
                break;
            case 3: // Terminado
                $fechaCampo = ', FechaTerminado = NOW()';
                break;
            case 4: // Cerrado
                $fechaCampo = ', FechaCierre = NOW()';
                break;
        }
        
        EjecutarSQL(
            $this->db,
            "UPDATE {$this->dbName}.ordenes 
             SET Estado = ? {$fechaCampo}
             WHERE ID = ?",
            [$estado, $id]
        );
    }

    /* =========================
       GET ORDER TOTALS
    ========================= */
    public function getTotals(int $id): array
    {
        $orden = $this->find($id);
        if (!$orden) {
            return ['subtotal' => 0, 'impuesto' => 0, 'descuento' => 0, 'total' => 0];
        }
        
        $total = (float)$orden['Monto'];
        $impuesto = (float)$orden['Impuesto'];
        $descuento = (float)$orden['Descuento'];
        $subtotal = $total - $impuesto + $descuento;
        
        return [
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'descuento' => $descuento,
            'total' => $total
        ];
    }

    /* =========================
       GET SERVICES FROM JSON
    ========================= */
    public function getServicios(int $id): array
    {
        $orden = $this->find($id);
        if (!$orden || !$orden['ServiciosJSON']) {
            return [];
        }
        
        $servicios = json_decode($orden['ServiciosJSON'], true) ?? [];
        
        // Asegurar que todos los servicios tengan nombre
        foreach ($servicios as &$servicio) {
            if (empty($servicio['nombre']) && !empty($servicio['id'])) {
                $servicioInfo = ObtenerPrimerRegistro(
                    $this->db,
                    "SELECT Descripcion FROM {$this->dbName}.servicios WHERE ID = ?",
                    [$servicio['id']]
                );
                if ($servicioInfo) {
                    $servicio['nombre'] = $servicioInfo['Descripcion'];
                } else {
                    $servicio['nombre'] = 'Servicio no encontrado';
                }
            }
            
            // Asegurar valores por defecto
            $servicio['nombre'] = $servicio['nombre'] ?? 'Sin nombre';
            $servicio['precio'] = $servicio['precio'] ?? 0;
        }
        
        return $servicios;
    }
}