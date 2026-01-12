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

// Parámetros de fecha
$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
$limite = (int)($_GET['limite'] ?? 10);

// Validar parámetros
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
    $fechaInicio = date('Y-m-01');
    $fechaFin = date('Y-m-d');
}

if ($limite < 5 || $limite > 50) {
    $limite = 10;
}

// Obtener servicios populares - Método compatible
$serviciosPopulares = array();

// Primero intentar con JSON_TABLE (MySQL 8.0+)
try {
    $serviciosPopulares = CrearConsulta($conn,
        "SELECT 
            s.ID,
            s.Descripcion,
            COUNT(*) as cantidad_ordenes,
            SUM(o.Monto) as ingresos_totales,
            AVG(o.Monto) as ticket_promedio,
            ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM {$dbName}.ordenes WHERE DATE(FechaIngreso) BETWEEN ? AND ? AND Estado >= 3)), 2) as porcentaje_participacion
         FROM {$dbName}.ordenes o
         CROSS JOIN JSON_TABLE(o.ServiciosJSON, '$[*]' COLUMNS (
             servicio_id INT PATH '$.id',
             servicio_precio DECIMAL(10,2) PATH '$.precio',
             servicio_nombre VARCHAR(255) PATH '$.nombre'
         )) AS jt
         JOIN {$dbName}.servicios s ON s.ID = jt.servicio_id
         WHERE DATE(o.FechaIngreso) BETWEEN ? AND ? 
         AND o.Estado >= 3
         GROUP BY s.ID, s.Descripcion
         ORDER BY cantidad_ordenes DESC, ingresos_totales DESC
         LIMIT ?", 
        [$fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $limite])->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    // Si JSON_TABLE no está disponible, usar método alternativo
    $serviciosPopulares = array();
}

// Si no hay datos con JSON_TABLE, usar método alternativo
if (empty($serviciosPopulares)) {
    try {
        $serviciosPopulares = CrearConsulta($conn,
            "SELECT 
                s.ID,
                s.Descripcion,
                COUNT(DISTINCT o.ID) as cantidad_ordenes,
                SUM(o.Monto) as ingresos_totales,
                AVG(o.Monto) as ticket_promedio,
                0 as porcentaje_participacion
             FROM {$dbName}.ordenes o
             JOIN {$dbName}.servicios s ON JSON_CONTAINS(o.ServiciosJSON, JSON_OBJECT('id', s.ID))
             WHERE DATE(o.FechaIngreso) BETWEEN ? AND ? 
             AND o.Estado >= 3
             GROUP BY s.ID, s.Descripcion
             ORDER BY cantidad_ordenes DESC, ingresos_totales DESC
             LIMIT ?", 
            [$fechaInicio, $fechaFin, $limite])->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        // Si tampoco funciona JSON_CONTAINS, usar método más básico
        $serviciosPopulares = array();
    }
}

// Si aún no hay datos, usar método más básico
if (empty($serviciosPopulares)) {
    // Obtener todos los servicios disponibles
    $todosServicios = CrearConsulta($conn,
        "SELECT ID, Descripcion FROM {$dbName}.servicios ORDER BY Descripcion",
        [])->fetch_all(MYSQLI_ASSOC);
    
    $serviciosStats = array();
    
    // Obtener órdenes del período
    $ordenes = CrearConsulta($conn,
        "SELECT ID, ServiciosJSON, Monto FROM {$dbName}.ordenes 
         WHERE DATE(FechaIngreso) BETWEEN ? AND ? AND Estado >= 3",
        [$fechaInicio, $fechaFin])->fetch_all(MYSQLI_ASSOC);
    
    // Procesar cada orden
    foreach ($ordenes as $orden) {
        if (!empty($orden['ServiciosJSON'])) {
            $servicios = json_decode($orden['ServiciosJSON'], true);
            if (is_array($servicios)) {
                foreach ($servicios as $servicio) {
                    if (isset($servicio['id'])) {
                        $servicioId = $servicio['id'];
                        if (!isset($serviciosStats[$servicioId])) {
                            $serviciosStats[$servicioId] = array(
                                'cantidad_ordenes' => 0,
                                'ingresos_totales' => 0
                            );
                        }
                        $serviciosStats[$servicioId]['cantidad_ordenes']++;
                        $serviciosStats[$servicioId]['ingresos_totales'] += $orden['Monto'];
                    }
                }
            }
        }
    }
    
    // Convertir a formato esperado
    foreach ($todosServicios as $servicio) {
        if (isset($serviciosStats[$servicio['ID']])) {
            $stats = $serviciosStats[$servicio['ID']];
            $serviciosPopulares[] = array(
                'ID' => $servicio['ID'],
                'Descripcion' => $servicio['Descripcion'],
                'cantidad_ordenes' => $stats['cantidad_ordenes'],
                'ingresos_totales' => $stats['ingresos_totales'],
                'ticket_promedio' => $stats['ingresos_totales'] / $stats['cantidad_ordenes'],
                'porcentaje_participacion' => 0
            );
        }
    }
    
    // Ordenar por cantidad de órdenes
    usort($serviciosPopulares, function($a, $b) {
        if ($a['cantidad_ordenes'] == $b['cantidad_ordenes']) return 0;
        return ($a['cantidad_ordenes'] < $b['cantidad_ordenes']) ? 1 : -1;
    });
    
    // Limitar resultados
    $serviciosPopulares = array_slice($serviciosPopulares, 0, $limite);
}

// Calcular totales
$totalOrdenes = array_sum(array_column($serviciosPopulares, 'cantidad_ordenes'));
$totalIngresos = array_sum(array_column($serviciosPopulares, 'ingresos_totales'));

// Recalcular porcentajes si es necesario
if (!empty($serviciosPopulares) && $serviciosPopulares[0]['porcentaje_participacion'] == 0) {
    $totalOrdenesCompletas = ObtenerPrimerRegistro($conn,
        "SELECT COUNT(*) as total FROM {$dbName}.ordenes 
         WHERE DATE(FechaIngreso) BETWEEN ? AND ? AND Estado >= 3",
        [$fechaInicio, $fechaFin])['total'] ?? 1;
    
    foreach ($serviciosPopulares as &$servicio) {
        $servicio['porcentaje_participacion'] = round(($servicio['cantidad_ordenes'] * 100.0) / $totalOrdenesCompletas, 2);
    }
}

// Obtener categorías de servicios para análisis adicional
$categorias = CrearConsulta($conn,
    "SELECT 
        cs.Descripcion as categoria,
        COUNT(DISTINCT o.ID) as ordenes,
        SUM(o.Monto) as ingresos
     FROM {$dbName}.ordenes o
     JOIN {$dbName}.categoriaservicio cs ON o.Categoria = cs.ID
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ? 
     AND o.Estado >= 3
     GROUP BY cs.Descripcion
     ORDER BY ordenes DESC",
    [$fechaInicio, $fechaFin])->fetch_all(MYSQLI_ASSOC);

require 'lavacar/partials/header.php';
?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-trophy me-2"></i>Servicios Populares</h2>
            <p class="text-muted mb-0">Ranking de servicios más solicitados y rentables</p>
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
                    <div class="col-md-3">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" value="<?= $fechaInicio ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" name="fecha_fin" value="<?= $fechaFin ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Top</label>
                        <select class="form-select" name="limite">
                            <option value="5" <?= $limite == 5 ? 'selected' : '' ?>>Top 5</option>
                            <option value="10" <?= $limite == 10 ? 'selected' : '' ?>>Top 10</option>
                            <option value="15" <?= $limite == 15 ? 'selected' : '' ?>>Top 15</option>
                            <option value="20" <?= $limite == 20 ? 'selected' : '' ?>>Top 20</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-report-info">
                            <i class="fa-solid fa-search me-1"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-frosh-gray ms-2" onclick="resetearFiltros()">
                            <i class="fa-solid fa-refresh me-1"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Resumen General -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="summary-card primary">
                <div class="summary-icon">
                    <i class="fa-solid fa-list-ol"></i>
                </div>
                <div class="summary-content">
                    <h3><?= count($serviciosPopulares) ?></h3>
                    <p>Servicios Analizados</p>
                    <small>En el período seleccionado</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card success">
                <div class="summary-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="summary-content">
                    <h3>₡<?= number_format($totalIngresos, 0) ?></h3>
                    <p>Ingresos Totales</p>
                    <small>De servicios analizados</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card info">
                <div class="summary-icon">
                    <i class="fa-solid fa-chart-bar"></i>
                </div>
                <div class="summary-content">
                    <h3><?= $totalOrdenes ?></h3>
                    <p>Órdenes Totales</p>
                    <small>Con estos servicios</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card warning">
                <div class="summary-icon">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div class="summary-content">
                    <h3><?= !empty($serviciosPopulares) ? htmlspecialchars(substr($serviciosPopulares[0]['Descripcion'], 0, 15)) . '...' : 'N/A' ?></h3>
                    <p>Servicio #1</p>
                    <small><?= !empty($serviciosPopulares) ? $serviciosPopulares[0]['cantidad_ordenes'] . ' órdenes' : 'Sin datos' ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ranking de Servicios -->
        <div class="col-lg-8">
            <div class="ranking-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-medal me-2"></i>Ranking de Servicios</h5>
                    <small class="text-muted">Ordenado por cantidad de órdenes</small>
                </div>
                <div class="card-body">
                    <?php if (empty($serviciosPopulares)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-trophy fa-3x mb-3"></i>
                            <h5>No hay datos para mostrar</h5>
                            <p>No se encontraron servicios en el período seleccionado</p>
                        </div>
                    <?php else: ?>
                        <div class="services-ranking">
                            <?php foreach ($serviciosPopulares as $index => $servicio): ?>
                                <div class="service-rank-item">
                                    <div class="rank-position">
                                        <div class="rank-number rank-<?= $index + 1 ?>">
                                            <?= $index + 1 ?>
                                        </div>
                                        <?php if ($index < 3): ?>
                                            <div class="rank-medal">
                                                <i class="fa-solid fa-medal"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="service-details">
                                        <h6><?= htmlspecialchars($servicio['Descripcion']) ?></h6>
                                        <div class="service-metrics">
                                            <span class="metric">
                                                <i class="fa-solid fa-list-check"></i>
                                                <?= $servicio['cantidad_ordenes'] ?> órdenes
                                            </span>
                                            <span class="metric">
                                                <i class="fa-solid fa-percentage"></i>
                                                <?= $servicio['porcentaje_participacion'] ?>% participación
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="service-revenue">
                                        <div class="revenue-amount">
                                            ₡<?= number_format($servicio['ingresos_totales'], 0) ?>
                                        </div>
                                        <div class="revenue-avg">
                                            Promedio: ₡<?= number_format($servicio['ticket_promedio'], 0) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="service-progress">
                                        <div class="progress-bar" style="width: <?= !empty($serviciosPopulares) ? ($servicio['cantidad_ordenes'] / $serviciosPopulares[0]['cantidad_ordenes']) * 100 : 0 ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Gráfico de Participación -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-pie me-2"></i>Participación</h5>
                    <small class="text-muted">Top 5 servicios</small>
                </div>
                <div class="card-body">
                    <?php if (empty($serviciosPopulares)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-pie fa-2x mb-2"></i>
                            <p>No hay datos para el gráfico</p>
                        </div>
                    <?php else: ?>
                        <div class="chart-container">
                            <canvas id="participacionChart" width="300" height="300"></canvas>
                        </div>
                        
                        <!-- Leyenda personalizada -->
                        <div class="chart-legend mt-3">
                            <?php 
                            $colores = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                            $top5 = array_slice($serviciosPopulares, 0, 5);
                            ?>
                            <?php foreach ($top5 as $index => $servicio): ?>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: <?= $colores[$index] ?>"></div>
                                    <div class="legend-text">
                                        <span><?= htmlspecialchars(substr($servicio['Descripcion'], 0, 20)) ?><?= strlen($servicio['Descripcion']) > 20 ? '...' : '' ?></span>
                                        <small><?= $servicio['porcentaje_participacion'] ?>%</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Análisis por Categorías -->
        <?php if (!empty($categorias)): ?>
        <div class="col-12">
            <div class="categories-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-tags me-2"></i>Análisis por Categorías</h5>
                    <small class="text-muted">Rendimiento por tipo de servicio</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($categorias as $categoria): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="category-item">
                                    <div class="category-header">
                                        <h6><?= htmlspecialchars($categoria['categoria']) ?></h6>
                                        <span class="category-badge"><?= $categoria['ordenes'] ?> órdenes</span>
                                    </div>
                                    <div class="category-revenue">
                                        <strong>₡<?= number_format($categoria['ingresos'], 0) ?></strong>
                                        <small>Ingresos totales</small>
                                    </div>
                                    <div class="category-avg">
                                        <span>Promedio: ₡<?= number_format($categoria['ingresos'] / $categoria['ordenes'], 0) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
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

/* ===== SUMMARY CARDS ===== */
.summary-card {
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

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.summary-card.primary .summary-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.summary-card.success .summary-icon { background: linear-gradient(135deg, #10b981, #059669); }
.summary-card.info .summary-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.summary-card.warning .summary-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.summary-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.summary-content p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 4px 0;
    font-weight: 600;
}

.summary-content small {
    font-size: 0.8rem;
    color: #94a3b8;
}

/* ===== RANKING CARD ===== */
.ranking-card, .chart-card, .categories-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    height: 100%;
}

.ranking-card .card-header, .chart-card .card-header, .categories-card .card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: none;
}

.ranking-card .card-header h5, .chart-card .card-header h5, .categories-card .card-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
}

.ranking-card .card-body, .chart-card .card-body, .categories-card .card-body {
    padding: 20px 24px 24px;
}

/* ===== SERVICES RANKING ===== */
.services-ranking {
    max-height: 600px;
    overflow-y: auto;
}

.service-rank-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
    position: relative;
}

.service-rank-item:last-child {
    border-bottom: none;
}

.rank-position {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.rank-number {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: white;
}

.rank-number.rank-1 { background: linear-gradient(135deg, #ffd700, #ffb300); }
.rank-number.rank-2 { background: linear-gradient(135deg, #c0c0c0, #a0a0a0); }
.rank-number.rank-3 { background: linear-gradient(135deg, #cd7f32, #b8860b); }
.rank-number:not(.rank-1):not(.rank-2):not(.rank-3) { background: linear-gradient(135deg, #64748b, #475569); }

.rank-medal {
    position: absolute;
    top: -8px;
    right: -8px;
    color: #ffd700;
    font-size: 16px;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.service-details {
    flex: 1;
}

.service-details h6 {
    margin: 0 0 8px;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.service-metrics {
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

.service-revenue {
    text-align: right;
    min-width: 120px;
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

.service-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #f1f5f9;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    transition: width 0.3s ease;
}

/* ===== CHART CONTAINER ===== */
.chart-container {
    position: relative;
    height: 300px;
}

.chart-legend {
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

/* ===== CATEGORIES ===== */
.category-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.category-item:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.category-header h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 600;
}

.category-badge {
    background: #3b82f6;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.category-revenue strong {
    display: block;
    font-size: 1.2rem;
    color: #10b981;
    margin-bottom: 4px;
}

.category-revenue small {
    color: #64748b;
    font-size: 0.8rem;
}

.category-avg {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #e2e8f0;
}

.category-avg span {
    font-size: 0.85rem;
    color: #475569;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .service-rank-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .service-revenue {
        text-align: left;
        min-width: auto;
    }
    
    .service-metrics {
        flex-direction: column;
        gap: 8px;
    }
    
    .chart-container {
        height: 250px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($serviciosPopulares)): ?>
// Datos para el gráfico de participación
const top5Servicios = <?= json_encode(array_slice($serviciosPopulares, 0, 5)) ?>;
const colores = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];

// Configurar gráfico de participación
const ctx = document.getElementById('participacionChart').getContext('2d');
const participacionChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: top5Servicios.map(s => s.Descripcion.length > 20 ? s.Descripcion.substring(0, 20) + '...' : s.Descripcion),
        datasets: [{
            data: top5Servicios.map(s => parseFloat(s.porcentaje_participacion)),
            backgroundColor: colores,
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
                        return context.label + ': ' + context.parsed + '%';
                    }
                }
            }
        },
        cutout: '60%'
    }
});
<?php endif; ?>

function resetearFiltros() {
    window.location.href = window.location.pathname;
}

function exportarReporte() {
    showAlert('Exportación de reporte en desarrollo', 'info');
}

// Función para mostrar alertas
function showAlert(message, type = 'info') {
    const existingAlert = document.querySelector('.modern-alert');
    if (existingAlert) existingAlert.remove();
    
    const alert = document.createElement('div');
    alert.className = `modern-alert alert-${type}`;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-triangle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    alert.innerHTML = `
        <i class="fa-solid ${icons[type] || icons.info}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="alert-close">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    
    alert.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        background: white; border-radius: 12px; padding: 16px 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border-left: 4px solid ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#3b82f6'};
        display: flex; align-items: center; gap: 12px; max-width: 400px;
        animation: slideIn 0.3s ease; font-weight: 500; color: #1e293b;
    `;
    
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .alert-close { background: none; border: none; color: #64748b; cursor: pointer; padding: 4px; border-radius: 4px; transition: all 0.2s ease; }
        .alert-close:hover { background: #f1f5f9; color: #1e293b; }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(alert);
    setTimeout(() => {
        if (alert && alert.parentNode) {
            alert.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => alert.remove(), 300);
        }
    }, 5000);
}
</script>

<?php require 'lavacar/partials/footer.php'; ?>