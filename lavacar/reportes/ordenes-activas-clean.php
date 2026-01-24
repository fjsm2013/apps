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
$ordenes = $ordenManager->getActive();

// Calcular contadores por estado
$contadores = [
    'total' => count($ordenes),
    'pendientes' => 0,
    'proceso' => 0,
    'terminados' => 0
];

foreach ($ordenes as $orden) {
    switch ($orden['Estado']) {
        case 1:
            $contadores['pendientes']++;
            break;
        case 2:
            $contadores['proceso']++;
            break;
        case 3:
            $contadores['terminados']++;
            break;
    }
}

require 'lavacar/partials/header.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-list me-2"></i>Órdenes Activas</h2>
            <p class="text-muted mb-0">Gestión de órdenes en proceso</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <!-- Filtros de Estado - Desktop (SIEMPRE VISIBLE) -->
            <div class="btn-group" role="group" style="display: flex !important;">
                <input type="radio" class="btn-check" name="estadoFilter" id="todos" value="all" checked>
                <label class="btn btn-outline-secondary btn-sm" for="todos">
                    Todos <span class="badge bg-secondary ms-1"><?= $contadores['total'] ?></span>
                </label>

                <input type="radio" class="btn-check" name="estadoFilter" id="pendientes" value="1">
                <label class="btn btn-outline-ordenes-warning btn-sm" for="pendientes">
                    Pendientes <span class="badge badge-ordenes-warning ms-1"><?= $contadores['pendientes'] ?></span>
                </label>

                <input type="radio" class="btn-check" name="estadoFilter" id="proceso" value="2">
                <label class="btn btn-outline-ordenes-info btn-sm" for="proceso">
                    En Proceso <span class="badge badge-ordenes-info ms-1"><?= $contadores['proceso'] ?></span>
                </label>

                <input type="radio" class="btn-check" name="estadoFilter" id="terminados" value="3">
                <label class="btn btn-outline-ordenes-success btn-sm" for="terminados">
                    Terminados <span class="badge badge-ordenes-success ms-1"><?= $contadores['terminados'] ?></span>
                </label>
            </div>

            <!-- Filtros de Estado - Mobile (OCULTO) -->
            <div class="d-none flex-grow-1">
                <select class="form-select form-select-sm" id="estadoFilterMobile">
                    <option value="all">📋 Todas las órdenes (<?= $contadores['total'] ?>)</option>
                    <option value="1">⏰ Pendientes (<?= $contadores['pendientes'] ?>)</option>
                    <option value="2">⚙️ En Proceso (<?= $contadores['proceso'] ?>)</option>
                    <option value="3">✅ Terminadas (<?= $contadores['terminados'] ?>)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Resumen de Estados - Solo visible cuando hay órdenes -->
    <?php if (!empty($ordenes)): ?>
    <div class="row mb-4 d-none d-lg-flex">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body py-3">
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-list-check fa-2x text-secondary me-3"></i>
                                <div>
                                    <h4 class="mb-0 text-secondary"><?= $contadores['total'] ?></h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-clock fa-2x me-3" style="color: var(--ordenes-warning);"></i>
                                <div>
                                    <h4 class="mb-0" style="color: var(--ordenes-warning);"><?= $contadores['pendientes'] ?></h4>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-gear fa-2x me-3" style="color: var(--ordenes-info);"></i>
                                <div>
                                    <h4 class="mb-0" style="color: var(--ordenes-info);"><?= $contadores['proceso'] ?></h4>
                                    <small class="text-muted">En Proceso</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-check fa-2x me-3" style="color: var(--ordenes-success);"></i>
                                <div>
                                    <h4 class="mb-0" style="color: var(--ordenes-success);"><?= $contadores['terminados'] ?></h4>
                                    <small class="text-muted">Terminados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($ordenes)): ?>
        <div class="alert alert-info text-center">
            <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
            <h5>No hay órdenes activas</h5>
            <p class="mb-0">Todas las órdenes han sido completadas o no hay órdenes registradas.</p>
        </div>
    <?php else: ?>
        <!-- Vista Principal: Tabla Mejorada -->
        <div class="card">
            <div class="card-body p-0">
                <!-- Vista Desktop: Tabla Compacta -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th style="width: 25%;">Cliente</th>
                                <th style="width: 15%;">Vehículo</th>
                                <th style="width: 15%;">Monto</th>
                                <th style="width: 15%;">Estado</th>
                                <th style="width: 22%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $orden): ?>
                            <tr data-estado="<?= $orden['Estado'] ?>">
                                <td>
                                    <strong class="text-primary">#<?= $orden['ID'] ?></strong>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= safe_htmlspecialchars($orden['ClienteNombre'], 'Sin cliente') ?></strong>
                                        <br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></small>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-dark"><?= safe_htmlspecialchars($orden['Placa'], 'Sin placa') ?></strong>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        ₡<?= safe_number_format($orden['Monto'], 0) ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php
                                    $estadoClass = 'badge-frosh-light';
                                    $estadoText = 'Pendiente';
                                    
                                    switch ($orden['Estado']) {
                                        case 1:
                                            $estadoClass = 'badge-ordenes-warning';
                                            $estadoText = 'Pendiente';
                                            break;
                                        case 2:
                                            $estadoClass = 'badge-ordenes-info';
                                            $estadoText = 'En Proceso';
                                            break;
                                        case 3:
                                            $estadoClass = 'badge-ordenes-success';
                                            $estadoText = 'Terminado';
                                            break;
                                        case 4:
                                            $estadoClass = 'badge-ordenes-dark';
                                            $estadoText = 'Cerrado';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $estadoClass ?>">
                                        <?= $estadoText ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-sm" style="background: var(--ordenes-info); color: white; border-color: var(--ordenes-info);" onclick="verDetalleOrden(<?= $orden['ID'] ?>)" title="Ver Detalle">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <?php if ($orden['Estado'] < 4): ?>
                                        <button class="btn btn-sm btn-outline-warning" onclick="editarOrden(<?= $orden['ID'] ?>)" title="Editar Orden">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm" style="background: var(--ordenes-success); color: white; border-color: var(--ordenes-success);" onclick="cambiarEstado(<?= $orden['ID'] ?>, <?= $orden['Estado'] + 1 ?>)" title="Avanzar Estado">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if ($orden['Estado'] == 3): ?>
                                        <button class="btn btn-sm btn-warning" onclick="mostrarCalculadoraCierre(<?= $orden['ID'] ?>)" title="Calculadora de Cierre">
                                            <i class="fa-solid fa-calculator"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Vista Mobile: Cards Compactas -->
                <div class="d-md-none">
                    <div class="mobile-orders-container p-3">
                        <?php foreach ($ordenes as $orden): ?>
                            <?php
                            $estadoClass = 'secondary';
                            $estadoText = 'Pendiente';
                            $estadoIcon = 'fa-clock';
                            $estadoColor = '#6b7280';
                            
                            switch ($orden['Estado']) {
                                case 1:
                                    $estadoClass = 'warning';
                                    $estadoText = 'Pendiente';
                                    $estadoIcon = 'fa-clock';
                                    $estadoColor = '#D3AF37';
                                    break;
                                case 2:
                                    $estadoClass = 'info';
                                    $estadoText = 'En Proceso';
                                    $estadoIcon = 'fa-gear';
                                    $estadoColor = '#274AB3';
                                    break;
                                case 3:
                                    $estadoClass = 'success';
                                    $estadoText = 'Terminado';
                                    $estadoIcon = 'fa-check';
                                    $estadoColor = '#10b981';
                                    break;
                                case 4:
                                    $estadoClass = 'dark';
                                    $estadoText = 'Cerrado';
                                    $estadoIcon = 'fa-lock';
                                    $estadoColor = '#374151';
                                    break;
                            }
                            ?>
                            <div class="order-card mb-3" data-estado="<?= $orden['Estado'] ?>">
                                <div class="order-card-header">
                                    <div class="order-main-info">
                                        <h6 class="mb-1">Orden #<?= $orden['ID'] ?></h6>
                                        <small class="text-muted d-block"><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></small>
                                    </div>
                                    <span class="badge" style="background-color: <?= $estadoColor ?>; color: white;">
                                        <i class="fa-solid <?= $estadoIcon ?> me-1"></i>
                                        <?= $estadoText ?>
                                    </span>
                                </div>

                                <div class="order-card-body">
                                    <div class="client-info mb-3">
                                        <div class="info-item">
                                            <i class="fa-solid fa-user me-2 text-muted"></i>
                                            <span><strong><?= safe_htmlspecialchars($orden['ClienteNombre'], 'Sin cliente') ?></strong></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-car me-2 text-muted"></i>
                                            <span><?= safe_htmlspecialchars($orden['Placa'], 'Sin placa') ?></span>
                                        </div>
                                    </div>

                                    <div class="order-details-row">
                                        <div class="amount-info">
                                            <strong class="text-success">
                                                ₡<?= safe_number_format($orden['Monto'], 0) ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-card-actions">
                                    <button class="btn btn-sm" style="background: var(--ordenes-info); color: white; border-color: var(--ordenes-info);" onclick="verDetalleOrden(<?= $orden['ID'] ?>)" title="Ver Detalle">
                                        <i class="fa-solid fa-eye me-1"></i>Ver
                                    </button>
                                    <?php if ($orden['Estado'] < 4): ?>
                                    <button class="btn btn-sm btn-outline-warning" onclick="editarOrden(<?= $orden['ID'] ?>)" title="Editar Orden">
                                        <i class="fa-solid fa-edit me-1"></i>Editar
                                    </button>
                                    <button class="btn btn-sm" style="background: var(--ordenes-success); color: white; border-color: var(--ordenes-success);" onclick="cambiarEstado(<?= $orden['ID'] ?>, <?= $orden['Estado'] + 1 ?>)" title="Avanzar Estado">
                                        <i class="fa-solid fa-arrow-right me-1"></i>Avanzar
                                    </button>
                                    <?php endif; ?>
                                    <?php if ($orden['Estado'] == 3): ?>
                                    <button class="btn btn-sm btn-warning" onclick="mostrarCalculadoraCierre(<?= $orden['ID'] ?>)" title="Calculadora de Cierre">
                                        <i class="fa-solid fa-calculator me-1"></i>Calculadora
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>


    <?php endif; ?>
</main>

<!-- Modal de Detalle de Orden -->
<div class="modal fade" id="detalleOrdenModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-eye me-2"></i>
                    Detalle de Orden <span id="modalOrdenId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center py-4">
                    <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Cargando detalles...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-frosh-light" data-bs-dismiss="modal">Cerrar</button>
                <div id="modalActions">
                    <!-- Botones de acción se cargan dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Estado -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-arrow-right me-2"></i>
                    Cambiar Estado de Orden
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i id="estadoIcon" class="fa-solid fa-question-circle fa-3x text-primary mb-3"></i>
                    <h6 id="estadoTitulo">¿Confirmar cambio de estado?</h6>
                    <p id="estadoDescripcion" class="text-muted"></p>
                </div>
                
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Orden #<span id="confirmOrdenId"></span></strong>
                    <br>
                    <small id="confirmOrdenInfo"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-frosh-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-frosh-primary" id="confirmarCambioBtn">
                    <i class="fa-solid fa-check me-1"></i>
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editor de Orden -->
<div class="modal fade" id="editarOrdenModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-edit me-2"></i>
                    Editar Orden #<span id="editOrdenId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="editOrdenContent">
                    <!-- Contenido del editor se carga aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarCambiosBtn">
                    <i class="fa-solid fa-save me-1"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Calculadora de Cierre -->
<div class="modal fade" id="calculadoraCierreModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-calculator me-2"></i>
                    Calculadora de Cierre - Orden #<span id="calcOrdenId"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="calculadoraContent">
                    <!-- Contenido de la calculadora se carga aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="cerrarOrdenBtn">
                    <i class="fa-solid fa-lock me-1"></i>
                    Cerrar Orden
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== COLORES PERSONALIZADOS PARA ÓRDENES ACTIVAS ===== */
:root {
    /* Colores específicos para órdenes activas - sugerencia de marketing */
    --ordenes-warning: #D3AF37;  /* Dorado suave para pendientes */
    --ordenes-info: #274AB3;     /* Azul profundo para en proceso */
    --ordenes-success: #10b981;  /* Verde success mantener */
    --ordenes-dark: #374151;     /* Gris oscuro para cerrado */
}

/* ===== BADGES PERSONALIZADOS PARA ÓRDENES ===== */
.badge-ordenes-warning {
    background: var(--ordenes-warning);
    color: white;
    font-weight: 600;
}

.badge-ordenes-info {
    background: var(--ordenes-info);
    color: white;
    font-weight: 600;
}

.badge-ordenes-success {
    background: var(--ordenes-success);
    color: white;
    font-weight: 600;
}

.badge-ordenes-dark {
    background: var(--ordenes-dark);
    color: white;
    font-weight: 600;
}

/* ===== BOTONES DE FILTRO PERSONALIZADOS ===== */
.btn-outline-ordenes-warning {
    border-color: var(--ordenes-warning);
    color: var(--ordenes-warning);
    background: transparent;
}

.btn-outline-ordenes-warning:hover,
.btn-outline-ordenes-warning:focus,
.btn-outline-ordenes-warning.active {
    background: var(--ordenes-warning);
    border-color: var(--ordenes-warning);
    color: white;
}

.btn-outline-ordenes-info {
    border-color: var(--ordenes-info);
    color: var(--ordenes-info);
    background: transparent;
}

.btn-outline-ordenes-info:hover,
.btn-outline-ordenes-info:focus,
.btn-outline-ordenes-info.active {
    background: var(--ordenes-info);
    border-color: var(--ordenes-info);
    color: white;
}

.btn-outline-ordenes-success {
    border-color: var(--ordenes-success);
    color: var(--ordenes-success);
    background: transparent;
}

.btn-outline-ordenes-success:hover,
.btn-outline-ordenes-success:focus,
.btn-outline-ordenes-success.active {
    background: var(--ordenes-success);
    border-color: var(--ordenes-success);
    color: white;
}

/* ===== STATUS BADGES MÓVILES PERSONALIZADOS ===== */
.status-warning {
    background: rgba(211, 175, 55, 0.1);
    color: var(--ordenes-warning);
    border: 1px solid var(--ordenes-warning);
}

.status-info {
    background: rgba(39, 74, 179, 0.1);
    color: var(--ordenes-info);
    border: 1px solid var(--ordenes-info);
}

.status-success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--ordenes-success);
    border: 1px solid var(--ordenes-success);
}

.status-dark {
    background: rgba(55, 65, 81, 0.1);
    color: var(--ordenes-dark);
    border: 1px solid var(--ordenes-dark);
}

/* ===== MODERN ORDER CARDS ===== */
.order-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

/* Card Header */
.order-card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.order-number {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
}

.order-number i {
    color: #64748b;
    font-size: 1rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fbbf24;
}

.status-info {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #60a5fa;
}

.status-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #34d399;
}

.status-dark {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #94a3b8;
}

/* Card Body */
.order-card-body {
    padding: 24px;
}

.order-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-label i {
    width: 16px;
    text-align: center;
    color: #94a3b8;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    word-break: break-word;
}

.info-value.amount {
    color: #059669;
    font-size: 1.1rem;
    font-weight: 700;
}

/* Card Actions */
.order-card-actions {
    padding: 20px 24px;
    background: #f8fafc;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.action-btn {
    flex: 1;
    min-width: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 20px;
    border: none;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn i {
    font-size: 1rem;
}

.action-btn-primary {
    background: var(--ordenes-info, #274AB3);
    color: white;
    box-shadow: 0 2px 8px rgba(39, 74, 179, 0.3);
}

.action-btn-primary:hover {
    background: #1e3a8a;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(39, 74, 179, 0.4);
}

.action-btn-success {
    background: var(--ordenes-success, #10b981);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.action-btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.action-btn:active {
    transform: translateY(0);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .order-card-header {
        padding: 16px;
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .order-card-body {
        padding: 20px 16px;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .order-card-actions {
        padding: 16px;
        flex-direction: column;
    }
    
    .action-btn {
        min-width: auto;
        padding: 16px 20px;
    }
}

/* Tablet adjustments */
@media (min-width: 577px) and (max-width: 991px) {
    .order-info-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .action-btn {
        min-width: 120px;
    }
}

/* Animation for state changes */
.order-card.updating {
    opacity: 0.7;
    pointer-events: none;
}

.order-card.updating .action-btn {
    background: #94a3b8;
    cursor: not-allowed;
}

/* Mobile filter select styling */
#estadoFilterMobile {
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #374151;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

#estadoFilterMobile:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Responsive header adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .d-flex.gap-2.align-items-center.flex-wrap {
        justify-content: space-between;
    }
}

/* ===== TABLE RESPONSIVE CONTROL ===== */
.table-responsive {
    display: block;
}

@media (max-width: 767px) {
    .table-responsive {
        display: none !important;
    }
}

/* ===== MOBILE ORDERS CARDS ===== */
.mobile-orders-container {
    background: transparent;
    display: none;
}

@media (max-width: 767px) {
    .mobile-orders-container {
        display: block !important;
    }
}

.order-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;
}

.order-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.order-card-header {
    padding: 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.order-main-info {
    flex: 1;
}

.order-main-info h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
    font-size: 1rem;
}

.order-main-info small {
    font-size: 0.8rem;
    color: #64748b;
}

.order-card-body {
    padding: 16px;
}

.client-info {
    margin-bottom: 0;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    font-size: 0.85rem;
    color: #374151;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item i {
    color: #64748b;
    width: 16px;
    flex-shrink: 0;
}

.order-details-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.amount-info strong {
    font-size: 1.1rem;
    font-weight: 700;
}

.order-card-actions {
    padding: 12px 16px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 8px;
    align-items: center;
}

.order-card-actions .btn {
    font-size: 0.8rem;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    flex: 1;
}

/* ===== MOBILE RESPONSIVE IMPROVEMENTS ===== */
@media (max-width: 768px) {
    .container.my-4 {
        margin-left: 12px !important;
        margin-right: 12px !important;
        padding-left: 0;
        padding-right: 0;
    }
    
    /* Header improvements */
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
        text-align: center;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-4 h2 {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-4 p {
        font-size: 0.85rem;
    }
    
    /* Filter buttons mobile */
    .btn-group {
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .btn-group .btn {
        font-size: 0.8rem;
        padding: 6px 12px;
    }
    
    .btn-group .badge {
        font-size: 0.7rem;
    }
}

@media (max-width: 576px) {
    .order-card-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .order-card-actions .btn {
        width: 100%;
        flex: none;
    }
    
    .order-details-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .amount-info {
        width: 100%;
        text-align: left;
    }
}

/* ===== MODAL ICON FIXES ===== */
.modal .fa-solid {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    display: inline-block !important;
}

.modal .fa-spinner {
    animation: fa-spin 2s infinite linear !important;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Asegurar que los iconos en modales tengan el tamaño correcto */
.modal .fa-3x {
    font-size: 3em !important;
}

.modal .fa-2x {
    font-size: 2em !important;
}

/* Mejorar la visibilidad de los iconos en modales */
.modal-body .fa-solid {
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ===== MODAL DE CONFIRMACIÓN ESPECÍFICO ===== */
#cambiarEstadoModal .modal-dialog {
    max-width: 400px !important;
}

#cambiarEstadoModal .modal-body {
    padding: 2rem 1.5rem;
}

#cambiarEstadoModal .text-center {
    text-align: center !important;
}

#cambiarEstadoModal #estadoTitulo {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

#cambiarEstadoModal #estadoDescripcion {
    font-size: 0.95rem;
    color: #64748b;
    margin-bottom: 0;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

#cambiarEstadoModal .alert {
    margin-top: 1.5rem;
    margin-bottom: 0;
}

#cambiarEstadoModal .modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
}

/* Asegurar que el icono del modal sea visible */
#cambiarEstadoModal #estadoIcon {
    display: inline-block !important;
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    margin-bottom: 1rem !important;
}
</style>

<script>
function verDetalleOrden(ordenId) {
    const modal = new bootstrap.Modal(document.getElementById('detalleOrdenModal'));
    document.getElementById('modalOrdenId').textContent = `#${ordenId}`;
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Cargando detalles...</p>
        </div>
    `;
    document.getElementById('modalActions').innerHTML = '';
    
    modal.show();
    
    // Cargar detalles
    fetch(`ajax/detalle-orden.php?id=${ordenId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarDetalleOrden(data);
            } else {
                document.getElementById('modalContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        Error: ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles de la orden
                </div>
            `;
        });
}

function mostrarDetalleOrden(data) {
    const { orden, servicios, totales, fechas, siguiente_estado, siguiente_estado_texto, siguiente_estado_icon } = data;
    
    // Determinar estado actual
    const estadoInfo = getEstadoInfo(orden.Estado);
    
    const content = `
        <div class="row">
            <!-- Información General -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Información General</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Estado Actual:</strong><br>
                            <span class="badge fs-6" style="background-color: ${estadoInfo.color}; color: white;">
                                <i class="fa-solid ${estadoInfo.icon} me-1"></i>
                                ${estadoInfo.text}
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Fecha de Ingreso:</strong><br>
                            <span class="text-muted">${fechas.ingreso || 'No registrada'}</span>
                        </div>
                        ${fechas.proceso ? `
                        <div class="mb-2">
                            <strong>Fecha de Proceso:</strong><br>
                            <span class="text-muted">${fechas.proceso}</span>
                        </div>` : ''}
                        ${fechas.terminado ? `
                        <div class="mb-2">
                            <strong>Fecha de Terminado:</strong><br>
                            <span class="text-muted">${fechas.terminado}</span>
                        </div>` : ''}
                        ${fechas.cierre ? `
                        <div class="mb-2">
                            <strong>Fecha de Cierre:</strong><br>
                            <span class="text-muted">${fechas.cierre}</span>
                        </div>` : ''}
                    </div>
                </div>
            </div>

            <!-- Cliente y Vehículo -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-user-car me-2"></i>Cliente y Vehículo</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Cliente:</strong><br>
                            <span>${orden.ClienteNombre}</span>
                            ${orden.ClienteCorreo ? `<br><small class="text-muted">${orden.ClienteCorreo}</small>` : ''}
                        </div>
                        <div class="mb-2">
                            <strong>Vehículo:</strong><br>
                            <span class="text-primary fw-bold">${orden.Placa}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Detalles:</strong><br>
                            <span>${orden.Marca} ${orden.Modelo} (${orden.Year})</span>
                            ${orden.Color ? `<br><small class="text-muted">Color: ${orden.Color}</small>` : ''}
                        </div>
                        <div class="mb-0">
                            <strong>Categoría:</strong><br>
                            <span class="badge badge-frosh-gray">${orden.TipoVehiculo}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios -->
        <div class="card bg-light mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fa-solid fa-tags me-2"></i>Servicios Contratados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${servicios.map(servicio => `
                                <tr>
                                    <td>${servicio.nombre || `Servicio #${servicio.id}` || 'Servicio sin nombre'}</td>
                                    <td class="text-end">₡${parseFloat(servicio.precio).toLocaleString('es-CR', {minimumFractionDigits: 2})}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Subtotal</th>
                                <th class="text-end">₡${totales.subtotal.toLocaleString('es-CR', {minimumFractionDigits: 2})}</th>
                            </tr>
                            <tr>
                                <th>IVA (13%)</th>
                                <th class="text-end">₡${totales.impuesto.toLocaleString('es-CR', {minimumFractionDigits: 2})}</th>
                            </tr>
                            <tr class="table-success">
                                <th>Total</th>
                                <th class="text-end">₡${totales.total.toLocaleString('es-CR', {minimumFractionDigits: 2})}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        ${orden.Observaciones ? `
        <div class="alert alert-info">
            <h6><i class="fa-solid fa-comment me-2"></i>Observaciones</h6>
            <p class="mb-0">${orden.Observaciones}</p>
        </div>` : ''}
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    
    // Agregar botones de acción según el estado
    let botonesAccion = '';
    
    // Botón Editar Orden (disponible si no está cerrada)
    if (orden.Estado < 4) {
        botonesAccion += `
            <button type="button" class="btn btn-outline-primary me-2" onclick="editarOrden(${orden.ID})">
                <i class="fa-solid fa-edit me-1"></i>
                Editar Orden
            </button>
        `;
    }
    
    // Botón Calculadora de Cierre (solo para estado Terminado)
    if (orden.Estado === 3) {
        botonesAccion += `
            <button type="button" class="btn btn-warning me-2" onclick="mostrarCalculadoraCierre(${orden.ID})">
                <i class="fa-solid fa-calculator me-1"></i>
                Calculadora de Cierre
            </button>
        `;
    }
    
    // Botón de avanzar estado (funcionalidad existente)
    if (siguiente_estado) {
        const estadoInfo = getEstadoInfo(siguiente_estado);
        const buttonColor = estadoInfo.color;
        
        botonesAccion += `
            <button type="button" class="btn" style="background: ${buttonColor}; color: white; border-color: ${buttonColor};" onclick="mostrarConfirmacionEstado(${orden.ID}, ${siguiente_estado}, '${siguiente_estado_texto}', '${siguiente_estado_icon}')">
                <i class="fa-solid ${siguiente_estado_icon} me-1"></i>
                ${siguiente_estado_texto}
            </button>
        `;
    }
    
    document.getElementById('modalActions').innerHTML = botonesAccion;
    
    // Forzar refresh de iconos después de actualizar el contenido
    setTimeout(() => {
        refreshFontAwesome();
    }, 100);
}

function getEstadoInfo(estado) {
    const estados = {
        1: { class: 'ordenes-warning', text: 'Pendiente', icon: 'fa-clock', cssClass: 'warning', color: '#D3AF37' },
        2: { class: 'ordenes-info', text: 'En Proceso', icon: 'fa-gear', cssClass: 'info', color: '#274AB3' },
        3: { class: 'ordenes-success', text: 'Terminado', icon: 'fa-check', cssClass: 'success', color: '#10b981' },
        4: { class: 'ordenes-dark', text: 'Cerrado', icon: 'fa-lock', cssClass: 'dark', color: '#374151' }
    };
    return estados[estado] || estados[1];
}

function mostrarConfirmacionEstado(ordenId, nuevoEstado, textoAccion, iconoAccion) {
    try {
        console.log('mostrarConfirmacionEstado called with:', { ordenId, nuevoEstado, textoAccion, iconoAccion });
        
        // Cerrar modal de detalle
        const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleOrdenModal'));
        if (detalleModal) detalleModal.hide();
        
        // Configurar modal de confirmación
        const estadoInfo = getEstadoInfo(nuevoEstado);
        console.log('estadoInfo:', estadoInfo);
        
        // Configurar icono con color directo
        const iconElement = document.getElementById('estadoIcon');
        if (iconElement) {
            iconElement.className = `fa-solid ${iconoAccion} fa-3x mb-3`;
            iconElement.style.color = estadoInfo.color;
            iconElement.style.display = 'inline-block';
        }
        
        // Configurar textos
        const tituloElement = document.getElementById('estadoTitulo');
        if (tituloElement) {
            tituloElement.textContent = `¿${textoAccion}?`;
            tituloElement.style.display = 'block';
            tituloElement.style.visibility = 'visible';
        }
        
        const descripcionElement = document.getElementById('estadoDescripcion');
        if (descripcionElement) {
            descripcionElement.textContent = `La orden cambiará al estado: ${estadoInfo.text}`;
            descripcionElement.style.display = 'block';
            descripcionElement.style.visibility = 'visible';
        }
        
        const confirmOrdenIdElement = document.getElementById('confirmOrdenId');
        if (confirmOrdenIdElement) {
            confirmOrdenIdElement.textContent = ordenId;
        }
        
        const confirmOrdenInfoElement = document.getElementById('confirmOrdenInfo');
        if (confirmOrdenInfoElement) {
            confirmOrdenInfoElement.textContent = `Esta acción actualizará el estado de la orden y registrará la fecha del cambio.`;
        }
        
        // Configurar botón de confirmación
        const confirmarBtn = document.getElementById('confirmarCambioBtn');
        if (confirmarBtn) {
            confirmarBtn.className = 'btn';
            confirmarBtn.style.backgroundColor = estadoInfo.color;
            confirmarBtn.style.borderColor = estadoInfo.color;
            confirmarBtn.style.color = 'white';
            confirmarBtn.disabled = false;
            confirmarBtn.innerHTML = `<i class="fa-solid fa-check me-1"></i> ${textoAccion}`;
            confirmarBtn.onclick = () => ejecutarCambioEstado(ordenId, nuevoEstado);
        }
        
        // Mostrar modal
        const confirmModal = new bootstrap.Modal(document.getElementById('cambiarEstadoModal'));
        confirmModal.show();
        
        // Forzar refresh de iconos y texto después de mostrar el modal
        setTimeout(() => {
            refreshFontAwesome();
            // Asegurar que los textos sean visibles
            const titulo = document.getElementById('estadoTitulo');
            const descripcion = document.getElementById('estadoDescripcion');
            if (titulo) titulo.style.opacity = '1';
            if (descripcion) descripcion.style.opacity = '1';
        }, 150);
        
    } catch (error) {
        console.error('Error in mostrarConfirmacionEstado:', error);
        // Fallback: usar toast en lugar del modal problemático
        showAlert(`¿Confirmar ${textoAccion}?`, 'warning');
    }
}

function ejecutarCambioEstado(ordenId, nuevoEstado) {
    const confirmarBtn = document.getElementById('confirmarCambioBtn');
    const originalContent = confirmarBtn.innerHTML;
    const originalStyle = {
        backgroundColor: confirmarBtn.style.backgroundColor,
        borderColor: confirmarBtn.style.borderColor,
        color: confirmarBtn.style.color
    };
    
    // Cerrar modal inmediatamente al iniciar el proceso
    const confirmModal = bootstrap.Modal.getInstance(document.getElementById('cambiarEstadoModal'));
    if (confirmModal) confirmModal.hide();
    
    // Mostrar loading en toast en lugar del botón
    showAlert('Procesando cambio de estado...', 'info');
    
    fetch('ajax/cambiar-estado-orden.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orden_id: ordenId, estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar toast de éxito
            showAlert(data.message, 'success');
            
            // Recargar página después de un breve delay
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showAlert('Error al cambiar estado', 'error');
    });
}

function cambiarEstado(ordenId, nuevoEstado) {
    const estados = {
        2: { texto: 'Iniciar Proceso', icono: 'fa-play' },
        3: { texto: 'Marcar Terminado', icono: 'fa-check' },
        4: { texto: 'Cerrar Orden', icono: 'fa-lock' }
    };
    
    const estado = estados[nuevoEstado];
    if (estado) {
        mostrarConfirmacionEstado(ordenId, nuevoEstado, estado.texto, estado.icono);
    }
}

// Función para mostrar alertas modernas
function showAlert(message, type = 'info') {
    // Remover alertas existentes
    const existingAlert = document.querySelector('.modern-alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Mapear tipos a Bootstrap
    const getBootstrapAlertType = (type) => {
        const typeMap = {
            'success': 'success',
            'error': 'danger',
            'warning': 'warning',
            'info': 'info'
        };
        return typeMap[type] || 'info';
    };
    
    // Mapear tipos a iconos
    const getAlertIcon = (type) => {
        const iconMap = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return iconMap[type] || 'fa-info-circle';
    };
    
    // Crear nueva alerta (toast style)
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${getBootstrapAlertType(type)} alert-dismissible fade show modern-alert`;
    alertDiv.style.cssText = `
        position: fixed; 
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 320px; 
        max-width: 400px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        border: none;
        font-weight: 500;
        animation: slideInRight 0.3s ease-out;
    `;
    
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fa-solid ${getAlertIcon(type)} me-2" style="font-size: 1.1em;"></i>
            <span class="flex-grow-1">${message}</span>
            <button type="button" class="btn-close ms-2" onclick="closeAlert(this)"></button>
        </div>
    `;
    
    // Agregar animaciones CSS si no existen
    if (!document.querySelector('#toast-animations-ordenes')) {
        const style = document.createElement('style');
        style.id = 'toast-animations-ordenes';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alertDiv);
    
    // Auto-remover después de 3 segundos con animación
    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) alertDiv.remove();
            }, 300);
        }
    }, 3000);
}

// Función para cerrar alerta manualmente
function closeAlert(button) {
    const alertDiv = button.closest('.modern-alert');
    if (alertDiv && alertDiv.parentNode) {
        alertDiv.style.animation = 'slideOutRight 0.2s ease-in';
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) alertDiv.remove();
        }, 200);
    }
}

// Función para forzar la recarga de iconos Font Awesome
function refreshFontAwesome() {
    // Forzar re-renderizado de iconos Font Awesome
    const icons = document.querySelectorAll('.fa-solid, .fas, .far, .fab');
    icons.forEach(icon => {
        // Forzar repaint
        icon.style.display = 'none';
        icon.offsetHeight; // Trigger reflow
        icon.style.display = '';
        
        // Asegurar que tenga las clases correctas de Font Awesome
        if (icon.classList.contains('fa-solid')) {
            icon.style.fontFamily = '"Font Awesome 6 Free"';
            icon.style.fontWeight = '900';
        }
    });
    
    // Específicamente para el modal de confirmación
    const modalIcon = document.getElementById('estadoIcon');
    if (modalIcon) {
        modalIcon.style.fontFamily = '"Font Awesome 6 Free"';
        modalIcon.style.fontWeight = '900';
        modalIcon.style.display = 'inline-block';
    }
}

// Filtrado de órdenes
document.addEventListener('DOMContentLoaded', function() {
    const filterRadios = document.querySelectorAll('input[name="estadoFilter"]');
    const mobileSelect = document.getElementById('estadoFilterMobile');
    
    // Función para aplicar filtro
    function aplicarFiltro(filterValue) {
        // Filtrar filas de tabla (desktop)
        const tableRows = document.querySelectorAll('tbody tr[data-estado]');
        tableRows.forEach(row => {
            const estado = row.getAttribute('data-estado');
            if (filterValue === 'all' || filterValue === estado) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Filtrar cards mobile
        const mobileCards = document.querySelectorAll('.order-card[data-estado]');
        mobileCards.forEach(card => {
            const estado = card.getAttribute('data-estado');
            if (filterValue === 'all' || filterValue === estado) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Actualizar contadores visibles
        updateVisibleCounts(filterValue);
    }
    
    // Función para actualizar contadores de elementos visibles
    function updateVisibleCounts(filterValue) {
        const allRows = document.querySelectorAll('tbody tr[data-estado]');
        const allCards = document.querySelectorAll('.order-card[data-estado]');
        let visibleCount = 0;
        
        // Contar filas visibles (desktop)
        allRows.forEach(element => {
            if (element.style.display !== 'none') {
                visibleCount++;
            }
        });
        
        // Si no hay filas visibles, contar cards (mobile)
        if (visibleCount === 0) {
            allCards.forEach(element => {
                if (element.style.display !== 'none') {
                    visibleCount++;
                }
            });
        }
        
        // Mostrar mensaje si no hay resultados
        const noResultsMsg = document.getElementById('noResultsMessage');
        if (noResultsMsg) {
            noResultsMsg.remove();
        }
        
        if (visibleCount === 0 && filterValue !== 'all') {
            const container = document.querySelector('.card .card-body');
            const message = document.createElement('div');
            message.id = 'noResultsMessage';
            message.className = 'alert alert-info text-center m-3';
            message.innerHTML = `
                <i class="fa-solid fa-search fa-2x mb-3"></i>
                <h5>No hay órdenes en este estado</h5>
                <p class="mb-0">No se encontraron órdenes que coincidan con el filtro seleccionado.</p>
            `;
            container.appendChild(message);
        }
    }
    
    // Event listeners para radio buttons (desktop)
    filterRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                aplicarFiltro(this.value);
                // Sincronizar con mobile select
                if (mobileSelect) {
                    mobileSelect.value = this.value;
                }
            }
        });
    });
    
    // Event listener para mobile select
    if (mobileSelect) {
        mobileSelect.addEventListener('change', function() {
            aplicarFiltro(this.value);
            // Sincronizar con radio buttons
            const targetRadio = document.querySelector(`input[name="estadoFilter"][value="${this.value}"]`);
            if (targetRadio) {
                targetRadio.checked = true;
            }
        });
    }
    
    // Aplicar filtro inicial
    const checkedRadio = document.querySelector('input[name="estadoFilter"]:checked');
    if (checkedRadio) {
        aplicarFiltro(checkedRadio.value);
    }
});
</script>

<script>
/* =========================
   EDITOR DE ÓRDENES
========================= */
function editarOrden(ordenId) {
    console.log('Editando orden:', ordenId);
    
    // Cerrar modal de detalle
    const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleOrdenModal'));
    if (detalleModal) detalleModal.hide();
    
    // Mostrar modal de editor
    document.getElementById('editOrdenId').textContent = ordenId;
    
    // Cargar contenido del editor
    document.getElementById('editOrdenContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fa-solid fa-spinner fa-spin fa-2x mb-3"></i>
            <p>Cargando editor de orden...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('editarOrdenModal'));
    modal.show();
    
    // Cargar datos de la orden para editar
    fetch('ajax/obtener-orden-editar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orden_id: ordenId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderizarEditorOrden(data.orden);
        } else {
            document.getElementById('editOrdenContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Error al cargar la orden: ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('editOrdenContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                Error al cargar la orden
            </div>
        `;
    });
}

function renderizarEditorOrden(orden) {
    const servicios = JSON.parse(orden.ServiciosJSON || '[]');
    
    let serviciosHtml = '';
    servicios.forEach((servicio, index) => {
        serviciosHtml += `
            <tr>
                <td>
                    <input type="text" class="form-control" value="${servicio.nombre || 'Servicio ' + servicio.id}" 
                           id="servicio_nombre_${index}" placeholder="Nombre del servicio">
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">₡</span>
                        <input type="number" class="form-control" value="${servicio.precio}" 
                               id="servicio_precio_${index}" min="0" step="0.01" onchange="recalcularTotalesEditor()">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicioEditor(${index})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    const content = `
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fa-solid fa-info-circle me-2"></i>Información de la Orden</h6>
                <div class="card">
                    <div class="card-body">
                        <p><strong>Cliente:</strong> ${orden.ClienteNombre}</p>
                        <p><strong>Vehículo:</strong> ${orden.Placa} - ${orden.Marca} ${orden.Modelo}</p>
                        <p><strong>Estado:</strong> ${getEstadoTexto(orden.Estado)}</p>
                        <p><strong>Fecha:</strong> ${new Date(orden.FechaIngreso).toLocaleString('es-CR')}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6><i class="fa-solid fa-comment me-2"></i>Observaciones</h6>
                <textarea class="form-control" id="edit_observaciones" rows="4" placeholder="Observaciones adicionales">${orden.Observaciones || ''}</textarea>
            </div>
        </div>
        
        <hr>
        
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6><i class="fa-solid fa-list me-2"></i>Servicios</h6>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarServicioEditor()">
                        <i class="fa-solid fa-plus me-1"></i>
                        Agregar Servicio
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th width="200">Precio</th>
                                <th width="80" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="servicios-editor-body">
                            ${serviciosHtml}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td colspan="2" class="text-end"><strong id="editor-subtotal">₡0.00</strong></td>
                            </tr>
                            <tr>
                                <td><strong>IVA (13%)</strong></td>
                                <td colspan="2" class="text-end"><strong id="editor-iva">₡0.00</strong></td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Total</strong></td>
                                <td colspan="2" class="text-end"><strong id="editor-total">₡0.00</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('editOrdenContent').innerHTML = content;
    
    // Configurar el botón de guardar
    document.getElementById('guardarCambiosBtn').onclick = () => guardarCambiosOrden(orden.ID);
    
    // Calcular totales iniciales
    setTimeout(recalcularTotalesEditor, 100);
}

function agregarServicioEditor() {
    const tbody = document.getElementById('servicios-editor-body');
    const index = tbody.children.length;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="text" class="form-control" value="" 
                   id="servicio_nombre_${index}" placeholder="Nombre del servicio">
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" class="form-control" value="0" 
                       id="servicio_precio_${index}" min="0" step="0.01" onchange="recalcularTotalesEditor()">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicioEditor(${index})">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Focus en el nombre del nuevo servicio
    document.getElementById(`servicio_nombre_${index}`).focus();
}

function eliminarServicioEditor(index) {
    const row = document.querySelector(`#servicio_nombre_${index}`).closest('tr');
    if (row) {
        row.remove();
        recalcularTotalesEditor();
    }
}

function recalcularTotalesEditor() {
    let subtotal = 0;
    
    // Sumar todos los precios de servicios
    const precios = document.querySelectorAll('[id^="servicio_precio_"]');
    precios.forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const iva = subtotal * 0.13;
    const total = subtotal + iva;
    
    // Actualizar UI
    document.getElementById('editor-subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('editor-iva').textContent = formatCurrency(iva);
    document.getElementById('editor-total').textContent = formatCurrency(total);
}

function guardarCambiosOrden(ordenId) {
    const btn = document.getElementById('guardarCambiosBtn');
    const originalContent = btn.innerHTML;
    
    // Mostrar loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...';
    
    // Recopilar datos
    const servicios = [];
    const nombres = document.querySelectorAll('[id^="servicio_nombre_"]');
    const precios = document.querySelectorAll('[id^="servicio_precio_"]');
    
    nombres.forEach((nombreInput, index) => {
        const precioInput = precios[index];
        if (nombreInput.value.trim() && precioInput.value > 0) {
            servicios.push({
                id: `custom_${index}`,
                nombre: nombreInput.value.trim(),
                precio: parseFloat(precioInput.value),
                personalizado: true
            });
        }
    });
    
    const datos = {
        orden_id: ordenId,
        servicios: servicios,
        observaciones: document.getElementById('edit_observaciones').value.trim()
    };
    
    // Enviar al servidor
    fetch('ajax/actualizar-orden.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Orden actualizada exitosamente', 'success');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editarOrdenModal'));
            if (modal) modal.hide();
            
            // Recargar página para mostrar cambios
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error al actualizar la orden', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}

/* =========================
   CALCULADORA DE CIERRE
========================= */
function mostrarCalculadoraCierre(ordenId) {
    console.log('Mostrando calculadora de cierre para orden:', ordenId);
    
    // Cerrar modal de detalle
    const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleOrdenModal'));
    if (detalleModal) detalleModal.hide();
    
    // Mostrar modal de calculadora
    document.getElementById('calcOrdenId').textContent = ordenId;
    
    // Cargar contenido de la calculadora
    document.getElementById('calculadoraContent').innerHTML = `
        <div class="text-center py-4">
            <i class="fa-solid fa-spinner fa-spin fa-2x mb-3"></i>
            <p>Cargando calculadora de cierre...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('calculadoraCierreModal'));
    modal.show();
    
    // Cargar datos de la orden
    fetch('ajax/obtener-orden-editar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orden_id: ordenId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderizarCalculadoraCierre(data.orden);
        } else {
            document.getElementById('calculadoraContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Error al cargar la orden: ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('calculadoraContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                Error al cargar la orden
            </div>
        `;
    });
}

function renderizarCalculadoraCierre(orden) {
    const servicios = JSON.parse(orden.ServiciosJSON || '[]');
    
    let serviciosHtml = '';
    servicios.forEach((servicio, index) => {
        serviciosHtml += `
            <tr>
                <td>${servicio.nombre || 'Servicio ' + servicio.id}</td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">₡</span>
                        <input type="number" class="form-control" value="${servicio.precio}" 
                               id="calc_precio_${index}" min="0" step="0.01" onchange="recalcularTotalesCalculadora()">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicioCalculadora(${index})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    const content = `
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Información</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Cliente:</strong><br>${orden.ClienteNombre}</p>
                        <p><strong>Vehículo:</strong><br>${orden.Placa} - ${orden.Marca} ${orden.Modelo}</p>
                        <p><strong>Estado:</strong><br><span class="badge bg-success">Terminado</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fa-solid fa-calculator me-2"></i>Calculadora de Precios</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarItemCalculadora()">
                            <i class="fa-solid fa-plus me-1"></i>
                            Agregar Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Concepto</th>
                                        <th width="150">Precio</th>
                                        <th width="60" class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="calculadora-body">
                                    ${serviciosHtml}
                                </tbody>
                            </table>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fa-solid fa-percent me-1"></i>
                                    Descuento
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="calc_descuento" 
                                           value="${orden.Descuento || 0}" min="0" step="0.01" 
                                           onchange="recalcularTotalesCalculadora()">
                                    <span class="input-group-text">₡</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fa-solid fa-comment me-1"></i>
                                    Notas de Cierre
                                </label>
                                <textarea class="form-control" id="calc_notas" rows="2" 
                                          placeholder="Notas adicionales para el cierre">${orden.Observaciones || ''}</textarea>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end"><strong id="calc-subtotal">₡0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descuento:</strong></td>
                                        <td class="text-end"><strong id="calc-descuento-display">₡0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IVA (13%):</strong></td>
                                        <td class="text-end"><strong id="calc-iva">₡0.00</strong></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td><strong>Total Final:</strong></td>
                                        <td class="text-end"><strong id="calc-total">₡0.00</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('calculadoraContent').innerHTML = content;
    
    // Configurar el botón de cerrar orden
    document.getElementById('cerrarOrdenBtn').onclick = () => cerrarOrdenFinal(orden.ID);
    
    // Calcular totales iniciales
    setTimeout(recalcularTotalesCalculadora, 100);
}

function agregarItemCalculadora() {
    const tbody = document.getElementById('calculadora-body');
    const index = tbody.children.length;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="text" class="form-control form-control-sm" 
                   id="calc_nombre_${index}" placeholder="Concepto adicional">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">₡</span>
                <input type="number" class="form-control" value="0" 
                       id="calc_precio_${index}" min="0" step="0.01" onchange="recalcularTotalesCalculadora()">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicioCalculadora(${index})">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Focus en el nombre del nuevo item
    document.getElementById(`calc_nombre_${index}`).focus();
}

function eliminarServicioCalculadora(index) {
    const row = document.querySelector(`#calc_precio_${index}`).closest('tr');
    if (row) {
        row.remove();
        recalcularTotalesCalculadora();
    }
}

function recalcularTotalesCalculadora() {
    let subtotal = 0;
    
    // Sumar todos los precios
    const precios = document.querySelectorAll('[id^="calc_precio_"]');
    precios.forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const descuento = parseFloat(document.getElementById('calc_descuento').value) || 0;
    const subtotalConDescuento = subtotal - descuento;
    const iva = subtotalConDescuento * 0.13;
    const total = subtotalConDescuento + iva;
    
    // Actualizar UI
    document.getElementById('calc-subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('calc-descuento-display').textContent = formatCurrency(descuento);
    document.getElementById('calc-iva').textContent = formatCurrency(iva);
    document.getElementById('calc-total').textContent = formatCurrency(total);
}

function cerrarOrdenFinal(ordenId) {
    const btn = document.getElementById('cerrarOrdenBtn');
    const originalContent = btn.innerHTML;
    
    // Mostrar loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Cerrando...';
    
    // Recopilar datos finales
    const servicios = [];
    const nombres = document.querySelectorAll('[id^="calc_nombre_"]');
    const precios = document.querySelectorAll('[id^="calc_precio_"]');
    
    precios.forEach((precioInput, index) => {
        const nombreInput = nombres[index];
        const nombre = nombreInput ? nombreInput.value.trim() : `Servicio ${index + 1}`;
        
        if (precioInput.value > 0) {
            servicios.push({
                id: `final_${index}`,
                nombre: nombre || `Servicio ${index + 1}`,
                precio: parseFloat(precioInput.value),
                personalizado: true
            });
        }
    });
    
    const datos = {
        orden_id: ordenId,
        servicios: servicios,
        descuento: parseFloat(document.getElementById('calc_descuento').value) || 0,
        observaciones: document.getElementById('calc_notas').value.trim(),
        estado: 4 // Cerrado
    };
    
    // Enviar al servidor
    fetch('ajax/cerrar-orden-final.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Orden cerrada exitosamente', 'success');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('calculadoraCierreModal'));
            if (modal) modal.hide();
            
            // Recargar página para mostrar cambios
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error al cerrar la orden', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}

// Función auxiliar para obtener texto del estado
function getEstadoTexto(estado) {
    const estados = {
        1: 'Pendiente',
        2: 'En Proceso', 
        3: 'Terminado',
        4: 'Cerrado'
    };
    return estados[estado] || 'Desconocido';
}

// Función auxiliar para formatear moneda
function formatCurrency(value) {
    return value.toLocaleString('es-CR', {
        style: 'currency',
        currency: 'CRC'
    });
}

// Función para mostrar toast (alias para showAlert)
function showToast(message, type) {
    showAlert(message, type);
}
</script>

<?php require 'lavacar/partials/footer.php'; ?>
