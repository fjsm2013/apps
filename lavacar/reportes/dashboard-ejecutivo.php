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

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

// Obtener datos para el dashboard
$hoy = date('Y-m-d');
$inicioMes = date('Y-m-01');
$finMes = date('Y-m-t');
$inicioSemana = date('Y-m-d', strtotime('monday this week'));
$finSemana = date('Y-m-d', strtotime('sunday this week'));

// KPIs principales
$ventasHoy = ObtenerPrimerRegistro($conn, 
    "SELECT COALESCE(SUM(Monto), 0) as total FROM {$dbName}.ordenes 
     WHERE DATE(FechaIngreso) = ? AND Estado >= 3", [$hoy])['total'] ?? 0;

$ventasMes = ObtenerPrimerRegistro($conn, 
    "SELECT COALESCE(SUM(Monto), 0) as total FROM {$dbName}.ordenes 
     WHERE DATE(FechaIngreso) BETWEEN ? AND ? AND Estado >= 3", [$inicioMes, $finMes])['total'] ?? 0;

$ordenesHoy = ObtenerPrimerRegistro($conn, 
    "SELECT COUNT(*) as total FROM {$dbName}.ordenes 
     WHERE DATE(FechaIngreso) = ?", [$hoy])['total'] ?? 0;

$ordenesMes = ObtenerPrimerRegistro($conn, 
    "SELECT COUNT(*) as total FROM {$dbName}.ordenes 
     WHERE DATE(FechaIngreso) BETWEEN ? AND ?", [$inicioMes, $finMes])['total'] ?? 0;

$clientesNuevos = ObtenerPrimerRegistro($conn, 
    "SELECT COUNT(*) as total FROM {$dbName}.clientes 
     WHERE DATE(fecha_registro) BETWEEN ? AND ?", [$inicioMes, $finMes])['total'] ?? 0;

// Ordenes activas por estado
$ordenesActivas = $ordenManager->getActive();
$estadosCount = array('pendientes' => 0, 'proceso' => 0, 'terminados' => 0);
foreach ($ordenesActivas as $orden) {
    switch ($orden['Estado']) {
        case 1: $estadosCount['pendientes']++; break;
        case 2: $estadosCount['proceso']++; break;
        case 3: $estadosCount['terminados']++; break;
    }
}

// Servicios mas populares del mes
$serviciosPopulares = CrearConsulta($conn,
    "SELECT s.Descripcion, COUNT(*) as cantidad, SUM(o.Monto) as ingresos
     FROM {$dbName}.ordenes o
     JOIN {$dbName}.servicios s ON JSON_EXTRACT(o.ServiciosJSON, '$[0].id') = s.ID
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ? AND o.Estado >= 3
     GROUP BY s.ID, s.Descripcion
     ORDER BY cantidad DESC
     LIMIT 5", array($inicioMes, $finMes))->fetch_all(MYSQLI_ASSOC);

// Ventas por dia de la semana actual
$ventasSemana = CrearConsulta($conn,
    "SELECT DATE(FechaIngreso) as fecha, COALESCE(SUM(Monto), 0) as total
     FROM {$dbName}.ordenes 
     WHERE DATE(FechaIngreso) BETWEEN ? AND ? AND Estado >= 3
     GROUP BY DATE(FechaIngreso)
     ORDER BY fecha", array($inicioSemana, $finSemana))->fetch_all(MYSQLI_ASSOC);

// Completar dias faltantes de la semana
$ventasPorDia = array();
for ($i = 0; $i < 7; $i++) {
    $fecha = date('Y-m-d', strtotime($inicioSemana . " +{$i} days"));
    $dia = date('l', strtotime($fecha));
    $diasEs = array(
        'Monday' => 'Lun', 'Tuesday' => 'Mar', 'Wednesday' => 'Mie',
        'Thursday' => 'Jue', 'Friday' => 'Vie', 'Saturday' => 'Sab', 'Sunday' => 'Dom'
    );
    
    $venta = array_filter($ventasSemana, function($v) use ($fecha) { return $v['fecha'] === $fecha; });
    $ventasPorDia[] = array(
        'dia' => $diasEs[$dia],
        'fecha' => $fecha,
        'total' => $venta ? array_values($venta)[0]['total'] : 0
    );
}

require 'lavacar/partials/header.php';
?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-chart-pie me-2"></i>Dashboard Ejecutivo</h2>
            <p class="text-muted mb-0">Vista general del rendimiento del negocio</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-report-info btn-sm" onclick="actualizarDatos()">
                <i class="fa-solid fa-refresh me-1"></i> Actualizar
            </button>
            <a href="index.php" class="btn btn-frosh-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <!-- KPIs Principales -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="kpi-card success">
                <div class="kpi-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="kpi-content">
                    <h3>₡<?= number_format($ventasHoy, 0) ?></h3>
                    <p>Ventas Hoy</p>
                    <small class="kpi-trend positive">
                        <i class="fa-solid fa-arrow-up"></i>
                        Dia actual
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="kpi-card info">
                <div class="kpi-icon">
                    <i class="fa-solid fa-calendar-month"></i>
                </div>
                <div class="kpi-content">
                    <h3>₡<?= number_format($ventasMes, 0) ?></h3>
                    <p>Ventas del Mes</p>
                    <small class="kpi-trend">
                        <i class="fa-solid fa-calendar"></i>
                        <?= date('F Y') ?>
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="kpi-card warning">
                <div class="kpi-icon">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div class="kpi-content">
                    <h3><?= $ordenesHoy ?></h3>
                    <p>Ordenes Hoy</p>
                    <small class="kpi-trend">
                        <i class="fa-solid fa-clock"></i>
                        Total del dia
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="kpi-card primary">
                <div class="kpi-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="kpi-content">
                    <h3><?= $clientesNuevos ?></h3>
                    <p>Clientes Nuevos</p>
                    <small class="kpi-trend">
                        <i class="fa-solid fa-user-plus"></i>
                        Este mes
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Grafico de Ventas Semanales -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-line me-2"></i>Ventas de la Semana</h5>
                    <small class="text-muted">Del <?= date('d/m', strtotime($inicioSemana)) ?> al <?= date('d/m', strtotime($finSemana)) ?></small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ventasChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de Ordenes -->
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-tasks me-2"></i>Ordenes Activas</h5>
                    <small class="text-muted">Estado actual</small>
                </div>
                <div class="card-body">
                    <div class="status-item">
                        <div class="status-info">
                            <span class="status-label">Pendientes</span>
                            <span class="status-count"><?= $estadosCount['pendientes'] ?></span>
                        </div>
                        <div class="status-bar">
                            <div class="status-progress warning" style="width: <?= count($ordenesActivas) > 0 ? ($estadosCount['pendientes'] / count($ordenesActivas)) * 100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div class="status-item">
                        <div class="status-info">
                            <span class="status-label">En Proceso</span>
                            <span class="status-count"><?= $estadosCount['proceso'] ?></span>
                        </div>
                        <div class="status-bar">
                            <div class="status-progress info" style="width: <?= count($ordenesActivas) > 0 ? ($estadosCount['proceso'] / count($ordenesActivas)) * 100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div class="status-item">
                        <div class="status-info">
                            <span class="status-label">Terminados</span>
                            <span class="status-count"><?= $estadosCount['terminados'] ?></span>
                        </div>
                        <div class="status-bar">
                            <div class="status-progress success" style="width: <?= count($ordenesActivas) > 0 ? ($estadosCount['terminados'] / count($ordenesActivas)) * 100 : 0 ?>%"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="ordenes-activas.php" class="btn btn-report-success btn-sm w-100">
                            <i class="fa-solid fa-eye me-1"></i> Ver Todas las Ordenes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios Populares -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-trophy me-2"></i>Servicios Mas Populares</h5>
                    <small class="text-muted">Top 5 del mes</small>
                </div>
                <div class="card-body">
                    <?php if (empty($serviciosPopulares)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-simple fa-2x mb-2"></i>
                            <p>No hay datos suficientes para mostrar</p>
                        </div>
                    <?php else: ?>
                        <div class="services-list">
                            <?php foreach ($serviciosPopulares as $index => $servicio): ?>
                                <div class="service-item">
                                    <div class="service-rank"><?= $index + 1 ?></div>
                                    <div class="service-info">
                                        <h6><?= htmlspecialchars($servicio['Descripcion']) ?></h6>
                                        <small><?= $servicio['cantidad'] ?> veces - ₡<?= number_format($servicio['ingresos'], 0) ?></small>
                                    </div>
                                    <div class="service-progress">
                                        <div class="progress-bar" style="width: <?= ($servicio['cantidad'] / $serviciosPopulares[0]['cantidad']) * 100 ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Resumen Mensual -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-calendar-check me-2"></i>Resumen del Mes</h5>
                    <small class="text-muted"><?= date('F Y') ?></small>
                </div>
                <div class="card-body">
                    <div class="summary-stats">
                        <div class="summary-item">
                            <div class="summary-icon success">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                            <div class="summary-content">
                                <h6>Ingresos Totales</h6>
                                <p>₡<?= number_format($ventasMes, 0) ?></p>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-icon info">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <div class="summary-content">
                                <h6>Ordenes Procesadas</h6>
                                <p><?= $ordenesMes ?> ordenes</p>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-icon warning">
                                <i class="fa-solid fa-calculator"></i>
                            </div>
                            <div class="summary-content">
                                <h6>Ticket Promedio</h6>
                                <p>₡<?= $ordenesMes > 0 ? number_format($ventasMes / $ordenesMes, 0) : 0 ?></p>
                            </div>
                        </div>

                        <div class="summary-item">
                            <div class="summary-icon primary">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <div class="summary-content">
                                <h6>Nuevos Clientes</h6>
                                <p><?= $clientesNuevos ?> clientes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* ===== KPI CARDS ===== */
.kpi-card {
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

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.kpi-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.kpi-card.success .kpi-icon { background: linear-gradient(135deg, #10b981, #059669); }
.kpi-card.info .kpi-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.kpi-card.warning .kpi-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.kpi-card.primary .kpi-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.kpi-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.kpi-content p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 4px 0;
    font-weight: 600;
}

.kpi-trend {
    font-size: 0.8rem;
    color: #94a3b8;
}

.kpi-trend.positive {
    color: #10b981;
}

/* ===== DASHBOARD CARDS ===== */
.dashboard-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    height: 100%;
}

.dashboard-card .card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: none;
}

.dashboard-card .card-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
}

.dashboard-card .card-body {
    padding: 20px 24px 24px;
}

/* ===== CHART CONTAINER ===== */
.chart-container {
    position: relative;
    height: 300px;
}

/* ===== STATUS ITEMS ===== */
.status-item {
    margin-bottom: 20px;
}

.status-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.status-label {
    font-weight: 600;
    color: #475569;
}

.status-count {
    font-weight: 700;
    color: #1e293b;
}

.status-bar {
    height: 8px;
    background: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
}

.status-progress {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.status-progress.warning { background: linear-gradient(90deg, #f59e0b, #d97706); }
.status-progress.info { background: linear-gradient(90deg, #3b82f6, #2563eb); }
.status-progress.success { background: linear-gradient(90deg, #10b981, #059669); }

/* ===== SERVICES LIST ===== */
.services-list {
    max-height: 300px;
    overflow-y: auto;
}

.service-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.service-item:last-child {
    border-bottom: none;
}

.service-rank {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.service-info {
    flex: 1;
}

.service-info h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
}

.service-info small {
    color: #64748b;
}

.service-progress {
    width: 60px;
    height: 6px;
    background: #f1f5f9;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    border-radius: 3px;
    transition: width 0.3s ease;
}

/* ===== SUMMARY STATS ===== */
.summary-stats {
    display: grid;
    gap: 16px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 12px;
}

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.summary-icon.success { background: linear-gradient(135deg, #10b981, #059669); }
.summary-icon.info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.summary-icon.warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
.summary-icon.primary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.summary-content h6 {
    margin: 0;
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
}

.summary-content p {
    margin: 2px 0 0;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .kpi-card {
        padding: 16px;
    }
    
    .kpi-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .kpi-content h3 {
        font-size: 1.5rem;
    }
    
    .dashboard-card .card-header,
    .dashboard-card .card-body {
        padding: 16px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para el grafico de ventas
const ventasData = <?= json_encode($ventasPorDia) ?>;

// Configurar grafico de ventas semanales
const ctx = document.getElementById('ventasChart').getContext('2d');
const ventasChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ventasData.map(function(v) { return v.dia; }),
        datasets: [{
            label: 'Ventas (₡)',
            data: ventasData.map(function(v) { return parseFloat(v.total); }),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
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
            x: {
                grid: {
                    display: false
                }
            }
        },
        elements: {
            point: {
                hoverBackgroundColor: '#2563eb'
            }
        }
    }
});

function actualizarDatos() {
    showAlert('Datos actualizados correctamente', 'success');
    setTimeout(function() {
        location.reload();
    }, 1000);
}

// Funcion para mostrar alertas
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