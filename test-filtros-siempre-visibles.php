<?php
session_start();
require_once 'lib/config.php';
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
        case 1: $contadores['pendientes']++; break;
        case 2: $contadores['proceso']++; break;
        case 3: $contadores['terminados']++; break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Filtros Siempre Visibles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
    :root {
        --ordenes-warning: #D3AF37;
        --ordenes-info: #274AB3;
        --ordenes-success: #10b981;
    }

    .badge-ordenes-warning { background: var(--ordenes-warning); color: white; font-weight: 600; }
    .badge-ordenes-info { background: var(--ordenes-info); color: white; font-weight: 600; }
    .badge-ordenes-success { background: var(--ordenes-success); color: white; font-weight: 600; }

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
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="fa-solid fa-list me-2"></i>Órdenes Activas</h2>
                <p class="text-muted mb-0">Gestión de órdenes en proceso</p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <!-- Filtros de Estado - Siempre Visibles -->
                <div class="btn-group d-flex" role="group">
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
                
                <!-- Botón Cerrar OCULTO -->
                <button class="btn btn-frosh-primary btn-sm d-none" onclick="window.close()">
                    <i class="fa-solid fa-times me-1"></i> Cerrar
                </button>
            </div>
        </div>
        
        <div class="alert alert-success">
            <h5>✅ Cambios Implementados:</h5>
            <ul class="mb-0">
                <li><strong>Filtros Desktop:</strong> Siempre visibles (eliminado d-none d-md-flex)</li>
                <li><strong>Filtros Mobile:</strong> Ocultos (agregado d-none)</li>
                <li><strong>Botón Cerrar:</strong> Oculto (agregado d-none)</li>
                <li><strong>Responsive:</strong> Filtros funcionan en todas las pantallas</li>
            </ul>
        </div>

        <?php if (!empty($ordenes)): ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Placa</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ordenes as $orden): ?>
                                <tr>
                                    <td><strong>#<?= $orden['ID'] ?></strong></td>
                                    <td><?= safe_htmlspecialchars($orden['ClienteNombre'], 'Sin cliente') ?></td>
                                    <td><?= safe_htmlspecialchars($orden['Placa'], 'Sin placa') ?></td>
                                    <td><strong class="text-success">₡<?= safe_number_format($orden['Monto'], 0) ?></strong></td>
                                    <td>
                                        <?php
                                        switch ($orden['Estado']) {
                                            case 1: echo '<span class="badge badge-ordenes-warning">Pendiente</span>'; break;
                                            case 2: echo '<span class="badge badge-ordenes-info">En Proceso</span>'; break;
                                            case 3: echo '<span class="badge badge-ordenes-success">Terminado</span>'; break;
                                            default: echo '<span class="badge bg-secondary">Desconocido</span>'; break;
                                        }
                                        ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
                <h5>No hay órdenes activas</h5>
                <p class="mb-0">Todas las órdenes han sido completadas o no hay órdenes registradas.</p>
            </div>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="lavacar/reportes/ordenes-activas.php" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right me-2"></i>Ir a Órdenes Activas Original
            </a>
            <a href="lavacar/dashboard.php" class="btn btn-secondary">
                <i class="fa-solid fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <script>
    // Funcionalidad de filtros
    document.addEventListener('DOMContentLoaded', function() {
        const filterRadios = document.querySelectorAll('input[name="estadoFilter"]');
        
        filterRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Filtro seleccionado:', this.value);
                // Aquí iría la lógica de filtrado
            });
        });
    });
    </script>
</body>
</html>