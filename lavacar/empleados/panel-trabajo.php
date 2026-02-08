<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: ../../login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

// Solo obtener órdenes pendientes (1) y en proceso (2) - NO terminadas
$ordenes = CrearConsulta(
    $conn,
    "SELECT DISTINCT o.ID, o.Estado, o.FechaIngreso, o.FechaProceso, o.Monto, o.ClienteID, o.VehiculoID, o.TipoServicio, o.Categoria,
            COALESCE(c.NombreCompleto, 'Cliente no asignado') as ClienteNombre, 
            COALESCE(v.Placa, 'Sin placa') as Placa
     FROM {$dbName}.ordenes o
     LEFT JOIN {$dbName}.clientes c ON c.ID = o.ClienteID
     LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
     WHERE o.Estado IN (1, 2)
     ORDER BY o.Estado ASC, o.FechaIngreso ASC",
    []
)->fetch_all(MYSQLI_ASSOC);

// Asegurar que los valores críticos no sean null y eliminar duplicados por ID
$ordenesUnicas = [];
foreach ($ordenes as $orden) {
    // Usar ID como clave para evitar duplicados
    if (!isset($ordenesUnicas[$orden['ID']])) {
        $orden['ClienteNombre'] = $orden['ClienteNombre'] ?? 'Cliente no asignado';
        $orden['Placa'] = $orden['Placa'] ?? 'Sin placa';
        $orden['Monto'] = $orden['Monto'] ?? 0.00;
        $orden['TipoServicio'] = $orden['TipoServicio'] ?? 1;
        $orden['Categoria'] = $orden['Categoria'] ?? 0;
        $ordenesUnicas[$orden['ID']] = $orden;
    }
}

// Convertir de vuelta a array indexado
$ordenes = array_values($ordenesUnicas);

// Calcular contadores solo para estados activos
$contadores = [
    'pendientes' => 0,
    'proceso' => 0,
    'total_activas' => count($ordenes)
];

foreach ($ordenes as $orden) {
    switch ($orden['Estado']) {
        case 1:
            $contadores['pendientes']++;
            break;
        case 2:
            $contadores['proceso']++;
            break;
    }
}

// Comentado temporalmente - No necesario para panel informativo
// require 'lavacar/partials/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Trabajo - <?= htmlspecialchars($user['company']['name']) ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>

<style>
/* Reset y configuración base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f8f9fa;
    color: #333;
    line-height: 1.4;
}

.container-fluid {
    padding: 15px;
    max-width: 100%;
    position: relative;
}

/* Header de empresa - Compacto y centrado */
.company-header {
    text-align: center;
    background: white;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-bottom: 3px solid #274AB3;
}

.company-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #274AB3;
    margin-bottom: 5px;
}

.company-header p {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

/* Indicador EN VIVO - Esquina superior derecha */
.live-indicator {
    position: fixed;
    top: 20px;
    right: 20px;
    display: inline-flex;
    align-items: center;
    background: #dc3545;
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    z-index: 1000;
}

.live-indicator::before {
    content: '';
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    margin-right: 6px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.1); }
    100% { opacity: 1; transform: scale(1); }
}

/* Estadísticas compactas */
.header-stats {
    background: linear-gradient(135deg, #274AB3, #0dcaf0);
    color: white;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(39, 74, 179, 0.2);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 2px;
}

.stat-label {
    font-size: 0.8rem;
    opacity: 0.9;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filtros compactos */
.btn-group .btn {
    padding: 8px 15px;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 8px !important;
    margin: 0 2px;
}

/* Tarjetas de trabajo optimizadas */
.trabajo-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    min-height: 280px;
    cursor: default;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.trabajo-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.trabajo-card.pendiente {
    border-left: 6px solid #ffc107;
    background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%);
}

.trabajo-card.proceso {
    border-left: 6px solid #0dcaf0;
    background: linear-gradient(135deg, #e0f7ff 0%, #ffffff 100%);
}

/* Placa estilo Costa Rica */
.placa-display {
    font-size: 2.2rem;
    font-weight: 900;
    letter-spacing: 3px;
    padding: 15px 25px;
    border-radius: 8px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 4px solid #1a5490;
    text-align: center;
    margin: 12px 0;
    font-family: 'Arial Black', Arial, sans-serif;
    color: #1a5490;
    text-shadow: 1px 1px 0px rgba(255,255,255,0.8);
    box-shadow: 
        0 4px 8px rgba(0,0,0,0.15),
        inset 0 1px 0 rgba(255,255,255,0.8),
        inset 0 -1px 0 rgba(0,0,0,0.1);
    position: relative;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.placa-display::before {
    content: 'COSTA RICA';
    position: absolute;
    top: 3px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.4rem;
    font-weight: 600;
    color: #1a5490;
    letter-spacing: 1px;
    opacity: 0.7;
}

.placa-display::after {
    content: '';
    position: absolute;
    bottom: 3px;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #1a5490, transparent);
    opacity: 0.5;
}

.estado-badge {
    font-size: 0.9rem;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Lista de servicios mejorada */
.servicios-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin: 10px 0;
    border-left: 4px solid #274AB3;
}

.servicios-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #274AB3;
    margin-bottom: 8px;
    text-align: center;
}

.servicio-item {
    background: white;
    border-radius: 6px;
    padding: 6px 10px;
    margin: 4px 0;
    font-size: 0.85rem;
    font-weight: 600;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-left: 3px solid #28a745;
}

.servicio-item.mas-servicios {
    background: #e9ecef;
    color: #6c757d;
    border-left: 3px solid #6c757d;
    font-style: italic;
}

.tiempo-info {
    font-size: 0.8rem;
    color: #495057;
    font-weight: 600;
    text-align: center;
}

.cliente-info {
    text-align: center;
    margin: 10px 0;
}

.cliente-info h6 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
}

/* Grid optimizado */
.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .grid-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .placa-display {
        font-size: 1.8rem;
        letter-spacing: 2px;
        padding: 12px 18px;
        min-height: 50px;
    }
    
    .placa-display::before {
        font-size: 0.35rem;
        top: 2px;
    }
    
    .placa-espera {
        font-size: 1.2rem;
        letter-spacing: 1.5px;
        padding: 6px 10px;
        min-height: 35px;
    }
    
    .trabajo-card {
        min-height: 250px;
    }
    
    .live-indicator {
        top: 10px;
        right: 10px;
        padding: 6px 10px;
        font-size: 0.7rem;
    }
}

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

/* Footer compacto con branding FROSH */
.last-update {
    font-size: 0.75rem;
    color: #6c757d;
    text-align: center;
    margin-top: 20px;
    padding: 15px 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-top: 3px solid #274AB3;
}

.frosh-branding {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e9ecef;
}

.frosh-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #274AB3;
    text-decoration: none;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.frosh-logo:hover {
    color: #1a3a8a;
    text-decoration: none;
    transform: translateY(-1px);
}

.frosh-logo img {
    width: 36px;
    height: 36px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.frosh-logo:hover img {
    transform: scale(1.05);
}

.frosh-website {
    color: #6c757d;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.frosh-website:hover {
    color: #274AB3;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .frosh-branding {
        flex-direction: column;
        gap: 8px;
    }
    
    .frosh-logo {
        font-size: 0.9rem;
    }
    
    .frosh-logo img {
        width: 32px;
        height: 32px;
    }
    
    .frosh-website {
        font-size: 0.8rem;
    }
}

/* Indicador de actualización */
.refresh-indicator {
    position: fixed;
    top: 60px;
    right: 20px;
    background: rgba(39, 74, 179, 0.95);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    z-index: 1000;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(39, 74, 179, 0.3);
}

/* Layout de 2 columnas */
.layout-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

/* Columna izquierda - En Espera (3/12) */
.cola-espera {
    flex: 0 0 25%;
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border-left: 6px solid #ffc107;
    max-height: 80vh;
    overflow-y: auto;
}

.cola-espera h3 {
    color: #ffc107;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.item-espera {
    background: linear-gradient(135deg, #fff8e1, #ffffff);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 4px solid #ffc107;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.numero-orden {
    font-size: 1.1rem;
    font-weight: 800;
    color: #ffc107;
    margin-bottom: 5px;
}

.placa-espera {
    font-size: 1.4rem;
    font-weight: 900;
    font-family: 'Arial Black', Arial, sans-serif;
    color: #1a5490;
    text-align: center;
    letter-spacing: 2px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 3px solid #1a5490;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 5px;
    text-shadow: 1px 1px 0px rgba(255,255,255,0.8);
    box-shadow: 
        0 2px 4px rgba(0,0,0,0.1),
        inset 0 1px 0 rgba(255,255,255,0.8);
    position: relative;
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Columna derecha - Trabajando (9/12) */
.trabajando-container {
    flex: 1;
}

.trabajando-container h3 {
    color: #0dcaf0;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Grid para órdenes en proceso */
.grid-trabajando {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

/* Tarjetas de trabajo en proceso */
.trabajo-card.proceso {
    border-left: 6px solid #0dcaf0;
    background: linear-gradient(135deg, #e0f7ff 0%, #ffffff 100%);
}

/* Responsive */
@media (max-width: 992px) {
    .layout-container {
        flex-direction: column;
    }
    
    .cola-espera {
        flex: none;
        max-height: 300px;
    }
    
    .grid-trabajando {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .layout-container {
        gap: 15px;
    }
    
    .cola-espera {
        padding: 15px;
        max-height: 250px;
    }
    
    .item-espera {
        padding: 12px;
    }
    
    .placa-espera {
        font-size: 1.2rem;
    }
    
    .grid-trabajando {
        grid-template-columns: 1fr;
    }
}
</style>

<main class="container-fluid">
    <!-- Indicador EN VIVO - Esquina superior derecha -->
    <span class="live-indicator">
        EN VIVO
    </span>

    <!-- Header de empresa - Compacto y centrado -->
    <div class="company-header">
        <h1><i class="fa-solid fa-building me-2"></i><?= htmlspecialchars($user['company']['name']) ?></h1>
        <p>Estado de Vehículos en Proceso</p>
    </div>

    <!-- Estadísticas compactas - Solo para empleados -->
    <?php if (!empty($ordenes)): ?>
    <div class="header-stats">
        <div class="row">
            <div class="col-6">
                <div class="stat-item">
                    <div class="stat-number"><?= $contadores['pendientes'] ?></div>
                    <div class="stat-label">⏰ En Espera</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-item">
                    <div class="stat-number"><?= $contadores['proceso'] ?></div>
                    <div class="stat-label">⚙️ Trabajando</div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filtros simplificados - Solo estados activos -->
    <?php /* Comentado - Ya no necesario con el nuevo layout
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group w-100" role="group">
                <input type="radio" class="btn-check" name="estadoFilter" id="todos" value="all" checked>
                <label class="btn btn-outline-secondary" for="todos">
                    <i class="fa-solid fa-list me-1"></i>Todos los Vehículos (<?= $contadores['total_activas'] ?>)
                </label>

                <input type="radio" class="btn-check" name="estadoFilter" id="pendientes" value="1">
                <label class="btn btn-outline-warning" for="pendientes">
                    <i class="fa-solid fa-clock me-1"></i>⏰ En Espera (<?= $contadores['pendientes'] ?>)
                </label>

                <input type="radio" class="btn-check" name="estadoFilter" id="proceso" value="2">
                <label class="btn btn-outline-info" for="proceso">
                    <i class="fa-solid fa-gear me-1"></i>⚙️ Trabajando (<?= $contadores['proceso'] ?>)
                </label>
            </div>
        </div>
    </div>
    */ ?>

    <!-- Layout de 2 columnas -->
    <?php if (empty($ordenes)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-check-circle fa-4x mb-3 text-success"></i>
        <h3>¡No hay vehículos en proceso!</h3>
        <p>Todos los trabajos han sido completados</p>
        <div class="mt-4">
            <i class="fa-solid fa-clock me-2"></i>
            <small class="text-muted">Esta pantalla se actualiza automáticamente</small>
        </div>
    </div>
    <?php else: ?>
    
    <div class="layout-container">
        <!-- Columna izquierda: En Espera (25%) -->
        <div class="cola-espera">
            <h3>
                <i class="fa-solid fa-clock me-2"></i>
                En Espera (<?= $contadores['pendientes'] ?>)
            </h3>
            
            <?php 
            $ordenesEspera = array_filter($ordenes, function($orden) {
                return $orden['Estado'] == 1;
            });
            ?>
            
            <?php if (empty($ordenesEspera)): ?>
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-check-circle fa-2x mb-2"></i>
                    <p class="mb-0">No hay vehículos esperando</p>
                </div>
            <?php else: ?>
                <?php foreach ($ordenesEspera as $orden): ?>
                <div class="item-espera">
                    <div class="numero-orden">
                        <i class="fa-solid fa-hashtag me-1"></i>
                        Orden <?= str_pad($orden['ID'], 3, '0', STR_PAD_LEFT) ?>
                    </div>
                    <div class="placa-espera">
                        <?= htmlspecialchars($orden['Placa']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Columna derecha: Trabajando (75%) -->
        <div class="trabajando-container">
            <h3>
                <i class="fa-solid fa-gear fa-spin me-2"></i>
                Trabajando Ahora (<?= $contadores['proceso'] ?>)
            </h3>
            
            <?php 
            $ordenesTrabajando = array_filter($ordenes, function($orden) {
                return $orden['Estado'] == 2;
            });
            ?>
            
            <?php if (empty($ordenesTrabajando)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-pause-circle fa-3x mb-3"></i>
                    <h4>No hay vehículos en proceso</h4>
                    <p>Los trabajos aparecerán aquí cuando inicien</p>
                </div>
            <?php else: ?>
                <div class="grid-trabajando">
                    <?php foreach ($ordenesTrabajando as $orden): 
                        // Obtener servicios desde la tabla orden_servicios
                        $servicios = [];
                        try {
                            $serviciosResult = CrearConsulta(
                                $conn,
                                "SELECT os.ServicioPersonalizado, os.Descripcion, s.Descripcion as ServicioNombre
                                 FROM {$dbName}.orden_servicios os
                                 LEFT JOIN {$dbName}.servicios s ON os.ServicioID = s.ID
                                 WHERE os.OrdenID = ?
                                 ORDER BY os.ID",
                                [$orden['ID']]
                            )->fetch_all(MYSQLI_ASSOC);
                            
                            foreach ($serviciosResult as $servicio) {
                                // Prioridad: ServicioPersonalizado > Descripcion > ServicioNombre
                                $nombreServicio = '';
                                if (!empty($servicio['ServicioPersonalizado'])) {
                                    $nombreServicio = $servicio['ServicioPersonalizado'];
                                } elseif (!empty($servicio['Descripcion'])) {
                                    $nombreServicio = $servicio['Descripcion'];
                                } elseif (!empty($servicio['ServicioNombre'])) {
                                    $nombreServicio = $servicio['ServicioNombre'];
                                }
                                
                                if (!empty($nombreServicio)) {
                                    $servicios[] = $nombreServicio;
                                }
                            }
                        } catch (Exception $e) {
                            error_log("Error obteniendo servicios para orden {$orden['ID']}: " . $e->getMessage());
                        }
                        
                        // Si no hay servicios en la tabla, usar servicios por defecto basados en TipoServicio
                        if (empty($servicios)) {
                            switch ($orden['TipoServicio']) {
                                case 1:
                                    $servicios = ['Lavado Exterior Básico', 'Secado Manual'];
                                    break;
                                case 2:
                                    $servicios = ['Lavado Exterior Completo', 'Limpieza Interior', 'Aspirado de Tapicería'];
                                    break;
                                case 3:
                                    $servicios = ['Lavado Exterior Premium', 'Limpieza Interior Profunda', 'Aspirado Completo', 'Encerado y Pulido'];
                                    break;
                                case 4:
                                    $servicios = ['Detallado Completo', 'Lavado de Motor', 'Limpieza de Llantas', 'Encerado Premium'];
                                    break;
                                default:
                                    $servicios = ['Lavado Estándar', 'Secado Básico'];
                            }
                        }
                        
                        $fechaIngreso = new DateTime($orden['FechaIngreso']);
                        $ahora = new DateTime();
                        
                        // Si está en proceso y tiene FechaProceso, usar esa fecha
                        // Si no, usar FechaIngreso como fallback
                        if ($orden['Estado'] == 2 && !empty($orden['FechaProceso'])) {
                            $fechaInicio = new DateTime($orden['FechaProceso']);
                            $tiempoLabel = 'trabajando';
                        } else {
                            $fechaInicio = $fechaIngreso;
                            $tiempoLabel = ($orden['Estado'] == 1) ? 'esperando' : 'desde ingreso';
                        }
                        //var_dump($fechaInicio);
						echo $orden['FechaProceso'];
                        $tiempoTranscurrido = $fechaInicio->diff($ahora);
                        
                        // Formato de tiempo más legible
                        $tiempoTexto = '';
                        if ($tiempoTranscurrido->h > 0) {
                            $tiempoTexto = $tiempoTranscurrido->format('%h:%I horas');
                        } else {
                            $tiempoTexto = $tiempoTranscurrido->format('%i minutos');
                        }
                    ?>
                    <div class="trabajo-card card proceso">
                        <div class="card-body">
                            <!-- Header con estado y número de orden -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="estado-badge bg-info text-white">
                                    <i class="fa-solid fa-gear fa-spin me-1"></i>TRABAJANDO
                                </span>
                                <div class="text-end">
                                    <small class="text-muted">Orden</small>
                                    <div class="fw-bold">#<?= str_pad($orden['ID'], 3, '0', STR_PAD_LEFT) ?></div>
                                </div>
                            </div>

                            <!-- Placa del vehículo -->
                            <div class="placa-display">
                                <?= htmlspecialchars($orden['Placa']) ?>
                            </div>

                            <!-- Tiempo en proceso -->
                            <div class="tiempo-info mb-3">
                                <i class="fa-solid fa-stopwatch me-1"></i>
                                <?= $tiempoTexto ?> <?= $tiempoLabel ?>
                            </div>

                            <!-- Servicios/Trabajos a realizar - DESTACADO -->
                            <div class="servicios-section">
                                <div class="servicios-title">
                                    <i class="fa-solid fa-wrench me-1"></i>TRABAJOS A REALIZAR
                                </div>
                                <?php if (!empty($servicios)): ?>
                                    <?php foreach ($servicios as $servicio): 
                                        // Asignar iconos específicos según el tipo de servicio
                                        $icono = 'fa-check-circle';
                                        $servicioLower = strtolower($servicio);
                                        
                                        if (strpos($servicioLower, 'lavado') !== false || strpos($servicioLower, 'exterior') !== false) {
                                            $icono = 'fa-soap';
                                        } elseif (strpos($servicioLower, 'interior') !== false || strpos($servicioLower, 'limpieza') !== false) {
                                            $icono = 'fa-spray-can';
                                        } elseif (strpos($servicioLower, 'aspirado') !== false || strpos($servicioLower, 'tapicería') !== false) {
                                            $icono = 'fa-wind';
                                        } elseif (strpos($servicioLower, 'encerado') !== false || strpos($servicioLower, 'pulido') !== false || strpos($servicioLower, 'cera') !== false) {
                                            $icono = 'fa-sparkles';
                                        } elseif (strpos($servicioLower, 'secado') !== false) {
                                            $icono = 'fa-fan';
                                        } elseif (strpos($servicioLower, 'motor') !== false) {
                                            $icono = 'fa-engine';
                                        } elseif (strpos($servicioLower, 'llantas') !== false || strpos($servicioLower, 'ruedas') !== false) {
                                            $icono = 'fa-circle';
                                        } elseif (strpos($servicioLower, 'detallado') !== false || strpos($servicioLower, 'premium') !== false) {
                                            $icono = 'fa-star';
                                        } elseif (strpos($servicioLower, 'desinfección') !== false) {
                                            $icono = 'fa-shield-virus';
                                        } elseif (strpos($servicioLower, 'chasis') !== false) {
                                            $icono = 'fa-car-side';
                                        }
                                    ?>
                                        <div class="servicio-item">
                                            <i class="fa-solid <?= $icono ?> me-1"></i>
                                            <?= htmlspecialchars($servicio) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Servicios por defecto cuando no hay datos específicos -->
                                    <div class="servicio-item">
                                        <i class="fa-solid fa-soap me-1"></i>
                                        Lavado Exterior
                                    </div>
                                    <div class="servicio-item">
                                        <i class="fa-solid fa-spray-can me-1"></i>
                                        Limpieza Interior
                                    </div>
                                    <div class="servicio-item">
                                        <i class="fa-solid fa-car me-1"></i>
                                        Aspirado
                                    </div>
                                    <div class="servicio-item">
                                        <i class="fa-solid fa-sparkles me-1"></i>
                                        Secado y Acabado
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <i class="fa-solid fa-info-circle me-1"></i>
                                            Servicios estándar - Verificar con cliente
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php endif; ?>

    <!-- Información de última actualización -->
    <div class="last-update">
        <div>
            <i class="fa-solid fa-clock me-1"></i>
            Última actualización: <span id="lastUpdate"><?= date('d/m/Y H:i:s') ?></span>
        </div>
        <div>
            <i class="fa-solid fa-info-circle me-1"></i>
            Actualización automática cada 30 segundos
        </div>
        <div>
            <small class="text-muted">
                <i class="fa-solid fa-eye me-1"></i>
                Solo se muestran vehículos en espera y en proceso
            </small>
        </div>
        
        <!-- Branding FROSH -->
        <div class="frosh-branding">
            <a href="https://www.froshsystems.com" target="_blank" class="frosh-logo">
                <img src="../../lib/images/logo-frosh.png" alt="FROSH Logo">
                <span>Powered by FROSH</span>
            </a>
            <span class="text-muted">•</span>
            <a href="https://www.froshsystems.com" target="_blank" class="frosh-website">
                www.froshsystems.com
            </a>
        </div>
    </div>

    <!-- Indicador de actualización sutil -->
    <div class="refresh-indicator" id="refreshIndicator" style="display: none;">
        <i class="fa-solid fa-spinner fa-spin me-2"></i>Actualizando...
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshIndicator = document.getElementById('refreshIndicator');
    
    // Función para actualizar la hora
    function updateLastUpdateTime() {
        const now = new Date();
        const timeString = now.toLocaleString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('lastUpdate').textContent = timeString;
    }
    
    // Auto-refresh cada 30 segundos con indicador visual
    setInterval(() => {
        // Mostrar indicador de actualización
        refreshIndicator.style.display = 'block';
        document.body.classList.add('updating');
        
        // Actualizar después de un breve delay para mostrar el indicador
        setTimeout(() => {
            location.reload();
        }, 1000);
    }, 30000);
    
    // Actualizar la hora cada segundo
    setInterval(updateLastUpdateTime, 1000);
    
    // Prevenir interacciones no deseadas en modo informativo
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault(); // Deshabilitar menú contextual
    });
    
    // Deshabilitar selección de texto para una apariencia más limpia
    document.body.style.userSelect = 'none';
    document.body.style.webkitUserSelect = 'none';
    document.body.style.mozUserSelect = 'none';
    document.body.style.msUserSelect = 'none';
});
</script>
</script>

</body>
</html>

<?php 
// Comentado temporalmente - No necesario para panel informativo
// require 'lavacar/partials/footer.php'; 
?>