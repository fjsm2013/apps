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
    <title>Test Confirmación de Servicios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Función formatPrice() JavaScript</h2>
        
        <div class="card">
            <div class="card-header">
                <h5>Prueba de Formateo de Precios</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary" onclick="testFormatPrice()">Probar Formateo</button>
                <div id="results" class="mt-3"></div>
            </div>
        </div>
        
        <div class="alert alert-success mt-4">
            <h5>✅ Problema Solucionado</h5>
            <p>El error "Undefined variable $price" se debía a que estaba mezclando PHP y JavaScript incorrectamente.</p>
            <ul>
                <li><strong>Antes:</strong> <code>&lt;?= formatCurrency($price) ?&gt;</code> dentro de JavaScript</li>
                <li><strong>Ahora:</strong> <code>₡ ${formatPrice(price)}</code> usando función JavaScript</li>
            </ul>
        </div>
    </div>

    <script>
    function formatPrice(amount) {
        if (amount === null || amount === undefined || amount === '') return '0';
        return Number(amount).toLocaleString('es-CR', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    function testFormatPrice() {
        const testValues = [1500, 25000, 150750, 0, null, undefined, ''];
        const results = document.getElementById('results');
        
        let html = '<h6>Resultados del Formateo:</h6><ul class="list-group">';
        
        testValues.forEach(value => {
            const formatted = formatPrice(value);
            html += `<li class="list-group-item d-flex justify-content-between">
                <span>formatPrice(${value})</span>
                <strong>₡ ${formatted}</strong>
            </li>`;
        });
        
        html += '</ul>';
        results.innerHTML = html;
    }
    </script>
</body>
</html>