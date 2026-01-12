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
$limite = (int)($_GET['limite'] ?? 20);

// Validar parámetros
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
    $fechaInicio = date('Y-m-01');
    $fechaFin = date('Y-m-d');
}

if ($limite < 10 || $limite > 100) {
    $limite = 20;
}

// Top clientes frecuentes
$clientesFrecuentes = CrearConsulta($conn,
    "SELECT 
        c.ID,
        c.NombreCompleto,
        c.Cedula,
        c.Correo,
        c.Telefono,
        COUNT(o.ID) as total_visitas,
        SUM(o.Monto) as gasto_total,
        AVG(o.Monto) as gasto_promedio,
        MAX(o.FechaIngreso) as ultima_visita,
        MIN(o.FechaIngreso) as primera_visita,
        COUNT(DISTINCT v.ID) as vehiculos_registrados,
        DATEDIFF(MAX(o.FechaIngreso), MIN(o.FechaIngreso)) as dias_como_cliente
     FROM {$dbName}.clientes c
     JOIN {$dbName}.ordenes o ON o.ClienteID = c.ID
     LEFT JOIN {$dbName}.vehiculos v ON v.ClienteID = c.ID
     WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
     AND o.Estado >= 3
     GROUP BY c.ID
     ORDER BY total_visitas DESC, gasto_total DESC
     LIMIT ?",
    array($fechaInicio, $fechaFin, $limite))->fetch_all(MYSQLI_ASSOC);

// Análisis de lealtad por rangos
$rangosFrecuencia = CrearConsulta($conn,
    "SELECT 
        CASE 
            WHEN visitas = 1 THEN 'Ocasional (1 visita)'
            WHEN visitas BETWEEN 2 AND 3 THEN 'Regular (2-3 visitas)'
            WHEN visitas BETWEEN 4 AND 6 THEN 'Frecuente (4-6 visitas)'
            WHEN visitas BETWEEN 7 AND 10 THEN 'Muy Frecuente (7-10 visitas)'
            ELSE 'VIP (10+ visitas)'
        END as categoria_lealtad,
        COUNT(*) as cantidad_clientes,
        SUM(gasto_total) as ingresos_categoria,
        AVG(gasto_promedio) as ticket_promedio_categoria
     FROM (
         SELECT 
             c.ID,
             COUNT(o.ID) as visitas,
             SUM(o.Monto) as gasto_total,
             AVG(o.Monto) as gasto_promedio
         FROM {$dbName}.clientes c
         JOIN {$dbName}.ordenes o ON o.ClienteID = c.ID
         WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
         AND o.Estado >= 3
         GROUP BY c.ID
     ) as cliente_stats
     GROUP BY categoria_lealtad
     ORDER BY 
         CASE categoria_lealtad
             WHEN 'Ocasional (1 visita)' THEN 1
             WHEN 'Regular (2-3 visitas)' THEN 2
             WHEN 'Frecuente (4-6 visitas)' THEN 3
             WHEN 'Muy Frecuente (7-10 visitas)' THEN 4
             ELSE 5
         END",
    array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);

// Estadísticas generales
$estadisticasGenerales = array(
    'total_clientes' => count($clientesFrecuentes),
    'gasto_total' => array_sum(array_column($clientesFrecuentes, 'gasto_total')),
    'visitas_totales' => array_sum(array_column($clientesFrecuentes, 'total_visitas')),
    'ticket_promedio' => 0,
    'cliente_top' => null
);

if ($estadisticasGenerales['visitas_totales'] > 0) {
    $estadisticasGenerales['ticket_promedio'] = $estadisticasGenerales['gasto_total'] / $estadisticasGenerales['visitas_totales'];
}

if (!empty($clientesFrecuentes)) {
    $estadisticasGenerales['cliente_top'] = $clientesFrecuentes[0];
}

// Análisis de retención (clientes que regresan)
$retencion = CrearConsulta($conn,
    "SELECT 
        COUNT(DISTINCT CASE WHEN visitas > 1 THEN cliente_id END) as clientes_recurrentes,
        COUNT(DISTINCT cliente_id) as total_clientes_periodo
     FROM (
         SELECT 
             c.ID as cliente_id,
             COUNT(o.ID) as visitas
         FROM {$dbName}.clientes c
         JOIN {$dbName}.ordenes o ON o.ClienteID = c.ID
         WHERE DATE(o.FechaIngreso) BETWEEN ? AND ?
         AND o.Estado >= 3
         GROUP BY c.ID
     ) as cliente_visitas",
    array($fechaInicio, $fechaFin))->fetch_all(MYSQLI_ASSOC);

$tasaRetencion = 0;
if ($retencion[0]['total_clientes_periodo'] > 0) {
    $tasaRetencion = ($retencion[0]['clientes_recurrentes'] / $retencion[0]['total_clientes_periodo']) * 100;
}

require 'lavacar/partials/header.php';
?>

<main class="container container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-users me-2"></i>Clientes Frecuentes</h2>
            <p class="text-muted mb-0">Análisis de fidelidad y valor de clientes</p>
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
                        <label class="form-label">Límite</label>
                        <select class="form-select" name="limite">
                            <option value="10" <?= $limite == 10 ? 'selected' : '' ?>>Top 10</option>
                            <option value="20" <?= $limite == 20 ? 'selected' : '' ?>>Top 20</option>
                            <option value="50" <?= $limite == 50 ? 'selected' : '' ?>>Top 50</option>
                            <option value="100" <?= $limite == 100 ? 'selected' : '' ?>>Top 100</option>
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

    <!-- Estadísticas Principales -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="loyalty-card primary">
                <div class="loyalty-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="loyalty-content">
                    <h3><?= $estadisticasGenerales['total_clientes'] ?></h3>
                    <p>Clientes Activos</p>
                    <small>En el período seleccionado</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="loyalty-card success">
                <div class="loyalty-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="loyalty-content">
                    <h3>₡<?= number_format($estadisticasGenerales['gasto_total'], 0) ?></h3>
                    <p>Ingresos Totales</p>
                    <small>De clientes analizados</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="loyalty-card info">
                <div class="loyalty-icon">
                    <i class="fa-solid fa-repeat"></i>
                </div>
                <div class="loyalty-content">
                    <h3><?= number_format($tasaRetencion, 1) ?>%</h3>
                    <p>Tasa de Retención</p>
                    <small>Clientes que regresan</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="loyalty-card warning">
                <div class="loyalty-icon">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div class="loyalty-content">
                    <h3><?= $estadisticasGenerales['cliente_top'] ? $estadisticasGenerales['cliente_top']['total_visitas'] : 0 ?></h3>
                    <p>Cliente Top</p>
                    <small><?= $estadisticasGenerales['cliente_top'] ? htmlspecialchars(substr($estadisticasGenerales['cliente_top']['NombreCompleto'], 0, 15)) . '...' : 'N/A' ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ranking de Clientes -->
        <div class="col-lg-8">
            <div class="ranking-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-trophy me-2"></i>Ranking de Clientes Frecuentes</h5>
                    <small class="text-muted">Top <?= $limite ?> clientes por frecuencia y gasto</small>
                </div>
                <div class="card-body">
                    <?php if (empty($clientesFrecuentes)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-users fa-3x mb-3"></i>
                            <h5>No hay datos para mostrar</h5>
                            <p>No se encontraron clientes en el período seleccionado</p>
                        </div>
                    <?php else: ?>
                        <div class="clients-ranking">
                            <?php foreach ($clientesFrecuentes as $index => $cliente): ?>
                                <?php
                                $lealtad = 'ocasional';
                                if ($cliente['total_visitas'] >= 10) $lealtad = 'vip';
                                elseif ($cliente['total_visitas'] >= 7) $lealtad = 'muy_frecuente';
                                elseif ($cliente['total_visitas'] >= 4) $lealtad = 'frecuente';
                                elseif ($cliente['total_visitas'] >= 2) $lealtad = 'regular';
                                
                                $lealtadClass = array(
                                    'vip' => 'gold',
                                    'muy_frecuente' => 'success',
                                    'frecuente' => 'info',
                                    'regular' => 'warning',
                                    'ocasional' => 'secondary'
                                )[$lealtad];
                                
                                $lealtadText = array(
                                    'vip' => 'VIP',
                                    'muy_frecuente' => 'Muy Frecuente',
                                    'frecuente' => 'Frecuente',
                                    'regular' => 'Regular',
                                    'ocasional' => 'Ocasional'
                                )[$lealtad];
                                ?>
                                <div class="client-rank-item">
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
                                    
                                    <div class="client-details">
                                        <h6><?= htmlspecialchars($cliente['NombreCompleto']) ?></h6>
                                        <div class="client-info">
                                            <?php if ($cliente['Cedula']): ?>
                                                <span class="info-item">
                                                    <i class="fa-solid fa-id-card"></i>
                                                    <?= htmlspecialchars($cliente['Cedula']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($cliente['Telefono']): ?>
                                                <span class="info-item">
                                                    <i class="fa-solid fa-phone"></i>
                                                    <?= htmlspecialchars($cliente['Telefono']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="client-metrics">
                                            <span class="metric">
                                                <i class="fa-solid fa-calendar-check"></i>
                                                <?= $cliente['total_visitas'] ?> visitas
                                            </span>
                                            <span class="metric">
                                                <i class="fa-solid fa-car"></i>
                                                <?= $cliente['vehiculos_registrados'] ?> vehículos
                                            </span>
                                            <span class="metric">
                                                <i class="fa-solid fa-clock"></i>
                                                <?= $cliente['dias_como_cliente'] ?> días
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="client-revenue">
                                        <div class="revenue-amount">
                                            ₡<?= number_format($cliente['gasto_total'], 0) ?>
                                        </div>
                                        <div class="revenue-avg">
                                            Promedio: ₡<?= number_format($cliente['gasto_promedio'], 0) ?>
                                        </div>
                                        <div class="last-visit">
                                            Última: <?= date('d/m/Y', strtotime($cliente['ultima_visita'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="client-loyalty">
                                        <span class="loyalty-badge <?= $lealtadClass ?>"><?= $lealtadText ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Análisis de Lealtad -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-chart-pie me-2"></i>Análisis de Lealtad</h5>
                    <small class="text-muted">Distribución por categorías</small>
                </div>
                <div class="card-body">
                    <?php if (empty($rangosFrecuencia)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fa-solid fa-chart-pie fa-2x mb-2"></i>
                            <p>No hay datos de lealtad</p>
                        </div>
                    <?php else: ?>
                        <div class="chart-container">
                            <canvas id="lealtadChart" width="300" height="300"></canvas>
                        </div>
                        
                        <!-- Leyenda de lealtad -->
                        <div class="loyalty-legend mt-3">
                            <?php 
                            $coloresLealtad = array('#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6');
                            ?>
                            <?php foreach ($rangosFrecuencia as $index => $rango): ?>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: <?= $coloresLealtad[$index % count($coloresLealtad)] ?>"></div>
                                    <div class="legend-text">
                                        <span><?= htmlspecialchars($rango['categoria_lealtad']) ?></span>
                                        <small><?= $rango['cantidad_clientes'] ?> clientes</small>
                                    </div>
                                    <div class="legend-revenue">
                                        <strong>₡<?= number_format($rango['ingresos_categoria'], 0) ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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

/* ===== LOYALTY CARDS ===== */
.loyalty-card {
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

.loyalty-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.loyalty-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.loyalty-card.primary .loyalty-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.loyalty-card.success .loyalty-icon { background: linear-gradient(135deg, #10b981, #059669); }
.loyalty-card.info .loyalty-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.loyalty-card.warning .loyalty-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

.loyalty-content h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.loyalty-content p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 4px 0;
    font-weight: 600;
}

.loyalty-content small {
    font-size: 0.8rem;
    color: #94a3b8;
}

/* ===== RANKING CARD ===== */
.ranking-card, .chart-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    height: 100%;
}

.ranking-card .card-header, .chart-card .card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: none;
}

.ranking-card .card-header h5, .chart-card .card-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
}

.ranking-card .card-body, .chart-card .card-body {
    padding: 20px 24px 24px;
}

/* ===== CLIENTS RANKING ===== */
.clients-ranking {
    max-height: 600px;
    overflow-y: auto;
}

.client-rank-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
    position: relative;
}

.client-rank-item:last-child {
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

.client-details {
    flex: 1;
}

.client-details h6 {
    margin: 0 0 8px;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.client-info {
    display: flex;
    gap: 16px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.8rem;
    color: #64748b;
}

.info-item i {
    color: #94a3b8;
}

.client-metrics {
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

.client-revenue {
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
    margin: 2px 0;
}

.last-visit {
    font-size: 0.8rem;
    color: #94a3b8;
}

.client-loyalty {
    min-width: 100px;
    text-align: center;
}

.loyalty-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.loyalty-badge.gold {
    background: linear-gradient(135deg, #ffd700, #ffb300);
    color: #92400e;
}

.loyalty-badge.success {
    background: #d1fae5;
    color: #065f46;
}

.loyalty-badge.info {
    background: #dbeafe;
    color: #1e40af;
}

.loyalty-badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.loyalty-badge.secondary {
    background: #f1f5f9;
    color: #475569;
}

/* ===== CHART CONTAINER ===== */
.chart-container {
    position: relative;
    height: 300px;
}

.loyalty-legend {
    max-height: 200px;
    overflow-y: auto;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}

.legend-item:last-child {
    border-bottom: none;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}

.legend-text {
    flex: 1;
}

.legend-text span {
    display: block;
    font-size: 0.85rem;
    color: #475569;
    font-weight: 600;
}

.legend-text small {
    font-size: 0.8rem;
    color: #64748b;
}

.legend-revenue strong {
    font-size: 0.9rem;
    color: #10b981;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .client-rank-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .client-revenue {
        text-align: left;
        min-width: auto;
        width: 100%;
    }
    
    .client-loyalty {
        min-width: auto;
        text-align: left;
    }
    
    .client-info, .client-metrics {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($rangosFrecuencia)): ?>
// Datos para el gráfico de lealtad
const lealtadData = <?= json_encode($rangosFrecuencia) ?>;
const coloresLealtad = ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'];

// Configurar gráfico de lealtad
const ctx = document.getElementById('lealtadChart').getContext('2d');
const lealtadChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: lealtadData.map(function(l) { return l.categoria_lealtad; }),
        datasets: [{
            data: lealtadData.map(function(l) { return l.cantidad_clientes; }),
            backgroundColor: coloresLealtad,
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
                        return context.label + ': ' + context.parsed + ' clientes';
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