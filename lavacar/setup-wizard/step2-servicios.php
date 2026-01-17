<?php
/**
 * Step 2: Configuración de Servicios
 */

// Obtener datos actuales
$servicios = $stepData['servicios'] ?? [];
$hasServices = count($servicios) > 0;
?>

<div class="wizard-step">
    <div class="text-center mb-4">
        <i class="fas fa-tools text-primary" style="font-size: 3rem;"></i>
        <h3 class="mt-3">Configuración de Servicios</h3>
        <p class="text-muted">Selecciona los servicios individuales que ofreces en tu lavadero</p>
    </div>

    <?php if ($hasServices): ?>
    <div class="alert alert-success mb-4">
        <i class="fas fa-check-circle me-2"></i>
        <strong>¡Servicios configurados!</strong> 
        Tienes <?= count($servicios) ?> servicios activos. Puedes agregar más o continuar al siguiente paso.
    </div>
    <?php endif; ?>

    <form id="wizardForm" method="POST">
        <input type="hidden" name="action" value="save_services">
        
        <!-- Servicios Individuales Recomendados -->
        <div class="wizard-card">
            <h5><i class="fas fa-list-check me-2"></i>Servicios Disponibles</h5>
            <p class="text-muted">Servicios precargados y sugerencias adicionales para tu lavadero</p>
            
            <div class="row">
                <?php 
                $serviciosRecomendados = [
                    // Servicios ya precargados (marcados por defecto)
                    ['descripcion' => 'Lavado Exterior', 'detalle' => 'Lavado de la carrocería externa', 'precargado' => true],
                    ['descripcion' => 'Limpieza Interior', 'detalle' => 'Limpieza completa del interior del vehículo', 'precargado' => true],
                    ['descripcion' => 'Lavado Chasis', 'detalle' => 'Limpieza del chasis y bajos del vehículo', 'precargado' => true],
                    // Servicios sugeridos adicionales
                    ['descripcion' => 'Encerado', 'detalle' => 'Aplicación de cera protectora', 'precargado' => false],
                    ['descripcion' => 'Pulido de Vidrios', 'detalle' => 'Pulido y limpieza especializada de vidrios', 'precargado' => false]
                ];
                ?>
                
                <?php foreach ($serviciosRecomendados as $index => $servicio): ?>
                <?php 
                // Verificar si ya existe en la base de datos
                $yaExiste = false;
                $tieneDetalles = false;
                foreach ($servicios as $servicioExistente) {
                    if ($servicioExistente['Descripcion'] === $servicio['descripcion']) {
                        $yaExiste = true;
                        $tieneDetalles = !empty($servicioExistente['Detalles']);
                        break;
                    }
                }
                
                $debeEstarMarcado = $servicio['precargado'] || $yaExiste;
                $esPrecargado = $servicio['precargado'];
                $esSugerido = in_array($servicio['descripcion'], ['Encerado', 'Pulido de Vidrios']);
                ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card service-card <?= $debeEstarMarcado ? 'selected' : '' ?> <?= $esPrecargado ? 'precargado' : '' ?> <?= $esSugerido ? 'sugerido' : '' ?>">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="servicios_seleccionados[]" 
                                       value="<?= $index ?>" 
                                       id="servicio_<?= $index ?>"
                                       <?= $debeEstarMarcado ? 'checked' : '' ?>
                                       <?= $esPrecargado ? 'data-precargado="true"' : '' ?>>
                                <label class="form-check-label w-100" for="servicio_<?= $index ?>">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0"><?= $servicio['descripcion'] ?></h6>
                                        <?php if ($esPrecargado): ?>
                                        <span class="badge bg-success">Precargado</span>
                                        <?php elseif ($esSugerido): ?>
                                        <span class="badge bg-warning">Sugerido</span>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted"><?= $servicio['detalle'] ?></small>
                                    <?php if ($yaExiste): ?>
                                    <div class="mt-1">
                                        <?php if ($tieneDetalles): ?>
                                        <small class="text-success"><i class="fas fa-check-circle"></i> Ya configurado</small>
                                        <?php else: ?>
                                        <small class="text-info"><i class="fas fa-info-circle"></i> Se agregarán detalles</small>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Información sobre servicios precargados -->
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Servicios Precargados:</strong> Los servicios marcados con "Precargado" son esenciales para tu lavadero. 
                Los marcados con "Sugerido" son recomendaciones adicionales populares.
                <br><small class="text-muted">Si un servicio ya existe pero no tiene detalles, se actualizará automáticamente.</small>
            </div>
        </div>

        <!-- Resumen de Servicios Actuales -->
        <?php if ($hasServices): ?>
        <div class="wizard-card mt-4">
            <h5><i class="fas fa-list me-2"></i>Servicios Actuales</h5>
            <div class="row">
                <?php foreach ($servicios as $servicio): ?>
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="d-flex align-items-center p-2 bg-light rounded">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span><?= htmlspecialchars($servicio['Descripcion']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Información Importante -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Configuración Opcional:</strong> Los precios pueden configurarse ahora o más tarde desde la administración. 
            Puedes continuar al siguiente paso sin establecer precios y configurarlos posteriormente.
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marcar/desmarcar tarjetas de servicios
    document.querySelectorAll('.service-card .form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.service-card');
            if (this.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    });
});
</script>

<style>
.service-card {
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid #e9ecef;
    height: 100%;
}

.service-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.service-card.selected {
    border-color: #198754;
    background-color: #f8fff9;
}

.service-card .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

/* Estilos para servicios precargados */
.service-card.precargado {
    border-color: #10b981;
    background-color: #f0fdf4;
}

.service-card.precargado:hover {
    border-color: #059669;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}

.service-card.precargado.selected {
    border-color: #059669;
    background-color: #ecfdf5;
}

/* Estilos para servicios sugeridos */
.service-card.sugerido {
    border-color: #D3AF37;
    background-color: #fffbeb;
}

.service-card.sugerido:hover {
    border-color: #b8941f;
    box-shadow: 0 2px 8px rgba(211, 175, 55, 0.2);
}

.service-card.sugerido.selected {
    border-color: #b8941f;
    background-color: #fef3c7;
}

/* Badges para tipos de servicios */
.badge.bg-success {
    background-color: #10b981 !important;
}

.badge.bg-warning {
    background-color: #D3AF37 !important;
    color: #1f2937 !important;
}

.servicio-personalizado {
    background-color: #f8f9fa;
}
</style>