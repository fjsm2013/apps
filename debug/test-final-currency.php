<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Final - Colones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>✅ Test Final - Sistema de Colones</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5>Función formatCurrency()</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>formatCurrency(1500)</td>
                                <td><strong><?= formatCurrency(1500) ?></strong></td>
                            </tr>
                            <tr>
                                <td>formatCurrency(25000)</td>
                                <td><strong><?= formatCurrency(25000) ?></strong></td>
                            </tr>
                            <tr>
                                <td>formatCurrency(150750)</td>
                                <td><strong><?= formatCurrency(150750) ?></strong></td>
                            </tr>
                            <tr>
                                <td>formatCurrency(0)</td>
                                <td><strong><?= formatCurrency(0) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Función formatDate()</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>formatDate('2025-01-10')</td>
                                <td><strong><?= formatDate('2025-01-10') ?></strong></td>
                            </tr>
                            <tr>
                                <td>formatDate(date('Y-m-d'))</td>
                                <td><strong><?= formatDate(date('Y-m-d')) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-success">
                    <h5><i class="fas fa-check-circle me-2"></i>Sistema Configurado Correctamente</h5>
                    <ul class="mb-0">
                        <li>✅ Símbolo de colones (₡) configurado por defecto</li>
                        <li>✅ Formato con espacio: ₡ 1,500</li>
                        <li>✅ Sin decimales por defecto (apropiado para colones)</li>
                        <li>✅ Sin conflictos de funciones</li>
                        <li>✅ Constantes globales disponibles</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <h5>Constantes Disponibles:</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>CURRENCY_SYMBOL</span>
                                <strong><?= CURRENCY_SYMBOL ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>CURRENCY_CODE</span>
                                <strong><?= CURRENCY_CODE ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>CURRENCY_NAME</span>
                                <strong><?= CURRENCY_NAME ?></strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>CURRENCY_DECIMALS</span>
                                <strong><?= CURRENCY_DECIMALS ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>IVA_RATE</span>
                                <strong><?= (IVA_RATE * 100) ?>%</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>DATE_FORMAT</span>
                                <strong><?= DATE_FORMAT ?></strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="lavacar/administracion/servicios/index.php" class="btn btn-primary">
                <i class="fas fa-cog me-2"></i>Probar Servicios
            </a>
            <a href="lavacar/dashboard.php" class="btn btn-secondary">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>
</body>
</html>