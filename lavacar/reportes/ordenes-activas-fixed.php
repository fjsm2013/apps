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