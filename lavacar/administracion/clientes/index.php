<?php
session_start();

require_once '../../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/ClientesManager.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

$manager = new ClientesManager($conn, $dbName);

$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

/* ACTIONS */
if ($action === 'toggle' && $id) {
    $manager->toggle($id);
    header("Location: index.php");
    exit;
}

if ($action === 'delete' && $id) {
    $manager->delete($id);
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id'] ?? 0);

    $data = [
        'NombreCompleto' => trim($_POST['NombreCompleto']),
        'Cedula'         => trim($_POST['Cedula']),
        'Empresa'        => trim($_POST['Empresa']),
        'Correo'         => trim($_POST['Correo']),
        'Telefono'       => trim($_POST['Telefono']),
        'Direccion'      => trim($_POST['Direccion']),
        'Distrito'       => trim($_POST['Distrito']),
        'Canton'         => trim($_POST['Canton']),
        'Provincia'      => trim($_POST['Provincia']),
        'Pais'           => trim($_POST['Pais'] ?: 'CR'),
        'IVA'            => (int)($_POST['IVA'] ?? 13),
        'active'         => (int)($_POST['active'] ?? 1),
    ];

    $id ? $manager->update($id, $data) : $manager->create($data);

    header("Location: index.php");
    exit;
}

// Parámetros de búsqueda
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? 'all';

// Obtener clientes con información de vehículos
$clientes = $manager->getAllWithVehicles($search, $status);

// Estadísticas rápidas
$stats = $manager->getStats();

// Define breadcrumbs
$breadcrumbs = array(
    array('title' => 'Administración', 'url' => '../index.php'),
    array('title' => 'Clientes', 'url' => '')
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
                    <h2><i class="fa-solid fa-users me-2"></i>Gestión de Clientes</h2>
                    <p class="text-muted mb-0">Administra la información de tus clientes y sus vehículos</p>
                </div>
                <button class="btn btn-frosh-dark btn-lg new-client-btn" onclick="openCreate()">
                    <i class="fa fa-user-plus me-2"></i> 
                    <span class="d-none d-sm-inline">Nuevo Cliente</span>
                    <span class="d-sm-none">Nuevo</span>
                </button>
            </div>

            <?php /* Comentado temporalmente - Estadísticas de clientes
            // Estadísticas rápidas
            $statsConfig = [
                'total_clientes' => [
                    'value' => $stats['total_clientes'] ?? 0,
                    'label' => 'Total Clientes',
                    'icon' => 'fa-solid fa-users'
                ],
                'clientes_activos' => [
                    'value' => $stats['clientes_activos'] ?? 0,
                    'label' => 'Activos',
                    'icon' => 'fa-solid fa-user-check'
                ],
                'total_vehiculos' => [
                    'value' => $stats['total_vehiculos'] ?? 0,
                    'label' => 'Vehículos',
                    'icon' => 'fa-solid fa-car'
                ],
                'nuevos_mes' => [
                    'value' => $stats['nuevos_mes'] ?? 0,
                    'label' => 'Nuevos este mes',
                    'icon' => 'fa-solid fa-calendar-days'
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
                        'label' => 'Buscar por nombre o cédula',
                        'placeholder' => 'Nombre completo o número de cédula...',
                        'width' => 6
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
                        'width' => 3
                    ]
                ],
                'search_text' => 'Buscar',
                'reset_text' => 'Limpiar',
                'reset_url' => 'index.php'
            ];
            echo generateSearchFilters($filterConfig);
            ?>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-list me-2"></i>Lista de Clientes</h5>
                    <small class="text-muted">
                        <?= count($clientes) ?> cliente(s) encontrado(s)
                        <?= $search ? " para '{$search}'" : '' ?>
                    </small>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($clientes)): ?>
                    <div class="text-center py-5">
                        <i class="fa-solid fa-users fa-3x text-muted mb-3"></i>
                        <h5>No se encontraron clientes</h5>
                        <p class="text-muted">
                            <?= $search ? "No hay clientes que coincidan con '{$search}'" : 'No hay clientes registrados' ?>
                        </p>
                        <?php if (!$search): ?>
                        <button class="btn btn-frosh-dark" onclick="openCreate()">
                            <i class="fa fa-user-plus me-2"></i> Crear primer cliente
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <!-- Vista Desktop -->
                    <div class="table-responsive">>
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Contacto</th>
                                    <th>Vehículos</th>
                                    <th>Última Visita</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $c): ?>
                                <tr>
                                    <td>
                                        <div class="client-info">
                                            <strong><?= htmlspecialchars($c['NombreCompleto']) ?></strong>
                                            <small class="d-block text-muted">
                                                <?php if ($c['Cedula']): ?>
                                                Cédula: <?= htmlspecialchars($c['Cedula']) ?>
                                                <?php endif; ?>
                                                <?php if ($c['Empresa']): ?>
                                                <br>Empresa: <?= htmlspecialchars($c['Empresa']) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="contact-info">
                                            <?php if ($c['Correo']): ?>
                                            <div><i
                                                    class="fa-solid fa-envelope me-1"></i><?= htmlspecialchars($c['Correo']) ?>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ($c['Telefono']): ?>
                                            <div><i
                                                    class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($c['Telefono']) ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="vehicles-info">
                                            <?php if ($c['total_vehiculos'] > 0): ?>
                                            <span class="badge badge-frosh-dark">
                                                <i class="fa-solid fa-car me-1"></i><?= $c['total_vehiculos'] ?>
                                                vehículo(s)
                                            </span>
                                            <?php if ($c['vehiculos_principales']): ?>
                                            <small class="d-block text-muted mt-1">
                                                <?= htmlspecialchars($c['vehiculos_principales']) ?>
                                            </small>
                                            <?php endif; ?>
                                            <?php else: ?>
                                            <span class="text-muted">Sin vehículos</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($c['ultima_visita']): ?>
                                        <span class="text-success">
                                            <?= formatDate($c['ultima_visita']) ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="text-muted">Nunca</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $c['active'] ? 'badge-frosh-dark' : 'badge-frosh-light' ?>">
                                            <?= $c['active'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-frosh-dark edit-btn"
                                                data-client='<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>'
                                                title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-frosh-gray vehicles-btn"
                                                data-client-id="<?= $c['ID'] ?>"
                                                data-client-name="<?= htmlspecialchars($c['NombreCompleto']) ?>"
                                                title="Ver vehículos">
                                                <i class="fa fa-car"></i>
                                            </button>
                                            <a class="btn btn-sm btn-outline-frosh-gray"
                                                href="?action=toggle&id=<?= $c['ID'] ?>" title="Cambiar estado">
                                                <i class="fa fa-power-off"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-btn"
                                                data-client-id="<?= $c['ID'] ?>" title="Eliminar">
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
                        <div class="mobile-clients-container p-3">
                            <?php foreach ($clientes as $c): ?>
                            <div class="client-card mb-3">
                                <div class="client-card-header">
                                    <div class="client-main-info">
                                        <h6 class="mb-1"><?= htmlspecialchars($c['NombreCompleto']) ?></h6>
                                        <?php if ($c['Cedula']): ?>
                                        <small class="text-muted d-block">Cédula: <?= htmlspecialchars($c['Cedula']) ?></small>
                                        <?php endif; ?>
                                        <?php if ($c['Empresa']): ?>
                                        <small class="text-muted d-block">Empresa: <?= htmlspecialchars($c['Empresa']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge <?= $c['active'] ? 'badge-frosh-dark' : 'badge-frosh-light' ?>">
                                        <?= $c['active'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </div>

                                <div class="client-card-body">
                                    <?php if ($c['Correo'] || $c['Telefono']): ?>
                                    <div class="contact-row mb-3">
                                        <?php if ($c['Correo']): ?>
                                        <div class="contact-item">
                                            <i class="fa-solid fa-envelope me-2 text-muted"></i>
                                            <span><?= htmlspecialchars($c['Correo']) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($c['Telefono']): ?>
                                        <div class="contact-item">
                                            <i class="fa-solid fa-phone me-2 text-muted"></i>
                                            <span><?= htmlspecialchars($c['Telefono']) ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

                                    <div class="vehicles-row">
                                        <div class="vehicles-info">
                                            <?php if ($c['total_vehiculos'] > 0): ?>
                                            <span class="badge badge-frosh-dark">
                                                <i class="fa-solid fa-car me-1"></i><?= $c['total_vehiculos'] ?> vehículo(s)
                                            </span>
                                            <?php if ($c['vehiculos_principales']): ?>
                                            <small class="d-block text-muted mt-1">
                                                <?= htmlspecialchars($c['vehiculos_principales']) ?>
                                            </small>
                                            <?php endif; ?>
                                            <?php else: ?>
                                            <span class="text-muted">Sin vehículos</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($c['ultima_visita']): ?>
                                        <div class="last-visit">
                                            <small class="text-success">
                                                <i class="fa-solid fa-calendar me-1"></i>
                                                <?= formatDate($c['ultima_visita']) ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="client-card-actions">
                                    <button class="btn btn-sm btn-frosh-dark edit-btn"
                                        data-client='<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="fa fa-edit me-1"></i>Editar
                                    </button>
                                    <button class="btn btn-sm btn-frosh-gray vehicles-btn"
                                        data-client-id="<?= $c['ID'] ?>"
                                        data-client-name="<?= htmlspecialchars($c['NombreCompleto']) ?>">
                                        <i class="fa fa-car me-1"></i>Vehículos
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="?action=toggle&id=<?= $c['ID'] ?>">
                                                    <i class="fa fa-power-off me-2"></i>Cambiar estado
                                                </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger delete-btn" href="#"
                                                    data-client-id="<?= $c['ID'] ?>">
                                                    <i class="fa fa-trash me-2"></i>Eliminar
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

<!-- MODAL CLIENTE -->
<div class="modal fade" id="clienteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Cliente</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="id">

                <!-- Información Personal -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-primary border-bottom pb-2">
                            <i class="fa-solid fa-user me-2"></i>Información Personal
                        </h6>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nombre Completo *</label>
                        <input class="form-control" name="NombreCompleto" id="NombreCompleto" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cédula</label>
                        <input class="form-control" name="Cedula" id="Cedula" placeholder="1-1234-5678">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Empresa</label>
                        <input class="form-control" name="Empresa" id="Empresa">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">País</label>
                        <select class="form-select" name="Pais" id="Pais">
                            <option value="CR">Costa Rica</option>
                            <option value="GT">Guatemala</option>
                            <option value="NI">Nicaragua</option>
                            <option value="PA">Panamá</option>
                            <option value="HN">Honduras</option>
                            <option value="SV">El Salvador</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">IVA (%)</label>
                        <input type="number" class="form-control" name="IVA" id="IVA" value="13" min="0" max="100">
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h6 class="text-primary border-bottom pb-2">
                            <i class="fa-solid fa-address-book me-2"></i>Información de Contacto
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico *</label>
                        <input type="email" class="form-control" name="Correo" id="Correo" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono *</label>
                        <input class="form-control" name="Telefono" id="Telefono" required placeholder="8888-8888">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="Direccion" id="Direccion" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Distrito</label>
                        <input class="form-control" name="Distrito" id="Distrito">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cantón</label>
                        <input class="form-control" name="Canton" id="Canton">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Provincia</label>
                        <input class="form-control" name="Provincia" id="Provincia">
                    </div>
                </div>

                <!-- Estado -->
                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="text-primary border-bottom pb-2">
                            <i class="fa-solid fa-toggle-on me-2"></i>Estado
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado del Cliente</label>
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
                    <i class="fa-solid fa-save me-1"></i>Guardar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VEHÍCULOS -->
<div class="modal fade" id="vehiculosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vehiculosModalTitle">Vehículos del Cliente</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="vehiculosContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando vehículos...</p>
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
/* ===== STATS CARDS ===== */
.stats-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
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
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stats-card.primary .stats-icon {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

.stats-card.success .stats-icon {
    background: linear-gradient(135deg, #10b981, #059669);
}

.stats-card.info .stats-icon {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

.stats-card.warning .stats-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.stats-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.stats-content p {
    font-size: 0.85rem;
    color: #64748b;
    margin: 4px 0 0;
    font-weight: 500;
}

/* ===== FILTER CARD ===== */
.filter-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* ===== TABLE CARD ===== */
.table-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.table-card .card-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: none;
    border-radius: 16px 16px 0 0;
}

.table-card .card-header h5 {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
}

.table-card .card-body {
    padding: 0;
}

/* Ocultar tabla en mobile, mostrar en desktop */
.table-responsive {
    display: block;
}

@media (max-width: 767px) {
    .table-responsive {
        display: none !important;
    }
}

/* ===== CLIENT INFO STYLES ===== */
.client-info strong {
    color: #1e293b;
    font-size: 0.95rem;
}

.contact-info {
    font-size: 0.85rem;
}

.contact-info div {
    margin-bottom: 2px;
}

.contact-info i {
    color: #64748b;
    width: 14px;
}

.vehicles-info .badge {
    font-size: 0.75rem;
}

/* ===== MOBILE CLIENT CARDS ===== */
.mobile-clients-container {
    background: transparent;
    display: none; /* Oculto por defecto */
}

/* Mostrar solo en mobile */
@media (max-width: 767px) {
    .mobile-clients-container {
        display: block !important;
    }
}

.client-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: all 0.2s ease;
}

.client-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.client-card-header {
    padding: 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.client-main-info {
    flex: 1;
}

.client-main-info h6 {
    margin: 0;
    color: #1e293b;
    font-weight: 600;
    font-size: 1rem;
}

.client-main-info small {
    font-size: 0.8rem;
    color: #64748b;
}

.client-card-body {
    padding: 16px;
}

.contact-row {
    margin-bottom: 0;
}

.contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    font-size: 0.85rem;
    color: #374151;
}

.contact-item:last-child {
    margin-bottom: 0;
}

.contact-item i {
    color: #64748b;
    width: 16px;
    flex-shrink: 0;
}

.vehicles-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 8px;
}

.vehicles-info {
    flex: 1;
}

.vehicles-info .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.last-visit {
    text-align: right;
}

.last-visit small {
    font-size: 0.75rem;
    white-space: nowrap;
}

.client-card-actions {
    padding: 12px 16px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 8px;
    align-items: center;
}

.client-card-actions .btn {
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 500;
}

.client-card-actions .dropdown-toggle {
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
    
    .new-client-btn {
        font-size: 0.9rem;
        padding: 8px 16px;
    }
    
    .stats-card {
        padding: 16px;
    }

    .stats-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }

    .stats-content h3 {
        font-size: 1.3rem;
    }

    .filter-card {
        padding: 16px;
    }
    
    .table-card .card-header {
        padding: 16px;
    }
    
    .table-card .card-header h5 {
        font-size: 1.1rem;
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
    
    .new-client-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .client-card-actions {
        flex-wrap: wrap;
    }
    
    .client-card-actions .btn:not(.dropdown-toggle) {
        flex: 1;
        min-width: 0;
    }
    
    .vehicles-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .last-visit {
        text-align: left;
        width: 100%;
    }
}
</style>

<script>
// Inicializar modales al cargar la página
let modal, vehiculosModal;

document.addEventListener('DOMContentLoaded', function() {
    modal = new bootstrap.Modal(document.getElementById('clienteModal'));
    vehiculosModal = new bootstrap.Modal(document.getElementById('vehiculosModal'));

    // Event listeners para botones
    document.addEventListener('click', function(e) {
        // Botón editar
        if (e.target.closest('.edit-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.edit-btn');
            const clientData = JSON.parse(btn.getAttribute('data-client'));
            openEdit(clientData);
        }

        // Botón vehículos
        if (e.target.closest('.vehicles-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.vehicles-btn');
            const clientId = btn.getAttribute('data-client-id');
            const clientName = btn.getAttribute('data-client-name');
            viewVehicles(clientId, clientName);
        }

        // Botón eliminar
        if (e.target.closest('.delete-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.delete-btn');
            const clientId = btn.getAttribute('data-client-id');
            confirmDelete(clientId);
        }
    });

    // Auto-focus en el campo de búsqueda
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});

function openCreate() {
    document.getElementById('modalTitle').textContent = 'Nuevo Cliente';
    document.querySelector('#clienteModal form').reset();
    document.getElementById('id').value = 0;
    document.getElementById('Pais').value = 'CR';
    document.getElementById('IVA').value = 13;
    modal.show();
}

function openEdit(c) {
    document.getElementById('modalTitle').textContent = 'Editar Cliente';

    // Limpiar formulario primero
    document.querySelector('#clienteModal form').reset();

    // Llenar campos específicamente
    document.getElementById('id').value = c.ID || 0;
    document.getElementById('NombreCompleto').value = c.NombreCompleto || '';
    document.getElementById('Cedula').value = c.Cedula || '';
    document.getElementById('Empresa').value = c.Empresa || '';
    document.getElementById('Correo').value = c.Correo || '';
    document.getElementById('Telefono').value = c.Telefono || '';
    document.getElementById('Direccion').value = c.Direccion || '';
    document.getElementById('Distrito').value = c.Distrito || '';
    document.getElementById('Canton').value = c.Canton || '';
    document.getElementById('Provincia').value = c.Provincia || '';
    document.getElementById('Pais').value = c.Pais || 'CR';
    document.getElementById('IVA').value = c.IVA || 13;
    document.getElementById('active').value = c.active || 1;

    modal.show();
}

function viewVehicles(clienteId, clienteNombre) {
    document.getElementById('vehiculosModalTitle').textContent = `Vehículos de ${clienteNombre}`;
    const contentDiv = document.getElementById('vehiculosContent');

    // Show loading
    const loadingOverlay = FroshUtils.showLoading(contentDiv, 'Cargando vehículos...');

    vehiculosModal.show();

    // Load vehicles via AJAX
    FroshUtils.ajax(`ajax/vehiculos-cliente.php?cliente_id=${clienteId}`)
        .then(data => {
            FroshUtils.hideLoading(contentDiv);
            if (data.success) {
                displayVehicles(data.vehiculos, clienteId);
            } else {
                contentDiv.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        <h5>Error al cargar</h5>
                        <p>No se pudieron cargar los vehículos</p>
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
            console.error('Error loading vehicles:', error);
        });
}

function displayVehicles(vehiculos, clienteId) {
    if (vehiculos.length === 0) {
        document.getElementById('vehiculosContent').innerHTML = `
            <div class="text-center py-4">
                <i class="fa-solid fa-car fa-2x text-muted mb-2"></i>
                <h6>Sin vehículos registrados</h6>
                <p class="text-muted">Este cliente no tiene vehículos asociados</p>
                <a href="../vehiculos/vehiculos.php?cliente_id=${clienteId}" class="btn btn-frosh-dark btn-sm">
                    <i class="fa-solid fa-plus me-1"></i>Agregar Vehículo
                </a>
            </div>
        `;
        return;
    }

    let html = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">${vehiculos.length} vehículo(s) registrado(s)</h6>
            <a href="../vehiculos/vehiculos.php?cliente_id=${clienteId}" class="btn btn-frosh-dark btn-sm">
                <i class="fa-solid fa-plus me-1"></i>Gestionar Vehículos
            </a>
        </div>
        <div class="row g-3">
    `;

    vehiculos.forEach(vehiculo => {
        html += `
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">${vehiculo.Placa || 'Sin placa'}</h6>
                            <span class="badge ${vehiculo.active ? 'badge-frosh-dark' : 'badge-frosh-light'} badge-sm">
                                ${vehiculo.active ? 'Activo' : 'Inactivo'}
                            </span>
                        </div>
                        <p class="card-text">
                            <strong>${vehiculo.Marca || 'Sin marca'} ${vehiculo.Modelo || ''}</strong><br>
                            <small class="text-muted">
                                ${vehiculo.Year ? `Año: ${vehiculo.Year}` : ''}
                                ${vehiculo.Color ? ` • Color: ${vehiculo.Color}` : ''}
                            </small>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                ${vehiculo.total_ordenes || 0} orden(es)
                            </small>
                            <a href="../vehiculos/vehiculos.php?search=${vehiculo.Placa}" class="btn  btn-frosh-dark btn-sm" title="Buscar este vehículo">
                                <i class="fa-solid fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    html += '</div>';
    document.getElementById('vehiculosContent').innerHTML = html;
}

function confirmDelete(id) {
    FroshUtils.confirm(
        '¿Está seguro de que desea eliminar este cliente?', {
            title: 'Confirmar eliminación',
            subMessage: 'Esta acción no se puede deshacer.',
            confirmText: 'Eliminar',
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