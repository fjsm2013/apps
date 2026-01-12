<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

// Solo para testing - eliminar en producción
if (isset($_GET['test']) && $_GET['test'] === 'email') {
    $ordenId = (int)($_GET['orden_id'] ?? 1);
    $email = $_GET['email'] ?? 'test@example.com';
    $nombre = $_GET['nombre'] ?? 'Cliente de Prueba';
    
    require_once 'enviar_correo_orden.php';
    
    echo "<h2>Probando envío de email...</h2>";
    echo "<p><strong>Orden ID:</strong> {$ordenId}</p>";
    echo "<p><strong>Email:</strong> {$email}</p>";
    echo "<p><strong>Nombre:</strong> {$nombre}</p>";
    echo "<hr>";
    
    $resultado = enviarCorreoOrden($email, $nombre, $ordenId);
    
    if ($resultado) {
        echo "<div style='color: green; font-weight: bold;'>✅ Email enviado exitosamente!</div>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>❌ Error al enviar email</div>";
    }
    
    echo "<br><br>";
    echo "<a href='?'>← Volver</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Email - FROSH</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🧪 Test de Email - Orden</h1>
    
    <div class="warning">
        <strong>⚠️ Advertencia:</strong> Este archivo es solo para pruebas. 
        Elimínalo en producción por seguridad.
    </div>
    
    <form method="GET">
        <input type="hidden" name="test" value="email">
        
        <div class="form-group">
            <label>ID de Orden:</label>
            <input type="number" name="orden_id" value="1" required>
            <small>Debe existir una orden con este ID en la base de datos</small>
        </div>
        
        <div class="form-group">
            <label>Email de Prueba:</label>
            <input type="email" name="email" value="test@example.com" required>
        </div>
        
        <div class="form-group">
            <label>Nombre del Cliente:</label>
            <input type="text" name="nombre" value="Cliente de Prueba" required>
        </div>
        
        <button type="submit">🚀 Enviar Email de Prueba</button>
    </form>
    
    <hr style="margin: 40px 0;">
    
    <h3>📋 Información del Sistema</h3>
    <ul>
        <li><strong>Usuario:</strong> <?= safe_htmlspecialchars($user['name'], 'N/A') ?></li>
        <li><strong>Empresa:</strong> <?= safe_htmlspecialchars($user['company']['name'], 'N/A') ?></li>
        <li><strong>Base de Datos:</strong> <?= safe_htmlspecialchars($dbName) ?></li>
        <li><strong>Template:</strong> lavacar/lib/templates/orden.htm</li>
        <li><strong>Función:</strong> EmailSenderDFT()</li>
    </ul>
    
    <p><a href="../">← Volver al Sistema</a></p>
</body>
</html>