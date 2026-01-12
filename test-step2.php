<?php
/**
 * Test Step 2 Registration Process
 */
session_start();
require_once 'lib/config.php';

// Set up test session data
$_SESSION['registration_company_id'] = 3; // Use existing company
$_SESSION['registration_company_name'] = 'Test Company';
$_SESSION['registration_db_name'] = 'test_db';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_admin'])) {
    echo "<h3>Processing Step 2 Registration...</h3>";
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    echo "Received data:<br>";
    echo "- Name: $name<br>";
    echo "- Email: $email<br>";
    echo "- Username: $username<br>";
    echo "- Password length: " . strlen($password) . "<br>";
    echo "- Company ID: " . $_SESSION['registration_company_id'] . "<br>";
    
    // Validation
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $error = "Todos los campos son requeridos";
        echo "❌ Validation failed: Missing fields<br>";
    } elseif ($password !== $confirmPassword) {
        $error = "Las contraseñas no coinciden";
        echo "❌ Validation failed: Password mismatch<br>";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres";
        echo "❌ Validation failed: Password too short<br>";
    } else {
        echo "✅ Basic validation passed<br>";
        
        // Check if email already exists
        echo "Checking if email exists...<br>";
        $checkUser = ObtenerPrimerRegistro(
            $conn,
            "SELECT id FROM usuarios WHERE email = ?",
            [$email]
        );
        
        if ($checkUser) {
            $error = "Ya existe un usuario con este email";
            echo "❌ Email already exists<br>";
        } else {
            echo "✅ Email is unique<br>";
            
            try {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $companyId = $_SESSION['registration_company_id'];
                
                echo "Creating user...<br>";
                echo "- Company ID: $companyId<br>";
                echo "- Password hash length: " . strlen($passwordHash) . "<br>";
                
                // Create admin user
                $userId = EjecutarSQL(
                    $conn,
                    "INSERT INTO usuarios (id_empresa, name, email, user_name, password, permiso, estado) 
                     VALUES (?, ?, ?, ?, ?, 1, 'activo')",
                    [$companyId, $name, $email, $username, $passwordHash]
                );
                
                if ($userId) {
                    echo "✅ User created successfully with ID: $userId<br>";
                    
                    // Create default subscription (Bronze plan)
                    $startDate = date('Y-m-d');
                    $endDate = date('Y-m-d', strtotime('+30 days'));
                    
                    echo "Creating subscription...<br>";
                    echo "- Start date: $startDate<br>";
                    echo "- End date: $endDate<br>";
                    
                    $subscriptionResult = EjecutarSQL(
                        $conn,
                        "INSERT INTO suscripciones (id_empresa, id_plan, fecha_inicio, fecha_fin, estado, precio_actual) 
                         VALUES (?, 1, ?, ?, 'activa', 0.00)",
                        [$companyId, $startDate, $endDate]
                    );
                    
                    if ($subscriptionResult) {
                        echo "✅ Subscription created successfully<br>";
                        
                        // Update company status to active
                        echo "Updating company status...<br>";
                        $companyUpdateResult = EjecutarSQL(
                            $conn,
                            "UPDATE empresas SET estado = 'activo' WHERE id_empresa = ?",
                            [$companyId]
                        );
                        
                        if ($companyUpdateResult) {
                            echo "✅ Company status updated<br>";
                            
                            $success = "¡Registro completado exitosamente! Ya puede iniciar sesión.";
                            echo "<h3>✅ REGISTRATION SUCCESSFUL!</h3>";
                            echo "<p>User ID: $userId</p>";
                            echo "<p>All steps completed successfully.</p>";
                            
                            // Don't clear session for testing
                            // unset($_SESSION['registration_company_id']);
                            
                        } else {
                            $error = "Error al actualizar el estado de la empresa.";
                            echo "❌ Failed to update company status<br>";
                        }
                    } else {
                        $error = "Error al crear la suscripción.";
                        echo "❌ Failed to create subscription<br>";
                    }
                } else {
                    $error = "Error al crear el usuario administrador.";
                    echo "❌ Failed to create user<br>";
                }
                
            } catch (Exception $e) {
                $error = "Error en el registro: " . $e->getMessage();
                echo "❌ Exception: " . $e->getMessage() . "<br>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Step 2 Registration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; padding: 10px; background: #ffe6e6; border: 1px solid red; margin: 10px 0; }
        .success { color: green; padding: 10px; background: #e6ffe6; border: 1px solid green; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Test Step 2 Registration</h1>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Nombre Completo:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? 'Test Admin User') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? 'testadmin' . time() . '@example.com') ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nombre de Usuario:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? 'testadmin' . time()) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" value="TestPassword123!" required>
        </div>
        
        <div class="form-group">
            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirm_password" value="TestPassword123!" required>
        </div>
        
        <button type="submit" name="register_admin">Crear Usuario Administrador</button>
    </form>
    
    <hr>
    <p><a href="register.php">Back to Registration</a></p>
    <p><a href="debug-registration.php">Debug Registration</a></p>
</body>
</html>