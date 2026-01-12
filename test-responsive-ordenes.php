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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Responsive Órdenes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Responsive Órdenes</h2>
        
        <div class="alert alert-info">
            <strong>Total de órdenes:</strong> <?= count($ordenes) ?><br>
            <strong>Ancho de pantalla:</strong> <span id="screenWidth"></span>px
        </div>
        
        <?php if (!empty($ordenes)): ?>
        
        <!-- Vista Desktop (≥992px) -->
        <div class="d-none d-lg-block">
            <h4>Vista Desktop (≥992px)</h4>
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
                                            case 1: echo '<span class="badge bg-warning text-dark">Pendiente</span>'; break;
                                            case 2: echo '<span class="badge bg-info">En Proceso</span>'; break;
                                            case 3: echo '<span class="badge bg-success">Terminado</span>'; break;
                                            default: echo '<span class="badge bg-secondary">Desconocido</span>'; break;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vista Mobile (<992px) -->
        <div class="d-lg-none">
            <h4>Vista Mobile (<992px)</h4>
            <div class="row g-3">
                <?php foreach ($ordenes as $orden): ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0">#<?= $orden['ID'] ?></h6>
                                <?php
                                switch ($orden['Estado']) {
                                    case 1: echo '<span class="badge bg-warning text-dark">Pendiente</span>'; break;
                                    case 2: echo '<span class="badge bg-info">En Proceso</span>'; break;
                                    case 3: echo '<span class="badge bg-success">Terminado</span>'; break;
                                    default: echo '<span class="badge bg-secondary">Desconocido</span>'; break;
                                }
                                ?>
                            </div>
                            <p class="mb-1"><strong>Cliente:</strong> <?= safe_htmlspecialchars($orden['ClienteNombre'], 'Sin cliente') ?></p>
                            <p class="mb-1"><strong>Placa:</strong> <?= safe_htmlspecialchars($orden['Placa'], 'Sin placa') ?></p>
                            <p class="mb-0"><strong>Monto:</strong> <span class="text-success">₡<?= safe_number_format($orden['Monto'], 0) ?></span></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php else: ?>
        <div class="alert alert-warning">
            <h5>No hay órdenes activas</h5>
        </div>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="lavacar/reportes/ordenes-activas.php" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right me-2"></i>Ir a Órdenes Activas Original
            </a>
        </div>
    </div>

    <script>
    // Mostrar ancho de pantalla
    function updateScreenWidth() {
        document.getElementById('screenWidth').textContent = window.innerWidth;
    }
    
    updateScreenWidth();
    window.addEventListener('resize', updateScreenWidth);
    </script>
</body>
</html>