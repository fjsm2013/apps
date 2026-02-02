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
    <title>Test Display Órdenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Display Órdenes Activas</h2>
        
        <div class="alert alert-info">
            <strong>Total de órdenes encontradas:</strong> <?= count($ordenes) ?>
        </div>
        
        <?php if (empty($ordenes)): ?>
            <div class="alert alert-warning">
                <h5>No hay órdenes activas</h5>
                <p>No se encontraron órdenes en estado 1, 2 o 3.</p>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <h5>Órdenes Activas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
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
                                        $estadoClass = 'badge-secondary';
                                        $estadoText = 'Desconocido';
                                        
                                        switch ($orden['Estado']) {
                                            case 1:
                                                $estadoClass = 'bg-warning text-dark';
                                                $estadoText = 'Pendiente';
                                                break;
                                            case 2:
                                                $estadoClass = 'bg-info';
                                                $estadoText = 'En Proceso';
                                                break;
                                            case 3:
                                                $estadoClass = 'bg-success';
                                                $estadoText = 'Terminado';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $estadoClass ?>"><?= $estadoText ?></span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <h5>Datos de la primera orden:</h5>
                <pre><?= htmlspecialchars(print_r($ordenes[0], true)) ?></pre>
            </div>
        <?php endif; ?>
        
        <div class="mt-4">
            <a href="lavacar/reportes/ordenes-activas.php" class="btn btn-primary">
                <i class="fa-solid fa-arrow-right me-2"></i>Ir a Órdenes Activas Original
            </a>
        </div>
    </div>
</body>
</html>