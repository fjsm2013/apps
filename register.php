<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

// Redirect if already logged in
/*if (isLoggedIn()) {
    header("Location: lavacar/dashboard.php");
    exit;
}*/

$error = '';
$success = '';
$step = $_GET['step'] ?? 1;

// Handle success message for step 3
if ($step == 3 && isset($_GET['success'])) {
    $success = "¡Registro completado exitosamente! Ya puede iniciar sesión.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log all POST data for debugging
    // error_log("REGISTER DEBUG: POST received - " . print_r($_POST, true));
    // error_log("REGISTER DEBUG: Session data - " . print_r($_SESSION, true));
    
    if (isset($_POST['register_company'])) {
        // Step 1: Company Registration
        $companyName = trim($_POST['company_name']);
        $companyEmail = trim($_POST['company_email']);
        $companyPhone = trim($_POST['company_phone']);
        $companyCountry = trim($_POST['company_country']);
        $companyCity = trim($_POST['company_city']);
        $rucIdentification = trim($_POST['ruc_identification']);
        
        // Validation
        if (empty($companyName) || empty($companyEmail)) {
            $error = "Nombre de empresa y email son requeridos";
        } else {
            // Check if company email already exists
            $checkCompany = ObtenerPrimerRegistro(
                $conn,
                "SELECT id_empresa FROM empresas WHERE email = ?",
                [$companyEmail]
            );
            
            if ($checkCompany) {
                $error = "Ya existe una empresa registrada con este email";
            } else {
                // Generate unique database identifier (for future use if needed)
                // In shared database approach, this is just for reference
                $dbName = 'froshlav_' . time();
                
                // Insert company
                $companyId = EjecutarSQL(
                    $conn,
                    "INSERT INTO empresas (nombre, email, telefono, pais, ciudad, ruc_identificacion, nombre_base_datos, estado) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')",
                    [$companyName, $companyEmail, $companyPhone, $companyCountry, $companyCity, $rucIdentification, $dbName]
                );
                
                if ($companyId) {
                    // Create the tenant database automatically
                    require_once 'lib/TenantDatabaseManager.php';
                    $dbManager = new TenantDatabaseManager($conn);
                    $dbResult = $dbManager->createTenantDatabase($companyId, $companyName);
                    
                    if ($dbResult['success']) {
                        // Store company info in session for next step
                        $_SESSION['registration_company_id'] = $companyId;
                        $_SESSION['registration_company_name'] = $companyName;
                        $_SESSION['registration_db_name'] = $dbResult['database'];
                        
                        header("Location: register.php?step=2");
                        exit;
                    } else {
                        $error = "Error al crear la base de datos: " . $dbResult['message'];
                    }
                } else {
                    $error = "Error al registrar la empresa. Intente nuevamente.";
                }
            }
        }
    } elseif (isset($_POST['register_admin'])) {
        // error_log("REGISTER DEBUG: Processing admin registration");
        
        // Step 2: Admin User Registration
        if (!isset($_SESSION['registration_company_id'])) {
            // error_log("REGISTER DEBUG: No company ID in session, redirecting to step 1");
            header("Location: register.php?step=1");
            exit;
        }
        
        // error_log("REGISTER DEBUG: Company ID found: " . $_SESSION['registration_company_id']);
        
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // error_log("REGISTER DEBUG: Form data - Name: $name, Email: $email, Username: $username");
        
        // Validation
        if (empty($name) || empty($email) || empty($username) || empty($password)) {
            $error = "Todos los campos son requeridos";
            // error_log("REGISTER DEBUG: Validation failed - missing fields");
        } elseif ($password !== $confirmPassword) {
            $error = "Las contraseñas no coinciden";
            // error_log("REGISTER DEBUG: Validation failed - password mismatch");
        } elseif (strlen($password) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres";
            // error_log("REGISTER DEBUG: Validation failed - password too short");
        } else {
            // error_log("REGISTER DEBUG: Validation passed, checking email uniqueness");
            
            // Check if email already exists
            $checkUser = ObtenerPrimerRegistro(
                $conn,
                "SELECT id FROM usuarios WHERE email = ?",
                [$email]
            );
            
            if ($checkUser) {
                $error = "Ya existe un usuario con este email";
                // error_log("REGISTER DEBUG: Email already exists");
            } else {
                // error_log("REGISTER DEBUG: Email is unique, proceeding with user creation");
                
                try {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $companyId = $_SESSION['registration_company_id'];
                    
                    // error_log("REGISTER DEBUG: Creating user for company ID: $companyId");
                    
                    // Create admin user
                    $userId = EjecutarSQL(
                        $conn,
                        "INSERT INTO usuarios (id_empresa, name, email, user_name, password, permiso, estado) 
                         VALUES (?, ?, ?, ?, ?, 1, 'activo')",
                        [$companyId, $name, $email, $username, $passwordHash]
                    );
                    
                    // error_log("REGISTER DEBUG: User creation result: " . ($userId ? "Success (ID: $userId)" : "Failed"));
                    
                    if ($userId) {
                        // Create default subscription (Bronze plan)
                        $startDate = date('Y-m-d');
                        $endDate = date('Y-m-d', strtotime('+30 days')); // 30-day trial
                        
                        // error_log("REGISTER DEBUG: Creating subscription from $startDate to $endDate");
                        
                        $subscriptionResult = EjecutarSQL(
                            $conn,
                            "INSERT INTO suscripciones (id_empresa, id_plan, fecha_inicio, fecha_fin, estado, precio_actual) 
                             VALUES (?, 1, ?, ?, 'activa', 0.00)",
                            [$companyId, $startDate, $endDate]
                        );
                        
                        // error_log("REGISTER DEBUG: Subscription creation result: " . ($subscriptionResult ? "Success" : "Failed"));
                        
                        // Update company status to active
                        $companyUpdateResult = EjecutarSQL(
                            $conn,
                            "UPDATE empresas SET estado = 'activo' WHERE id_empresa = ?",
                            [$companyId]
                        );
                        
                        // error_log("REGISTER DEBUG: Company update result: " . ($companyUpdateResult ? "Success" : "Failed"));
                        
                        if ($subscriptionResult && $companyUpdateResult) {
                            // error_log("REGISTER DEBUG: All operations successful, clearing session and redirecting");
                            
                            // Clear registration session data
                            unset($_SESSION['registration_company_id']);
                            unset($_SESSION['registration_company_name']);
                            unset($_SESSION['registration_db_name']);
                            
                            // Redirect to success step
                            // error_log("REGISTER DEBUG: Redirecting to step 3");
                            header("Location: register.php?step=3&success=1");
                            exit;
                        } else {
                            $error = "Error al configurar la suscripción. Intente nuevamente.";
                            // error_log("REGISTER DEBUG: Failed to create subscription or update company");
                        }
                    } else {
                        $error = "Error al crear el usuario administrador. Intente nuevamente.";
                        // error_log("REGISTER DEBUG: Failed to create user");
                    }
                } catch (Exception $e) {
                    $error = "Error en el registro: " . $e->getMessage();
                    // error_log("REGISTER DEBUG: Exception caught: " . $e->getMessage());
                }
            }
        }
    } else {
        // error_log("REGISTER DEBUG: POST received but no recognized action");
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>FROSH · Registro de Empresa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- FROSH Global CSS -->
    <link rel="stylesheet" href="lib/css/frosh-global.css">
    <link rel="stylesheet" href="lib/css/frosh-security.css">

    <style>
    body {
        background: linear-gradient(135deg, var(--frosh-gray-50) 0%, var(--frosh-gray-100) 100%);
        min-height: 100vh;
        position: relative;
    }

    /* Subtle background pattern */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 25% 25%, rgba(0, 0, 0, 0.02) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(0, 0, 0, 0.02) 0%, transparent 50%);
        pointer-events: none;
        z-index: -1;
    }

    .register-container {
        padding: 2rem 1rem;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-card {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--content-border);
        max-width: 600px;
        width: 100%;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .register-header {
        background: linear-gradient(135deg, var(--frosh-black) 0%, var(--frosh-gray-800) 100%);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .register-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .register-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 0.5rem;
        letter-spacing: 2px;
        position: relative;
        z-index: 1;
    }

    .register-header p {
        opacity: 0.9;
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .register-body {
        padding: 2.5rem;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
        position: relative;
    }

    .step {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--frosh-gray-200);
        color: var(--frosh-gray-600);
        font-weight: 600;
        margin: 0 1rem;
        position: relative;
        z-index: 2;
        transition: all var(--transition-base);
    }

    .step.active {
        background: var(--report-info);
        color: white;
        box-shadow: 0 4px 12px rgba(39, 74, 179, 0.3);
    }

    .step.completed {
        background: var(--report-success);
        color: white;
    }

    .step-line {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateY(-50%);
        width: calc(100% - 80px);
        height: 2px;
        background: var(--frosh-gray-200);
        z-index: 1;
    }

    .step-line.completed {
        background: var(--report-success);
    }

    .form-section {
        display: none;
    }

    .form-section.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .btn-register {
        background: linear-gradient(135deg, var(--report-info), #1e3a8a);
        border: none;
        border-radius: var(--border-radius);
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        transition: all var(--transition-base);
        position: relative;
        overflow: hidden;
    }

    .btn-register:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(39, 74, 179, 0.3);
        color: white;
    }

    .btn-register:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .success-animation {
        text-align: center;
        padding: 3rem 2rem;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--report-success);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        animation: bounceIn 0.6s ease-out;
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Security indicators */
    .security-indicator {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--report-success);
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        animation: pulse-security 2s infinite;
    }

    @keyframes pulse-security {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Responsive design */
    @media (max-width: 576px) {
        .register-container {
            padding: 1rem;
        }
        
        .register-header {
            padding: 2rem 1.5rem;
        }
        
        .register-header h1 {
            font-size: 2rem;
        }
        
        .register-body {
            padding: 1.5rem;
        }
        
        .step {
            width: 35px;
            height: 35px;
            margin: 0 0.5rem;
        }
    }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="security-indicator" title="Conexión segura"></div>
        
        <div class="register-card">
            <div class="register-header">
                <h1>FROSH</h1>
                <p>Registro de Nueva Empresa</p>
            </div>

            <div class="register-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step-line <?= $step >= 2 ? 'completed' : '' ?>"></div>
                    <div class="step <?= $step >= 1 ? 'active' : '' ?> <?= $step > 1 ? 'completed' : '' ?>">1</div>
                    <div class="step <?= $step >= 2 ? 'active' : '' ?> <?= $step > 2 ? 'completed' : '' ?>">2</div>
                    <div class="step <?= $step >= 3 ? 'active' : '' ?>">3</div>
                </div>

                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
                <div class="alert alert-info">
                    <strong>Debug Info:</strong><br>
                    Current Step: <?= $step ?><br>
                    POST Data: <?= !empty($_POST) ? 'Present' : 'None' ?><br>
                    Session Company ID: <?= $_SESSION['registration_company_id'] ?? 'Not set' ?><br>
                    <?php if (isset($_POST['register_admin'])): ?>
                        Form submitted for admin registration<br>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        Error: <?= htmlspecialchars($error) ?><br>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php /* Debug info (can be removed later)
                <div class="alert alert-info">
                    <strong>Status:</strong> Backend funcionando correctamente ✅<br>
                    Ahora probando JavaScript mejorado...<br>
                </div>
                */ ?>

                <!-- Step 1: Company Information -->
                <div class="form-section <?= $step == 1 ? 'active' : '' ?>">
                    <h4 class="mb-4">
                        <i class="fas fa-building me-2 text-primary"></i>
                        Información de la Empresa
                    </h4>
                    
                    <form method="POST" id="companyForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre de la Empresa *</label>
                                <input type="text" class="form-control" name="company_name" 
                                       value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>" 
                                       required autocomplete="organization">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Corporativo *</label>
                                <input type="email" class="form-control" name="company_email" 
                                       value="<?= htmlspecialchars($_POST['company_email'] ?? '') ?>" 
                                       required autocomplete="email">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" name="company_phone" 
                                       value="<?= htmlspecialchars($_POST['company_phone'] ?? '') ?>" 
                                       autocomplete="tel">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">RUC/Identificación</label>
                                <input type="text" class="form-control" name="ruc_identification" 
                                       value="<?= htmlspecialchars($_POST['ruc_identification'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">País</label>
                                <select class="form-select" name="company_country">
                                    <option value="">Seleccionar país</option>
                                    <option value="Costa Rica" <?= ($_POST['company_country'] ?? '') == 'Costa Rica' ? 'selected' : '' ?>>Costa Rica</option>
                                    <option value="Guatemala" <?= ($_POST['company_country'] ?? '') == 'Guatemala' ? 'selected' : '' ?>>Guatemala</option>
                                    <option value="Honduras" <?= ($_POST['company_country'] ?? '') == 'Honduras' ? 'selected' : '' ?>>Honduras</option>
                                    <option value="El Salvador" <?= ($_POST['company_country'] ?? '') == 'El Salvador' ? 'selected' : '' ?>>El Salvador</option>
                                    <option value="Nicaragua" <?= ($_POST['company_country'] ?? '') == 'Nicaragua' ? 'selected' : '' ?>>Nicaragua</option>
                                    <option value="Panamá" <?= ($_POST['company_country'] ?? '') == 'Panamá' ? 'selected' : '' ?>>Panamá</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="company_city" 
                                       value="<?= htmlspecialchars($_POST['company_city'] ?? '') ?>" 
                                       autocomplete="address-level2">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="login.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver al Login
                            </a>
                            <button type="submit" name="register_company" class="btn btn-register">
                                Continuar <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Admin User -->
                <div class="form-section <?= $step == 2 ? 'active' : '' ?>">
                    <h4 class="mb-4">
                        <i class="fas fa-user-shield me-2 text-success"></i>
                        Usuario Administrador
                    </h4>
                    
                    <?php if (isset($_SESSION['registration_company_name'])): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Creando cuenta para: <strong><?= htmlspecialchars($_SESSION['registration_company_name']) ?></strong>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="adminForm">
                        <input type="hidden" name="register_admin" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control" name="name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                                   required autocomplete="name">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                                       required autocomplete="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre de Usuario *</label>
                                <input type="text" class="form-control" name="username" 
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                                       required autocomplete="username">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contraseña *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" 
                                           id="password" required autocomplete="new-password">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2" id="passwordStrength">
                                    <div class="password-strength-bar"></div>
                                </div>
                                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" name="confirm_password" 
                                       required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-check mb-4" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border: 2px solid #dee2e6;">
                            <input class="form-check-input" type="checkbox" id="acceptTerms" required checked style="transform: scale(1.2);">
                            <label class="form-check-label" for="acceptTerms" style="font-weight: 600; color: #495057;">
                                Acepto los <a href="#" class="text-primary">términos y condiciones</a> 
                                y la <a href="#" class="text-primary">política de privacidad</a>
                            </label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="register.php?step=1" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Anterior
                            </a>
                            <button type="submit" class="btn btn-register" id="submitBtn">
                                <i class="fas fa-user-plus me-2"></i>Continuar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Success -->
                <div class="form-section <?= $step == 3 ? 'active' : '' ?>">
                    <div class="success-animation">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <h3 class="text-success mb-3">¡Registro Completado!</h3>
                        <p class="text-muted mb-4">
                            Su empresa ha sido registrada exitosamente en FROSH. 
                            Ya puede acceder al sistema con sus credenciales.
                        </p>
                        <div class="alert alert-info">
                            <i class="fas fa-gift me-2"></i>
                            <strong>¡Prueba gratuita de 30 días activada!</strong><br>
                            Explore todas las funcionalidades del sistema sin costo.
                        </div>
                        <a href="login.php" class="btn btn-register btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
        
        // Password strength validation
        const password = document.getElementById('password');
        const strengthIndicator = document.getElementById('passwordStrength');
        
        if (password && strengthIndicator) {
            password.addEventListener('input', function() {
                const value = this.value;
                const strength = calculatePasswordStrength(value);
                updatePasswordStrength(strength);
            });
        }
        
        function calculatePasswordStrength(password) {
            let score = 0;
            
            if (password.length >= 8) score++;
            if (password.match(/[a-z]/)) score++;
            if (password.match(/[A-Z]/)) score++;
            if (password.match(/[0-9]/)) score++;
            if (password.match(/[^a-zA-Z0-9]/)) score++;
            
            return score;
        }
        
        function updatePasswordStrength(score) {
            const strengthIndicator = document.getElementById('passwordStrength');
            const strengthBar = strengthIndicator.querySelector('.password-strength-bar');
            
            // Remove all strength classes
            strengthIndicator.classList.remove('password-strength-weak', 'password-strength-fair', 'password-strength-good', 'password-strength-strong');
            
            if (score === 0) {
                strengthBar.style.width = '0%';
            } else if (score <= 2) {
                strengthIndicator.classList.add('password-strength-weak');
            } else if (score === 3) {
                strengthIndicator.classList.add('password-strength-fair');
            } else if (score === 4) {
                strengthIndicator.classList.add('password-strength-good');
            } else {
                strengthIndicator.classList.add('password-strength-strong');
            }
        }
        
        // Form validation
        const companyForm = document.getElementById('companyForm');
        const adminForm = document.getElementById('adminForm');
        
        if (companyForm) {
            companyForm.addEventListener('submit', function(e) {
                const companyName = this.querySelector('[name="company_name"]').value.trim();
                const companyEmail = this.querySelector('[name="company_email"]').value.trim();
                
                if (!companyName || !companyEmail) {
                    e.preventDefault();
                    showAlert('Por favor complete todos los campos requeridos', 'danger');
                    return false;
                }
                
                if (!isValidEmail(companyEmail)) {
                    e.preventDefault();
                    showAlert('Por favor ingrese un email válido', 'danger');
                    return false;
                }
            });
        }
        
        if (adminForm) {
            adminForm.addEventListener('submit', function(e) {
                console.log('Form submission started');
                
                const name = this.querySelector('[name="name"]').value.trim();
                const email = this.querySelector('[name="email"]').value.trim();
                const username = this.querySelector('[name="username"]').value.trim();
                const password = this.querySelector('[name="password"]').value;
                const confirmPassword = this.querySelector('[name="confirm_password"]').value;
                const acceptTerms = this.querySelector('#acceptTerms').checked;
                
                console.log('Form validation:', {
                    name: name,
                    email: email,
                    username: username,
                    passwordLength: password.length,
                    confirmPasswordLength: confirmPassword.length,
                    acceptTerms: acceptTerms
                });
                
                if (!name || !email || !username || !password || !confirmPassword) {
                    e.preventDefault();
                    console.log('Validation failed: Missing fields');
                    showAlert('Por favor complete todos los campos requeridos', 'danger');
                    return false;
                }
                
                if (!isValidEmail(email)) {
                    e.preventDefault();
                    console.log('Validation failed: Invalid email');
                    showAlert('Por favor ingrese un email válido', 'danger');
                    return false;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    console.log('Validation failed: Password mismatch');
                    showAlert('Las contraseñas no coinciden', 'danger');
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    console.log('Validation failed: Password too short');
                    showAlert('La contraseña debe tener al menos 8 caracteres', 'danger');
                    return false;
                }
                
                if (!acceptTerms) {
                    e.preventDefault();
                    console.log('Validation failed: Terms not accepted');
                    showAlert('Debe aceptar los términos y condiciones', 'danger');
                    return false;
                }
                
                console.log('All validation passed, submitting form');
                
                // Show loading state without interfering with form submission
                const submitBtn = this.querySelector('#submitBtn');
                if (submitBtn) {
                    console.log('Found submit button, updating state');
                    // Use setTimeout to change the button after form starts submitting
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...';
                    }, 10);
                }
                
                // Let the form submit naturally
                console.log('Form will now submit to server');
            });
        }
        
        // Email validation
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Alert system
        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create new alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert alert at the top of the register body
            const registerBody = document.querySelector('.register-body');
            const stepIndicator = document.querySelector('.step-indicator');
            registerBody.insertBefore(alertDiv, stepIndicator.nextSibling);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
        
        // Real-time email validation
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value && !isValidEmail(this.value)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    if (this.value) {
                        this.classList.add('is-valid');
                    }
                }
            });
            
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'is-valid');
            });
        });
        
        // Real-time password confirmation validation
        const confirmPasswordInput = document.querySelector('[name="confirm_password"]');
        if (confirmPasswordInput && password) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value && password.value) {
                    if (this.value === password.value) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                } else {
                    this.classList.remove('is-invalid', 'is-valid');
                }
            });
        }
        
        // Auto-generate username from name
        const nameInput = document.querySelector('[name="name"]');
        const usernameInput = document.querySelector('[name="username"]');
        
        if (nameInput && usernameInput) {
            nameInput.addEventListener('input', function() {
                if (!usernameInput.value) {
                    const username = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9\s]/g, '')
                        .replace(/\s+/g, '')
                        .substring(0, 20);
                    usernameInput.value = username;
                }
            });
        }
        
        // Security indicators
        function showSecurityIndicator() {
            const indicator = document.querySelector('.security-indicator');
            if (indicator) {
                indicator.style.animation = 'pulse-security 2s infinite';
            }
        }
        
        // Initialize security indicator
        showSecurityIndicator();
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
    </script>
</body>

</html>