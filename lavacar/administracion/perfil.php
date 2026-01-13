<?php
session_start();

require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: ../../login.php");
    exit;
}

$user = userInfo();

// Usar la base de datos principal (frosh_lavacar) para usuarios
$masterConn = $conn; // Esta es la conexión principal

$message = '';
$messageType = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userId = $user['id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $userName = trim($_POST['user_name']);
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validaciones básicas
        if (empty($name) || empty($email) || empty($userName)) {
            throw new Exception('Todos los campos obligatorios deben estar completos');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del email no es válido');
        }
        
        // Verificar si el email ya existe para otro usuario
        $stmt = $masterConn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ? AND id_empresa = ?");
        $stmt->bind_param("sii", $email, $userId, $user['company']['id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception('Este email ya está en uso por otro usuario');
        }
        
        // Verificar si el username ya existe para otro usuario
        $stmt = $masterConn->prepare("SELECT id FROM usuarios WHERE user_name = ? AND id != ? AND id_empresa = ?");
        $stmt->bind_param("sii", $userName, $userId, $user['company']['id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception('Este nombre de usuario ya está en uso');
        }
        
        // Si se quiere cambiar la contraseña
        $updatePassword = false;
        $hashedPassword = '';
        
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                throw new Exception('Debe ingresar su contraseña actual para cambiarla');
            }
            
            // Verificar contraseña actual
            $stmt = $masterConn->prepare("SELECT password FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userData = $result->fetch_assoc();
            
            if (!password_verify($currentPassword, $userData['password'])) {
                throw new Exception('La contraseña actual es incorrecta');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Las contraseñas nuevas no coinciden');
            }
            
            if (strlen($newPassword) < 6) {
                throw new Exception('La nueva contraseña debe tener al menos 6 caracteres');
            }
            
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePassword = true;
        }
        
        // Actualizar datos del usuario
        if ($updatePassword) {
            $stmt = $masterConn->prepare("UPDATE usuarios SET name = ?, email = ?, user_name = ?, password = ?, fecha_actualizacion = NOW() WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $userName, $hashedPassword, $userId);
        } else {
            $stmt = $masterConn->prepare("UPDATE usuarios SET name = ?, email = ?, user_name = ?, fecha_actualizacion = NOW() WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $userName, $userId);
        }
        
        if ($stmt->execute()) {
            $message = 'Perfil actualizado exitosamente';
            $messageType = 'success';
            
            // Actualizar la sesión con los nuevos datos
            $_SESSION['user_name'] = $userName;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_full_name'] = $name;
            
            // Recargar datos del usuario
            $user = userInfo();
        } else {
            throw new Exception('Error al actualizar el perfil');
        }
        
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

// Obtener datos actuales del usuario
$stmt = $masterConn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Define breadcrumbs
$breadcrumbs = array(
    array('title' => 'Administración', 'url' => 'index.php'),
    array('title' => 'Mi Perfil', 'url' => '')
);

require 'lavacar/partials/header.php';
?>

<?php include 'lavacar/partials/breadcrumb.php'; ?>

<div class="container container-fluid py-4">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2><i class="fa-solid fa-user-edit me-2"></i>Mi Perfil</h2>
                    <p class="text-muted mb-0">Administra tu información personal y configuración de cuenta</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-frosh-dark">
                        <i class="fa-solid fa-building me-1"></i>
                        <?= htmlspecialchars($user['company']['name']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if ($message): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
                <i class="fa-solid fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <!-- Información del Perfil -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-circle">
                            <i class="fa-solid fa-user fa-3x"></i>
                        </div>
                    </div>
                    <h5 class="card-title"><?= htmlspecialchars($userData['name']) ?></h5>
                    <p class="text-muted mb-2">@<?= htmlspecialchars($userData['user_name']) ?></p>
                    <p class="text-muted mb-3"><?= htmlspecialchars($userData['email']) ?></p>
                    
                    <div class="profile-stats">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-item">
                                    <h6 class="mb-0"><?= $userData['estado'] === 'activo' ? 'Activo' : 'Inactivo' ?></h6>
                                    <small class="text-muted">Estado</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <h6 class="mb-0"><?= $userData['ultimo_login'] ? date('d/m/Y', strtotime($userData['ultimo_login'])) : 'Nunca' ?></h6>
                                    <small class="text-muted">Último acceso</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <span class="badge <?= $userData['estado'] === 'activo' ? 'badge-frosh-success' : 'badge-frosh-light' ?>">
                            <i class="fa-solid fa-<?= $userData['estado'] === 'activo' ? 'check' : 'times' ?> me-1"></i>
                            <?= ucfirst($userData['estado']) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Información de la Cuenta -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Información de la Cuenta</h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <label class="form-label text-muted">Fecha de Registro</label>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($userData['fecha_creacion'])) ?></p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="form-label text-muted">Última Actualización</label>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($userData['fecha_actualizacion'])) ?></p>
                    </div>
                    <div class="info-item">
                        <label class="form-label text-muted">Empresa</label>
                        <p class="mb-0"><?= htmlspecialchars($user['company']['name']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-edit me-2"></i>Editar Perfil</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <!-- Información Personal -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fa-solid fa-user me-2"></i>Información Personal
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre de Usuario *</label>
                                <input type="text" class="form-control" name="user_name" value="<?= htmlspecialchars($userData['user_name']) ?>" required>
                                <small class="form-text text-muted">Este será tu identificador único en el sistema</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
                            </div>
                        </div>

                        <!-- Cambio de Contraseña -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fa-solid fa-lock me-2"></i>Cambiar Contraseña
                                    <small class="text-muted">(Opcional)</small>
                                </h6>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" name="current_password" placeholder="Ingresa tu contraseña actual">
                                <small class="form-text text-muted">Solo requerida si deseas cambiar tu contraseña</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" name="new_password" placeholder="Mínimo 6 caracteres">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" name="confirm_password" placeholder="Repite la nueva contraseña">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-frosh-light" onclick="window.location.reload()">
                                <i class="fa-solid fa-undo me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-frosh-primary">
                                <i class="fa-solid fa-save me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== PROFILE STYLES ===== */
.profile-avatar {
    position: relative;
    display: inline-block;
}

.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--report-info, #274AB3), #1e3a8a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin: 0 auto;
    box-shadow: 0 4px 20px rgba(39, 74, 179, 0.3);
}

.profile-stats {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.stat-item h6 {
    color: #1e293b;
    font-weight: 600;
}

.info-item label {
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 4px;
}

.info-item p {
    color: #374151;
    font-weight: 500;
}

/* ===== FROSH BADGES ===== */
.badge-frosh-success {
    background: var(--report-success, #10b981);
    color: white;
}

.badge-frosh-dark {
    background: var(--frosh-gray-800, #1f2937);
    color: white;
}

.badge-frosh-light {
    background: var(--frosh-gray-200, #e5e7eb);
    color: var(--frosh-gray-800, #1f2937);
}

/* ===== FORM ENHANCEMENTS ===== */
.form-control:focus {
    border-color: var(--report-info, #274AB3);
    box-shadow: 0 0 0 0.2rem rgba(39, 74, 179, 0.25);
}

.btn-frosh-primary {
    background: var(--report-info, #274AB3);
    border-color: var(--report-info, #274AB3);
    color: white;
    font-weight: 600;
}

.btn-frosh-primary:hover {
    background: #1e3a8a;
    border-color: #1e3a8a;
    color: white;
}

.btn-frosh-light {
    background: var(--frosh-gray-100, #f3f4f6);
    border-color: var(--frosh-gray-300, #d1d5db);
    color: var(--frosh-gray-800, #1f2937);
}

.btn-frosh-light:hover {
    background: var(--frosh-gray-200, #e5e7eb);
    border-color: var(--frosh-gray-400, #9ca3af);
    color: var(--frosh-gray-900, #111827);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .avatar-circle {
        width: 80px;
        height: 80px;
    }
    
    .avatar-circle i {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de contraseñas en tiempo real
    const newPassword = document.querySelector('input[name="new_password"]');
    const confirmPassword = document.querySelector('input[name="confirm_password"]');
    
    function validatePasswords() {
        if (newPassword.value && confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    }
    
    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
    
    // Validación de longitud de contraseña
    newPassword.addEventListener('input', function() {
        if (this.value && this.value.length < 6) {
            this.setCustomValidity('La contraseña debe tener al menos 6 caracteres');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php require 'lavacar/partials/footer.php'; ?>