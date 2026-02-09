<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once '../lib/config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Por favor ingrese email y contraseña.";
    }
    else {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id_admin, nombre, email, password_hash, rol, estado FROM administradores WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                if ($user['estado'] === 'activo') {
                    if (password_verify($password, $user['password_hash'])) {
                        // Login successful
                        $_SESSION['admin_id'] = $user['id_admin'];
                        $_SESSION['admin_name'] = $user['nombre'];
                        $_SESSION['admin_email'] = $user['email'];
                        $_SESSION['admin_role'] = $user['rol'];

                        // Update last access
                        $update_stmt = $conn->prepare("UPDATE administradores SET ultimo_acceso = NOW() WHERE id_admin = ?");
                        $update_stmt->bind_param("i", $user['id_admin']);
                        $update_stmt->execute();
                        $update_stmt->close();

                        header("Location: dashboard.php");
                        exit();
                    }
                    else {
                        $error = "Contraseña incorrecta.";
                    }
                }
                else {
                    $error = "Su cuenta está inactiva. Contacte al soporte.";
                }
            }
            else {
                $error = "No existe una cuenta con ese email.";
            }
            $stmt->close();
        }
        else {
            $error = "Error de base de datos: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - Frosh Systems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-radius: 10px;
        }
        .login-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-primary {
            background-color: #34495e;
            border-color: #34495e;
        }
        .btn-primary:hover {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="login-header">
            <h3><i class="fa-solid fa-lock me-2"></i>Frosh Systems</h3>
            <p class="mb-0 text-white-50">Acceso Administrativo</p>
        </div>
        <div class="card-body p-4">
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php
endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center py-3 bg-light border-0 rounded-bottom">
            <small class="text-muted">&copy; <?php echo date('Y'); ?> Frosh Systems</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>