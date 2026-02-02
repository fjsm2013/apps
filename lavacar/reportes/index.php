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

require 'lavacar/partials/header.php';
?>

<main class="container my-4">
    <!-- Header con Órdenes Activas destacada -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2><i class="fa-solid fa-chart-line me-2"></i>Centro de Reportes</h2>
            <p class="text-muted">Análisis y estadísticas del negocio</p>
        </div>
        <div class="col-md-4">
            <a href="ordenes-activas.php" class="text-decoration-none">
                <div class="report-card report-featured">
                    <div class="report-icon">
                        <i class="fa-solid fa-tasks"></i>
                    </div>
                    <div class="report-content">
                        <h5>Órdenes Activas</h5>
                        <p>Gestión de órdenes en proceso</p>
                        <div class="report-features">
                            <span class="feature-tag tag-realtime">Tiempo Real</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Reportes Principales -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-3"><i class="fa-solid fa-star me-2"></i>Reportes Principales</h4>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <a href="dashboard-ejecutivo.php" class="text-decoration-none">
                <div class="report-card report-main">
                    <div class="report-icon">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div class="report-content">
                        <h5>Dashboard Ejecutivo</h5>
                        <p>Vista general del negocio con KPIs principales</p>
                        <div class="report-features">
                            <span class="feature-tag tag-sales">Ventas</span>
                            <span class="feature-tag tag-trends">Tendencias</span>
                            <span class="feature-tag tag-metrics">Métricas</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="ventas-diarias.php" class="text-decoration-none">
                <div class="report-card report-main">
                    <div class="report-icon">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <div class="report-content">
                        <h5>Ventas Diarias</h5>
                        <p>Análisis detallado de ingresos por día</p>
                        <div class="report-features">
                            <span class="feature-tag tag-revenue">Ingresos</span>
                            <span class="feature-tag tag-comparison">Comparativas</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="ordenes-cerradas.php" class="text-decoration-none">
                <div class="report-card report-main">
                    <div class="report-icon">
                        <i class="fa-solid fa-archive"></i>
                    </div>
                    <div class="report-content">
                        <h5>Órdenes Cerradas</h5>
                        <p>Historial de órdenes completadas y facturadas</p>
                        <div class="report-features">
                            <span class="feature-tag tag-historical">Histórico</span>
                            <span class="feature-tag tag-revenue">Ingresos</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Reportes de Análisis -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-3"><i class="fa-solid fa-magnifying-glass-chart me-2"></i>Análisis de Negocio</h4>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="servicios-populares.php" class="text-decoration-none">
                <div class="report-card report-analysis">
                    <div class="report-icon">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div class="report-content">
                        <h5>Servicios Populares</h5>
                        <p>Ranking de servicios más solicitados</p>
                        <div class="report-features">
                            <span class="feature-tag tag-ranking">Top 10</span>
                            <span class="feature-tag tag-profit">Rentabilidad</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="clientes-frecuentes.php" class="text-decoration-none">
                <div class="report-card report-analysis">
                    <div class="report-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="report-content">
                        <h5>Clientes Frecuentes</h5>
                        <p>Análisis de fidelidad de clientes</p>
                        <div class="report-features">
                            <span class="feature-tag tag-loyalty">Lealtad</span>
                            <span class="feature-tag tag-value">Valor</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!--<div class="col-md-6 col-lg-4">
            <a href="rendimiento-empleados.php" class="text-decoration-none">
                <div class="report-card report-analysis">
                    <div class="report-icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="report-content">
                        <h5>Rendimiento del Equipo</h5>
                        <p>Productividad y eficiencia del personal</p>
                        <div class="report-features">
                            <span class="feature-tag tag-productivity">Productividad</span>
                            <span class="feature-tag tag-time">Tiempos</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>-->
    </div>

    <!-- Reportes Operacionales -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="mb-3"><i class="fa-solid fa-gears me-2"></i>Reportes Operacionales</h4>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="tiempos-servicio.php" class="text-decoration-none">
                <div class="report-card report-operational">
                    <div class="report-icon">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <div class="report-content">
                        <h5>Tiempos de Servicio</h5>
                        <p>Análisis de duración de servicios</p>
                        <div class="report-features">
                            <span class="feature-tag tag-efficiency">Eficiencia</span>
                            <span class="feature-tag tag-average">Promedio</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="vehiculos-atendidos.php" class="text-decoration-none">
                <div class="report-card report-operational">
                    <div class="report-icon">
                        <i class="fa-solid fa-car"></i>
                    </div>
                    <div class="report-content">
                        <h5>Vehículos Atendidos</h5>
                        <p>Estadísticas por tipo de vehículo</p>
                        <div class="report-features">
                            <span class="feature-tag tag-categories">Categorías</span>
                            <span class="feature-tag tag-frequency">Frecuencia</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="ingresos-mensuales.php" class="text-decoration-none">
                <div class="report-card report-operational">
                    <div class="report-icon">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>
                    <div class="report-content">
                        <h5>Ingresos Mensuales</h5>
                        <p>Evolución de ingresos por mes</p>
                        <div class="report-features">
                            <span class="feature-tag tag-historical">Histórico</span>
                            <span class="feature-tag tag-projection">Proyección</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <?php /* Comentado temporalmente - Acciones Rápidas
    <!-- Acciones Rápidas -->
    <div class="row g-4">
        <div class="col-12">
            <h4 class="mb-3"><i class="fa-solid fa-bolt me-2"></i>Acciones Rápidas</h4>
        </div>

        <div class="col-md-6 col-lg-3">
            <button class="quick-action-btn" onclick="exportarDatos()">
                <i class="fa-solid fa-download"></i>
                <span>Exportar Datos</span>
            </button>
        </div>

        <div class="col-md-6 col-lg-3">
            <button class="quick-action-btn" onclick="generarReportePersonalizado()">
                <i class="fa-solid fa-magic-wand-sparkles"></i>
                <span>Reporte Personalizado</span>
            </button>
        </div>

        <div class="col-md-6 col-lg-3">
            <button class="quick-action-btn" onclick="programarReporte()">
                <i class="fa-solid fa-clock"></i>
                <span>Programar Envío</span>
            </button>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="../dashboard.php" class="quick-action-btn text-decoration-none">
                <i class="fa-solid fa-home"></i>
                <span>Volver al Inicio</span>
            </a>
        </div>
    </div>
    */ ?>
</main>

<style>
/* ===== FROSH REPORT CARDS STYLING ===== */
.report-card {
    background: var(--content-bg, #ffffff);
    border-radius: var(--border-radius, 12px);
    padding: 24px;
    box-shadow: var(--shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06));
    border: 1px solid var(--content-border, #e5e7eb);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.report-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover, 0 25px 50px -12px rgba(0, 0, 0, 0.25));
    border-color: var(--report-info, #274AB3);
}

/* Iconos por categoría con colores marketing */
.report-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius, 12px);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    color: white !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Reportes principales - Azul profundo */
.report-main .report-icon {
    background: linear-gradient(135deg, var(--report-info, #274AB3), #1e3a8a);
    color: white !important;
}

/* Reporte destacado - Gradiente especial */
.report-featured .report-icon {
    background: linear-gradient(135deg, var(--report-warning, #D3AF37), var(--report-info, #274AB3));
    color: white !important;
}

.report-featured {
    border: 2px solid var(--report-warning, #D3AF37);
    box-shadow: 0 8px 25px rgba(211, 175, 55, 0.2);
}

.report-featured:hover {
    border-color: var(--report-info, #274AB3);
    box-shadow: 0 12px 35px rgba(39, 74, 179, 0.3);
}

/* Reportes de análisis - Dorado */
.report-analysis .report-icon {
    background: linear-gradient(135deg, var(--report-warning, #D3AF37), #b8941f);
    color: white !important;
}

/* Reportes operacionales - Verde */
.report-operational .report-icon {
    background: linear-gradient(135deg, var(--report-success, #10b981), #059669);
    color: white !important;
}

.report-icon i {
    font-size: 24px;
    color: inherit !important;
}

.report-content h5 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--content-text, #111827);
}

.report-content p {
    font-size: 0.9rem;
    color: var(--content-text-muted, #6b7280);
    margin-bottom: 16px;
    line-height: 1.5;
}

.report-features {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: auto;
}

.feature-tag {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Feature tag variations with marketing colors */
.tag-sales, .tag-revenue, .tag-profit { 
    background: var(--report-success, #10b981); 
}

.tag-trends, .tag-metrics, .tag-comparison, .tag-analytics { 
    background: var(--report-info, #274AB3); 
}

.tag-realtime, .tag-ranking, .tag-efficiency, .tag-productivity { 
    background: var(--report-warning, #D3AF37); 
}

.tag-loyalty, .tag-value, .tag-time, .tag-average, .tag-categories, .tag-frequency, .tag-historical, .tag-projection { 
    background: var(--frosh-gray-600, #4b5563); 
}

/* ===== FROSH QUICK ACTION BUTTONS ===== */
.quick-action-btn {
    width: 100%;
    background: var(--content-bg, #ffffff);
    border: 2px solid var(--report-warning, #D3AF37);
    border-radius: var(--border-radius, 12px);
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: var(--report-warning, #D3AF37);
    font-weight: 600;
    transition: all 0.2s ease;
    cursor: pointer;
}

.quick-action-btn:hover {
    background: var(--report-warning, #D3AF37);
    border-color: var(--report-warning, #D3AF37);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(211, 175, 55, 0.3);
    text-decoration: none;
}

.quick-action-btn i {
    font-size: 24px;
}

/* ===== FROSH SECTION HEADERS ===== */
h4 {
    color: var(--content-text, #111827);
    font-weight: 700;
    border-bottom: 3px solid var(--report-info, #274AB3);
    padding-bottom: 8px;
}

h4 i {
    color: var(--report-info, #274AB3);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .report-card {
        padding: 20px;
    }
    
    .report-icon {
        width: 50px;
        height: 50px;
    }
    
    .report-icon i {
        font-size: 20px;
    }
    
    .quick-action-btn {
        padding: 16px;
    }
}

/* ===== FROSH ALERT STYLES ===== */
.modern-alert {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: var(--content-bg, #ffffff);
    border-radius: var(--border-radius, 12px);
    padding: 16px 20px;
    box-shadow: var(--shadow-lg, 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04));
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 400px;
    animation: slideIn 0.3s ease;
    font-weight: 500;
    color: var(--content-text, #111827);
}

.alert-info {
    border-left: 4px solid var(--frosh-gray-600, #4b5563);
}

.alert-success {
    border-left: 4px solid var(--frosh-black, #000000);
}

.alert-error {
    border-left: 4px solid #ef4444;
}

.alert-warning {
    border-left: 4px solid #f59e0b;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.alert-close {
    background: none;
    border: none;
    color: var(--frosh-gray-500, #6b7280);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.alert-close:hover {
    background: var(--frosh-gray-100, #f3f4f6);
    color: var(--frosh-gray-900, #111827);
}
</style>

<script>
/* Comentado temporalmente - Funciones de Acciones Rápidas
function exportarDatos() {
    showAlert('Función de exportación en desarrollo', 'info');
}

function generarReportePersonalizado() {
    showAlert('Constructor de reportes personalizados próximamente', 'info');
}

function programarReporte() {
    showAlert('Programación de reportes automáticos en desarrollo', 'info');
}
*/

// Función para mostrar alertas modernas con estilo FROSH
function showAlert(message, type = 'info') {
    // Remover alertas existentes
    const existingAlert = document.querySelector('.modern-alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Crear nueva alerta
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
    
    document.body.appendChild(alert);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alert && alert.parentNode) {
            alert.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => alert.remove(), 300);
        }
    }, 5000);
}
</script>

<?php require 'lavacar/partials/footer.php'; ?>