<?php
/**
 * Step 4: Configuración de Usuarios
 */

// Obtener datos actuales
$usuarios = $stepData['usuarios'] ?? [];
$roles = $stepData['roles'] ?? [];
$currentUser = userInfo();
$hasAdditionalUsers = count($usuarios) > 1; // Más que solo el admin actual

// Debug temporal para ver la estructura del usuario
// error_log("DEBUG currentUser: " . print_r($currentUser, true));
?>

<div class="wizard-step">
    <div class="text-center mb-4">
        <i class="fas fa-users text-primary" style="font-size: 3rem;"></i>
        <h3 class="mt-3">Configuración de Usuarios</h3>
        <p class="text-muted">Configura tu equipo de trabajo y sus permisos</p>
    </div>

    <form id="wizardForm" method="POST">
        <input type="hidden" name="action" value="save_usuarios">
        
        <!-- Usuario Actual (Admin) -->
        <div class="wizard-card mb-4">
            <h5><i class="fas fa-user-shield me-2"></i>Administrador Principal</h5>
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <?php 
                            // Manejar diferentes estructuras de datos de usuario
                            $userName = '';
                            $userEmail = '';
                            
                            if (is_array($currentUser)) {
                                $userName = $currentUser['name'] ?? $currentUser['Nombre'] ?? $currentUser['nombre'] ?? 'Usuario';
                                $userEmail = $currentUser['email'] ?? $currentUser['Email'] ?? $currentUser['correo'] ?? 'email@ejemplo.com';
                            } else {
                                $userName = 'Usuario';
                                $userEmail = 'email@ejemplo.com';
                            }
                            ?>
                            <h6 class="mb-1"><?= htmlspecialchars($userName) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($userEmail) ?></small>
                            <br><span class="badge bg-primary">Administrador</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <span class="text-success">
                        <i class="fas fa-check-circle me-1"></i>Configurado en Sistema Principal
                    </span>
                </div>
            </div>
            <div class="mt-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los usuarios se gestionan a nivel del sistema principal FROSH. 
                    Tu cuenta de administrador ya está configurada y puede acceder a todos los lavaderos de tu empresa.
                </div>
            </div>
        </div>

        <!-- Gestión de Usuarios -->
        <?php /* Comentado temporalmente
        <div class="wizard-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-users-cog me-2"></i>Gestión de Usuarios</h5>
            </div>
            
            <div class="alert alert-warning mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Gestión Centralizada de Usuarios</strong><br>
                Los usuarios se gestionan desde el sistema principal FROSH, no desde cada lavadero individual. 
                Para agregar más usuarios a tu empresa, ve a la sección de Administración después de completar la configuración.
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-user-plus text-primary"></i>
                            <h6>Agregar Usuarios</h6>
                        </div>
                        <p class="text-muted">
                            Después de completar la configuración, podrás invitar usuarios desde:
                        </p>
                        <ul class="info-list">
                            <li><i class="fas fa-arrow-right text-primary"></i> Panel de Administración</li>
                            <li><i class="fas fa-arrow-right text-primary"></i> Gestión de Usuarios</li>
                            <li><i class="fas fa-arrow-right text-primary"></i> Configuración de Permisos</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-building text-info"></i>
                            <h6>Acceso Multi-Lavadero</h6>
                        </div>
                        <p class="text-muted">
                            Los usuarios pueden tener acceso a múltiples lavaderos de tu empresa:
                        </p>
                        <ul class="info-list">
                            <li><i class="fas fa-check text-success"></i> Un solo login para todos los lavaderos</li>
                            <li><i class="fas fa-check text-success"></i> Permisos configurables por ubicación</li>
                            <li><i class="fas fa-check text-success"></i> Gestión centralizada</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        */ ?>

        <!-- Roles y Permisos -->
        <div class="wizard-card mb-4">
            <h5><i class="fas fa-shield-alt me-2"></i>Roles y Permisos</h5>
            <p class="text-muted">Información sobre los permisos de cada rol</p>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="role-card">
                        <div class="role-header">
                            <i class="fas fa-user-tie text-primary"></i>
                            <h6>Supervisor</h6>
                        </div>
                        <ul class="role-permissions">
                            <li><i class="fas fa-check text-success"></i> Ver todas las órdenes</li>
                            <li><i class="fas fa-check text-success"></i> Crear y editar órdenes</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar clientes</li>
                            <li><i class="fas fa-check text-success"></i> Ver reportes</li>
                            <li><i class="fas fa-times text-danger"></i> Configuración del sistema</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="role-card">
                        <div class="role-header">
                            <i class="fas fa-user text-info"></i>
                            <h6>Empleado</h6>
                        </div>
                        <ul class="role-permissions">
                            <li><i class="fas fa-check text-success"></i> Ver órdenes asignadas</li>
                            <li><i class="fas fa-check text-success"></i> Actualizar estado de órdenes</li>
                            <li><i class="fas fa-check text-success"></i> Ver información de clientes</li>
                            <li><i class="fas fa-times text-danger"></i> Crear nuevas órdenes</li>
                            <li><i class="fas fa-times text-danger"></i> Ver reportes</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="role-card">
                        <div class="role-header">
                            <i class="fas fa-cash-register text-warning"></i>
                            <h6>Cajero</h6>
                        </div>
                        <ul class="role-permissions">
                            <li><i class="fas fa-check text-success"></i> Crear órdenes</li>
                            <li><i class="fas fa-check text-success"></i> Procesar pagos</li>
                            <li><i class="fas fa-check text-success"></i> Gestionar clientes</li>
                            <li><i class="fas fa-check text-success"></i> Ver reportes de ventas</li>
                            <li><i class="fas fa-times text-danger"></i> Configuración del sistema</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración de Notificaciones -->
        <?php /* Comentado temporalmente
        <div class="wizard-card">
            <h5><i class="fas fa-bell me-2"></i>Notificaciones del Equipo</h5>
            <p class="text-muted">Configura cómo se notificarán los cambios importantes</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="notificaciones[nuevas_ordenes]" 
                               id="notif_ordenes" checked>
                        <label class="form-check-label" for="notif_ordenes">
                            <strong>Nuevas Órdenes</strong><br>
                            <small class="text-muted">Notificar al equipo cuando se cree una nueva orden</small>
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="notificaciones[ordenes_completadas]" 
                               id="notif_completadas" checked>
                        <label class="form-check-label" for="notif_completadas">
                            <strong>Órdenes Completadas</strong><br>
                            <small class="text-muted">Notificar cuando una orden sea completada</small>
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6 mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="notificaciones[reportes_diarios]" 
                               id="notif_reportes">
                        <label class="form-check-label" for="notif_reportes">
                            <strong>Reportes Diarios</strong><br>
                            <small class="text-muted">Enviar resumen diario por email</small>
                        </label>
                    </div>
                </div>
                
                <div class="col-md-6 mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="notificaciones[alertas_sistema]" 
                               id="notif_alertas" checked>
                        <label class="form-check-label" for="notif_alertas">
                            <strong>Alertas del Sistema</strong><br>
                            <small class="text-muted">Notificar problemas técnicos o errores</small>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        */ ?>
        
        <!-- Mensaje de Finalización -->
        <div class="alert alert-success mt-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-flag-checkered me-3" style="font-size: 2rem;"></i>
                <div>
                    <h6 class="alert-heading mb-1">¡Configuración Inicial Completa!</h6>
                    <p class="mb-0">
                        Tu lavadero está listo para empezar a funcionar. 
                        Puedes configurar usuarios adicionales y notificaciones más tarde desde la administración.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Since users are managed centrally, we don't need the add/remove user functionality
    // Just show informational content
    console.log('Setup wizard step 4 loaded - Users managed centrally');
});
</script>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 1.2rem;
}

.info-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    height: 100%;
    transition: all 0.3s ease;
}

.info-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.info-header {
    text-align: center;
    margin-bottom: 15px;
}

.info-header i {
    font-size: 2rem;
    margin-bottom: 8px;
    display: block;
}

.info-header h6 {
    margin: 0;
    font-weight: 600;
}

.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    padding: 5px 0;
    font-size: 14px;
}

.info-list i {
    width: 16px;
    margin-right: 8px;
}

.role-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    height: 100%;
    transition: all 0.3s ease;
}

.role-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.role-header {
    text-align: center;
    margin-bottom: 15px;
}

.role-header i {
    font-size: 2rem;
    margin-bottom: 8px;
    display: block;
}

.role-header h6 {
    margin: 0;
    font-weight: 600;
}

.role-permissions {
    list-style: none;
    padding: 0;
    margin: 0;
}

.role-permissions li {
    padding: 5px 0;
    font-size: 14px;
}

.role-permissions i {
    width: 16px;
    margin-right: 8px;
}

.form-check-label strong {
    display: block;
    margin-bottom: 2px;
}
</style>