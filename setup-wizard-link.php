<?php
/**
 * Simple link to access the setup wizard
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>FROSH Setup Wizard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .container { max-width: 600px; margin: 0 auto; text-align: center; }
        .btn { display: inline-block; padding: 12px 24px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; margin: 10px; }
        .btn:hover { background: #0b5ed7; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 FROSH Setup Wizard</h1>
        <p>Configura tu autolavado paso a paso</p>
        
        <div>
            <a href="login.php" class="btn">🔐 Iniciar Sesión</a>
            <a href="lavacar/setup-wizard.php" class="btn">🚀 Ir al Setup Wizard</a>
            <a href="lavacar/dashboard.php" class="btn">📊 Dashboard</a>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
            <h3>📋 Pasos del Setup Wizard:</h3>
            <ol style="text-align: left; max-width: 400px; margin: 0 auto;">
                <li><strong>Empresa:</strong> Información básica, horarios, configuración</li>
                <li><strong>Servicios:</strong> Servicios que ofreces (preconfigurados + personalizados)</li>
                <li><strong>Precios:</strong> Tarifas por servicio y tipo de vehículo</li>
                <li><strong>Usuarios:</strong> Configuración de notificaciones (usuarios centralizados)</li>
            </ol>
        </div>
        
        <div style="margin-top: 20px; font-size: 14px; color: #666;">
            <p>✅ El wizard previene la creación de órdenes hasta completar la configuración</p>
            <p>🔄 Puedes volver a cualquier paso para modificar la configuración</p>
        </div>
    </div>
</body>
</html>