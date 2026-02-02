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
    <title>Test Colones Simple</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Función formatCurrency()</h2>
        
        <div class="alert alert-success">
            <h5>✅ Función formatCurrency() funcionando correctamente</h5>
            <ul class="mb-0">
                <li>formatCurrency(1500) = <strong><?= formatCurrency(1500) ?></strong></li>
                <li>formatCurrency(25000) = <strong><?= formatCurrency(25000) ?></strong></li>
                <li>formatCurrency(150750) = <strong><?= formatCurrency(150750) ?></strong></li>
                <li>formatCurrency(0) = <strong><?= formatCurrency(0) ?></strong></li>
            </ul>
        </div>
        
        <div class="alert alert-info">
            <h6>Configuración:</h6>
            <ul class="mb-0">
                <li>Símbolo por defecto: <strong>₡</strong> (colones costarricenses)</li>
                <li>Decimales por defecto: <strong>0</strong></li>
                <li>Formato: <strong>₡ 1,500</strong> (con espacio)</li>
            </ul>
        </div>
        
        <a href="lavacar/administracion/servicios/index.php" class="btn btn-primary">
            Probar en Servicios
        </a>
    </div>
</body>
</html>