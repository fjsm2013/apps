<?php
/**
 * Step 1: Configuración de Empresa
 */

// Obtener datos actuales de la empresa
$empresaData = $stepData['empresa'] ?? [];
$isCompleted = !empty($empresaData['nombre']);
?>

<div class="wizard-step">
    <div class="text-center mb-4">
        <i class="fas fa-building text-primary" style="font-size: 3rem;"></i>
        <h3 class="mt-3">Configuración de tu Empresa</h3>
        <p class="text-muted">Configura la información básica de tu lavadero para personalizar el sistema</p>
    </div>

    <form id="wizardForm" method="POST">
        <!-- Información Pre-llenada -->
        <?php if (!empty($empresaData['nombre']) && empty($empresaData['id'])): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Datos Pre-llenados:</strong> Hemos completado algunos campos con la información de tu cuenta. 
            Puedes modificar cualquier dato según las necesidades específicas de este lavadero.
        </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="wizard-card <?= $isCompleted ? 'completed' : 'required' ?>">
                    <h5><i class="fas fa-info-circle me-2"></i>Información Básica</h5>
                    
                    <div class="mb-3">
                        <label class="form-label required">Nombre del Lavadero</label>
                        <input type="text" name="nombre_empresa" class="form-control" 
                               value="<?= htmlspecialchars($empresaData['nombre'] ?? '') ?>" 
                               placeholder="Ej: AutoLimpio Express" required>
                        <small class="text-muted">Puedes usar el nombre de tu empresa o uno específico para este lavadero</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Eslogan/Descripción</label>
                        <input type="text" name="eslogan" class="form-control" 
                               value="<?= htmlspecialchars($empresaData['eslogan'] ?? '') ?>" 
                               placeholder="Ej: El mejor lavado de la ciudad">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required">Teléfono Principal</label>
                        <input type="tel" name="telefono" class="form-control" 
                               value="<?= htmlspecialchars($empresaData['telefono'] ?? '') ?>" 
                               placeholder="+506 8888 8888" required>
                        <?php if (!empty($empresaData['telefono'])): ?>
                        <small class="text-muted">📞 Tomado de tu cuenta principal</small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email de Contacto</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($empresaData['email'] ?? '') ?>" 
                               placeholder="contacto@milavadero.com">
                        <?php if (!empty($empresaData['email'])): ?>
                        <small class="text-muted">📧 Tomado de tu cuenta principal</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="wizard-card">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>Ubicación y Horarios</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Dirección Completa</label>
                        <textarea name="direccion" class="form-control" rows="3" 
                                  placeholder="Dirección completa del lavadero"><?= htmlspecialchars($empresaData['direccion'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Hora Apertura</label>
                            <input type="time" name="hora_apertura" class="form-control" 
                                   value="<?= $empresaData['hora_apertura'] ?? '08:00' ?>">
                            <?php if (!empty($empresaData['hora_apertura']) && $empresaData['hora_apertura'] != '08:00'): ?>
                            <small class="text-muted">🕐 Horario de tu empresa</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hora Cierre</label>
                            <input type="time" name="hora_cierre" class="form-control" 
                                   value="<?= $empresaData['hora_cierre'] ?? '18:00' ?>">
                            <?php if (!empty($empresaData['hora_cierre']) && $empresaData['hora_cierre'] != '18:00'): ?>
                            <small class="text-muted">🕕 Horario de tu empresa</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Días Laborales</label>
                        <div class="row">
                            <?php 
                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                            $diasSeleccionados = explode(',', $empresaData['dias_laborales'] ?? 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado');
                            ?>
                            <?php foreach ($dias as $dia): ?>
                            <div class="col-6 col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="dias_laborales[]" 
                                           value="<?= $dia ?>" id="dia_<?= $dia ?>"
                                           <?= in_array($dia, $diasSeleccionados) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="dia_<?= $dia ?>">
                                        <?= $dia ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="wizard-card">
                    <h5><i class="fas fa-cogs me-2"></i>Configuración Operativa</h5>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Capacidad Máxima Diaria</label>
                            <input type="number" name="capacidad_maxima" class="form-control" 
                                   value="<?= $empresaData['capacidad_maxima'] ?? '50' ?>" 
                                   placeholder="50" min="1">
                            <small class="text-muted">
                                Número máximo de vehículos por día
                                <?php if (!empty($empresaData['capacidad_maxima']) && $empresaData['capacidad_maxima'] != '50'): ?>
                                <br>🎯 Configurado según tu tipo de negocio
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Tiempo Promedio por Servicio</label>
                            <input type="number" name="tiempo_promedio" class="form-control" 
                                   value="<?= $empresaData['tiempo_promedio'] ?? '30' ?>" 
                                   placeholder="30" min="5">
                            <small class="text-muted">
                                Minutos por servicio básico
                                <?php if (!empty($empresaData['tiempo_promedio']) && $empresaData['tiempo_promedio'] != '30'): ?>
                                <br>⏱️ Optimizado para tu tipo de lavadero
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Moneda</label>
                            <select name="moneda" class="form-select">
                                <option value="CRC" <?= ($empresaData['moneda'] ?? 'CRC') === 'CRC' ? 'selected' : '' ?>>Colones (₡)</option>
                                <option value="USD" <?= ($empresaData['moneda'] ?? '') === 'USD' ? 'selected' : '' ?>>Dólares ($)</option>
                                <option value="EUR" <?= ($empresaData['moneda'] ?? '') === 'EUR' ? 'selected' : '' ?>>Euros (€)</option>
                            </select>
                            <?php if (!empty($empresaData['moneda']) && $empresaData['moneda'] != 'CRC'): ?>
                            <small class="text-muted">💰 Detectado según tu ubicación</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($isCompleted): ?>
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle me-2"></i>
            <strong>¡Configuración de empresa completada!</strong> 
            Puedes continuar al siguiente paso o modificar la información si es necesario.
        </div>
        <?php endif; ?>
    </form>
</div>