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

// Parámetros de filtrado
$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
$clienteId = $_GET['cliente_id'] ?? '';

// Validar fechas
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
    $fechaInicio = date('Y-m-01');
    $fechaFin = date('Y-m-d');
}

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

// Obtener órdenes cerradas con filtros
$whereConditions = ["o.Estado = 4"];
$params = [];

// Filtro por fecha
$whereConditions[] = "DATE(o.FechaCierre) BETWEEN ? AND ?";
$params[] = $fechaInicio;
$params[] = $fechaFin;

// Filtro por cliente
if (!empty($clienteId)) {
    $whereConditions[] = "o.ClienteID = ?";
    $params[] = $clienteId;
}

$whereClause = implode(' AND ', $whereConditions);

$ordenes = CrearConsulta(
    $conn,
    "SELECT o.*, 
            COALESCE(c.NombreCompleto, 'Cliente no asignado') as ClienteNombre, 
            COALESCE(c.Correo, '') as ClienteCorreo,
            COALESCE(v.Placa, 'Sin placa') as Placa,
            COALESCE(v.Marca, '') as Marca,
            COALESCE(v.Modelo, '') as Modelo,
            COALESCE(v.Year, '') as Year,
            COALESCE(v.Color, '') as Color,
            COALESCE(cat.TipoVehiculo, 'Sin categoría') as TipoVehiculo
     FROM {$dbName}.ordenes o
     LEFT JOIN {$dbName}.clientes c ON c.ID = o.ClienteID
     LEFT JOIN {$dbName}.vehiculos v ON v.ID = o.VehiculoID
     LEFT JOIN {$dbName}.categoriavehiculo cat ON cat.ID = v.CategoriaVehiculo
     WHERE {$whereClause}
     ORDER BY o.FechaCierre DESC",
    $params
)->fetch_all(MYSQLI_ASSOC);

// Agregar servicios a cada orden
foreach ($ordenes as &$orden) {
    $orden['ClienteNombre'] = $orden['ClienteNombre'] ?? 'Cliente no asignado';
    $orden['Placa'] = $orden['Placa'] ?? 'Sin placa';
    $orden['Monto'] = $orden['Monto'] ?? 0.00;
    $orden['servicios'] = $ordenManager->getServicios($orden['ID']);
}

// Calcular estadísticas
$totalOrdenes = count($ordenes);
$totalIngresos = array_sum(array_column($ordenes, 'Monto'));
$promedioOrden = $totalOrdenes > 0 ? $totalIngresos / $totalOrdenes : 0;

// Obtener lista de clientes para filtro
$clientes = CrearConsulta(
    $conn,
    "SELECT DISTINCT c.ID, c.NombreCompleto 
     FROM {$dbName}.clientes c
     JOIN {$dbName}.ordenes o ON o.ClienteID = c.ID
     WHERE o.Estado = 4
     ORDER BY c.NombreCompleto",
    []
)->fetch_all(MYSQLI_ASSOC);

require 'lavacar/partials/header.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-archive me-2"></i>Órdenes Cerradas</h2>
            <p class="text-muted mb-0">Historial de órdenes completadas y facturadas</p>
        </div>
        <div class="d-flex gap-2">
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
                    <div class="col-md-4">
                        <label class="form-label">Cliente</label>
                        <select class="form-select" name="cliente_id">
                            <option value="">Todos los clientes</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['ID'] ?>" <?= $clienteId == $cliente['ID'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cliente['NombreCompleto']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
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

    <!-- Resumen de Estadísticas -->
    <?php if (!empty($ordenes)): ?>
    <div class="row mb-4 g-4">
        <div class="col-md-6 col-xl-3">
            <div class="summary-card primary">
                <div class="summary-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="summary-content">
                    <h3><?= $totalOrdenes ?></h3>
                    <p>Órdenes Cerradas</p>
                    <small>En el período seleccionado</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card success">
                <div class="summary-icon">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="summary-content">
                    <h3>₡<?= number_format($totalIngresos, 0) ?></h3>
                    <p>Ingresos Totales</p>
                    <small>Suma de todas las órdenes</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card info">
                <div class="summary-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="summary-content">
                    <h3>₡<?= number_format($promedioOrden, 0) ?></h3>
                    <p>Ticket Promedio</p>
                    <small>Promedio por orden</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="summary-card warning">
                <div class="summary-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="summary-content">
                    <h3><?= count(array_unique(array_column($ordenes, 'ClienteID'))) ?></h3>
                    <p>Clientes Únicos</p>
                    <small>Clientes diferentes atendidos</small>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($ordenes)): ?>
        <div class="alert alert-info text-center">
            <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
            <h5>No hay órdenes cerradas</h5>
            <p class="mb-0">No se encontraron órdenes cerradas en el período seleccionado.</p>
        </div>
    <?php else: ?>
        <!-- Vista Principal: Tabla y Cards -->
        <div class="card">
            <div class="card-body p-0">
                <!-- Vista Desktop: Tabla -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 8%;">ID</th>
                                <th style="width: 20%;">Cliente</th>
                                <th style="width: 12%;">Vehículo</th>
                                <th style="width: 12%;">Monto</th>
                                <th style="width: 15%;">Fecha Cierre</th>
                                <th style="width: 15%;">Duración</th>
                                <th style="width: 18%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $orden): ?>
                            <tr>
                                <td>
                                    <strong class="text-success">#<?= $orden['ID'] ?></strong>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= safe_htmlspecialchars($orden['ClienteNombre'], 'Sin cliente') ?></strong>
                                        <?php if (!empty($orden['ClienteCorreo'])): ?>
                                            <br><small class="text-muted"><?= safe_htmlspecialchars($orden['ClienteCorreo']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-dark"><?= safe_htmlspecialchars($orden['Placa'], 'Sin placa') ?></strong>
                                    <?php if (!empty($orden['Marca'])): ?>
                                        <br><small class="text-muted"><?= safe_htmlspecialchars($orden['Marca'] . ' ' . $orden['Modelo']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        ₡<?= safe_number_format($orden['Monto'], 0) ?>
                                    </strong>
                                </td>
                                <td>
                                    <strong><?= date('d/m/Y', strtotime($orden['FechaCierre'])) ?></strong>
                                    <br><small class="text-muted"><?= date('H:i', strtotime($orden['FechaCierre'])) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $fechaIngreso = new DateTime($orden['FechaIngreso']);
                                    $fechaCierre = new DateTime($orden['FechaCierre']);
                                    $duracion = $fechaIngreso->diff($fechaCierre);
                                    
                                    if ($duracion->days > 0) {
                                        echo $duracion->days . ' día' . ($duracion->days > 1 ? 's' : '');
                                    } else {
                                        echo $duracion->h . 'h ' . $duracion->i . 'm';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-sm" style="background: var(--ordenes-info); color: white; border-color: var(--ordenes-info);" onclick="verDetalleOrden(<?= $orden['ID'] ?>)" title="Ver Detalle">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="imprimirFactura(<?= $orden['ID'] ?>)" title="Imprimir Factura">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" onclick="enviarPorEmail(<?= $orden['ID'] ?>)" title="Enviar por Email">
                                            <i class="fa-solid fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Vista Mobile: Cards -->
                <div class="d-md-none">
                    <div class="mobile-orders-container p-3">
                        <?php foreach ($ordenes as $orden): ?>
                            <div class="order-card mb-3 closed-order">
                                <div class="order-card-header">
                                    <div class="order-main-info">
                                        <h6 class="mb-1">Orden #<?= $orden['ID'] ?></h6>
                                        <small class="text-muted d-block">Cerrada: <?= date('d/m/Y H:i', strtotime($orden['FechaCierre'])) ?></small>
                                    </div>
                                    <span class="badge" style="background-color: #10b981; color: white;">
                                        <i class="fa-solid fa-check me-1"></i>
                                        Cerrada
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
                                        <?php if (!empty($orden['Marca'])): ?>
                                        <div class="info-item">
                                            <i class="fa-solid fa-info me-2 text-muted"></i>
                                            <span><?= safe_htmlspecialchars($orden['Marca'] . ' ' . $orden['Modelo'] . ' (' . $orden['Year'] . ')') ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Servicios Contratados -->
                                    <?php if (!empty($orden['servicios'])): ?>
                                    <div class="services-info mb-3">
                                        <div class="services-header mb-2">
                                            <i class="fa-solid fa-tags me-2 text-muted"></i>
                                            <span class="fw-bold text-muted" style="font-size: 0.85rem;">Servicios:</span>
                                        </div>
                                        <div class="services-list">
                                            <?php foreach ($orden['servicios'] as $index => $servicio): ?>
                                                <?php if ($index < 2): // Mostrar máximo 2 servicios en cerradas ?>
                                                <div class="service-item">
                                                    <span class="service-name"><?= safe_htmlspecialchars($servicio['nombre'], 'Servicio sin nombre') ?></span>
                                                    <span class="service-price">₡<?= safe_number_format($servicio['precio'], 0) ?></span>
                                                </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if (count($orden['servicios']) > 2): ?>
                                            <div class="service-item more-services">
                                                <span class="text-muted">+ <?= count($orden['servicios']) - 2 ?> servicio(s) más</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="order-details-row">
                                        <div class="amount-info">
                                            <strong class="text-success">
                                                ₡<?= safe_number_format($orden['Monto'], 0) ?>
                                            </strong>
                                        </div>
                                        <div class="duration-info">
                                            <?php
                                            $fechaIngreso = new DateTime($orden['FechaIngreso']);
                                            $fechaCierre = new DateTime($orden['FechaCierre']);
                                            $duracion = $fechaIngreso->diff($fechaCierre);
                                            ?>
                                            <small class="text-muted">
                                                Duración: 
                                                <?php if ($duracion->days > 0): ?>
                                                    <?= $duracion->days ?> día<?= $duracion->days > 1 ? 's' : '' ?>
                                                <?php else: ?>
                                                    <?= $duracion->h ?>h <?= $duracion->i ?>m
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-card-actions">
                                    <button class="btn btn-sm" style="background: var(--ordenes-info); color: white; border-color: var(--ordenes-info);" onclick="verDetalleOrden(<?= $orden['ID'] ?>)" title="Ver Detalle">
                                        <i class="fa-solid fa-eye me-1"></i>Ver
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="imprimirFactura(<?= $orden['ID'] ?>)" title="Imprimir">
                                        <i class="fa-solid fa-print me-1"></i>Imprimir
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="enviarPorEmail(<?= $orden['ID'] ?>)" title="Email">
                                        <i class="fa-solid fa-envelope me-1"></i>Email
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<!-- Modal de Detalle de Orden (reutilizado de ordenes-activas.php) -->
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
                    <!-- Botones específicos para órdenes cerradas -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reutilizar estilos de ordenes-activas.php */
:root {
    --ordenes-warning: #D3AF37;
    --ordenes-info: #274AB3;
    --ordenes-success: #10b981;
    --ordenes-dark: #374151;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Summary Cards */
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

.summary-card.primary .summary-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.summary-card.success .summary-icon { background: linear-gradient(135deg, #10b981, #059669); }
.summary-card.info .summary-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.summary-card.warning .summary-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

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

/* Mobile Cards - Reutilizar de ordenes-activas.php */
.mobile-orders-container {
    background: transparent;
    display: none;
}

@media (max-width: 767px) {
    .mobile-orders-container {
        display: block !important;
    }
    
    .table-responsive {
        display: none !important;
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

.order-card.closed-order {
    border-left: 4px solid #10b981;
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

/* Services display - reutilizar de ordenes-activas.php */
.services-info {
    background: #f8fafc !important;
    border-radius: 8px !important;
    padding: 12px !important;
    border: 1px solid #e2e8f0 !important;
    display: block !important;
    visibility: visible !important;
}

.services-header {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 8px !important;
    visibility: visible !important;
}

.services-list {
    display: flex !important;
    flex-direction: column !important;
    gap: 4px !important;
    visibility: visible !important;
}

.service-item {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    font-size: 0.8rem !important;
    padding: 4px 0 !important;
    visibility: visible !important;
    min-height: 20px !important;
}

.service-name {
    color: #374151 !important;
    font-weight: 500 !important;
    flex: 1 !important;
    margin-right: 8px !important;
    display: inline-block !important;
    visibility: visible !important;
}

.service-price {
    color: #059669 !important;
    font-weight: 600 !important;
    white-space: nowrap !important;
    display: inline-block !important;
    visibility: visible !important;
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

.duration-info {
    text-align: right;
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

/* Modal fixes */
.modal .table-responsive {
    display: block !important;
}
</style>

<script>
// Reutilizar funciones de ordenes-activas.php
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
    
    // Cargar detalles (reutilizar AJAX de ordenes-activas.php)
    fetch(`ajax/detalle-orden.php?id=${ordenId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarDetalleOrdenCerrada(data);
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

function mostrarDetalleOrdenCerrada(data) {
    const { orden, servicios, totales, fechas } = data;
    
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
                            <strong>Estado:</strong><br>
                            <span class="badge fs-6" style="background-color: #10b981; color: white;">
                                <i class="fa-solid fa-check me-1"></i>
                                Cerrada
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Fecha de Ingreso:</strong><br>
                            <span class="text-muted">${fechas.ingreso || 'No registrada'}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Fecha de Cierre:</strong><br>
                            <span class="text-muted">${fechas.cierre || 'No registrada'}</span>
                        </div>
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
                <h6 class="mb-0"><i class="fa-solid fa-tags me-2"></i>Servicios Realizados</h6>
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
                                    <td>${servicio.nombre || 'Servicio sin nombre'}</td>
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
                                <th>Total Facturado</th>
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
    
    // Botones específicos para órdenes cerradas
    document.getElementById('modalActions').innerHTML = `
        <button type="button" class="btn btn-outline-success" onclick="imprimirFactura(${orden.ID})">
            <i class="fa-solid fa-print me-1"></i>
            Imprimir Factura
        </button>
        <button type="button" class="btn btn-outline-primary" onclick="enviarPorEmail(${orden.ID})">
            <i class="fa-solid fa-envelope me-1"></i>
            Enviar por Email
        </button>
    `;
}

function imprimirFactura(ordenId) {
    showAlert('Función de impresión en desarrollo', 'info');
}

function enviarPorEmail(ordenId) {
    showAlert('Función de envío por email en desarrollo', 'info');
}

function resetearFiltros() {
    window.location.href = window.location.pathname;
}

// Función para mostrar alertas (reutilizada)
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