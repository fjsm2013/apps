<?php
session_start();

require_once 'lib/config.php';
require_once 'lib/Auth.php';

// Already logged in
if (isLoggedIn()) {
    header("Location: lavacar/dashboard.php");
    exit;
}

$error = "";

// Optional fallback POST (non-JS)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $payload = json_encode([
        'username'   => trim($_POST['username'] ?? ''),
        'password'   => $_POST['password'] ?? '',
        'rememberMe' => !empty($_POST['rememberMe'])
    ]);

    $ch = curl_init('process-login.php');

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => $payload
    ]);

    $response = curl_exec($ch);

    // DO NOT call curl_close() in PHP 8.4+
    unset($ch);

    $data = json_decode($response, true);

    if (!empty($data['success'])) {
        header("Location: " . ($data['redirect'] ?? 'dashboard.php'));
        exit;
    } else {
        $error = $data['message'] ?? 'Usuario o contraseña incorrectos';
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>FROSH · Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    .login-container {
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

    .login-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid var(--frosh-gray-200);
        transition: all 0.3s ease;
    }

    .login-card:hover {
        box-shadow: var(--shadow-hover);
        transform: translateY(-2px);
    }

    .login-header {
        background: var(--bg-gradient);
        color: white;
        padding: 3rem 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .login-header::before {
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

    .login-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 0.5rem;
        letter-spacing: 2px;
        position: relative;
        z-index: 1;
    }

    .login-header p {
        opacity: 0.9;
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .login-body {
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

    .input-group {
        position: relative;
        display: flex;
    }

    .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
    }

    .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: none;
        padding: 0.875rem 1rem;
        background: var(--frosh-gray-100);
        border: 2px solid var(--frosh-gray-200);
        color: var(--frosh-gray-600);
        transition: all 0.2s ease;
    }

    .input-group .btn:hover {
        background: var(--frosh-gray-200);
        color: var(--frosh-gray-800);
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid var(--frosh-gray-300);
        background: white;
        cursor: pointer;
    }

    .form-check-input:checked {
        background: var(--accent);
        border-color: var(--accent);
    }

    .form-check-label {
        font-size: 0.9rem;
        color: var(--frosh-gray-600);
        cursor: pointer;
        user-select: none;
    }

    .btn-login {
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

    .btn-login:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .btn-login:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .forgot-link {
        font-size: 0.9rem;
        text-decoration: none;
        color: var(--accent);
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .forgot-link:hover {
        color: var(--frosh-gray-800);
        text-decoration: underline;
    }

    .alert {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: none;
        font-weight: 500;
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

    .d-none {
        display: none !important;
    }

    .d-flex {
        display: flex !important;
    }

    .justify-content-between {
        justify-content: space-between !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .me-2 {
        margin-right: 0.5rem !important;
    }

    .ms-2 {
        margin-left: 0.5rem !important;
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
    @media (max-width: 480px) {
        body {
            padding: 10px;
        }
        
        .login-header {
            padding: 2rem 1.5rem;
        }
        
        .login-header h1 {
            font-size: 2rem;
        }
        
        .login-body {
            padding: 1.5rem;
        }
    }

    /* Loading state */
    .btn-login.loading {
        pointer-events: none;
    }

    .btn-login.loading::after {
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

    /* Modal improvements */
    .modal-content {
        border-radius: var(--radius);
        border: none;
        box-shadow: var(--shadow);
    }

    .modal-header {
        background: var(--frosh-gray-50);
        border-bottom: 1px solid var(--frosh-gray-200);
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .modal-footer {
        background: var(--frosh-gray-50);
        border-top: 1px solid var(--frosh-gray-200);
        border-radius: 0 0 var(--radius) var(--radius);
    }

    .btn-secondary {
        background: var(--frosh-gray-500);
        border-color: var(--frosh-gray-500);
        color: white;
    }

    .btn-secondary:hover {
        background: var(--frosh-gray-600);
        border-color: var(--frosh-gray-600);
    }

    .btn-dark {
        background: var(--frosh-gray-800);
        border-color: var(--frosh-gray-800);
        color: white;
    }

    .btn-dark:hover {
        background: var(--frosh-black);
        border-color: var(--frosh-black);
    }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="security-indicator" title="Conexión segura"></div>
        <div class="login-card">
            <div class="login-header">
                <h1>FROSH</h1>
                <p>Sistema de Gestión Empresarial</p>
            </div>

            <div class="login-body">

                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>

                <div id="loginError" class="alert alert-danger d-none"></div>

                <form id="loginForm" method="POST">
                    <div class="form-group">
                        <label class="form-label">Usuario o Email</label>
                        <input type="text" class="form-control" name="username" id="username" required autofocus autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password" required autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Recordarme</label>
                        </div>
                        <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" name="login" class="btn-login" id="loginButton">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="forgotForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Recuperar contraseña</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="forgotError" class="alert alert-danger d-none"></div>
                        <div id="forgotSuccess" class="alert alert-success d-none"></div>

                        <p>Ingresa tu email registrado. Te enviaremos un enlace para restablecer tu contraseña.</p>

                        <input type="email" class="form-control" id="forgotEmail" name="email" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark" id="forgotButton">
                            <i class="fas fa-paper-plane me-2"></i> Enviar enlace
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    class LoginSystem {
        constructor() {
            this.form = document.getElementById('loginForm');
            this.button = document.getElementById('loginButton');
            this.error = document.getElementById('loginError');
            this.password = document.getElementById('password');
            this.toggle = document.getElementById('togglePassword');

            this.init();
        }

        init() {
            this.toggle.addEventListener('click', () => {
                this.password.type = this.password.type === 'password' ? 'text' : 'password';
            });

            this.form.addEventListener('submit', e => this.login(e));
        }

        async login(e) {
            e.preventDefault();
            this.error.classList.add('d-none');
            this.button.disabled = true;

            const response = await fetch('process-login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: document.getElementById('username').value,
                    password: this.password.value,
                    rememberMe: document.getElementById('rememberMe').checked
                })
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = data.redirect || 'dashboard.php';
            } else {
                this.error.textContent = data.message || 'Error de autenticación';
                this.error.classList.remove('d-none');
                this.button.disabled = false;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => new LoginSystem());
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const forgotForm = document.getElementById('forgotForm');
        const forgotEmail = document.getElementById('forgotEmail');
        const forgotButton = document.getElementById('forgotButton');
        const forgotError = document.getElementById('forgotError');
        const forgotSuccess = document.getElementById('forgotSuccess');

        forgotForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Reset messages
            forgotError.classList.add('d-none');
            forgotSuccess.classList.add('d-none');

            // Button loading state
            forgotButton.disabled = true;
            forgotButton.innerHTML = 'Enviando...';

            try {
                const response = await fetch('process-forgot-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: forgotEmail.value.trim()
                    })
                });

                const data = await response.json();

                if (data.success) {
                    forgotSuccess.textContent = data.message;
                    forgotSuccess.classList.remove('d-none');
                    forgotForm.reset();
                } else {
                    throw new Error(data.message);
                }
                //forgotEmail.disabled = true;

            } catch (err) {
                forgotError.textContent = err.message || 'Error inesperado.';
                forgotError.classList.remove('d-none');
            } finally {
                forgotButton.disabled = false;
                forgotButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Enviar enlace';
            }
        });
    });
    </script>

</body>

</html>