<?php
/**
 * FROSH Setup Wizard - Configuración Inicial
 * Guía paso a paso para configurar la empresa antes de usar el sistema
 */

session_start();
require_once '../lib/config.php';
require_once 'lib/Auth.php';
require_once 'setup-wizard/setup-tables.php';

// Verificar autenticación
if (!isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

// Crear tablas necesarias si no existen
createSetupTables($conn, $dbName);

// Include step functions
require_once 'setup-wizard/functions.php';

// Verificar si el wizard ya fue completado
$wizardCompleted = checkWizardCompletion($conn, $dbName);

if ($wizardCompleted && !isset($_GET['force'])) {
    header("Location: dashboard.php");
    exit;
}

$currentStep = $_GET['step'] ?? 1;
$maxStep = 4;

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = processWizardStep($conn, $dbName, $currentStep, $_POST);
    if ($result['success']) {
        if ($currentStep < $maxStep) {
            header("Location: setup-wizard.php?step=" . ($currentStep + 1));
            exit;
        } else {
            // Marcar wizard como completado
            markWizardCompleted($conn, $dbName);
            header("Location: dashboard.php?setup_complete=1");
            exit;
        }
    } else {
        $error = $result['message'];
    }
}

// Obtener datos para cada paso
$stepData = getStepData($conn, $dbName, $currentStep);

include 'partials/header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Progress Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">
                        <i class="fas fa-magic me-2"></i>Configuración Inicial de FROSH
                    </h4>
                    
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: <?= ($currentStep / $maxStep) * 100 ?>%"></div>
                    </div>
                    
                    <div class="row text-center">
                        <?php for ($i = 1; $i <= $maxStep; $i++): ?>
                        <div class="col-3">
                            <div class="step-indicator <?= $i <= $currentStep ? 'active' : '' ?> <?= $i < $currentStep ? 'completed' : '' ?>">
                                <div class="step-number">
                                    <?php if ($i < $currentStep): ?>
                                        <i class="fas fa-check"></i>
                                    <?php else: ?>
                                        <?= $i ?>
                                    <?php endif; ?>
                                </div>
                                <div class="step-title">
                                    <?php
                                    $stepTitles = [
                                        1 => 'Empresa',
                                        2 => 'Servicios',
                                        3 => 'Precios',
                                        4 => 'Usuarios'
                                    ];
                                    echo $stepTitles[$i];
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Step Content -->
            <div class="card">
                <div class="card-body">
                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                    <?php endif; ?>

                    <?php switch ($currentStep): 
                        case 1: ?>
                            <?php include 'setup-wizard/step1-empresa.php'; ?>
                        <?php break; 
                        case 2: ?>
                            <?php include 'setup-wizard/step2-servicios.php'; ?>
                        <?php break; 
                        case 3: ?>
                            <?php include 'setup-wizard/step3-precios.php'; ?>
                        <?php break; 
                        case 4: ?>
                            <?php include 'setup-wizard/step4-usuarios.php'; ?>
                        <?php break; 
                    endswitch; ?>
                </div>
            </div>

            <!-- Navigation -->
            <div class="d-flex justify-content-between mt-4">
                <?php if ($currentStep > 1): ?>
                <a href="setup-wizard.php?step=<?= $currentStep - 1 ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Anterior
                </a>
                <?php else: ?>
                <div></div>
                <?php endif; ?>

                <div class="text-muted">
                    Paso <?= $currentStep ?> de <?= $maxStep ?>
                </div>

                <?php if ($currentStep < $maxStep): ?>
                <button type="submit" form="wizardForm" class="btn btn-primary">
                    Siguiente<i class="fas fa-arrow-right ms-2"></i>
                </button>
                <?php else: ?>
                <button type="submit" form="wizardForm" class="btn btn-success">
                    <i class="fas fa-check me-2"></i>Finalizar Configuración
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.step-indicator {
    text-align: center;
    margin-bottom: 10px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step-indicator.active .step-number {
    background-color: #0d6efd;
    color: white;
}

.step-indicator.completed .step-number {
    background-color: #198754;
    color: white;
}

.step-title {
    font-size: 12px;
    font-weight: 500;
    color: #6c757d;
}

.step-indicator.active .step-title,
.step-indicator.completed .step-title {
    color: #495057;
    font-weight: 600;
}

.wizard-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.wizard-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.wizard-card.completed {
    border-color: #198754;
    background-color: #f8fff9;
}

.wizard-card.required {
    border-color: #dc3545;
    background-color: #fff5f5;
}
</style>

<?php include 'partials/footer.php'; ?>

<?php
/**
 * Funciones del Wizard
 */

function checkWizardCompletion($conn, $dbName) {
    try {
        // Verificar si existe configuración de empresa
        $empresa = CrearConsulta($conn, "SELECT COUNT(*) as count FROM `{$dbName}`.`configuracion_empresa`", []);
        $empresaCount = $empresa ? $empresa->fetch_assoc()['count'] : 0;
        
        // Verificar si existen servicios
        $servicios = CrearConsulta($conn, "SELECT COUNT(*) as count FROM `{$dbName}`.`servicios` WHERE estado = 'activo'", []);
        $serviciosCount = $servicios ? $servicios->fetch_assoc()['count'] : 0;
        
        // Verificar si existen precios
        $precios = CrearConsulta($conn, "SELECT COUNT(*) as count FROM `{$dbName}`.`precios_servicios`", []);
        $preciosCount = $precios ? $precios->fetch_assoc()['count'] : 0;
        
        // Verificar si la configuración de usuarios está marcada como completa
        $usuariosConfig = CrearConsulta($conn, "SELECT valor FROM `{$dbName}`.`configuracion_sistema` WHERE clave = 'usuarios_configurados'", []);
        $usuariosCompleto = $usuariosConfig ? ($usuariosConfig->fetch_assoc()['valor'] ?? '0') === '1' : false;
        
        return ($empresaCount > 0 && $serviciosCount > 0 && $preciosCount > 0 && $usuariosCompleto);
    } catch (Exception $e) {
        return false;
    }
}

function processWizardStep($conn, $dbName, $step, $data) {
    try {
        switch ($step) {
            case 1:
                return processEmpresaStep($conn, $dbName, $data);
            case 2:
                return processServiciosStep($conn, $dbName, $data);
            case 3:
                return processPreciosStep($conn, $dbName, $data);
            case 4:
                return processUsuariosStep($conn, $dbName, $data);
            default:
                return ['success' => false, 'message' => 'Paso inválido'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function getStepData($conn, $dbName, $step) {
    switch ($step) {
        case 1:
            return getEmpresaData($conn, $dbName);
        case 2:
            return getServiciosData($conn, $dbName);
        case 3:
            return getPreciosData($conn, $dbName);
        case 4:
            return getUsuariosData($conn, $dbName);
        default:
            return [];
    }
}

function markWizardCompleted($conn, $dbName) {
    try {
        EjecutarSQL($conn, 
            "INSERT INTO `{$dbName}`.`configuracion_sistema` (clave, valor) 
             VALUES ('wizard_completed', ?) 
             ON DUPLICATE KEY UPDATE valor = ?", 
            [date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]
        );
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Funciones específicas para cada paso se incluirán en archivos separados
?>