<?php
session_start();

require_once '../../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/VehiculosManager.php';
require_once 'lavacar/backend/ClientesManager.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

$vehiculosManager = new VehiculosManager($conn, $dbName);
$clientesManager = new ClientesManager($conn, $dbName);

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

/* ACTIONS */
if ($action === 'toggle' && $id) {
    $vehiculosManager->toggle($id);
    header("Location: vehiculos.php");
    exit;
}

if ($action === 'delete' && $id) {
    $vehiculosManager->deactivate($id);
    header("Location: vehiculos.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    $data = [
        'ClienteID' => (int)($_POST['ClienteID'] ?? 0),
        'Marca' => trim($_POST['Marca'] ?? ''),
        'Modelo' => trim($_POST['Modelo'] ?? ''),
        'Year' => (int)($_POST['Year'] ?? 0),
        'Placa' => trim($_POST['Placa'] ?? ''),
        'CategoriaVehiculo' => (int)($_POST['CategoriaVehiculo'] ?? 0),
        'Color' => trim($_POST['Color'] ?? ''),
        'Observaciones' => trim($_POST['Observaciones'] ?? ''),
        'active' => (int)($_POST['active'] ?? 1),
    ];

    if ($id > 0) {
        $vehiculosManager->update($id, $data);
    } else {
        $vehiculosManager->create($data);
    }

    header("Location: vehiculos.php");
    exit;
}

// Parámetros de búsqueda
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? 'all';
$clienteId = (int)($_GET['cliente_id'] ?? 0);

// Obtener vehículos con información relacionada
$vehiculos = $vehiculosManager->getAllWithRelations($search, $status);

// Si hay filtro por cliente, aplicarlo
if ($clienteId > 0) {
    $vehiculos = array_filter($vehiculos, function($v) use ($clienteId) {
        return $v['ClienteID'] == $clienteId;
    });
}

// Estadísticas
$stats = $vehiculosManager->getStats();

// Obtener clientes para dropdown
$clientes = $clientesManager->all(['active' => 1], 'NombreCompleto ASC');

// Obtener categorías para dropdown
$categorias = $vehiculosManager->getCategories();

// Define breadcrumbs
$breadcrumbs = array(
    array('title' => 'Administración', 'url' => '../index.php'),
    array('title' => 'Vehículos', 'url' => '')
);

require 'lavacar/partials/header.php';
?>

<?php include 'lavacar/partials/breadcrumb.php'; ?>

<div class="container container-fluid py-4">

    <!-- Header con estadísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div class="header-info">
                    <h2><i class="fa-solid fa-car me-2"></i>Gestión de Vehículos</h2>
                    <p class="text-muted mb-0">Administra los vehículos registrados en el sistema</p>
                </div>
                <button class="btn btn-frosh-primary btn-lg new-vehicle-btn" onclick="openCreate()">
                    <i class="fa fa-plus me-2"></i> 
                    <span class="d-none d-sm-inline">Nuevo Vehículo</span>
                    <span class="d-sm-none">Nuevo</span>
                </button>
            </div>

            <?php /* Comentado temporalmente - Estadísticas de vehículos
            // Estadísticas rápidas
            $statsConfig = [
                'total_vehiculos' => [
                    'value' => $stats['total_vehiculos'] ?? 0,
                    'label' => 'Total Vehículos',
                    'icon' => 'fa-solid fa-car'
                ],
                'vehiculos_activos' => [
                    'value' => $stats['vehiculos_activos'] ?? 0,
                    'label' => 'Activos',
                    'icon' => 'fa-solid fa-car-side'
                ],
                'con_ordenes' => [
                    'value' => $stats['con_ordenes'] ?? 0,
                    'label' => 'Con Órdenes',
                    'icon' => 'fa-solid fa-list-check'
                ],
                'nuevos_mes' => [
                    'value' => $stats['nuevos_mes'] ?? 0,
                    'label' => 'Nuevos este mes',
                    'icon' => 'fa-solid fa-calendar-plus'
                ]
            ];
            echo generateStatsCards($statsConfig);
            */ ?>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <?php
            $filterConfig = [
                'fields' => [
                    [
                        'type' => 'search',
                        'name' => 'search',
                        'label' => 'Buscar por placa, marca, modelo o cliente',
                        'placeholder' => 'Ej: ABC123, Toyota, Corolla, Juan Pérez...',
                        'width' => 5
                    ],
                    [
                        'type' => 'select',
                        'name' => 'status',
                        'label' => 'Estado',
                        'options' => [
                            'all' => 'Todos',
                            'active' => 'Activos',
                            'inactive' => 'Inactivos'
                        ],
                        'width' => 2
                    ]
                ],
                'search_text' => 'Buscar',
                'reset_text' => 'Limpiar',
                'reset_url' => 'vehiculos.php'
            ];
            echo generateSearchFilters($filterConfig);
            ?>
        </div>
    </div>

    <!-- Tabla de vehículos -->
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-list me-2"></i>Lista de Vehículos</h5>
                    <small class="text-muted">
                        <?= count($vehiculos) ?> vehículo(s) encontrado(s)
                        <?= $search ? " para '{$search}'" : '' ?>
                    </small>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($vehiculos)): ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-car"></i>
                            <h5>No se encontraron vehículos</h5>
                            <p class="text-muted">
                                <?= $search ? "No hay vehículos que coincidan con '{$search}'" : 'No hay vehículos registrados' ?>
                            </p>
                            <?php if (!$search): ?>
                                <button class="btn btn-frosh-primary" onclick="openCreate()">
                                    <i class="fa fa-plus me-2"></i> Registrar primer vehículo
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!-- Vista Desktop -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Vehículo</th>
                                        <th>Propietario</th>
                                        <th>Categoría</th>
                                        <th>Historial</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vehiculos as $v): ?>
                                    <tr>
                                        <td>
                                            <div class="vehicle-info">
                                                <strong class="vehicle-plate"><?= safe_htmlspecialchars($v['Placa']) ?></strong>
                                                <div class="vehicle-details">
                                                    <span class="vehicle-brand"><?= safe_htmlspecialchars($v['Marca']) ?></span>
                                                    <span class="vehicle-model"><?= safe_htmlspecialchars($v['Modelo']) ?></span>
                                                    <?php if ($v['Year']): ?>
                                                        <span class="vehicle-year"><?= $v['Year'] ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if ($v['Color']): ?>
                                                    <small class="text-muted">
                                                        <i class="fa-solid fa-palette me-1"></i><?= safe_htmlspecialchars($v['Color']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="owner-info">
                                                <?php if ($v['cliente_nombre']): ?>
                                                    <strong><?= safe_htmlspecialchars($v['cliente_nombre']) ?></strong>
                                                    <?php if ($v['cliente_telefono']): ?>
                                                        <small class="d-block text-muted">
                                                            <i class="fa-solid fa-phone me-1"></i><?= safe_htmlspecialchars($v['cliente_telefono']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin propietario</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($v['categoria_nombre']): ?>
                                                <span class="badge badge-frosh-gray"><?= safe_htmlspecialchars($v['categoria_nombre']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Sin categoría</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="history-info">
                                                <?php if ($v['total_ordenes'] > 0): ?>
                                                    <div class="mb-1">
                                                        <span class="badge badge-frosh-dark"><?= $v['total_ordenes'] ?> orden(es)</span>
                                                    </div>
                                                    <div class="text-success">
                                                        <small><?= formatCurrency($v['total_gastado']) ?> pagados</small>
                                                    </div>
                                                    <?php if ($v['ultima_orden']): ?>
                                                        <div class="text-muted">
                                                            <small>Última: <?= formatDate($v['ultima_orden']) ?></small>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin historial</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?= generateStatusBadge($v['active'] ? 'active' : 'inactive') ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-frosh-dark edit-btn" 
                                                        data-vehicle='<?= htmlspecialchars(json_encode($v), ENT_QUOTES, 'UTF-8') ?>' 
                                                        title="Editar">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <?php if ($v['total_ordenes'] > 0): ?>
                                                    <button class="btn btn-sm btn-frosh-gray" onclick='viewHistory(<?= $v['ID'] ?>, "<?= safe_htmlspecialchars($v['Placa']) ?>")' title="Ver historial">
                                                        <i class="fa fa-history"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <a class="btn btn-sm btn-outline-frosh-gray" href="?action=toggle&id=<?= $v['ID'] ?>" title="Cambiar estado">
                                                    <i class="fa fa-power-off"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?= $v['ID'] ?>)" title="Desactivar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Vista Mobile -->
                        <div class="d-md-none">
                            <div class="mobile-vehicles-container p-3">
                                <?php foreach ($vehiculos as $v): ?>
                                <div class="vehicle-card mb-3">
                                    <div class="vehicle-card-header">
                                        <div class="vehicle-main-info">
                                            <h6 class="mb-1"><?= safe_htmlspecialchars($v['Placa']) ?></h6>
                                            <small class="text-muted d-block">
                                                <?= safe_htmlspecialchars($v['Marca']) ?> <?= safe_htmlspecialchars($v['Modelo']) ?>
                                                <?= $v['Year'] ? ' ' . $v['Year'] : '' ?>
                                            </small>
                                            <?php if ($v['Color']): ?>
                                            <small class="text-muted d-block">
                                                <i class="fa-solid fa-palette me-1"></i><?= safe_htmlspecialchars($v['Color']) ?>
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                        <?= generateStatusBadge($v['active'] ? 'active' : 'inactive') ?>
                                    </div>

                                    <div class="vehicle-card-body">
                                        <?php if ($v['cliente_nombre']): ?>
                                        <div class="owner-row mb-3">
                                            <div class="owner-item">
                                                <i class="fa-solid fa-user me-2 text-muted"></i>
                                                <span><?= safe_htmlspecialchars($v['cliente_nombre']) ?></span>
                                            </div>
                                            <?php if ($v['cliente_telefono']): ?>
                                            <div class="owner-item">
                                                <i class="fa-solid fa-phone me-2 text-muted"></i>
                                                <span><?= safe_htmlspecialchars($v['cliente_telefono']) ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>

                                        <div class="vehicle-details-row">
                                            <?php if ($v['categoria_nombre']): ?>
                                            <div class="category-info">
                                                <span class="badge badge-frosh-gray">
                                                    <i class="fa-solid fa-tag me-1"></i><?= safe_htmlspecialchars($v['categoria_nombre']) ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($v['total_ordenes'] > 0): ?>
                                            <div class="history-info">
                                                <span class="badge badge-frosh-dark"><?= $v['total_ordenes'] ?> orden(es)</span>
                                                <small class="d-block text-success mt-1">
                                                    <i class="fa-solid fa-coins me-1"></i><?= formatCurrency($v['total_gastado']) ?>
                                                </small>
                                                <?php if ($v['ultima_orden']): ?>
                                                <small class="d-block text-muted">
                                                    <i class="fa-solid fa-calendar me-1"></i><?= formatDate($v['ultima_orden']) ?>
                                                </small>
                                                <?php endif; ?>
                                            </div>
                                            <?php else: ?>
                                            <div class="history-info">
                                                <span class="text-muted">Sin historial</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="vehicle-card-actions">
                                        <button class="btn btn-sm btn-frosh-dark edit-btn"
                                            data-vehicle='<?= htmlspecialchars(json_encode($v), ENT_QUOTES, 'UTF-8') ?>'>
                                            <i class="fa fa-edit me-1"></i>Editar
                                        </button>
                                        <?php if ($v['total_ordenes'] > 0): ?>
                                        <button class="btn btn-sm btn-frosh-gray" onclick='viewHistory(<?= $v['ID'] ?>, "<?= safe_htmlspecialchars($v['Placa']) ?>")'>
                                            <i class="fa fa-history me-1"></i>Historial
                                        </button>
                                        <?php endif; ?>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="?action=toggle&id=<?= $v['ID'] ?>">
                                                        <i class="fa fa-power-off me-2"></i>Cambiar estado
                                                    </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?= $v['ID'] ?>)">
                                                        <i class="fa fa-trash me-2"></i>Desactivar
                                                    </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL VEHÍCULO -->
<div class="modal fade" id="vehiculoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Vehículo</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="id">
                
                <!-- Información del Vehículo -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-dark border-bottom pb-2">
                            <i class="fa-solid fa-car me-2"></i>Información del Vehículo
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Placa *</label>
                        <input class="form-control" name="Placa" id="Placa" required placeholder="ABC123">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Marca *</label>
                        <input class="form-control" name="Marca" id="Marca" required placeholder="Toyota">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Modelo *</label>
                        <input class="form-control" name="Modelo" id="Modelo" required placeholder="Corolla">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <input type="number" class="form-control" name="Year" id="Year" min="1900" max="2030" placeholder="2020">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Color</label>
                        <input class="form-control" name="Color" id="Color" placeholder="Blanco">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="CategoriaVehiculo" id="CategoriaVehiculo">
                            <option value="">Seleccionar categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['ID'] ?>"><?= safe_htmlspecialchars($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Propietario</label>
                        <select class="form-select" name="ClienteID" id="ClienteID">
                            <option value="">Sin propietario</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['ID'] ?>"><?= safe_htmlspecialchars($cliente['NombreCompleto']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="Observaciones" id="Observaciones" rows="3" placeholder="Notas adicionales sobre el vehículo..."></textarea>
                    </div>
                </div>

                <!-- Estado -->
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="text-dark border-bottom pb-2">
                            <i class="fa-solid fa-toggle-on me-2"></i>Estado
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado del Vehículo</label>
                        <select class="form-select" name="active" id="active">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-frosh-light" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-frosh-primary">
                    <i class="fa-solid fa-save me-1"></i>Guardar Vehículo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HISTORIAL -->
<div class="modal fade" id="historialModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historialModalTitle">Historial del Vehículo</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="historialContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-dark" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando historial...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-frosh-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== VEHICLE INFO STYLES ===== */
.vehicle-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.vehicle-plate {
    color: #1e293b;
    font-size: 1rem;
    font-weight: 700;
}

.vehicle-details {
    display: flex;
    gap: 8px;
    align-items: center;
    font-size: 0.9rem;
}

.vehicle-brand {
    color: #374151;
    font-weight: 600;
}

.vehicle-model {
    color: #6b7280;
}

.vehicle-year {
    color: #9ca3af;
    font-size: 0.85rem;
}

.owner-info strong {
    color: #1e293b;
    font-size: 0.95rem;
}

.history-info {
    font-size: 0.85rem;
}

.history-info .badge {
    font-size: 0.75rem;
}

/* ===== MOBILE VEHICLE CARDS ===== */
.vehicle-main-info h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
}

.vehicle-main-info small {
    font-size: 0.8rem;
}

.owner-row, .category-row, .history-row {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
}

.owner-row i, .category-row i {
    color: #64748b;
    width: 16px;
}

.history-row {
    justify-content: space-between;
    align-items: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .vehicle-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
    }
    
    .history-info {
        font-size: 0.8rem;
    }
}

/* ===== FROSH DARK BUTTONS ===== */
.btn-frosh-dark {
    background: var(--frosh-gray-800, #1f2937);
    border: 1px solid var(--frosh-gray-700, #374151);
    color: white;
    transition: all 0.2s ease;
}

.btn-frosh-dark:hover {
    background: var(--frosh-gray-700, #374151);
    border-color: var(--frosh-gray-600, #4b5563);
    color: white;
    transform: translateY(-1px);
}

.btn-frosh-gray {
    background: var(--frosh-gray-600, #4b5563);
    border: 1px solid var(--frosh-gray-500, #6b7280);
    color: white;
    transition: all 0.2s ease;
}

.btn-frosh-gray:hover {
    background: var(--frosh-gray-500, #6b7280);
    border-color: var(--frosh-gray-400, #9ca3af);
    color: white;
    transform: translateY(-1px);
}

.btn-frosh-primary {
    background: var(--frosh-black, #000000);
    border: 1px solid var(--frosh-black, #000000);
    color: white;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-frosh-primary:hover {
    background: var(--frosh-dark, #1a1a1a);
    border-color: var(--frosh-dark, #1a1a1a);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-frosh-light {
    background: var(--frosh-gray-100, #f3f4f6);
    border: 1px solid var(--frosh-gray-300, #d1d5db);
    color: var(--frosh-gray-800, #1f2937);
    transition: all 0.2s ease;
}

.btn-frosh-light:hover {
    background: var(--frosh-gray-200, #e5e7eb);
    border-color: var(--frosh-gray-400, #9ca3af);
    color: var(--frosh-gray-900, #111827);
    transform: translateY(-1px);
}

.btn-outline-frosh-dark {
    background: transparent;
    border: 1px solid var(--frosh-gray-700, #374151);
    color: var(--frosh-gray-700, #374151);
    transition: all 0.2s ease;
}

.btn-outline-frosh-dark:hover {
    background: var(--frosh-gray-800, #1f2937);
    border-color: var(--frosh-gray-800, #1f2937);
    color: white;
    transform: translateY(-1px);
}

.btn-outline-frosh-gray {
    background: transparent;
    border: 1px solid var(--frosh-gray-500, #6b7280);
    color: var(--frosh-gray-600, #4b5563);
    transition: all 0.2s ease;
}

.btn-outline-frosh-gray:hover {
    background: var(--frosh-gray-600, #4b5563);
    border-color: var(--frosh-gray-600, #4b5563);
    color: white;
    transform: translateY(-1px);
}

/* ===== FROSH BADGES ===== */
.badge-frosh-dark {
    background: var(--frosh-gray-800, #1f2937);
    color: white;
}

.badge-frosh-gray {
    background: var(--frosh-gray-600, #4b5563);
    color: white;
}

.badge-frosh-light {
    background: var(--frosh-gray-200, #e5e7eb);
    color: var(--frosh-gray-800, #1f2937);
}

/* Small button enhancements */
.btn-sm.btn-frosh-dark,
.btn-sm.btn-frosh-gray,
.btn-sm.btn-frosh-primary,
.btn-sm.btn-frosh-light,
.btn-sm.btn-outline-frosh-dark,
.btn-sm.btn-outline-frosh-gray {
    font-weight: 500;
    border-radius: 6px;
}

/* Focus states */
.btn-frosh-dark:focus,
.btn-frosh-gray:focus,
.btn-frosh-primary:focus,
.btn-outline-frosh-dark:focus,
.btn-outline-frosh-gray:focus {
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.25);
}

/* Disabled states */
.btn-frosh-dark:disabled,
.btn-frosh-gray:disabled,
.btn-frosh-primary:disabled,
.btn-outline-frosh-dark:disabled,
.btn-outline-frosh-gray:disabled {
    opacity: 0.5;
    transform: none;
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

/* ===== MOBILE VEHICLE CARDS ===== */
.mobile-vehicles-container {
    background: transparent;
    display: none;
}

@media (max-width: 767px) {
    .mobile-vehicles-container {
        display: block !important;
    }
}

.vehicle-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;
}

.vehicle-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.vehicle-card-header {
    padding: 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.vehicle-main-info {
    flex: 1;
}

.vehicle-main-info h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
    font-size: 1rem;
}

.vehicle-main-info small {
    font-size: 0.8rem;
    color: #64748b;
}

.vehicle-card-body {
    padding: 16px;
}

.owner-row {
    margin-bottom: 0;
}

.owner-item {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    font-size: 0.85rem;
    color: #374151;
}

.owner-item:last-child {
    margin-bottom: 0;
}

.owner-item i {
    color: #64748b;
    width: 16px;
    flex-shrink: 0;
}

.vehicle-details-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 12px;
}

.category-info .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.history-info {
    text-align: right;
}

.history-info .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.history-info small {
    font-size: 0.75rem;
}

.vehicle-card-actions {
    padding: 12px 16px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 8px;
    align-items: center;
}

.vehicle-card-actions .btn {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 500;
}

.vehicle-card-actions .dropdown-toggle {
    padding: 6px 8px;
    min-width: auto;
}

/* ===== RESPONSIVE IMPROVEMENTS ===== */
@media (max-width: 768px) {
    .container.container-fluid {
        padding-left: 12px;
        padding-right: 12px;
    }
    
    /* Header mobile improvements */
    .header-info h2 {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    
    .header-info p {
        font-size: 0.85rem;
    }
    
    .new-vehicle-btn {
        font-size: 0.9rem;
        padding: 8px 16px;
    }
    
    /* Mejorar el layout del header en mobile */
    .d-flex.justify-content-between.align-items-center.mb-3.flex-wrap {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
        text-align: center;
    }
    
    .header-info {
        width: 100%;
    }
    
    .new-vehicle-btn {
        width: 100%;
        justify-content: center;
    }
    
    .vehicle-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
    }
    
    .history-info {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .vehicle-card-actions {
        flex-wrap: wrap;
    }
    
    .vehicle-card-actions .btn:not(.dropdown-toggle) {
        flex: 1;
        min-width: 0;
    }
    
    .vehicle-details-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .history-info {
        text-align: left;
        width: 100%;
    }
}
</style>

<script>
// Inicializar modales al cargar la página
let modal, historialModal;

document.addEventListener('DOMContentLoaded', function() {
    modal = new bootstrap.Modal(document.getElementById('vehiculoModal'));
    historialModal = new bootstrap.Modal(document.getElementById('historialModal'));
    
    // Event listeners para botones de editar
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.edit-btn');
            const vehicleData = JSON.parse(btn.getAttribute('data-vehicle'));
            openEdit(vehicleData);
        }
    });
    
    // Auto-focus en el campo de búsqueda
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});

function openCreate() {
    document.getElementById('modalTitle').textContent = 'Nuevo Vehículo';
    document.querySelector('#vehiculoModal form').reset();
    document.getElementById('id').value = 0;
    document.getElementById('active').value = 1;
    modal.show();
}

function openEdit(v) {
    document.getElementById('modalTitle').textContent = 'Editar Vehículo';
    
    // Limpiar formulario primero
    document.querySelector('#vehiculoModal form').reset();
    
    // Llenar campos básicos - asegurar que el ID se establezca correctamente
    document.getElementById('id').value = v.ID || 0;
    document.getElementById('Placa').value = v.Placa || '';
    document.getElementById('Marca').value = v.Marca || '';
    document.getElementById('Modelo').value = v.Modelo || '';
    document.getElementById('Year').value = v.Year || '';
    document.getElementById('Color').value = v.Color || '';
    document.getElementById('CategoriaVehiculo').value = v.CategoriaVehiculo || '';
    document.getElementById('ClienteID').value = v.ClienteID || '';
    document.getElementById('Observaciones').value = v.Observaciones || '';
    document.getElementById('active').value = v.active || 1;
    
    modal.show();
}

function viewHistory(vehicleId, placa) {
    document.getElementById('historialModalTitle').textContent = `Historial de ${placa}`;
    const contentDiv = document.getElementById('historialContent');
    
    // Show loading
    const loadingOverlay = FroshUtils.showLoading(contentDiv, 'Cargando historial...');
    
    historialModal.show();
    
    // Load history via AJAX
    FroshUtils.ajax(`ajax/historial-vehiculo.php?vehicle_id=${vehicleId}`)
        .then(data => {
            FroshUtils.hideLoading(contentDiv);
            if (data.success) {
                displayHistory(data.historial);
            } else {
                contentDiv.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        <h5>Error al cargar</h5>
                        <p>No se pudo cargar el historial</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            FroshUtils.hideLoading(contentDiv);
            contentDiv.innerHTML = `
                <div class="empty-state">
                    <i class="fa-solid fa-wifi"></i>
                    <h5>Error de conexión</h5>
                    <p>Verifique su conexión a internet</p>
                </div>
            `;
            console.error('Error loading history:', error);
        });
}

function displayHistory(historial) {
    if (historial.length === 0) {
        document.getElementById('historialContent').innerHTML = `
            <div class="empty-state">
                <i class="fa-solid fa-history"></i>
                <h5>Sin historial</h5>
                <p class="text-muted">Este vehículo no tiene órdenes registradas</p>
            </div>
        `;
        return;
    }

    let html = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">${historial.length} orden(es) en el historial</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th class="text-end">Monto</th>
                    </tr>
                </thead>
                <tbody>
    `;

    historial.forEach(orden => {
        const estadoClass = {
            'Pendiente': 'warning',
            'En Proceso': 'info', 
            'Terminado': 'success',
            'Cancelado': 'danger'
        }[orden.estado_texto] || 'secondary';

        html += `
            <tr>
                <td>${FroshUtils.formatDate(orden.FechaIngreso)}</td>
                <td>${orden.cliente_nombre || 'Sin cliente'}</td>
                <td><span class="badge bg-${estadoClass}">${orden.estado_texto}</span></td>
                <td class="text-end">${FroshUtils.formatCurrency(orden.Monto)}</td>
            </tr>
        `;
    });

    html += '</tbody></table></div>';
    document.getElementById('historialContent').innerHTML = html;
}

function confirmDelete(id) {
    FroshUtils.confirm(
        '¿Está seguro de que desea desactivar este vehículo?',
        {
            title: 'Confirmar desactivación',
            subMessage: 'El vehículo será marcado como inactivo pero se mantendrá en el historial.',
            confirmText: 'Desactivar',
            cancelText: 'Cancelar'
        }
    ).then(confirmed => {
        if (confirmed) {
            window.location.href = `?action=delete&id=${id}`;
        }
    });
}
</script>

<?php require 'lavacar/partials/footer.php'; ?>