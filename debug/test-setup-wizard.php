<?php
/**
 * Test Setup Wizard
 * Simple test to verify the setup wizard functionality
 */

session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo "❌ Error: Usuario no autenticado. Inicia sesión primero.\n";
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "🧪 Probando Setup Wizard para: {$user['nombre']}\n";
echo "📊 Base de datos: {$dbName}\n\n";

// Test 1: Create setup tables
echo "1️⃣ Creando tablas de configuración...\n";
require_once 'lavacar/setup-wizard/setup-tables.php';
$result = createSetupTables($conn, $dbName);

if ($result['success']) {
    echo "✅ Tablas creadas correctamente\n";
} else {
    echo "❌ Error creando tablas: " . $result['message'] . "\n";
    exit;
}

// Test 2: Check setup completion
echo "\n2️⃣ Verificando estado de configuración...\n";
require_once 'lavacar/middleware/setup-check.php';
$setupStatus = checkSetupCompletion($conn, $dbName, false);

echo "📋 Estado actual:\n";
echo "   - Empresa: " . ($setupStatus['empresa_configurada'] ? "✅" : "❌") . "\n";
echo "   - Servicios: " . ($setupStatus['servicios_configurados'] ? "✅" : "❌") . "\n";
echo "   - Precios: " . ($setupStatus['precios_configurados'] ? "✅" : "❌") . "\n";
echo "   - Usuarios: " . ($setupStatus['usuarios_configurados'] ?? false ? "✅" : "❌") . "\n";
echo "   - Completo: " . ($setupStatus['setup_completo'] ? "✅" : "❌") . "\n";

// Test 3: Get setup progress
echo "\n3️⃣ Obteniendo progreso del wizard...\n";
$progress = getSetupProgress($conn, $dbName);
echo "📊 Progreso: {$progress['completed_steps']}/{$progress['total_steps']} pasos ({$progress['progress']}%)\n";

foreach ($progress['steps'] as $stepNum => $step) {
    $status = $step['completed'] ? "✅" : "❌";
    echo "   Paso {$stepNum}: {$status} {$step['title']}\n";
}

// Test 4: Test wizard functions
echo "\n4️⃣ Probando funciones del wizard...\n";
require_once 'lavacar/setup-wizard.php';

$wizardCompleted = checkWizardCompletion($conn, $dbName);
echo "🎯 Wizard completado: " . ($wizardCompleted ? "✅ Sí" : "❌ No") . "\n";

echo "\n🎉 Pruebas completadas. El setup wizard está listo para usar.\n";
echo "🔗 Accede al wizard en: lavacar/setup-wizard.php\n";
?>