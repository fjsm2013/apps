<?php
/**
 * Setup Wizard Functions
 * Contains all the processing and data retrieval functions for each step
 */

// Step 1: Empresa Functions
function processEmpresaStep($conn, $dbName, $data) {
    try {
        $diasLaborales = isset($data['dias_laborales']) ? implode(',', $data['dias_laborales']) : '';
        
        // Verificar si ya existe configuración
        $existe = CrearConsulta($conn, "SELECT COUNT(*) as count FROM `{$dbName}`.`configuracion_empresa`", []);
        $existeCount = $existe ? $existe->fetch_assoc()['count'] : 0;
        
        if ($existeCount > 0) {
            // Actualizar
            EjecutarSQL($conn, 
                "UPDATE `{$dbName}`.`configuracion_empresa` SET 
                 nombre = ?, eslogan = ?, telefono = ?, email = ?, direccion = ?,
                 hora_apertura = ?, hora_cierre = ?, dias_laborales = ?,
                 capacidad_maxima = ?, tiempo_promedio = ?, moneda = ?",
                [
                    $data['nombre_empresa'], $data['eslogan'], $data['telefono'], 
                    $data['email'], $data['direccion'], $data['hora_apertura'], 
                    $data['hora_cierre'], $diasLaborales, $data['capacidad_maxima'],
                    $data['tiempo_promedio'], $data['moneda']
                ]
            );
        } else {
            // Insertar
            EjecutarSQL($conn, 
                "INSERT INTO `{$dbName}`.`configuracion_empresa` 
                 (nombre, eslogan, telefono, email, direccion, hora_apertura, hora_cierre, 
                  dias_laborales, capacidad_maxima, tiempo_promedio, moneda) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $data['nombre_empresa'], $data['eslogan'], $data['telefono'], 
                    $data['email'], $data['direccion'], $data['hora_apertura'], 
                    $data['hora_cierre'], $diasLaborales, $data['capacidad_maxima'],
                    $data['tiempo_promedio'], $data['moneda']
                ]
            );
        }
        
        return ['success' => true, 'message' => 'Configuración de empresa guardada correctamente'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al guardar configuración: ' . $e->getMessage()];
    }
}

function getEmpresaData($conn, $dbName) {
    try {
        // Primero intentar obtener datos de la configuración del tenant
        $empresa = CrearConsulta($conn, "SELECT * FROM `{$dbName}`.`configuracion_empresa` LIMIT 1", []);
        $empresaData = $empresa ? $empresa->fetch_assoc() : [];
        
        // Si no hay datos en el tenant, pre-llenar con datos de la base padre
        if (empty($empresaData)) {
            // Obtener datos del usuario actual
            $user = userInfo();
            
            if ($user && isset($user['company'])) {
                // Obtener datos de la empresa desde la base padre
                $masterConn = $conn;
                $stmt = $masterConn->prepare("SELECT * FROM empresas WHERE id_empresa = ?");
                $stmt->bind_param("i", $user['company']['id']);
                $stmt->execute();
                $masterEmpresa = $stmt->get_result()->fetch_assoc();
                
                if ($masterEmpresa) {
                    // Pre-llenar con datos conocidos de la base padre
                    $empresaData = [
                        'nombre' => $masterEmpresa['nombre'] ?? '',
                        'eslogan' => '', // Específico de cada lavadero
                        'telefono' => $masterEmpresa['telefono'] ?? '',
                        'email' => $masterEmpresa['email'] ?? '',
                        'direccion' => '',
                        // Usar defaults de la empresa si están disponibles
                        'hora_apertura' => $masterEmpresa['hora_apertura_default'] ?? '08:00',
                        'hora_cierre' => $masterEmpresa['hora_cierre_default'] ?? '18:00',
                        'dias_laborales' => $masterEmpresa['dias_laborales_default'] ?? 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
                        'capacidad_maxima' => $masterEmpresa['capacidad_maxima_default'] ?? '50',
                        'tiempo_promedio' => $masterEmpresa['tiempo_promedio_default'] ?? '30',
                        'moneda' => $masterEmpresa['moneda_default'] ?? 'CRC'
                    ];
                    
                    // Construir dirección si hay ciudad y/o país
                    $direccionParts = [];
                    if (!empty($masterEmpresa['ciudad'])) {
                        $direccionParts[] = $masterEmpresa['ciudad'];
                    }
                    if (!empty($masterEmpresa['pais'])) {
                        $direccionParts[] = $masterEmpresa['pais'];
                    }
                    if (!empty($direccionParts)) {
                        $empresaData['direccion'] = implode(', ', $direccionParts);
                    }
                    
                    // Ajustar configuración según tipo de negocio si está disponible
                    if (!empty($masterEmpresa['tipo_negocio'])) {
                        switch ($masterEmpresa['tipo_negocio']) {
                            case 'lavadero_express':
                                $empresaData['capacidad_maxima'] = $masterEmpresa['capacidad_maxima_default'] ?? '100';
                                $empresaData['tiempo_promedio'] = $masterEmpresa['tiempo_promedio_default'] ?? '15';
                                break;
                            case 'detallado_premium':
                                $empresaData['capacidad_maxima'] = $masterEmpresa['capacidad_maxima_default'] ?? '20';
                                $empresaData['tiempo_promedio'] = $masterEmpresa['tiempo_promedio_default'] ?? '90';
                                break;
                            case 'cadena_lavaderos':
                                $empresaData['capacidad_maxima'] = $masterEmpresa['capacidad_maxima_default'] ?? '60';
                                $empresaData['tiempo_promedio'] = $masterEmpresa['tiempo_promedio_default'] ?? '30';
                                break;
                        }
                    }
                    
                    // Ajustar moneda según el país si no está configurada específicamente
                    if (empty($masterEmpresa['moneda_default']) && !empty($masterEmpresa['pais'])) {
                        $pais = strtolower($masterEmpresa['pais']);
                        if (strpos($pais, 'estados unidos') !== false || strpos($pais, 'usa') !== false) {
                            $empresaData['moneda'] = 'USD';
                        } elseif (strpos($pais, 'europa') !== false || strpos($pais, 'españa') !== false) {
                            $empresaData['moneda'] = 'EUR';
                        }
                        // Mantener CRC para Costa Rica y otros países centroamericanos
                    }
                }
            }
        }
        
        return ['empresa' => $empresaData ?: []];
    } catch (Exception $e) {
        error_log("Error en getEmpresaData: " . $e->getMessage());
        return ['empresa' => []];
    }
}

// Step 2: Servicios Functions
function processServiciosStep($conn, $dbName, $data) {
    try {
        $serviciosRecomendados = [
            // Servicios ya precargados (marcados por defecto)
            ['descripcion' => 'Lavado Exterior', 'detalle' => 'Lavado de la carrocería externa', 'precargado' => true],
            ['descripcion' => 'Limpieza Interior', 'detalle' => 'Limpieza completa del interior del vehículo', 'precargado' => true],
            ['descripcion' => 'Lavado Chasis', 'detalle' => 'Limpieza del chasis y bajos del vehículo', 'precargado' => true],
            // Servicios sugeridos adicionales
            ['descripcion' => 'Encerado', 'detalle' => 'Aplicación de cera protectora', 'precargado' => false],
            ['descripcion' => 'Pulido de Vidrios', 'detalle' => 'Pulido y limpieza especializada de vidrios', 'precargado' => false]
        ];
        
        // Procesar servicios seleccionados
        if (!empty($data['servicios_seleccionados'])) {
            foreach ($data['servicios_seleccionados'] as $index) {
                $servicio = $serviciosRecomendados[$index];
                
                // Verificar si el servicio ya existe
                $existeQuery = CrearConsulta($conn, 
                    "SELECT ID, Detalles FROM `{$dbName}`.`servicios` WHERE Descripcion = ?", 
                    [$servicio['descripcion']]
                );
                
                if ($existeQuery && $existeQuery->num_rows > 0) {
                    // El servicio existe, verificar si tiene detalles
                    $servicioExistente = $existeQuery->fetch_assoc();
                    
                    if (empty($servicioExistente['Detalles'])) {
                        // Actualizar con los detalles si no los tiene
                        EjecutarSQL($conn, 
                            "UPDATE `{$dbName}`.`servicios` SET Detalles = ? WHERE ID = ?",
                            [$servicio['detalle'], $servicioExistente['ID']]
                        );
                    }
                    // Si ya tiene detalles, no hacer nada (mantener los existentes)
                } else {
                    // El servicio no existe, crearlo nuevo
                    EjecutarSQL($conn, 
                        "INSERT INTO `{$dbName}`.`servicios` 
                         (Descripcion, Detalles, CategoriaServicioID) 
                         VALUES (?, ?, 1)",
                        [$servicio['descripcion'], $servicio['detalle']]
                    );
                }
            }
        }
        
        return ['success' => true, 'message' => 'Servicios configurados correctamente'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al configurar servicios: ' . $e->getMessage()];
    }
}

function getServiciosData($conn, $dbName) {
    try {
        // Obtener servicios de la tabla existente
        $servicios = CrearConsulta($conn, "SELECT * FROM `{$dbName}`.`servicios`", []);
        
        return [
            'servicios' => $servicios ? $servicios->fetch_all(MYSQLI_ASSOC) : []
        ];
    } catch (Exception $e) {
        return ['servicios' => []];
    }
}

// Step 3: Precios Functions
function processPreciosStep($conn, $dbName, $data) {
    try {
        // Obtener tipos de vehículo de la tabla categoriavehiculo existente
        $tiposVehiculo = CrearConsulta($conn, 
            "SELECT ID, TipoVehiculo FROM `{$dbName}`.`categoriavehiculo` WHERE Estado = 1", []
        );
        
        if (!$tiposVehiculo) {
            // Si no hay tipos de vehículo, simplemente continuar sin error
            return ['success' => true, 'message' => 'Paso de precios omitido - no hay tipos de vehículo configurados'];
        }
        
        $tipos = $tiposVehiculo->fetch_all(MYSQLI_ASSOC);
        
        // Limpiar precios existentes si se especifica
        if (!empty($data['limpiar_precios'])) {
            EjecutarSQL($conn, "DELETE FROM `{$dbName}`.`precios`", []);
        }
        
        // Procesar precios por servicio (opcional - puede estar vacío)
        if (!empty($data['precios'])) {
            foreach ($data['precios'] as $servicioId => $preciosPorTipo) {
                foreach ($preciosPorTipo as $tipoId => $precio) {
                    // Permitir precios de 0 o mayores (sin validación mínima)
                    if ($precio >= 0) {
                        // Usar la estructura de tu tabla precios existente
                        EjecutarSQL($conn, 
                            "INSERT INTO `{$dbName}`.`precios` 
                             (TipoCategoriaID, ServicioID, Precio, Descuento, Impuesto) 
                             VALUES (?, ?, ?, 0, 13)
                             ON DUPLICATE KEY UPDATE Precio = ?",
                            [$tipoId, $servicioId, $precio, $precio]
                        );
                    }
                }
            }
        }
        
        // Siempre retornar éxito, incluso si no se configuraron precios
        return ['success' => true, 'message' => 'Configuración de precios completada (puede configurar precios más tarde)'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al configurar precios: ' . $e->getMessage()];
    }
}

function getPreciosData($conn, $dbName) {
    try {
        // Obtener servicios de la tabla existente
        $servicios = CrearConsulta($conn, "SELECT * FROM `{$dbName}`.`servicios`", []);
        
        // Obtener tipos de vehículo de la tabla existente
        $tiposVehiculo = CrearConsulta($conn, 
            "SELECT ID, TipoVehiculo FROM `{$dbName}`.`categoriavehiculo` WHERE Estado = 1", []
        );
        
        // Obtener precios existentes
        $precios = CrearConsulta($conn, 
            "SELECT * FROM `{$dbName}`.`precios`", []
        );
        
        return [
            'servicios' => $servicios ? $servicios->fetch_all(MYSQLI_ASSOC) : [],
            'tipos_vehiculo' => $tiposVehiculo ? $tiposVehiculo->fetch_all(MYSQLI_ASSOC) : [],
            'precios' => $precios ? $precios->fetch_all(MYSQLI_ASSOC) : []
        ];
    } catch (Exception $e) {
        return ['servicios' => [], 'tipos_vehiculo' => [], 'precios' => []];
    }
}

// Step 4: Usuarios Functions
function processUsuariosStep($conn, $dbName, $data) {
    try {
        // Since users are managed in the parent database (frosh_lavacar), 
        // and notifications are temporarily commented out, we just mark as completed
        
        // Guardar configuración de notificaciones (comentado temporalmente)
        /*
        if (!empty($data['notificaciones'])) {
            foreach ($data['notificaciones'] as $tipo => $valor) {
                EjecutarSQL($conn, 
                    "INSERT INTO `{$dbName}`.`configuracion_sistema` (clave, valor) 
                     VALUES (?, ?) 
                     ON DUPLICATE KEY UPDATE valor = ?",
                    ["notificacion_{$tipo}", $valor ? '1' : '0', $valor ? '1' : '0']
                );
            }
        }
        */
        
        // Marcar que la configuración de usuarios está completa usando MySQLi directamente
        $sql = "INSERT INTO `{$dbName}`.`configuracion_sistema` (clave, valor) 
                VALUES ('usuarios_configurados', '1') 
                ON DUPLICATE KEY UPDATE valor = '1'";
        
        if (!$conn->query($sql)) {
            throw new Exception("Error marking users as configured: " . $conn->error);
        }
        
        return ['success' => true, 'message' => 'Configuración inicial completada correctamente'];
    } catch (Exception $e) {
        error_log("processUsuariosStep error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error al completar configuración: ' . $e->getMessage()];
    }
}

function getUsuariosData($conn, $dbName) {
    try {
        // Los usuarios se gestionan en frosh_lavacar, no en la base de datos del tenant
        return [
            'usuarios' => [],
            'usuarios_nuevos' => [],
            'roles' => [
                'admin' => 'Administrador',
                'supervisor' => 'Supervisor', 
                'empleado' => 'Empleado',
                'cajero' => 'Cajero'
            ]
        ];
    } catch (Exception $e) {
        return ['usuarios' => [], 'usuarios_nuevos' => [], 'roles' => []];
    }
}
?>