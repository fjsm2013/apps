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
                <div>
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
                                        <button class="btn btn-sm" style="background: var(--ordenes-success); color: white; border-color: var(--ordenes-success);" onclick="cambiarEstado(<?= $orden['ID'] ?>, <?= $orden['Estado'] + 1 ?>)" title="Avanzar Estado">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Vista Tablet/Mobile: Cards Compactas (OCULTA POR AHORA) -->
                <div class="d-none">
                    <div class="row g-2 p-3">
                        <?php foreach ($ordenes as $orden): ?>
                            <?php
                            $estadoClass = 'secondary';
                            $estadoText = 'Pendiente';
                            $estadoIcon = 'fa-clock';
                            
                            switch ($orden['Estado']) {
                                case 1:
                                    $estadoClass = 'warning';
                                    $estadoText = 'Pendiente';
                                    $estadoIcon = 'fa-clock';
                                    break;
                                case 2:
                                    $estadoClass = 'info';
                                    $estadoText = 'En Proceso';
                                    $estadoIcon = 'fa-gear';
                                    break;
                                case 3:
                                    $estadoClass = 'success';
                                    $estadoText = 'Terminado';
                                    $estadoIcon = 'fa-check';
                                    break;
                                case 4:
                                    $estadoClass = 'dark';
                                    $estadoText = 'Cerrado';
                                    $estadoIcon = 'fa-lock';
                                    break;
                            }
                            ?>
                            <div class="col-12" data-estado="<?= $orden['Estado'] ?>">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 text-primary">#<?= $orden['ID'] ?></h6>
                                            <span class="badge bg-<?= $estadoClass ?>">
                                                <i class="fa-solid <?= $estadoIcon ?> me-1"></i>
                                                <?= $estadoText ?>
                                            </span>
                                        </div>
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Cliente</small>
                                                <strong><?= safe_htmlspecialchars($orden['ClienteNombre'], 'Sin cliente') ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Vehículo</small>
                                                <strong><?= safe_htmlspecialchars($orden['Placa'], 'Sin placa') ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Monto</small>
                                                <strong class="text-success">₡<?= safe_number_format($orden['Monto'], 0) ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Fecha</small>
                                                <small><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></small>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm flex-fill" style="background: var(--ordenes-info); color: white; border-color: var(--ordenes-info);" onclick="verDetalleOrden(<?= $orden['ID'] ?>)">
                                                <i class="fa-solid fa-eye me-1"></i> Ver
                                            </button>
                                            <?php if ($orden['Estado'] < 4): ?>
                                            <button class="btn btn-sm flex-fill" style="background: var(--ordenes-success); color: white; border-color: var(--ordenes-success);" onclick="cambiarEstado(<?= $orden['ID'] ?>, <?= $orden['Estado'] + 1 ?>)">
                                                <i class="fa-solid fa-arrow-right me-1"></i> Avanzar
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
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
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
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
                            <span class="badge badge-${estadoInfo.class} fs-6">
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
    
    // Agregar botón de acción si es posible avanzar estado
    if (siguiente_estado) {
        const estadoInfo = getEstadoInfo(siguiente_estado);
        const buttonColor = siguiente_estado === 2 ? 'var(--ordenes-info)' : 
                           siguiente_estado === 3 ? 'var(--ordenes-success)' : 
                           'var(--ordenes-dark)';
        
        document.getElementById('modalActions').innerHTML = `
            <button type="button" class="btn" style="background: ${buttonColor}; color: white; border-color: ${buttonColor};" onclick="mostrarConfirmacionEstado(${orden.ID}, ${siguiente_estado}, '${siguiente_estado_texto}', '${siguiente_estado_icon}')">
                <i class="fa-solid ${siguiente_estado_icon} me-1"></i>
                ${siguiente_estado_texto}
            </button>
        `;
    }
}

function getEstadoInfo(estado) {
    const estados = {
        1: { class: 'ordenes-warning', text: 'Pendiente', icon: 'fa-clock', cssClass: 'warning' },
        2: { class: 'ordenes-info', text: 'En Proceso', icon: 'fa-gear', cssClass: 'info' },
        3: { class: 'ordenes-success', text: 'Terminado', icon: 'fa-check', cssClass: 'success' },
        4: { class: 'ordenes-dark', text: 'Cerrado', icon: 'fa-lock', cssClass: 'dark' }
    };
    return estados[estado] || estados[1];
}

function mostrarConfirmacionEstado(ordenId, nuevoEstado, textoAccion, iconoAccion) {
    // Cerrar modal de detalle
    const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleOrdenModal'));
    if (detalleModal) detalleModal.hide();
    
    // Configurar modal de confirmación
    const estadoInfo = getEstadoInfo(nuevoEstado);
    
    document.getElementById('estadoIcon').className = `fa-solid ${iconoAccion} fa-3x text-${estadoInfo.class} mb-3`;
    document.getElementById('estadoTitulo').textContent = `¿${textoAccion}?`;
    document.getElementById('estadoDescripcion').textContent = `La orden cambiará al estado: ${estadoInfo.text}`;
    document.getElementById('confirmOrdenId').textContent = ordenId;
    document.getElementById('confirmOrdenInfo').textContent = `Esta acción actualizará el estado de la orden y registrará la fecha del cambio.`;
    
    // Configurar botón de confirmación
    const confirmarBtn = document.getElementById('confirmarCambioBtn');
    confirmarBtn.className = `btn btn-${estadoInfo.class}`;
    confirmarBtn.innerHTML = `<i class="fa-solid fa-check me-1"></i> ${textoAccion}`;
    confirmarBtn.onclick = () => ejecutarCambioEstado(ordenId, nuevoEstado);
    
    // Mostrar modal
    const confirmModal = new bootstrap.Modal(document.getElementById('cambiarEstadoModal'));
    confirmModal.show();
}

function ejecutarCambioEstado(ordenId, nuevoEstado) {
    const confirmarBtn = document.getElementById('confirmarCambioBtn');
    const originalContent = confirmarBtn.innerHTML;
    
    // Mostrar loading
    confirmarBtn.disabled = true;
    confirmarBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Procesando...';
    
    fetch('ajax/cambiar-estado-orden.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orden_id: ordenId, estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal de confirmación
            const confirmModal = bootstrap.Modal.getInstance(document.getElementById('cambiarEstadoModal'));
            if (confirmModal) confirmModal.hide();
            
            showAlert(data.message, 'success');
            
            // Recargar página después de un momento
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('Error: ' + data.message, 'error');
            confirmarBtn.disabled = false;
            confirmarBtn.innerHTML = originalContent;
        }
    })
    .catch(error => {
        showAlert('Error al cambiar estado', 'error');
        confirmarBtn.disabled = false;
        confirmarBtn.innerHTML = originalContent;
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
    
    // Agregar estilos inline para la alerta
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        background: white;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        border-left: 4px solid ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#3b82f6'};
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 400px;
        animation: slideIn 0.3s ease;
        font-weight: 500;
        color: #1e293b;
    `;
    
    // Agregar animación
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .alert-close {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        .alert-close:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
    `;
    document.head.appendChild(style);
    
    document.body.appendChild(alert);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alert && alert.parentNode) {
            alert.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => alert.remove(), 300);
        }
    }, 5000);
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
        
        // Actualizar contadores visibles
        updateVisibleCounts(filterValue);
    }
    
    // Función para actualizar contadores de elementos visibles
    function updateVisibleCounts(filterValue) {
        const allRows = document.querySelectorAll('tbody tr[data-estado]');
        let visibleCount = 0;
        
        allRows.forEach(element => {
            if (element.style.display !== 'none') {
                visibleCount++;
            }
        });
        
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

<?php require 'lavacar/partials/footer.php'; ?>