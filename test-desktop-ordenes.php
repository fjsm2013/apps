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
    <title>Test Desktop Filtros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
    :root {
        /* Colores específicos para órdenes activas */
        --ordenes-warning: #D3AF37;  /* Dorado suave para pendientes */
        --ordenes-info: #274AB3;     /* Azul profundo para en proceso */
        --ordenes-success: #10b981;  /* Verde success */
        --ordenes-danger: #ef4444;   /* Rojo para alertas */
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
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Test Filtros Desktop</h2>
        
        <div class="alert alert-info">
            <strong>Ancho de pantalla:</strong> <span id="screenWidth"></span>px<br>
            <strong>Total órdenes:</strong> <?= $contadores['total'] ?><br>
            <strong>Breakpoint md (≥768px):</strong> <span id="mdBreakpoint"></span>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Filtros de Estado</h5>
            </div>
            <div class="card-body">
                
                <!-- Filtros Desktop (≥768px) -->
                <div class="mb-3">
                    <h6>Desktop Filters (d-none d-md-flex):</h6>
                    <div class="btn-group d-none d-md-flex" role="group">
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
                    <div class="d-md-none">
                        <small class="text-muted">Filtros desktop ocultos en pantallas pequeñas</small>
                    </div>
                </div>
                
                <!-- Filtros Mobile (<768px) -->
                <div class="mb-3">
                    <h6>Mobile Filter (d-md-none):</h6>
                    <div class="d-md-none">
                        <select class="form-select form-select-sm" id="estadoFilterMobile">
                            <option value="all">📋 Todas las órdenes (<?= $contadores['total'] ?>)</option>
                            <option value="1">⏰ Pendientes (<?= $contadores['pendientes'] ?>)</option>
                            <option value="2">⚙️ En Proceso (<?= $contadores['proceso'] ?>)</option>
                            <option value="3">✅ Terminadas (<?= $contadores['terminados'] ?>)</option>
                        </select>
                    </div>
                    <div class="d-none d-md-block">
                        <small class="text-muted">Filtro mobile oculto en pantallas grandes</small>
                    </div>
                </div>
                
                <!-- Filtros Siempre Visibles (para comparar) -->
                <div class="mb-3">
                    <h6>Always Visible (sin clases responsive):</h6>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="estadoFilterAlways" id="todosAlways" value="all" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="todosAlways">
                            Todos <span class="badge bg-secondary ms-1"><?= $contadores['total'] ?></span>
                        </label>

                        <input type="radio" class="btn-check" name="estadoFilterAlways" id="pendientesAlways" value="1">
                        <label class="btn btn-outline-ordenes-warning btn-sm" for="pendientesAlways">
                            Pendientes <span class="badge badge-ordenes-warning ms-1"><?= $contadores['pendientes'] ?></span>
                        </label>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="mt-4">
            <a href="lavacar/reportes/ordenes-activas.php" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right me-2"></i>Ir a Órdenes Activas Original
            </a>
        </div>
    </div>

    <script>
    function updateScreenInfo() {
        const width = window.innerWidth;
        document.getElementById('screenWidth').textContent = width;
        document.getElementById('mdBreakpoint').textContent = width >= 768 ? 'SÍ (≥768px)' : 'NO (<768px)';
    }
    
    updateScreenInfo();
    window.addEventListener('resize', updateScreenInfo);
    </script>
</body>
</html>