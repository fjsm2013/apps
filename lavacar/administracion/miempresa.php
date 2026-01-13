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

// Usar la base de datos principal (frosh_lavacar) para empresas
$masterConn = $conn; // Esta es la conexión principal

$message = '';
$messageType = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $empresaId = $user['company']['id'];
        $nombre = trim($_POST['nombre']);
        $ruc = trim($_POST['ruc_identificacion']);
        $telefono = trim($_POST['telefono']);
        $email = trim($_POST['email']);
        $pais = trim($_POST['pais']);
        $ciudad = trim($_POST['ciudad']);
        
        // Validaciones básicas
        if (empty($nombre) || empty($email)) {
            throw new Exception('El nombre de la empresa y el email son obligatorios');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del email no es válido');
        }
        
        // Verificar si el email ya existe para otra empresa
        $stmt = $masterConn->prepare("SELECT id_empresa FROM empresas WHERE email = ? AND id_empresa != ?");
        $stmt->bind_param("si", $email, $empresaId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception('Este email ya está en uso por otra empresa');
        }
        
        // Actualizar datos de la empresa
        $stmt = $masterConn->prepare("UPDATE empresas SET nombre = ?, ruc_identificacion = ?, telefono = ?, email = ?, pais = ?, ciudad = ?, fecha_actualizacion = NOW() WHERE id_empresa = ?");
        $stmt->bind_param("ssssssi", $nombre, $ruc, $telefono, $email, $pais, $ciudad, $empresaId);
        
        if ($stmt->execute()) {
            $message = 'Información de la empresa actualizada exitosamente';
            $messageType = 'success';
            
            // Actualizar la sesión con los nuevos datos
            $_SESSION['company_name'] = $nombre;
            $_SESSION['company_email'] = $email;
            
            // Recargar datos del usuario
            $user = userInfo();
        } else {
            throw new Exception('Error al actualizar la información de la empresa');
        }
        
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

// Obtener datos actuales de la empresa
$stmt = $masterConn->prepare("SELECT * FROM empresas WHERE id_empresa = ?");
$stmt->bind_param("i", $user['company']['id']);
$stmt->execute();
$empresaData = $stmt->get_result()->fetch_assoc();

// Obtener información de suscripción
$stmt = $masterConn->prepare("
    SELECT s.*, p.nombre as plan_nombre, p.descripcion as plan_descripcion, p.precio_mensual, p.precio_anual
    FROM suscripciones s 
    LEFT JOIN planes p ON s.id_plan = p.id_plan 
    WHERE s.id_empresa = ? AND s.estado = 'activa'
    ORDER BY s.fecha_inicio DESC 
    LIMIT 1
");
$stmt->bind_param("i", $user['company']['id']);
$stmt->execute();
$suscripcionData = $stmt->get_result()->fetch_assoc();

// Obtener estadísticas de uso (desde la base de datos tenant)
$tenantDbName = $user['company']['db'];
$tenantConn = $conn; // Usar la misma conexión pero cambiar la base de datos

$stats = [
    'usuarios' => 0,
    'clientes' => 0,
    'vehiculos' => 0,
    'ordenes_mes' => 0
];

try {
    // Contar usuarios
    $stmt = $masterConn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE id_empresa = ? AND estado = 'activo'");
    $stmt->bind_param("i", $user['company']['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stats['usuarios'] = $result['total'];
    
    // Contar datos del tenant (si la base de datos existe)
    if ($tenantDbName) {
        $tenantConn->select_db($tenantDbName);
        
        // Clientes
        $result = $tenantConn->query("SELECT COUNT(*) as total FROM clientes WHERE active = 1");
        if ($result) {
            $stats['clientes'] = $result->fetch_assoc()['total'];
        }
        
        // Vehículos
        $result = $tenantConn->query("SELECT COUNT(*) as total FROM vehiculos WHERE active = 1");
        if ($result) {
            $stats['vehiculos'] = $result->fetch_assoc()['total'];
        }
        
        // Órdenes del mes actual
        $result = $tenantConn->query("SELECT COUNT(*) as total FROM ordenes WHERE MONTH(FechaIngreso) = MONTH(NOW()) AND YEAR(FechaIngreso) = YEAR(NOW())");
        if ($result) {
            $stats['ordenes_mes'] = $result->fetch_assoc()['total'];
        }
        
        // Volver a la base de datos principal
        $tenantConn->select_db('frosh_lavacar');
    }
} catch (Exception $e) {
    // Si hay error, mantener stats en 0
    error_log("Error obteniendo estadísticas: " . $e->getMessage());
}

// Define breadcrumbs
$breadcrumbs = array(
    array('title' => 'Administración', 'url' => 'index.php'),
    array('title' => 'Mi Empresa', 'url' => '')
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
                    <h2><i class="fa-solid fa-building me-2"></i>Mi Empresa</h2>
                    <p class="text-muted mb-0">Administra la información y configuración de tu empresa</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge <?= $empresaData['estado'] === 'activo' ? 'badge-frosh-success' : 'badge-frosh-warning' ?>">
                        <i class="fa-solid fa-<?= $empresaData['estado'] === 'activo' ? 'check' : 'clock' ?> me-1"></i>
                        <?= ucfirst($empresaData['estado']) ?>
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
        <!-- Información de la Empresa -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="company-avatar mb-3">
                        <div class="company-circle">
                            <i class="fa-solid fa-building fa-3x"></i>
                        </div>
                    </div>
                    <h5 class="card-title"><?= htmlspecialchars($empresaData['nombre']) ?></h5>
                    <p class="text-muted mb-2"><?= htmlspecialchars($empresaData['email']) ?></p>
                    <?php if ($empresaData['ciudad'] && $empresaData['pais']): ?>
                    <p class="text-muted mb-3">
                        <i class="fa-solid fa-map-marker-alt me-1"></i>
                        <?= htmlspecialchars($empresaData['ciudad']) ?>, <?= htmlspecialchars($empresaData['pais']) ?>
                    </p>
                    <?php endif; ?>
                    
                    <div class="company-stats">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-item">
                                    <h6 class="mb-0"><?= $stats['usuarios'] ?></h6>
                                    <small class="text-muted">Usuarios</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <h6 class="mb-0"><?= $stats['clientes'] ?></h6>
                                    <small class="text-muted">Clientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-6">
                                <div class="stat-item">
                                    <h6 class="mb-0"><?= $stats['vehiculos'] ?></h6>
                                    <small class="text-muted">Vehículos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <h6 class="mb-0"><?= $stats['ordenes_mes'] ?></h6>
                                    <small class="text-muted">Órdenes/Mes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información de Suscripción -->
            <?php if ($suscripcionData): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Plan Actual</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0"><?= htmlspecialchars($suscripcionData['plan_nombre']) ?></h5>
                            <small class="text-muted"><?= htmlspecialchars($suscripcionData['plan_descripcion']) ?></small>
                        </div>
                        <div class="text-end">
                            <h6 class="mb-0">$<?= number_format($suscripcionData['precio_mensual'], 2) ?></h6>
                            <small class="text-muted">/mes</small>
                        </div>
                    </div>
                    
                    <div class="subscription-info">
                        <div class="info-item mb-2">
                            <small class="text-muted">Fecha de inicio:</small>
                            <span class="float-end"><?= date('d/m/Y', strtotime($suscripcionData['fecha_inicio'])) ?></span>
                        </div>
                        <div class="info-item mb-2">
                            <small class="text-muted">Fecha de vencimiento:</small>
                            <span class="float-end"><?= date('d/m/Y', strtotime($suscripcionData['fecha_fin'])) ?></span>
                        </div>
                        <div class="info-item">
                            <small class="text-muted">Estado:</small>
                            <span class="badge badge-frosh-success float-end"><?= ucfirst($suscripcionData['estado']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Información Técnica -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-database me-2"></i>Información Técnica</h6>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <label class="form-label text-muted">ID de Empresa</label>
                        <p class="mb-0 font-monospace"><?= $empresaData['id_empresa'] ?></p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="form-label text-muted">Base de Datos</label>
                        <p class="mb-0 font-monospace"><?= htmlspecialchars($empresaData['nombre_base_datos']) ?></p>
                    </div>
                    <div class="info-item mb-3">
                        <label class="form-label text-muted">Fecha de Registro</label>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($empresaData['fecha_registro'])) ?></p>
                    </div>
                    <div class="info-item">
                        <label class="form-label text-muted">Última Actualización</label>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($empresaData['fecha_actualizacion'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Edición -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fa-solid fa-edit me-2"></i>Información de la Empresa</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <!-- Información Básica -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fa-solid fa-building me-2"></i>Información Básica
                                </h6>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nombre de la Empresa *</label>
                                <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($empresaData['nombre']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">RUC/Identificación</label>
                                <input type="text" class="form-control" name="ruc_identificacion" value="<?= htmlspecialchars($empresaData['ruc_identificacion']) ?>" placeholder="123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Corporativo *</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($empresaData['email']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" value="<?= htmlspecialchars($empresaData['telefono']) ?>" placeholder="+506 8888-8888">
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fa-solid fa-map-marker-alt me-2"></i>Ubicación
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">País</label>
                                <select class="form-select" name="pais">
                                    <option value="">Seleccionar país</option>
                                    <option value="Costa Rica" <?= $empresaData['pais'] === 'Costa Rica' ? 'selected' : '' ?>>Costa Rica</option>
                                    <option value="Guatemala" <?= $empresaData['pais'] === 'Guatemala' ? 'selected' : '' ?>>Guatemala</option>
                                    <option value="Nicaragua" <?= $empresaData['pais'] === 'Nicaragua' ? 'selected' : '' ?>>Nicaragua</option>
                                    <option value="Panamá" <?= $empresaData['pais'] === 'Panamá' ? 'selected' : '' ?>>Panamá</option>
                                    <option value="Honduras" <?= $empresaData['pais'] === 'Honduras' ? 'selected' : '' ?>>Honduras</option>
                                    <option value="El Salvador" <?= $empresaData['pais'] === 'El Salvador' ? 'selected' : '' ?>>El Salvador</option>
                                    <option value="México" <?= $empresaData['pais'] === 'México' ? 'selected' : '' ?>>México</option>
                                    <option value="Colombia" <?= $empresaData['pais'] === 'Colombia' ? 'selected' : '' ?>>Colombia</option>
                                    <option value="Otro" <?= !in_array($empresaData['pais'], ['Costa Rica', 'Guatemala', 'Nicaragua', 'Panamá', 'Honduras', 'El Salvador', 'México', 'Colombia']) && $empresaData['pais'] ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="ciudad" value="<?= htmlspecialchars($empresaData['ciudad']) ?>" placeholder="San José">
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
/* ===== COMPANY STYLES ===== */
.company-avatar {
    position: relative;
    display: inline-block;
}

.company-circle {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--report-info, #274AB3), #1e3a8a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin: 0 auto;
    box-shadow: 0 4px 20px rgba(39, 74, 179, 0.3);
}

.company-stats {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.stat-item h6 {
    color: #1e293b;
    font-weight: 600;
    font-size: 1.1rem;
}

.subscription-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.badge-frosh-warning {
    background: var(--report-warning, #D3AF37);
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
.form-control:focus,
.form-select:focus {
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

/* ===== CARDS ENHANCEMENTS ===== */
.card {
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    border-radius: 12px 12px 0 0 !important;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .company-circle {
        width: 80px;
        height: 80px;
        border-radius: 16px;
    }
    
    .company-circle i {
        font-size: 2rem;
    }
    
    .stat-item h6 {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de email en tiempo real
    const emailInput = document.querySelector('input[name="email"]');
    
    emailInput.addEventListener('input', function() {
        const email = this.value;
        if (email && !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            this.setCustomValidity('Por favor ingresa un email válido');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Formateo automático de teléfono
    const telefonoInput = document.querySelector('input[name="telefono"]');
    
    telefonoInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length >= 8) {
            // Formato para Costa Rica: +506 8888-8888
            if (value.startsWith('506')) {
                value = value.substring(3);
            }
            if (value.length === 8) {
                this.value = `+506 ${value.substring(0, 4)}-${value.substring(4)}`;
            }
        }
    });
});
</script>

<?php require 'lavacar/partials/footer.php'; ?>