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

// Validar fechas
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
    $fechaInicio = date('Y-m-01');
    $fechaFin = date('Y-m-d');
}

// Obtener tiempos de servicio
$tiemposServicio = CrearConsulta($conn,
    "SELECT 
        o.ID,
        o.FechaIngreso,
        o.FechaProceso,
        o.FechaTerminado,
        o.FechaCierre,
        COALESCE(c.NombreCompleto, 'Cliente no asignado') as ClienteNombre,
        COALESCE(v.Placa, 'Sin placa') as Placa,
        o.Monto,
        CASE 
            WHEN o.FechaProceso IS NOT NULL AND o.FechaTerminado IS NOT NULL 
            THEN TIMESTAMPDIFF(MINUTE, o.FechaProceso, o.FechaTerminado)
            ELSE NULL 
        END as TiempoProcesoMinutos,
        CASE 
            WHEN o.FechaIngreso IS NOT NULL AND o.FechaTerminado IS NOT NULL 
            THEN TIMESTAMPDIFF(MINUTE, o.FechaIngreso, o.FechaTerminado)
            ELSE NULL 
        END as TiempoTotalMinutos,
        CASE 
            WHEN o.FechaIngreso IS NOT NULL AND o.FechaProceso IS NOT NULL 
            THEN TIMESTAMPDIFF(MINUTE, o.FechaIngreso, o.FechaProceso)
            ELSE NULL 
        END as TiempoEsperaMinutos
     FROM {$dbName}.ordenes o
     LEFT JOIN {$dbName}.clientes c ON c.ID = o.ClienteID
     LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
     AND o.Estado >= 3
     ORDER BY o.FechaIngreso DESC",
    array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);

// Calcular estadísticas
$estadisticas = array(
    'total_ordenes' => count($tiemposServicio),
    'tiempo_promedio_proceso' => 0,
    'tiempo_promedio_total' => 0,
    'tiempo_promedio_espera' => 0,
    'tiempo_min_proceso' => null,
    'tiempo_max_proceso' => null,
    'ordenes_rapidas' => 0, // menos de 30 min
    'ordenes_lentas' => 0   // más de 120 min
);

$tiemposProceso = array();
$tiemposTotales = array();
$tiemposEspera = array();

foreach ($tiemposServicio as $orden) {
    if ($orden['TiempoProcesoMinutos'] !== null) {
        $tiemposProceso[] = $orden['TiempoProcesoMinutos'];
        
        if ($orden['TiempoProcesoMinutos'] < 30) {
            $estadisticas['ordenes_rapidas']++;
        } elseif ($orden['TiempoProcesoMinutos'] > 120) {
            $estadisticas['ordenes_lentas']++;
        }
    }
    
    if ($orden['TiempoTotalMinutos'] !== null) {
        $tiemposTotales[] = $orden['TiempoTotalMinutos'];
    }
    
    if ($orden['TiempoEsperaMinutos'] !== null) {
        $tiemposEspera[] = $orden['TiempoEsperaMinutos'];
    }
}

if (!empty($tiemposProceso)) {
    $estadisticas['tiempo_promedio_proceso'] = array_sum($tiemposProceso) / count($tiemposProceso);
    $estadisticas['tiempo_min_proceso'] = min($tiemposProceso);
    $estadisticas['tiempo_max_proceso'] = max($tiemposProceso);
}

if (!empty($tiemposTotales)) {
    $estadisticas['tiempo_promedio_total'] = array_sum($tiemposTotales) / count($tiemposTotales);
}

if (!empty($tiemposEspera)) {
    $estadisticas['tiempo_promedio_espera'] = array_sum($tiemposEspera) / count($tiemposEspera);
}

// Análisis por rangos de tiempo
$rangosTiempo = array(
    'rapido' => array('min' => 0, 'max' => 30, 'count' => 0, 'label' => 'Rápido (< 30 min)'),
    'normal' => array('min' => 30, 'max' => 60, 'count' => 0, 'label' => 'Normal (30-60 min)'),
    'lento' => array('min' => 60, 'max' => 120, 'count' => 0, 'label' => 'Lento (60-120 min)'),
    'muy_lento' => array('min' => 120, 'max' => 999, 'count' => 0, 'label' => 'Muy Lento (> 120 min)')
);

foreach ($tiemposProceso as $tiempo) {
    foreach ($rangosTiempo as $key => &$rango) {
        if ($tiempo >= $rango['min'] && $tiempo < $rango['max']) {
            $rango['count']++;
            break;
        }
    }
}

require 'lavacar/partials/header.php';
?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-stopwatch me-2"></i>Tiempos de Servicio</h2>
            <p class="text-muted mb-0">Análisis de duración y eficiencia de servicios</p>
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
                        <button type="button" class="btn btn-outline-frosh-gray ms-2" onclick="resetearFiltros()">
                            <i class="fa-solid fa-refresh me-1"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stats-card primary">
                <div class="stats-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stats-content">
                    <h3><?= number_format($estadisticas['tiempo_promedio_proceso'], 1) ?> min</h3>
                    <p>Tiempo Promedio de Proceso</p>
                    <small>Desde inicio hasta terminado</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stats-card success">
                <div class="stats-icon">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <div class="stats-content">
                    <h3><?= number_format($estadisticas['tiempo_promedio_total'], 1) ?> min</h3>
                    <p>Tiempo Total Promedio</p>
                    <small>Desde ingreso hasta terminado</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stats-card warning">
                <div class="stats-icon">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div class="stats-content">
                    <h3><?= number_format($estadisticas['tiempo_promedio_espera'], 1) ?> min</h3>
                    <p>Tiempo de Espera Promedio</p>
                    <small>Antes de iniciar proceso</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stats-card info">
                <div class="stats-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="stats-content">
                    <h3><?= $estadisticas['total_ordenes'] ?></h3>
                    <p>Órdenes Analizadas</p>
                    <small>En el período seleccionado</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Distribución por Rangos -->
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-bar me-2"></i>Distribución por Tiempo</h5>
                    <small class="text-muted">Clasificación de órdenes por duración</small>
                </div>
                <div class="card-body">
                    <?php if (empty($tiemposProceso)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-bar fa-2x mb-2"></i>
                            <p>No hay datos de tiempos disponibles</p>
                        </div>
                    <?php else: ?>
                        <div class="time-ranges">
                            <?php foreach ($rangosTiempo as $key => $rango): ?>
                                <?php 
                                $porcentaje = $estadisticas['total_ordenes'] > 0 ? ($rango['count'] / $estadisticas['total_ordenes']) * 100 : 0;
                                $colorClass = array(
                                    'rapido' => 'success',
                                    'normal' => 'info', 
                                    'lento' => 'warning',
                                    'muy_lento' => 'danger'
                                )[$key];
                                ?>
                                <div class="time-range-item">
                                    <div class="range-info">
                                        <h6><?= $rango['label'] ?></h6>
                                        <div class="range-stats">
                                            <span class="count"><?= $rango['count'] ?> órdenes</span>
                                            <span class="percentage"><?= number_format($porcentaje, 1) ?>%</span>
                                        </div>
                                    </div>
                                    <div class="range-bar">
                                        <div class="range-progress bg-<?= $colorClass ?>" style="width: <?= $porcentaje ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Gráfico de Tendencias -->
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-line me-2"></i>Tendencia de Tiempos</h5>
                    <small class="text-muted">Evolución de tiempos promedio</small>
                </div>
                <div class="card-body">
                    <?php if (empty($tiemposServicio)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-line fa-2x mb-2"></i>
                            <p>No hay datos para mostrar tendencias</p>
                        </div>
                    <?php else: ?>
                        <div class="chart-container">
                            <canvas id="tendenciasChart" width="400" height="300"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla Detallada -->
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-table me-2"></i>Detalle de Órdenes</h5>
                    <small class="text-muted">Tiempos individuales por orden</small>
                </div>
                <div class="card-body">
                    <?php if (empty($tiemposServicio)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-table fa-2x mb-2"></i>
                            <p>No hay órdenes para mostrar</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Orden</th>
                                        <th>Cliente</th>
                                        <th>Vehículo</th>
                                        <th>Fecha Ingreso</th>
                                        <th class="text-center">Tiempo Espera</th>
                                        <th class="text-center">Tiempo Proceso</th>
                                        <th class="text-center">Tiempo Total</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-center">Eficiencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tiemposServicio as $orden): ?>
                                        <?php
                                        $eficiencia = 'normal';
                                        if ($orden['TiempoProcesoMinutos'] !== null) {
                                            if ($orden['TiempoProcesoMinutos'] < 30) $eficiencia = 'alta';
                                            elseif ($orden['TiempoProcesoMinutos'] > 120) $eficiencia = 'baja';
                                        }
                                        
                                        $eficienciaClass = array(
                                            'alta' => 'success',
                                            'normal' => 'info',
                                            'baja' => 'warning'
                                        )[$eficiencia];
                                        
                                        $eficienciaText = array(
                                            'alta' => 'Alta',
                                            'normal' => 'Normal', 
                                            'baja' => 'Baja'
                                        )[$eficiencia];
                                        ?>
                                        <tr>
                                            <td>
                                                <strong>#<?= $orden['ID'] ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($orden['ClienteNombre']) ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= htmlspecialchars($orden['Placa']) ?></span>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></td>
                                            <td class="text-center">
                                                <?= $orden['TiempoEsperaMinutos'] !== null ? $orden['TiempoEsperaMinutos'] . ' min' : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $orden['TiempoProcesoMinutos'] !== null ? $orden['TiempoProcesoMinutos'] . ' min' : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <strong><?= $orden['TiempoTotalMinutos'] !== null ? $orden['TiempoTotalMinutos'] . ' min' : '-' ?></strong>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-success">₡<?= number_format($orden['Monto'], 0) ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $eficienciaClass ?>"><?= $eficienciaText ?></span>
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
.stats-card.warning .stats-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stats-card.info .stats-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }

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

/* ===== TIME RANGES ===== */
.time-ranges {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.time-range-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.range-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.range-info h6 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
}

.range-stats {
    display: flex;
    gap: 12px;
    align-items: center;
}

.count {
    font-size: 0.9rem;
    color: #64748b;
}

.percentage {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
}

.range-bar {
    height: 8px;
    background: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
}

.range-progress {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
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
    
    .chart-card .card-header, .chart-card .card-body,
    .table-card .card-header, .table-card .card-body {
        padding: 16px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($tiemposServicio)): ?>
// Preparar datos para gráfico de tendencias
const tiemposData = <?= json_encode(array_slice($tiemposServicio, 0, 20)) ?>;

// Configurar gráfico de tendencias
const ctx = document.getElementById('tendenciasChart').getContext('2d');
const tendenciasChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: tiemposData.map(function(orden) {
            const fecha = new Date(orden.FechaIngreso);
            return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
        }),
        datasets: [{
            label: 'Tiempo de Proceso (min)',
            data: tiemposData.map(function(orden) { return orden.TiempoProcesoMinutos; }),
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }, {
            label: 'Tiempo Total (min)',
            data: tiemposData.map(function(orden) { return orden.TiempoTotalMinutos; }),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 2,
            fill: false,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' min';
                    }
                }
            }
        }
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