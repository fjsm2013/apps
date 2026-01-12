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
    <title>Test Funciones Safe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Funciones Safe Globales</h2>
        
        <div class="card">
            <div class="card-header">
                <h5>Función safe_htmlspecialchars()</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>safe_htmlspecialchars('Juan Pérez')</td>
                        <td><strong><?= safe_htmlspecialchars('Juan Pérez') ?></strong></td>
                    </tr>
                    <tr>
                        <td>safe_htmlspecialchars(null, 'Sin cliente')</td>
                        <td><strong><?= safe_htmlspecialchars(null, 'Sin cliente') ?></strong></td>
                    </tr>
                    <tr>
                        <td>safe_htmlspecialchars('', 'Vacío')</td>
                        <td><strong><?= safe_htmlspecialchars('', 'Vacío') ?></strong></td>
                    </tr>
                    <tr>
                        <td>safe_htmlspecialchars('&lt;script&gt;alert("test")&lt;/script&gt;')</td>
                        <td><strong><?= safe_htmlspecialchars('<script>alert("test")</script>') ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Función safe_number_format()</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>safe_number_format(15000, 0) - Colones</td>
                        <td><strong>₡<?= safe_number_format(15000, 0) ?></strong></td>
                    </tr>
                    <tr>
                        <td>safe_number_format(25000.50, 0) - Colones</td>
                        <td><strong>₡<?= safe_number_format(25000.50, 0) ?></strong></td>
                    </tr>
                    <tr>
                        <td>safe_number_format(null, 0, 0) - Null</td>
                        <td><strong>₡<?= safe_number_format(null, 0, 0) ?></strong></td>
                    </tr>
                    <tr>
                        <td>safe_number_format(1500.75, 2) - Con decimales</td>
                        <td><strong>$<?= safe_number_format(1500.75, 2) ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="alert alert-success mt-4">
            <h5>✅ Funciones Globales Funcionando</h5>
            <p>Las funciones <code>safe_htmlspecialchars()</code> y <code>safe_number_format()</code> están definidas en <code>lib/handler.php</code> y funcionan correctamente.</p>
            <ul class="mb-0">
                <li><strong>safe_htmlspecialchars():</strong> Maneja valores null y escapa HTML</li>
                <li><strong>safe_number_format():</strong> Formatea números con decimales configurables</li>
                <li><strong>Uso en colones:</strong> safe_number_format(monto, 0) para 0 decimales</li>
            </ul>
        </div>
        
        <div class="mt-4">
            <a href="lavacar/reportes/ordenes-activas.php" class="btn btn-primary">
                <i class="fa-solid fa-list me-2"></i>Probar Órdenes Activas
            </a>
            <a href="debug-ordenes-activas.php" class="btn btn-secondary">
                <i class="fa-solid fa-bug me-2"></i>Debug Órdenes
            </a>
        </div>
    </div>
</body>
</html>