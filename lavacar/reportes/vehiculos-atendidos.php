<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

// Debug: Verificar conexión y datos disponibles
$debugInfo = array();
$debugInfo['database'] = $dbName;

// Verificar si hay órdenes en la base de datos
$totalOrdenes = ObtenerPrimerRegistro($conn, "SELECT COUNT(*) as total FROM {$dbName}.ordenes", array());
$debugInfo['total_ordenes_db'] = $totalOrdenes['total'] ?? 0;

// Verificar si hay vehículos
$totalVehiculos = ObtenerPrimerRegistro($conn, "SELECT COUNT(*) as total FROM {$dbName}.vehiculos", array());
$debugInfo['total_vehiculos_db'] = $totalVehiculos['total'] ?? 0;

// Verificar estructura de tablas
$tablas = CrearConsulta($conn, "SHOW TABLES FROM {$dbName}", array())->fetch_all(MYSQLI_ASSOC);
$debugInfo['tablas_disponibles'] = array_column($tablas, "Tables_in_{$dbName}");

// Parámetros de fecha
$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

// Validar fechas
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
    $fechaInicio = date('Y-m-01');
    $fechaFin = date('Y-m-d');
}

// Estadísticas por categoría de vehículo - Versión simplificada
$vehiculosPorCategoria = CrearConsulta($conn,
    "SELECT 
        CASE 
            WHEN cs.Descripcion IS NOT NULL THEN cs.Descripcion
            WHEN o.Categoria = 1 THEN 'Categoría 1'
            WHEN o.Categoria = 2 THEN 'Categoría 2'
            WHEN o.Categoria = 3 THEN 'Categoría 3'
            ELSE 'Sin categoría'
        END as categoria,
        COUNT(DISTINCT o.ID) as total_ordenes,
        COUNT(DISTINCT COALESCE(v.ID, o.VehiculoID)) as vehiculos_unicos,
        SUM(o.Monto) as ingresos_totales,
        AVG(o.Monto) as ticket_promedio
     FROM {$dbName}.ordenes o
     LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
     LEFT JOIN {$dbName}.categoriaservicios cs ON cs.ID = o.Categoria
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
     AND o.Estado >= 3
     GROUP BY 
        CASE 
            WHEN cs.Descripcion IS NOT NULL THEN cs.Descripcion
            WHEN o.Categoria = 1 THEN 'Categoría 1'
            WHEN o.Categoria = 2 THEN 'Categoría 2'
            WHEN o.Categoria = 3 THEN 'Categoría 3'
            ELSE 'Sin categoría'
        END
     ORDER BY total_ordenes DESC",
    array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);

// Top vehículos más atendidos - Versión simplificada
$topVehiculos = CrearConsulta($conn,
    "SELECT 
        COALESCE(v.Placa, CONCAT('Vehículo-', o.VehiculoID)) as Placa,
        COALESCE(v.Marca, 'Sin marca') as Marca,
        COALESCE(v.Modelo, 'Sin modelo') as Modelo,
        COALESCE(v.Year, '') as Year,
        COALESCE(v.Color, '') as Color,
        CASE 
            WHEN cs.Descripcion IS NOT NULL THEN cs.Descripcion
            WHEN o.Categoria = 1 THEN 'Categoría 1'
            WHEN o.Categoria = 2 THEN 'Categoría 2'
            WHEN o.Categoria = 3 THEN 'Categoría 3'
            ELSE 'Sin categoría'
        END as categoria,
        COALESCE(c.NombreCompleto, 'Cliente no asignado') as propietario,
        COUNT(o.ID) as total_visitas,
        SUM(o.Monto) as gasto_total,
        AVG(o.Monto) as gasto_promedio,
        MAX(o.FechaIngreso) as ultima_visita,
        MIN(o.FechaIngreso) as primera_visita
     FROM {$dbName}.ordenes o
     LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
     LEFT JOIN {$dbName}.categoriaservicios cs ON cs.ID = o.Categoria
     LEFT JOIN {$dbName}.clientes c ON c.ID = COALESCE(v.ClienteID, o.ClienteID)
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
     AND o.Estado >= 3
     AND o.VehiculoID IS NOT NULL
     GROUP BY o.VehiculoID, v.Placa, v.Marca, v.Modelo, v.Year, v.Color, categoria, c.NombreCompleto
     ORDER BY total_visitas DESC, gasto_total DESC
     LIMIT 20",
    array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);

// Análisis de frecuencia de visitas
$frecuenciaVisitas = CrearConsulta($conn,
    "SELECT 
        CASE 
            WHEN visitas = 1 THEN 'Una vez'
            WHEN visitas BETWEEN 2 AND 3 THEN '2-3 veces'
            WHEN visitas BETWEEN 4 AND 6 THEN '4-6 veces'
            WHEN visitas BETWEEN 7 AND 10 THEN '7-10 veces'
            ELSE 'Más de 10 veces'
        END as rango_frecuencia,
        COUNT(*) as cantidad_vehiculos
     FROM (
         SELECT v.ID, COUNT(o.ID) as visitas
         FROM {$dbName}.vehiculos v
         JOIN {$dbName}.ordenes o ON o.VehiculoID = v.ID
         WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
         AND o.Estado >= 3
         GROUP BY v.ID
     ) as vehiculo_visitas
     GROUP BY rango_frecuencia
     ORDER BY 
         CASE rango_frecuencia
             WHEN 'Una vez' THEN 1
             WHEN '2-3 veces' THEN 2
             WHEN '4-6 veces' THEN 3
             WHEN '7-10 veces' THEN 4
             ELSE 5
         END",
    array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);

// Estadísticas generales
$estadisticasGenerales = array(
    'total_ordenes' => array_sum(array_column($vehiculosPorCategoria, 'total_ordenes')),
    'vehiculos_unicos' => array_sum(array_column($vehiculosPorCategoria, 'vehiculos_unicos')),
    'ingresos_totales' => array_sum(array_column($vehiculosPorCategoria, 'ingresos_totales')),
    'ticket_promedio' => 0
);

if ($estadisticasGenerales['total_ordenes'] > 0) {
    $estadisticasGenerales['ticket_promedio'] = $estadisticasGenerales['ingresos_totales'] / $estadisticasGenerales['total_ordenes'];
}

// Si no hay datos, crear datos de ejemplo para mostrar la interfaz
if (empty($vehiculosPorCategoria)) {
    $vehiculosPorCategoria = array(
        array(
            'categoria' => 'Sin datos disponibles',
            'total_ordenes' => 0,
            'vehiculos_unicos' => 0,
            'ingresos_totales' => 0,
            'ticket_promedio' => 0
        )
    );
}

// Vehículos nuevos vs recurrentes
$vehiculosNuevos = CrearConsulta($conn,
    "SELECT COUNT(DISTINCT v.ID) as nuevos
     FROM {$dbName}.vehiculos v
     JOIN {$dbName}.ordenes o ON o.VehiculoID = v.ID
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
     AND o.Estado >= 3
     AND v.ID NOT IN (
         SELECT DISTINCT o2.VehiculoID 
         FROM {$dbName}.ordenes o2 
         WHERE DATE(o2.FechaIngreso) < ? 
         AND o2.Estado >= 3
         AND o2.VehiculoID IS NOT NULL
     )",
    array($fechaInicio, $fechaFin, $fechaInicio))->fetch_all(MYSQLI_ASSOC);

$vehiculosRecurrentes = max(0, $estadisticasGenerales['vehiculos_unicos'] - ($vehiculosNuevos[0]['nuevos'] ?? 0));

// Si no hay datos de frecuencia, crear datos de ejemplo
if (empty($frecuenciaVisitas)) {
    $frecuenciaVisitas = array(
        array('rango_frecuencia' => 'Sin datos', 'cantidad_vehiculos' => 0)
    );
}

require 'lavacar/partials/header.php';

// Define breadcrumbs for this page
$breadcrumbs = array(
    array('title' => 'Reportes', 'url' => 'index.php'),
    array('title' => 'Vehículos Atendidos', 'url' => '')
);
?>

<?php include 'lavacar/partials/breadcrumb.php'; ?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-car me-2"></i>Vehículos Atendidos</h2>
            <p class="text-muted mb-0">Estadísticas y análisis por tipo de vehículo</p>
        </div>
        <div class="d-flex gap-2">
            <!--<button class="btn btn-report-success btn-sm" onclick="exportarReporte()">
                <i class="fa-solid fa-download me-1"></i> Exportar
            </button>-->
            <a href="index.php" class="btn btn-frosh-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" value="<?= $fechaInicio ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" name="fecha_fin" value="<?= $fechaFin ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-report-info">
                            <i class="fa-solid fa-search me-1"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-report-warning ms-2" onclick="resetearFiltros()">
                            <i class="fa-solid fa-refresh me-1"></i> Reset
                        </button>
                    </div>
                </form>
                
                <?php if ($debugInfo['total_ordenes_db'] == 0): ?>
                <div class="alert alert-info mt-3">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Información del sistema:</strong> 
                    Base de datos: <?= $debugInfo['database'] ?> | 
                    Órdenes: <?= $debugInfo['total_ordenes_db'] ?> | 
                    Vehículos: <?= $debugInfo['total_vehiculos_db'] ?> |
                    Tablas: <?= implode(', ', $debugInfo['tablas_disponibles']) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stats-card primary">
                <div class="stats-icon">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div class="stats-content">
                    <h3><?= $estadisticasGenerales['vehiculos_unicos'] ?></h3>
                    <p>Vehículos Únicos</p>
                    <small>Atendidos en el período</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stats-card success">
                <div class="stats-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="stats-content">
                    <h3><?= $estadisticasGenerales['total_ordenes'] ?></h3>
                    <p>Total de Servicios</p>
                    <small>Órdenes completadas</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stats-card info">
                <div class="stats-icon">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="stats-content">
                    <h3><?= $vehiculosNuevos[0]['nuevos'] ?? 0 ?></h3>
                    <p>Vehículos Nuevos</p>
                    <small>Primera vez en el período</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stats-card warning">
                <div class="stats-icon">
                    <i class="fa-solid fa-repeat"></i>
                </div>
                <div class="stats-content">
                    <h3><?= $vehiculosRecurrentes ?></h3>
                    <p>Vehículos Recurrentes</p>
                    <small>Clientes que regresan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Estadísticas por Categoría -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-bar me-2"></i>Análisis por Categoría de Vehículo</h5>
                    <small class="text-muted">Rendimiento por tipo de vehículo</small>
                </div>
                <div class="card-body">
                    <?php if (empty($vehiculosPorCategoria)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-car fa-2x mb-2"></i>
                            <p>No hay datos de vehículos para mostrar</p>
                        </div>
                    <?php else: ?>
                        <div class="categories-analysis">
                            <?php foreach ($vehiculosPorCategoria as $index => $categoria): ?>
                                <?php 
                                $porcentajeOrdenes = $estadisticasGenerales['total_ordenes'] > 0 ? 
                                    ($categoria['total_ordenes'] / $estadisticasGenerales['total_ordenes']) * 100 : 0;
                                $colores = array('#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4');
                                $color = $colores[$index % count($colores)];
                                ?>
                                <div class="category-analysis-item">
                                    <div class="category-header">
                                        <div class="category-info">
                                            <h6><?= htmlspecialchars($categoria['categoria']) ?></h6>
                                            <div class="category-metrics">
                                                <span class="metric">
                                                    <i class="fa-solid fa-list-check"></i>
                                                    <?= $categoria['total_ordenes'] ?> órdenes
                                                </span>
                                                <span class="metric">
                                                    <i class="fa-solid fa-car"></i>
                                                    <?= $categoria['vehiculos_unicos'] ?> vehículos
                                                </span>
                                            </div>
                                        </div>
                                        <div class="category-revenue">
                                            <div class="revenue-amount">₡<?= number_format($categoria['ingresos_totales'], 0) ?></div>
                                            <div class="revenue-avg">Promedio: ₡<?= number_format($categoria['ticket_promedio'], 0) ?></div>
                                        </div>
                                    </div>
                                    <div class="category-progress">
                                        <div class="progress-bar" style="background-color: <?= $color ?>; width: <?= $porcentajeOrdenes ?>%"></div>
                                        <span class="progress-label"><?= number_format($porcentajeOrdenes, 1) ?>%</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Frecuencia de Visitas -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-pie me-2"></i>Frecuencia de Visitas</h5>
                    <small class="text-muted">Distribución de lealtad</small>
                </div>
                <div class="card-body">
                    <?php if (empty($frecuenciaVisitas)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-pie fa-2x mb-2"></i>
                            <p>No hay datos de frecuencia</p>
                        </div>
                    <?php else: ?>
                        <div class="chart-container">
                            <canvas id="frecuenciaChart" width="300" height="300"></canvas>
                        </div>
                        
                        <!-- Leyenda de frecuencia -->
                        <div class="frequency-legend mt-3">
                            <?php 
                            $coloresFrecuencia = array('#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6');
                            ?>
                            <?php foreach ($frecuenciaVisitas as $index => $freq): ?>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: <?= $coloresFrecuencia[$index % count($coloresFrecuencia)] ?>"></div>
                                    <div class="legend-text">
                                        <span><?= htmlspecialchars($freq['rango_frecuencia']) ?></span>
                                        <small><?= $freq['cantidad_vehiculos'] ?> vehículos</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Vehículos -->
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-trophy me-2"></i>Top 20 Vehículos Más Atendidos</h5>
                    <small class="text-muted">Ranking por número de visitas y gasto total</small>
                </div>
                <div class="card-body">
                    <?php if (empty($topVehiculos)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-trophy fa-2x mb-2"></i>
                            <p>No hay datos de vehículos para mostrar</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Vehículo</th>
                                        <th>Propietario</th>
                                        <th>Categoría</th>
                                        <th class="text-center">Visitas</th>
                                        <th class="text-end">Gasto Total</th>
                                        <th class="text-end">Gasto Promedio</th>
                                        <th>Última Visita</th>
                                        <th class="text-center">Lealtad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topVehiculos as $index => $vehiculo): ?>
                                        <?php
                                        $lealtad = 'baja';
                                        if ($vehiculo['total_visitas'] >= 10) $lealtad = 'alta';
                                        elseif ($vehiculo['total_visitas'] >= 5) $lealtad = 'media';
                                        
                                        $lealtadClass = array(
                                            'alta' => 'report-success',
                                            'media' => 'report-warning',
                                            'baja' => 'secondary'
                                        )[$lealtad];
                                        
                                        $lealtadText = array(
                                            'alta' => 'Alta',
                                            'media' => 'Media',
                                            'baja' => 'Baja'
                                        )[$lealtad];
                                        
                                        $rankClass = '';
                                        if ($index < 3) {
                                            $rankClass = array('gold', 'silver', 'bronze')[$index];
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="rank-position <?= $rankClass ?>">
                                                    <?= $index + 1 ?>
                                                    <?php if ($index < 3): ?>
                                                        <i class="fa-solid fa-medal ms-1"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="vehicle-info">
                                                    <strong><?= htmlspecialchars($vehiculo['Placa']) ?></strong>
                                                    <small><?= htmlspecialchars($vehiculo['Marca'] . ' ' . $vehiculo['Modelo'] . ' ' . $vehiculo['Year']) ?></small>
                                                    <?php if ($vehiculo['Color']): ?>
                                                        <span class="color-badge" style="background-color: <?= strtolower($vehiculo['Color']) ?>"></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($vehiculo['propietario']) ?></td>
                                            <td>
                                                <span class="badge badge-frosh-light text-dark"><?= htmlspecialchars($vehiculo['categoria']) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-report-info"><?= $vehiculo['total_visitas'] ?></span>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-success">₡<?= number_format($vehiculo['gasto_total'], 0) ?></strong>
                                            </td>
                                            <td class="text-end">
                                                ₡<?= number_format($vehiculo['gasto_promedio'], 0) ?>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y', strtotime($vehiculo['ultima_visita'])) ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-<?= $lealtadClass ?>"><?= $lealtadText ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* ===== FILTER CARD ===== */
.filter-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* ===== STATS CARDS ===== */
.stats-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stats-card.primary .stats-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.stats-card.success .stats-icon { background: linear-gradient(135deg, #10b981, #059669); }
.stats-card.info .stats-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.stats-card.warning .stats-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.stats-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.stats-content p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 4px 0;
    font-weight: 600;
}

.stats-content small {
    font-size: 0.8rem;
    color: #94a3b8;
}

/* ===== CHART CARDS ===== */
.chart-card, .table-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    height: 100%;
}

.chart-card .card-header, .table-card .card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: none;
}

.chart-card .card-header h5, .table-card .card-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
}

.chart-card .card-body, .table-card .card-body {
    padding: 20px 24px 24px;
}

.chart-container {
    position: relative;
    height: 300px;
}

/* ===== CATEGORIES ANALYSIS ===== */
.categories-analysis {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.category-analysis-item {
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.category-info h6 {
    margin: 0 0 8px;
    color: #1e293b;
    font-weight: 600;
}

.category-metrics {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.metric {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.85rem;
    color: #64748b;
}

.metric i {
    color: #94a3b8;
}

.category-revenue {
    text-align: right;
}

.revenue-amount {
    font-size: 1.1rem;
    font-weight: 700;
    color: #10b981;
}

.revenue-avg {
    font-size: 0.8rem;
    color: #64748b;
}

.category-progress {
    position: relative;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-label {
    position: absolute;
    right: 8px;
    top: -24px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
}

/* ===== FREQUENCY LEGEND ===== */
.frequency-legend {
    max-height: 200px;
    overflow-y: auto;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.legend-text {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.legend-text span {
    font-size: 0.85rem;
    color: #475569;
}

.legend-text small {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
}

/* ===== RANKING STYLES ===== */
.rank-position {
    display: flex;
    align-items: center;
    font-weight: 700;
    font-size: 1.1rem;
}

.rank-position.gold { color: #ffd700; }
.rank-position.silver { color: #c0c0c0; }
.rank-position.bronze { color: #cd7f32; }

.vehicle-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.vehicle-info strong {
    color: #1e293b;
}

.vehicle-info small {
    color: #64748b;
    font-size: 0.8rem;
}

.color-badge {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    border: 1px solid #e2e8f0;
    margin-left: 4px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .stats-card {
        padding: 16px;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .stats-content h3 {
        font-size: 1.5rem;
    }
    
    .category-header {
        flex-direction: column;
        gap: 12px;
    }
    
    .category-revenue {
        text-align: left;
    }
    
    .category-metrics {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Debug information
console.log('Datos de frecuencia:', <?= json_encode($frecuenciaVisitas) ?>);
console.log('Datos de categorías:', <?= json_encode($vehiculosPorCategoria) ?>);
console.log('Top vehículos:', <?= json_encode($topVehiculos) ?>);

<?php if (!empty($frecuenciaVisitas) && $frecuenciaVisitas[0]['cantidad_vehiculos'] > 0): ?>
// Datos para el gráfico de frecuencia
const frecuenciaData = <?= json_encode($frecuenciaVisitas) ?>;
const coloresFrecuencia = ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'];

// Configurar gráfico de frecuencia
const ctx = document.getElementById('frecuenciaChart').getContext('2d');
const frecuenciaChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: frecuenciaData.map(function(f) { return f.rango_frecuencia; }),
        datasets: [{
            data: frecuenciaData.map(function(f) { return f.cantidad_vehiculos; }),
            backgroundColor: coloresFrecuencia,
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverBorderWidth: 3,
            hoverBorderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' vehículos';
                    }
                }
            }
        },
        cutout: '60%'
    }
});
<?php else: ?>
// No hay datos para mostrar el gráfico
console.log('No hay datos de frecuencia para mostrar el gráfico');
const ctx = document.getElementById('frecuenciaChart');
if (ctx) {
    ctx.getContext('2d').fillText('No hay datos disponibles', 150, 150);
}
<?php endif; ?>

function resetearFiltros() {
    window.location.href = window.location.pathname;
}

function exportarReporte() {
    showAlert('Exportación de reporte en desarrollo', 'info');
}

// Función para mostrar alertas
function showAlert(message, type) {
    type = type || 'info';
    const existingAlert = document.querySelector('.modern-alert');
    if (existingAlert) existingAlert.remove();
    
    const alert = document.createElement('div');
    alert.className = 'modern-alert alert-' + type;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-triangle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    alert.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i>' +
        '<span>' + message + '</span>' +
        '<button onclick="this.parentElement.remove()" class="alert-close">' +
        '<i class="fa-solid fa-times"></i></button>';
    
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;' +
        'background: white; border-radius: 12px; padding: 16px 20px;' +
        'box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);' +
        'border-left: 4px solid ' + (type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#3b82f6') + ';' +
        'display: flex; align-items: center; gap: 12px; max-width: 400px;' +
        'animation: slideIn 0.3s ease; font-weight: 500; color: #1e293b;';
    
    const style = document.createElement('style');
    style.textContent = '@keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }' +
        '.alert-close { background: none; border: none; color: #64748b; cursor: pointer; padding: 4px; border-radius: 4px; transition: all 0.2s ease; }' +
        '.alert-close:hover { background: #f1f5f9; color: #1e293b; }';
    document.head.appendChild(style);
    
    document.body.appendChild(alert);
    setTimeout(function() {
        if (alert && alert.parentNode) {
            alert.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(function() { alert.remove(); }, 300);
        }
    }, 5000);
}
</script>

<?php require 'lavacar/partials/footer.php'; ?>