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

// Parámetros de año
$year = (int)($_GET['year'] ?? date('Y'));
if ($year < 2020 || $year > 2030) {
    $year = (int)date('Y');
}

// Obtener ingresos mensuales del año
$ingresosMensuales = CrearConsulta($conn,
    "SELECT 
        MONTH(FechaIngreso) as mes,
        MONTHNAME(FechaIngreso) as nombre_mes,
        COUNT(*) as total_ordenes,
        SUM(Monto) as ingresos_totales,
        AVG(Monto) as ticket_promedio,
        COUNT(DISTINCT ClienteID) as clientes_unicos
     FROM {$dbName}.ordenes
     WHERE YEAR(FechaIngreso) = ? AND Estado >= 3
     GROUP BY MONTH(FechaIngreso), MONTHNAME(FechaIngreso)
     ORDER BY mes",
    array($year))->fetch_all(MYSQLI_ASSOC);

// Completar meses faltantes
$mesesCompletos = array();
$mesesNombres = array(
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
);

for ($i = 1; $i <= 12; $i++) {
    $mesData = null;
    foreach ($ingresosMensuales as $mes) {
        if ($mes['mes'] == $i) {
            $mesData = $mes;
            break;
        }
    }
    
    $mesesCompletos[] = array(
        'mes' => $i,
        'nombre_mes' => $mesesNombres[$i],
        'total_ordenes' => $mesData ? $mesData['total_ordenes'] : 0,
        'ingresos_totales' => $mesData ? $mesData['ingresos_totales'] : 0,
        'ticket_promedio' => $mesData ? $mesData['ticket_promedio'] : 0,
        'clientes_unicos' => $mesData ? $mesData['clientes_unicos'] : 0
    );
}

// Estadísticas del año
$estadisticasAnuales = array(
    'ingresos_totales' => array_sum(array_column($mesesCompletos, 'ingresos_totales')),
    'ordenes_totales' => array_sum(array_column($mesesCompletos, 'total_ordenes')),
    'clientes_unicos' => 0,
    'ticket_promedio' => 0,
    'mejor_mes' => null,
    'peor_mes' => null
);

// Calcular clientes únicos del año
$clientesUnicos = ObtenerPrimerRegistro($conn,
    "SELECT COUNT(DISTINCT ClienteID) as total
     FROM {$dbName}.ordenes
     WHERE YEAR(FechaIngreso) = ? AND Estado >= 3",
    array($year));
$estadisticasAnuales['clientes_unicos'] = $clientesUnicos['total'] ?? 0;

if ($estadisticasAnuales['ordenes_totales'] > 0) {
    $estadisticasAnuales['ticket_promedio'] = $estadisticasAnuales['ingresos_totales'] / $estadisticasAnuales['ordenes_totales'];
}

// Encontrar mejor y peor mes
$mesesConDatos = array_filter($mesesCompletos, function($mes) { return $mes['ingresos_totales'] > 0; });
if (!empty($mesesConDatos)) {
    usort($mesesConDatos, function($a, $b) {
        if ($a['ingresos_totales'] == $b['ingresos_totales']) return 0;
        return ($a['ingresos_totales'] < $b['ingresos_totales']) ? 1 : -1;
    });
    $estadisticasAnuales['mejor_mes'] = $mesesConDatos[0];
    $estadisticasAnuales['peor_mes'] = end($mesesConDatos);
}

// Comparación con año anterior
$yearAnterior = $year - 1;
$ingresoYearAnterior = ObtenerPrimerRegistro($conn,
    "SELECT COALESCE(SUM(Monto), 0) as total
     FROM {$dbName}.ordenes
     WHERE YEAR(FechaIngreso) = ? AND Estado >= 3",
    array($yearAnterior));

$crecimiento = 0;
if ($ingresoYearAnterior['total'] > 0) {
    $crecimiento = (($estadisticasAnuales['ingresos_totales'] - $ingresoYearAnterior['total']) / $ingresoYearAnterior['total']) * 100;
}

// Proyección para el resto del año (si es año actual)
$proyeccion = null;
if ($year == date('Y')) {
    $mesActual = (int)date('n');
    $mesesTranscurridos = $mesActual;
    
    if ($mesesTranscurridos > 0) {
        $promedioMensual = $estadisticasAnuales['ingresos_totales'] / $mesesTranscurridos;
        $proyeccion = $promedioMensual * 12;
    }
}

require 'lavacar/partials/header.php';
?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-chart-column me-2"></i>Ingresos Mensuales <?= $year ?></h2>
            <p class="text-muted mb-0">Evolución de ingresos y análisis de tendencias</p>
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

    <!-- Selector de Año -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <select class="form-select" name="year">
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-report-info">
                            <i class="fa-solid fa-search me-1"></i> Ver Año
                        </button>
                        <button type="button" class="btn btn-outline-report-warning ms-2" onclick="verAnoActual()">
                            <i class="fa-solid fa-calendar me-1"></i> Año Actual
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Estadísticas Anuales -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="annual-card primary">
                <div class="annual-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="annual-content">
                    <h3>₡<?= number_format($estadisticasAnuales['ingresos_totales'], 0) ?></h3>
                    <p>Ingresos Totales <?= $year ?></p>
                    <?php if ($crecimiento != 0): ?>
                        <small class="growth-indicator <?= $crecimiento > 0 ? 'positive' : 'negative' ?>">
                            <i class="fa-solid fa-arrow-<?= $crecimiento > 0 ? 'up' : 'down' ?>"></i>
                            <?= abs(number_format($crecimiento, 1)) ?>% vs <?= $yearAnterior ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="annual-card success">
                <div class="annual-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="annual-content">
                    <h3><?= number_format($estadisticasAnuales['ordenes_totales']) ?></h3>
                    <p>Órdenes Completadas</p>
                    <small>Promedio: <?= $estadisticasAnuales['ordenes_totales'] > 0 ? number_format($estadisticasAnuales['ordenes_totales'] / 12, 1) : 0 ?> por mes</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="annual-card info">
                <div class="annual-icon">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div class="annual-content">
                    <h3>₡<?= number_format($estadisticasAnuales['ticket_promedio'], 0) ?></h3>
                    <p>Ticket Promedio</p>
                    <small>Por orden completada</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="annual-card warning">
                <div class="annual-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="annual-content">
                    <h3><?= $estadisticasAnuales['clientes_unicos'] ?></h3>
                    <p>Clientes Únicos</p>
                    <small>Atendidos en <?= $year ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Gráfico de Ingresos Mensuales -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-area me-2"></i>Evolución Mensual de Ingresos</h5>
                    <small class="text-muted">Tendencia de ingresos por mes en <?= $year ?></small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ingresosChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mejores y Peores Meses -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-medal me-2"></i>Rendimiento Mensual</h5>
                    <small class="text-muted">Mejores y peores resultados</small>
                </div>
                <div class="card-body">
                    <?php if ($estadisticasAnuales['mejor_mes']): ?>
                        <div class="performance-item best">
                            <div class="performance-icon">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Mejor Mes</h6>
                                <strong><?= $estadisticasAnuales['mejor_mes']['nombre_mes'] ?></strong>
                                <div class="performance-stats">
                                    <span>₡<?= number_format($estadisticasAnuales['mejor_mes']['ingresos_totales'], 0) ?></span>
                                    <small><?= $estadisticasAnuales['mejor_mes']['total_ordenes'] ?> órdenes</small>
                                </div>
                            </div>
                        </div>

                        <div class="performance-item worst">
                            <div class="performance-icon">
                                <i class="fa-solid fa-chart-line-down"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Mes con Menor Ingreso</h6>
                                <strong><?= $estadisticasAnuales['peor_mes']['nombre_mes'] ?></strong>
                                <div class="performance-stats">
                                    <span>₡<?= number_format($estadisticasAnuales['peor_mes']['ingresos_totales'], 0) ?></span>
                                    <small><?= $estadisticasAnuales['peor_mes']['total_ordenes'] ?> órdenes</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($proyeccion && $year == date('Y')): ?>
                        <div class="performance-item projection">
                            <div class="performance-icon">
                                <i class="fa-solid fa-crystal-ball"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Proyección Anual</h6>
                                <strong>₡<?= number_format($proyeccion, 0) ?></strong>
                                <div class="performance-stats">
                                    <span>Basado en promedio actual</span>
                                    <small>Faltan <?= 12 - (int)date('n') ?> meses</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($estadisticasAnuales['mejor_mes'])): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-simple fa-2x mb-2"></i>
                            <p>No hay datos para mostrar</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla Mensual Detallada -->
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-table me-2"></i>Detalle Mensual <?= $year ?></h5>
                    <small class="text-muted">Información completa por mes</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mes</th>
                                    <th class="text-center">Órdenes</th>
                                    <th class="text-end">Ingresos</th>
                                    <th class="text-end">Ticket Promedio</th>
                                    <th class="text-center">Clientes Únicos</th>
                                    <th class="text-center">Crecimiento</th>
                                    <th class="text-center">Rendimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mesesCompletos as $index => $mes): ?>
                                    <?php
                                    $crecimientoMes = 0;
                                    if ($index > 0 && $mesesCompletos[$index-1]['ingresos_totales'] > 0) {
                                        $crecimientoMes = (($mes['ingresos_totales'] - $mesesCompletos[$index-1]['ingresos_totales']) / $mesesCompletos[$index-1]['ingresos_totales']) * 100;
                                    }
                                    
                                    $rendimiento = 'normal';
                                    if ($estadisticasAnuales['mejor_mes'] && $mes['mes'] == $estadisticasAnuales['mejor_mes']['mes']) {
                                        $rendimiento = 'mejor';
                                    } elseif ($estadisticasAnuales['peor_mes'] && $mes['mes'] == $estadisticasAnuales['peor_mes']['mes'] && $mes['ingresos_totales'] > 0) {
                                        $rendimiento = 'peor';
                                    }
                                    
                                    $rendimientoClass = array(
                                        'mejor' => 'report-success',
                                        'peor' => 'report-warning',
                                        'normal' => 'light'
                                    )[$rendimiento];
                                    
                                    $rendimientoText = array(
                                        'mejor' => 'Mejor',
                                        'peor' => 'Menor',
                                        'normal' => 'Normal'
                                    )[$rendimiento];
                                    ?>
                                    <tr class="<?= $mes['ingresos_totales'] == 0 ? 'table-light' : '' ?>">
                                        <td>
                                            <strong><?= $mes['nombre_mes'] ?></strong>
                                            <?php if ($year == date('Y') && $mes['mes'] == date('n')): ?>
                                                <span class="badge badge-report-info ms-2">Actual</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $mes['total_ordenes'] > 0 ? '<span class="badge badge-report-info">' . $mes['total_ordenes'] . '</span>' : '-' ?>
                                        </td>
                                        <td class="text-end">
                                            <?= $mes['ingresos_totales'] > 0 ? '<strong class="text-success">₡' . number_format($mes['ingresos_totales'], 0) . '</strong>' : '-' ?>
                                        </td>
                                        <td class="text-end">
                                            <?= $mes['ticket_promedio'] > 0 ? '₡' . number_format($mes['ticket_promedio'], 0) : '-' ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $mes['clientes_unicos'] > 0 ? $mes['clientes_unicos'] : '-' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($crecimientoMes != 0): ?>
                                                <span class="growth-badge <?= $crecimientoMes > 0 ? 'positive' : 'negative' ?>">
                                                    <i class="fa-solid fa-arrow-<?= $crecimientoMes > 0 ? 'up' : 'down' ?>"></i>
                                                    <?= abs(number_format($crecimientoMes, 1)) ?>%
                                                </span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($mes['ingresos_totales'] > 0): ?>
                                                <span class="badge badge-<?= $rendimientoClass ?> text-dark"><?= $rendimientoText ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Sin datos</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>TOTAL <?= $year ?></th>
                                    <th class="text-center"><?= number_format($estadisticasAnuales['ordenes_totales']) ?></th>
                                    <th class="text-end">₡<?= number_format($estadisticasAnuales['ingresos_totales'], 0) ?></th>
                                    <th class="text-end">₡<?= number_format($estadisticasAnuales['ticket_promedio'], 0) ?></th>
                                    <th class="text-center"><?= $estadisticasAnuales['clientes_unicos'] ?></th>
                                    <th class="text-center">
                                        <?php if ($crecimiento != 0): ?>
                                            <span class="growth-badge <?= $crecimiento > 0 ? 'positive' : 'negative' ?>">
                                                <i class="fa-solid fa-arrow-<?= $crecimiento > 0 ? 'up' : 'down' ?>"></i>
                                                <?= abs(number_format($crecimiento, 1)) ?>%
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </th>
                                    <th class="text-center">-</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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

/* ===== ANNUAL CARDS ===== */
.annual-card {
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

.annual-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.annual-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.annual-card.primary .annual-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.annual-card.success .annual-icon { background: linear-gradient(135deg, #10b981, #059669); }
.annual-card.info .annual-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.annual-card.warning .annual-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.annual-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.annual-content p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 4px 0;
    font-weight: 600;
}

.growth-indicator {
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.growth-indicator.positive { color: #10b981; }
.growth-indicator.negative { color: #ef4444; }

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
    height: 350px;
}

/* ===== PERFORMANCE ITEMS ===== */
.performance-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    margin-bottom: 16px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.performance-item.best {
    background: linear-gradient(135deg, #fef3c7, #fbbf24);
    border-color: #f59e0b;
}

.performance-item.worst {
    background: linear-gradient(135deg, #fee2e2, #fca5a5);
    border-color: #ef4444;
}

.performance-item.projection {
    background: linear-gradient(135deg, #dbeafe, #93c5fd);
    border-color: #3b82f6;
}

.performance-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    background: rgba(0, 0, 0, 0.1);
}

.performance-content h6 {
    margin: 0 0 4px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
}

.performance-content strong {
    display: block;
    font-size: 1.1rem;
    color: #1e293b;
    margin-bottom: 4px;
}

.performance-stats span {
    font-size: 0.9rem;
    font-weight: 600;
    color: #059669;
}

.performance-stats small {
    display: block;
    font-size: 0.8rem;
    color: #6b7280;
}

/* ===== GROWTH BADGES ===== */
.growth-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.growth-badge.positive {
    background: #d1fae5;
    color: #065f46;
}

.growth-badge.negative {
    background: #fee2e2;
    color: #991b1b;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .annual-card {
        padding: 16px;
    }
    
    .annual-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .annual-content h3 {
        font-size: 1.5rem;
    }
    
    .performance-item {
        flex-direction: column;
        text-align: center;
    }
    
    .chart-container {
        height: 250px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para el gráfico de ingresos mensuales
const mesesData = <?= json_encode($mesesCompletos) ?>;

// Configurar gráfico de ingresos mensuales
const ctx = document.getElementById('ingresosChart').getContext('2d');
const ingresosChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: mesesData.map(function(mes) { return mes.nombre_mes; }),
        datasets: [{
            label: 'Ingresos (₡)',
            data: mesesData.map(function(mes) { return parseFloat(mes.ingresos_totales); }),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }, {
            label: 'Órdenes',
            data: mesesData.map(function(mes) { return parseInt(mes.total_ordenes); }),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            fill: false,
            tension: 0.4,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₡' + value.toLocaleString();
                    }
                },
                grid: {
                    color: '#f1f5f9'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    callback: function(value) {
                        return value + ' órdenes';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

function verAnoActual() {
    window.location.href = window.location.pathname + '?year=' + new Date().getFullYear();
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