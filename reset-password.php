<?php
require_once 'lib/config.php';     // must provide $conn (MySQLi instance)
require_once 'lib/Auth.php';
require_once "lib/PasswordResetManager.php";

// Use MySQLi connection (same as the rest of the system)
$resetManager = new PasswordResetManager($conn);

// 3 MODES:
//  A) Request reset (user submits email)
//  B) Click reset link (token in URL → show password form)
//  C) Submit new password (POST with token)

$msg = null;

/***************************************************
 * A) USER REQUESTS RESET (email submission)
 ***************************************************/
if (isset($_POST['action']) && $_POST['action'] === "request") {

    $resetManager->createResetRequest($_POST['email']);

    // Same generic message to avoid enumeration
    $msg = "Si el email existe en nuestro sistema, se ha enviado un enlace de recuperación.";
}

/***************************************************
 * B) TOKEN PRESENT → USER CLICKED LINK
 ***************************************************/
$validToken = false;
$userId = null;

if (isset($_GET['token'])) {

    $token = $_GET['token'];

    $userId = $resetManager->validateToken($token);

    if ($userId) {
        $validToken = true;
    } else {
        $msg = "Token inválido o expirado.";
    }
}

/***************************************************
 * C) USER SUBMITS NEW PASSWORD
 ***************************************************/
if (isset($_POST['action']) && $_POST['action'] === "reset") {

    $token = $_POST['token'];

    $userId = $resetManager->validateToken($token);

    if ($userId) {
        $resetManager->resetPassword($userId, $_POST['password'], $token);
        $msg = "Contraseña restablecida exitosamente. Ya puedes iniciar sesión.";
        $validToken = false;
    } else {
        $msg = "Token inválido o expirado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FROSH · Recuperar Contraseña</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
    :root {
        /* FROSH Brand Colors */
        --frosh-black: #000000;
        --frosh-dark: #1a1a1a;
        --frosh-gray-900: #111827;
        --frosh-gray-800: #1f2937;
        --frosh-gray-700: #374151;
        --frosh-gray-600: #4b5563;
        --frosh-gray-500: #6b7280;
        --frosh-gray-400: #9ca3af;
        --frosh-gray-300: #d1d5db;
        --frosh-gray-200: #e5e7eb;
        --frosh-gray-100: #f3f4f6;
        --frosh-gray-50: #f9fafb;
        
        /* Marketing Colors */
        --report-warning: #D3AF37;
        --report-info: #274AB3;
        --report-success: #10b981;
        --report-danger: #ef4444;
        
        /* Login Specific */
        --bg-gradient: linear-gradient(135deg, var(--frosh-black) 0%, var(--frosh-gray-800) 100%);
        --accent: var(--report-info);
        --radius: 16px;
        --shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
        --shadow-hover: 0 35px 80px rgba(0, 0, 0, 0.5);
    }

    * {
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, var(--frosh-gray-50) 0%, var(--frosh-gray-100) 100%);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 
                 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
        margin: 0;
        padding: 20px;
        position: relative;
        overflow-x: hidden;
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

    .reset-container {
        width: 100%;
        max-width: 440px;
        position: relative;
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

    .reset-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid var(--frosh-gray-200);
        transition: all 0.3s ease;
    }

    .reset-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .reset-header {
        background: var(--bg-gradient);
        color: white;
        padding: 3rem 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .reset-header::before {
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

    .reset-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 0.5rem;
        letter-spacing: 2px;
        position: relative;
        z-index: 1;
    }

    .reset-header p {
        opacity: 0.9;
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .reset-body {
        padding: 2.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--frosh-gray-700);
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border-radius: 12px;
        border: 2px solid var(--frosh-gray-200);
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--frosh-gray-900);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(39, 74, 179, 0.1);
        transform: translateY(-1px);
    }

    .form-control:hover {
        border-color: var(--frosh-gray-300);
    }

    .btn-reset {
        width: 100%;
        background: var(--bg-gradient);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-weight: 600;
        font-size: 1rem;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-reset:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-reset:active {
        transform: translateY(0);
    }

    .btn-reset:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        text-decoration: none;
        color: var(--accent);
        font-weight: 500;
        transition: all 0.2s ease;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: var(--frosh-gray-50);
        border: 1px solid var(--frosh-gray-200);
    }

    .back-link:hover {
        color: var(--frosh-gray-800);
        background: var(--frosh-gray-100);
        border-color: var(--frosh-gray-300);
        text-decoration: none;
        transform: translateY(-1px);
    }

    .alert {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        color: #991b1b;
        border-left: 4px solid var(--report-danger);
    }

    .alert-success {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        color: #166534;
        border-left: 4px solid var(--report-success);
    }

    .alert-info {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        border-left: 4px solid var(--accent);
    }

    .password-requirements {
        background: var(--frosh-gray-50);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: var(--frosh-gray-600);
    }

    .password-requirements ul {
        margin: 0.5rem 0 0;
        padding-left: 1.2rem;
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
    }

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
    @media (max-width: 480px) {
        body {
            padding: 10px;
        }
        
        .reset-header {
            padding: 2rem 1.5rem;
        }
        
        .reset-header h1 {
            font-size: 2rem;
        }
        
        .reset-body {
            padding: 1.5rem;
        }
    }

    /* Loading state */
    .btn-reset.loading {
        pointer-events: none;
    }

    .btn-reset.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .footer-text {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--frosh-gray-200);
        color: var(--frosh-gray-500);
        font-size: 0.85rem;
    }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="security-indicator" title="Conexión segura"></div>
        <div class="reset-card">
            <div class="reset-header">
                <h1>FROSH</h1>
                <?php if (!isset($_GET['token']) && empty($validToken)): ?>
                    <p>Recuperar Contraseña</p>
                <?php elseif ($validToken): ?>
                    <p>Nueva Contraseña</p>
                <?php else: ?>
                    <p>Recuperar Contraseña</p>
                <?php endif; ?>
            </div>

            <div class="reset-body">
                <?php if ($msg): ?>
                    <?php 
                    $alertType = 'info';
                    $alertIcon = 'fa-info-circle';
                    
                    if (strpos($msg, 'exitosamente') !== false || strpos($msg, 'successful') !== false) {
                        $alertType = 'success';
                        $alertIcon = 'fa-check-circle';
                    } elseif (strpos($msg, 'inválido') !== false || strpos($msg, 'expirado') !== false || strpos($msg, 'Invalid') !== false) {
                        $alertType = 'danger';
                        $alertIcon = 'fa-exclamation-triangle';
                    }
                    ?>
                    <div class="alert alert-<?= $alertType ?>">
                        <i class="fas <?= $alertIcon ?>"></i>
                        <?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>

                <!-- MODE A: Request a reset -->
                <?php if (!isset($_GET['token']) && empty($validToken)): ?>
                <form method="post" id="requestForm">
                    <input type="hidden" name="action" value="request">

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email Registrado
                        </label>
                        <input type="email" class="form-control" name="email" required 
                               placeholder="tu@email.com" autofocus>
                    </div>

                    <button type="submit" class="btn-reset" id="requestButton">
                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar Enlace de Recuperación
                    </button>
                </form>
                <?php endif; ?>

                <!-- MODE B: Show new password form -->
                <?php if ($validToken): ?>
                <form method="post" id="resetForm">
                    <input type="hidden" name="action" value="reset">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock me-2"></i>Nueva Contraseña
                        </label>
                        <input type="password" id="password" class="form-control" name="password" 
                               required minlength="6" placeholder="Mínimo 6 caracteres">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirmar Nueva Contraseña
                        </label>
                        <input type="password" id="password_confirm" class="form-control" 
                               name="password_confirm" required minlength="6" 
                               placeholder="Repite la nueva contraseña">
                    </div>

                    <div class="password-requirements">
                        <strong>Requisitos de la contraseña:</strong>
                        <ul>
                            <li>Mínimo 6 caracteres</li>
                            <li>Ambas contraseñas deben coincidir</li>
                            <li>Se recomienda usar letras, números y símbolos</li>
                        </ul>
                    </div>

                    <p id="errorMsg" class="alert alert-danger" style="display:none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Las contraseñas no coinciden.
                    </p>

                    <button type="submit" class="btn-reset" id="resetButton">
                        <i class="fas fa-key me-2"></i>
                        Cambiar Contraseña
                    </button>
                </form>
                <?php endif; ?>

                <div class="footer-text">
                    <a href='login.php' class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Volver al Login
                    </a>
                </div>

                <div class="footer-text">
                    <small>
                        &copy; <?= date('Y') ?> FROSH Systems. Todos los derechos reservados.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Password Validation -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const resetForm = document.getElementById("resetForm");
        const requestForm = document.getElementById("requestForm");

        // Password validation for reset form
        if (resetForm) {
            const passwordInput = document.getElementById("password");
            const confirmInput = document.getElementById("password_confirm");
            const errorMsg = document.getElementById("errorMsg");
            const resetButton = document.getElementById("resetButton");

            function validatePasswords() {
                const pass = passwordInput.value;
                const confirm = confirmInput.value;
                
                if (pass && confirm) {
                    if (pass !== confirm) {
                        errorMsg.style.display = "block";
                        resetButton.disabled = true;
                        return false;
                    } else {
                        errorMsg.style.display = "none";
                        resetButton.disabled = false;
                        return true;
                    }
                }
                return true;
            }

            passwordInput.addEventListener("input", validatePasswords);
            confirmInput.addEventListener("input", validatePasswords);

            resetForm.addEventListener("submit", function(e) {
                if (!validatePasswords()) {
                    e.preventDefault();
                    return false;
                }
                
                // Add loading state
                resetButton.classList.add("loading");
                resetButton.disabled = true;
                resetButton.innerHTML = "Cambiando contraseña...";
            });
        }

        // Loading state for request form
        if (requestForm) {
            const requestButton = document.getElementById("requestButton");
            
            requestForm.addEventListener("submit", function(e) {
                requestButton.classList.add("loading");
                requestButton.disabled = true;
                requestButton.innerHTML = "Enviando enlace...";
            });
        }
    });
    </script>
</body>

</html>