<?php
/**
 * Simplified registration for testing - Step 2 only
 */
session_start();
require_once 'lib/config.php';

// Set up test session data if not present
if (!isset($_SESSION['registration_company_id'])) {
    $_SESSION['registration_company_id'] = 3; // Use existing company
    $_SESSION['registration_company_name'] = 'Test Company';
    $_SESSION['registration_db_name'] = 'test_db';
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_admin'])) {
    echo "<div style='background: #e7f3ff; padding: 10px; margin: 10px 0; border-left: 4px solid #2196F3;'>";
    echo "<strong>Processing Registration...</strong><br>";
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    echo "Name: $name<br>";
    echo "Email: $email<br>";
    echo "Username: $username<br>";
    echo "Password length: " . strlen($password) . "<br>";
    echo "Company ID: " . $_SESSION['registration_company_id'] . "<br>";
    echo "</div>";
    
    // Basic validation
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $error = "Todos los campos son requeridos";
    } elseif ($password !== $confirmPassword) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres";
    } else {
        // Check if email already exists
        $checkUser = ObtenerPrimerRegistro(
            $conn,
            "SELECT id FROM usuarios WHERE email = ?",
            [$email]
        );
        
        if ($checkUser) {
            $error = "Ya existe un usuario con este email";
        } else {
            try {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $companyId = $_SESSION['registration_company_id'];
                
                echo "<div style='background: #fff3cd; padding: 10px; margin: 10px 0; border-left: 4px solid #ffc107;'>";
                echo "Creating user...<br>";
                
                // Create admin user
                $userId = EjecutarSQL(
                    $conn,
                    "INSERT INTO usuarios (id_empresa, name, email, user_name, password, permiso, estado) 
                     VALUES (?, ?, ?, ?, ?, 1, 'activo')",
                    [$companyId, $name, $email, $username, $passwordHash]
                );
                
                echo "User creation result: " . ($userId ? "Success (ID: $userId)" : "Failed") . "<br>";
                
                if ($userId) {
                    // Create default subscription (Bronze plan)
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d', strtotime('+30 days'));
                    
                    echo "Creating subscription...<br>";
                    
                    $subscriptionResult = EjecutarSQL(
                        $conn,
                        "INSERT INTO suscripciones (id_empresa, id_plan, fecha_inicio, fecha_fin, estado, precio_actual) 
                         VALUES (?, 1, ?, ?, 'activa', 0.00)",
                        [$companyId, $startDate, $endDate]
                    );
                    
                    echo "Subscription result: " . ($subscriptionResult ? "Success" : "Failed") . "<br>";
                    
                    // Update company status to active
                    $companyUpdateResult = EjecutarSQL(
                        $conn,
                        "UPDATE empresas SET estado = 'activo' WHERE id_empresa = ?",
                        [$companyId]
                    );
                    
                    echo "Company update result: " . ($companyUpdateResult ? "Success" : "Failed") . "<br>";
                    echo "</div>";
                    
                    if ($subscriptionResult && $companyUpdateResult) {
                        $success = "¡Registro completado exitosamente! Usuario ID: $userId";
                        echo "<div style='background: #d4edda; padding: 15px; margin: 10px 0; border-left: 4px solid #28a745; color: #155724;'>";
                        echo "<strong>✅ REGISTRATION SUCCESSFUL!</strong><br>";
                        echo "User ID: $userId<br>";
                        echo "All operations completed successfully.<br>";
                        echo "<a href='login.php' style='color: #155724; font-weight: bold;'>Go to Login</a>";
                        echo "</div>";
                    } else {
                        $error = "Error al configurar la suscripción.";
                    }
                } else {
                    $error = "Error al crear el usuario administrador.";
                }
                
            } catch (Exception $e) {
                $error = "Error en el registro: " . $e->getMessage();
                echo "<div style='background: #f8d7da; padding: 10px; margin: 10px 0; border-left: 4px solid #dc3545;'>";
                echo "Exception: " . $e->getMessage() . "<br>";
                echo "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simple Registration Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        button { background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Simple Registration Test - Step 2</h1>
    
    <div style="background: #e7f3ff; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <strong>Session Info:</strong><br>
        Company ID: <?= $_SESSION['registration_company_id'] ?? 'Not set' ?><br>
        Company Name: <?= $_SESSION['registration_company_name'] ?? 'Not set' ?><br>
        DB Name: <?= $_SESSION['registration_db_name'] ?? 'Not set' ?>
    </div>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Nombre Completo *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? 'Test Admin User') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? 'testadmin' . time() . '@example.com') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nombre de Usuario *</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? 'testadmin' . time()) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Contraseña *</label>
            <input type="password" name="password" value="TestPassword123!" required>
        </div>
        
        <div class="form-group">
            <label>Confirmar Contraseña *</label>
            <input type="password" name="confirm_password" value="TestPassword123!" required>
        </div>
        
        <button type="submit" name="register_admin">Crear Usuario Administrador</button>
    </form>
    
    <hr style="margin: 30px 0;">
    <p><a href="register.php?step=2">Back to Full Registration</a></p>
    <p><a href="debug-registration.php">Debug Registration System</a></p>
</body>
</html>