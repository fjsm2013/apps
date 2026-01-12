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

// Obtener ventas diarias
$ventasDiarias = CrearConsulta($conn,
    "SELECT 
        DATE(FechaIngreso) as fecha,
        COUNT(*) as ordenes,
        COALESCE(SUM(CASE WHEN Estado >= 3 THEN Monto ELSE 0 END), 0) as ventas_completadas,
        COALESCE(SUM(Monto), 0) as ventas_totales,
        COALESCE(AVG(CASE WHEN Estado >= 3 THEN Monto ELSE NULL END), 0) as ticket_promedio
     FROM {$dbName}.ordenes 
     WHERE DATE(FechaIngreso) BETWEEN ? AND ?
     GROUP BY DATE(FechaIngreso)
     ORDER BY fecha DESC", 
    [$fechaInicio, $fechaFin])->fetch_all(MYSQLI_ASSOC);

// Calcular totales del período
$totales = [
    'ordenes' => array_sum(array_column($ventasDiarias, 'ordenes')),
    'ventas_completadas' => array_sum(array_column($ventasDiarias, 'ventas_completadas')),
    'ventas_totales' => array_sum(array_column($ventasDiarias, 'ventas_totales')),
    'ticket_promedio' => count($ventasDiarias) > 0 ? array_sum(array_column($ventasDiarias, 'ventas_completadas')) / array_sum(array_column($ventasDiarias, 'ordenes')) : 0
];

// Obtener el mejor día
$mejorDia = !empty($ventasDiarias) ? array_reduce($ventasDiarias, function($carry, $item) {
    return (!$carry || $item['ventas_completadas'] > $carry['ventas_completadas']) ? $item : $carry;
}) : null;

// Datos para gráfico
$chartData = array_reverse($ventasDiarias); // Mostrar cronológicamente

require 'lavacar/partials/header.php';
?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-calendar-day me-2"></i>Ventas Diarias</h2>
            <p class="text-muted mb-0">Análisis detallado de ingresos por día</p>
        </div>
        <div class="d-flex gap-2">
            <!--<button class="btn btn-report-success btn-sm" onclick="exportarExcel()">
                <i class="fa-solid fa-file-excel me-1"></i> Excel
            </button>-->
            <a href="index.php" class="btn btn-frosh-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros de Fecha -->
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

    <!-- Resumen del Período -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="summary-card success">
                <div class="summary-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="summary-content">
                    <h3>₡<?= number_format($totales['ventas_completadas'], 0) ?></h3>
                    <p>Ventas Completadas</p>
                    <small>Del período seleccionado</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card info">
                <div class="summary-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="summary-content">
                    <h3><?= $totales['ordenes'] ?></h3>
                    <p>Órdenes Totales</p>
                    <small>Todas las órdenes</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card warning">
                <div class="summary-icon">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div class="summary-content">
                    <h3>₡<?= number_format($totales['ticket_promedio'], 0) ?></h3>
                    <p>Ticket Promedio</p>
                    <small>Por orden completada</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card primary">
                <div class="summary-icon">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div class="summary-content">
                    <h3><?= $mejorDia ? '₡' . number_format($mejorDia['ventas_completadas'], 0) : '₡0' ?></h3>
                    <p>Mejor Día</p>
                    <small><?= $mejorDia ? date('d/m/Y', strtotime($mejorDia['fecha'])) : 'N/A' ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Gráfico de Ventas -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-area me-2"></i>Evolución de Ventas</h5>
                    <small class="text-muted">Ventas completadas por día</small>
                </div>
                <div class="card-body">
                    <?php if (empty($ventasDiarias)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-chart-simple fa-3x mb-3"></i>
                            <h5>No hay datos para mostrar</h5>
                            <p>No se encontraron ventas en el período seleccionado</p>
                        </div>
                    <?php else: ?>
                        <div class="chart-container">
                            <canvas id="ventasChart" width="400" height="200"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Días -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-medal me-2"></i>Top 5 Días</h5>
                    <small class="text-muted">Mejores días del período</small>
                </div>
                <div class="card-body">
                    <?php 
                    $topDias = $ventasDiarias;
                    usort($topDias, function($a, $b) { 
                        if ($a['ventas_completadas'] == $b['ventas_completadas']) return 0;
                        return ($a['ventas_completadas'] < $b['ventas_completadas']) ? 1 : -1;
                    });
                    $topDias = array_slice($topDias, 0, 5);
                    ?>
                    
                    <?php if (empty($topDias)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-trophy fa-2x mb-2"></i>
                            <p>No hay datos disponibles</p>
                        </div>
                    <?php else: ?>
                        <div class="top-days-list">
                            <?php foreach ($topDias as $index => $dia): ?>
                                <div class="top-day-item">
                                    <div class="day-rank rank-<?= $index + 1 ?>"><?= $index + 1 ?></div>
                                    <div class="day-info">
                                        <h6><?= date('d/m/Y', strtotime($dia['fecha'])) ?></h6>
                                        <small><?= date('l', strtotime($dia['fecha'])) ?></small>
                                    </div>
                                    <div class="day-amount">
                                        <strong>₡<?= number_format($dia['ventas_completadas'], 0) ?></strong>
                                        <small><?= $dia['ordenes'] ?> órdenes</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla Detallada -->
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-table me-2"></i>Detalle Diario</h5>
                    <small class="text-muted">Información completa por día</small>
                </div>
                <div class="card-body">
                    <?php if (empty($ventasDiarias)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-table fa-2x mb-2"></i>
                            <p>No hay datos para mostrar en la tabla</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Día</th>
                                        <th class="text-center">Órdenes</th>
                                        <th class="text-end">Ventas Completadas</th>
                                        <th class="text-end">Ventas Totales</th>
                                        <th class="text-end">Ticket Promedio</th>
                                        <th class="text-center">Tasa Completado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventasDiarias as $dia): ?>
                                        <?php 
                                        $tasaCompletado = $dia['ventas_totales'] > 0 ? ($dia['ventas_completadas'] / $dia['ventas_totales']) * 100 : 0;
                                        $diaSemana = [
                                            'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles',
                                            'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'
                                        ][date('l', strtotime($dia['fecha']))];
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?= date('d/m/Y', strtotime($dia['fecha'])) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= $diaSemana ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?= $dia['ordenes'] ?></span>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-success">₡<?= number_format($dia['ventas_completadas'], 0) ?></strong>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-muted">₡<?= number_format($dia['ventas_totales'], 0) ?></span>
                                            </td>
                                            <td class="text-end">
                                                ₡<?= number_format($dia['ticket_promedio'], 0) ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" style="width: <?= $tasaCompletado ?>%">
                                                        <?= number_format($tasaCompletado, 1) ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">TOTALES</th>
                                        <th class="text-center"><?= $totales['ordenes'] ?></th>
                                        <th class="text-end">₡<?= number_format($totales['ventas_completadas'], 0) ?></th>
                                        <th class="text-end">₡<?= number_format($totales['ventas_totales'], 0) ?></th>
                                        <th class="text-end">₡<?= number_format($totales['ticket_promedio'], 0) ?></th>
                                        <th class="text-center">
                                            <?= $totales['ventas_totales'] > 0 ? number_format(($totales['ventas_completadas'] / $totales['ventas_totales']) * 100, 1) : 0 ?>%
                                        </th>
                                    </tr>
                                </tfoot>
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

.summary-card.success .summary-icon { background: linear-gradient(135deg, #10b981, #059669); }
.summary-card.info .summary-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.summary-card.warning .summary-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.summary-card.primary .summary-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

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

/* ===== TOP DAYS LIST ===== */
.top-days-list {
    max-height: 350px;
    overflow-y: auto;
}

.top-day-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.top-day-item:last-child {
    border-bottom: none;
}

.day-rank {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
}

.day-rank.rank-1 { background: linear-gradient(135deg, #ffd700, #ffb300); }
.day-rank.rank-2 { background: linear-gradient(135deg, #c0c0c0, #a0a0a0); }
.day-rank.rank-3 { background: linear-gradient(135deg, #cd7f32, #b8860b); }
.day-rank:not(.rank-1):not(.rank-2):not(.rank-3) { background: linear-gradient(135deg, #64748b, #475569); }

.day-info {
    flex: 1;
}

.day-info h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
}

.day-info small {
    color: #64748b;
    text-transform: capitalize;
}

.day-amount {
    text-align: right;
}

.day-amount strong {
    display: block;
    color: #10b981;
    font-size: 0.95rem;
}

.day-amount small {
    color: #64748b;
    font-size: 0.8rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .summary-card {
        padding: 16px;
    }
    
    .summary-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .summary-content h3 {
        font-size: 1.5rem;
    }
    
    .chart-card .card-header, .chart-card .card-body,
    .table-card .card-header, .table-card .card-body {
        padding: 16px;
    }
    
    .chart-container {
        height: 250px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($ventasDiarias)): ?>
// Datos para el gráfico
const chartData = <?= json_encode($chartData) ?>;

// Configurar gráfico
const ctx = document.getElementById('ventasChart').getContext('2d');
const ventasChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(d => {
            const fecha = new Date(d.fecha + 'T00:00:00');
            return fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
        }),
        datasets: [{
            label: 'Ventas Completadas (₡)',
            data: chartData.map(d => parseFloat(d.ventas_completadas)),
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
            data: chartData.map(d => parseInt(d.ordenes)),
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
<?php endif; ?>

function resetearFiltros() {
    window.location.href = window.location.pathname;
}

function exportarExcel() {
    showAlert('Exportación a Excel en desarrollo', 'info');
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