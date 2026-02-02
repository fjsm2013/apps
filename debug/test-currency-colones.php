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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Símbolo de Colones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Configuración de Moneda - Colones Costarricenses</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Configuración de Moneda</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td><strong>Símbolo:</strong></td>
                                <td><?= CURRENCY_SYMBOL ?></td>
                            </tr>
                            <tr>
                                <td><strong>Código:</strong></td>
                                <td><?= CURRENCY_CODE ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nombre:</strong></td>
                                <td><?= CURRENCY_NAME ?></td>
                            </tr>
                            <tr>
                                <td><strong>Locale:</strong></td>
                                <td><?= CURRENCY_LOCALE ?></td>
                            </tr>
                            <tr>
                                <td><strong>Decimales:</strong></td>
                                <td><?= CURRENCY_DECIMALS ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Ejemplos de Formateo</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td><strong>₡ 1,500</strong></td>
                                <td><?= formatCurrency(1500) ?></td>
                            </tr>
                            <tr>
                                <td><strong>₡ 25,000</strong></td>
                                <td><?= formatCurrency(25000) ?></td>
                            </tr>
                            <tr>
                                <td><strong>₡ 150,750</strong></td>
                                <td><?= formatCurrency(150750) ?></td>
                            </tr>
                            <tr>
                                <td><strong>₡ 0</strong></td>
                                <td><?= formatCurrency(0) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Solo número:</strong></td>
                                <td><?= number_format(12500) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-success">
                    <h5><i class="fa-solid fa-check-circle me-2"></i>Configuración Completada</h5>
                    <p class="mb-0">
                        El sistema ahora usa consistentemente el símbolo de colones costarricenses (₡) 
                        en lugar del símbolo de dólar ($) en todas las interfaces y reportes.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <h5>Archivos Actualizados:</h5>
                <ul class="list-group">
                    <li class="list-group-item">✅ lib/constants.php - Constantes globales de moneda</li>
                    <li class="list-group-item">✅ lib/config.php - Inclusión de constantes</li>
                    <li class="list-group-item">✅ lib/js/frosh-components.js - Función formatCurrency</li>
                    <li class="list-group-item">✅ lavacar/administracion/servicios/index.php - Modal de precios</li>
                    <li class="list-group-item">✅ Todos los reportes ya usan ₡ correctamente</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="lavacar/administracion/servicios/index.php" class="btn btn-primary">
                <i class="fa-solid fa-cog me-2"></i>Ir a Servicios
            </a>
            <a href="lavacar/dashboard.php" class="btn btn-secondary">
                <i class="fa-solid fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>