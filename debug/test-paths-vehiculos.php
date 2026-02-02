<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

echo "<h2>Test de Rutas - Sistema de Archivos</h2>";
echo "<p><strong>Directorio actual después de config.php:</strong> " . getcwd() . "</p>";
echo "<p><strong>APP_ROOT definido:</strong> " . APP_ROOT . "</p>";

echo "<h3>Verificación de Archivos:</h3>";
$files_to_check = [
    'lib/Auth.php',
    'lavacar/backend/CategoriaVehiculoManager.php',
    'lavacar/backend/ServiciosManager.php',
    'lavacar/backend/preciosManager.php',
    'lavacar/partials/header.php',
    'lavacar/partials/footer.php'
];

foreach ($files_to_check as $file) {
    $exists = file_exists($file);
    $status = $exists ? '✅' : '❌';
    echo "<p>{$status} {$file} - " . ($exists ? 'EXISTS' : 'NOT FOUND') . "</p>";
}

echo "<h3>Rutas Correctas para Archivos en Subdirectorios:</h3>";
echo "<ul>";
echo "<li><strong>Desde lavacar/administracion/vehiculos/index.php:</strong></li>";
echo "<ul>";
echo "<li>config.php: <code>../../../lib/config.php</code> (ruta absoluta hasta apps/lib/)</li>";
echo "<li>Auth.php: <code>lib/Auth.php</code> (después del chdir)</li>";
echo "<li>Manager: <code>lavacar/backend/CategoriaVehiculoManager.php</code> (después del chdir)</li>";
echo "<li>Header: <code>lavacar/partials/header.php</code> (después del chdir)</li>";
echo "</ul>";
echo "</ul>";

echo "<h3>Estructura del Sistema:</h3>";
echo "<pre>";
echo "apps/                           <- APP_ROOT (chdir aquí)
├── lib/
│   ├── config.php                 <- chdir(APP_ROOT) se ejecuta aquí
│   ├── Auth.php                   <- lib/Auth.php
│   └── handler.php
├── lavacar/
│   ├── backend/
│   │   ├── ServiciosManager.php   <- lavacar/backend/ServiciosManager.php
│   │   └── preciosManager.php
│   ├── partials/
│   │   ├── header.php             <- lavacar/partials/header.php
│   │   └── footer.php
│   └── administracion/
│       ├── servicios/
│       │   └── index.php          <- Aquí estamos
│       └── vehiculos/
│           └── index.php          <- Aquí estamos
";
echo "</pre>";
?>