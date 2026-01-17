<?php
/**
 * Step 3: Configuración de Precios
 */

// Obtener datos actuales
$servicios = $stepData['servicios'] ?? [];
$tiposVehiculo = $stepData['tipos_vehiculo'] ?? [];
$precios = $stepData['precios'] ?? [];
$hasPrecios = count($precios) > 0;
?>

<div class="wizard-step">
    <div class="text-center mb-4">
        <i class="fas fa-dollar-sign text-primary" style="font-size: 3rem;"></i>
        <h3 class="mt-3">Configuración de Precios</h3>
        <p class="text-muted">Define el precio de cada servicio según el tipo de vehículo</p>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Paso Opcional:</strong> Puedes configurar los precios ahora o más tarde. 
            Haz clic en "Siguiente" para continuar sin configurar precios.
        </div>
    </div>

    <?php if (empty($servicios)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>No hay servicios configurados.</strong> 
        Necesitas configurar servicios antes de establecer precios.
        <a href="setup-wizard.php?step=2" class="btn btn-warning btn-sm ms-2">Volver a Servicios</a>
    </div>
    <?php elseif (empty($tiposVehiculo)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>No hay tipos de vehículo configurados.</strong> 
        Necesitas configurar los tipos de vehículo en la administración antes de establecer precios.
    </div>
    <?php else: ?>

    <?php if ($hasPrecios): ?>
    <div class="alert alert-success mb-4">
        <i class="fas fa-check-circle me-2"></i>
        <strong>¡Precios configurados!</strong> 
        Tienes precios para <?= count($precios) ?> combinaciones servicio-vehículo. Puedes modificarlos si es necesario.
    </div>
    <?php endif; ?>

    <form id="wizardForm" method="POST">
        <input type="hidden" name="action" value="save_precios">
        
        <!-- Matriz de Precios -->
        <div class="wizard-card">
            <h5><i class="fas fa-table me-2"></i>Matriz de Precios por Servicio y Tipo de Vehículo</h5>
            <p class="text-muted">Establece el precio de cada servicio individual según el tipo de vehículo</p>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Servicio</th>
                            <?php foreach ($tiposVehiculo as $tipo): ?>
                            <th class="text-center"><?= htmlspecialchars($tipo['TipoVehiculo']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicios as $servicio): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($servicio['Descripcion']) ?></strong>
                            </td>
                            <?php foreach ($tiposVehiculo as $tipo): ?>
                            <?php 
                            // Buscar precio existente
                            $precioActual = '';
                            foreach ($precios as $precio) {
                                if ($precio['ServicioID'] == $servicio['ID'] && $precio['TipoCategoriaID'] == $tipo['ID']) {
                                    $precioActual = $precio['Precio'];
                                    break;
                                }
                            }
                            ?>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">₡</span>
                                    <input type="number" 
                                           name="precios[<?= $servicio['ID'] ?>][<?= $tipo['ID'] ?>]" 
                                           class="form-control text-end" 
                                           value="<?= $precioActual ?>"
                                           placeholder="0" 
                                           step="500">
                                </div>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Herramientas de Ayuda -->
        <div class="wizard-card mt-4">
            <h5><i class="fas fa-tools me-2"></i>Herramientas de Configuración Rápida</h5>
            <p class="text-muted">Usa estas herramientas para configurar precios más rápidamente</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6><i class="fas fa-copy me-2"></i>Copiar Precios por Fila</h6>
                            <p class="text-muted small">Establece el mismo precio para todos los tipos de vehículo de un servicio</p>
                            <div class="input-group input-group-sm">
                                <select class="form-select" id="servicioToCopy">
                                    <option value="">Seleccionar servicio</option>
                                    <?php foreach ($servicios as $servicio): ?>
                                    <option value="<?= $servicio['ID'] ?>"><?= htmlspecialchars($servicio['Descripcion']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" class="form-control" id="precioToCopy" placeholder="Precio" step="500">
                                <button type="button" class="btn btn-outline-primary" onclick="copyPriceToRow()">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6><i class="fas fa-percentage me-2"></i>Aplicar Factor por Columna</h6>
                            <p class="text-muted small">Multiplica todos los precios de un tipo de vehículo por un factor</p>
                            <div class="input-group input-group-sm">
                                <select class="form-select" id="tipoToFactor">
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tiposVehiculo as $tipo): ?>
                                    <option value="<?= $tipo['ID'] ?>"><?= htmlspecialchars($tipo['TipoVehiculo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" class="form-control" id="factorToApply" placeholder="Factor" step="0.1" value="1.0">
                                <button type="button" class="btn btn-outline-success" onclick="applyFactorToColumn()">
                                    <i class="fas fa-calculator"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Configuración -->
        <div class="wizard-card mt-4">
            <h5><i class="fas fa-chart-bar me-2"></i>Resumen de Precios</h5>
            <div class="row" id="preciosSummary">
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">Servicios</h6>
                        <h4 class="text-primary"><?= count($servicios) ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">Tipos de Vehículo</h6>
                        <h4 class="text-info"><?= count($tiposVehiculo) ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">Precios Configurados</h6>
                        <h4 class="text-success" id="preciosConfigurados">0</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h6 class="text-muted">Precio Promedio</h6>
                        <h4 class="text-warning" id="precioPromedio">₡0</h4>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar resumen cuando cambian los precios
    document.querySelectorAll('input[name^="precios"]').forEach(input => {
        input.addEventListener('input', updateSummary);
    });
    
    // Actualizar resumen inicial
    updateSummary();
});

function copyPriceToRow() {
    const servicioId = document.getElementById('servicioToCopy').value;
    const precio = document.getElementById('precioToCopy').value;
    
    if (!servicioId || !precio) {
        alert('Selecciona un servicio y establece un precio');
        return;
    }
    
    // Copiar precio a todas las columnas de esa fila
    document.querySelectorAll(`input[name^="precios[${servicioId}]"]`).forEach(input => {
        input.value = precio;
    });
    
    updateSummary();
}

function applyFactorToColumn() {
    const tipoId = document.getElementById('tipoToFactor').value;
    const factor = parseFloat(document.getElementById('factorToApply').value);
    
    if (!tipoId || !factor) {
        alert('Selecciona un tipo de vehículo y establece un factor');
        return;
    }
    
    // Aplicar factor a todas las filas de esa columna
    document.querySelectorAll(`input[name$="[${tipoId}]"]`).forEach(input => {
        const currentValue = parseFloat(input.value) || 0;
        if (currentValue > 0) {
            input.value = Math.round(currentValue * factor);
        }
    });
    
    updateSummary();
}

function updateSummary() {
    let preciosConfigurados = 0;
    let totalPrecios = 0;
    let sumaPrecios = 0;
    
    document.querySelectorAll('input[name^="precios"]').forEach(input => {
        const valor = parseFloat(input.value) || 0;
        totalPrecios++;
        if (valor > 0) {
            preciosConfigurados++;
            sumaPrecios += valor;
        }
    });
    
    const promedio = preciosConfigurados > 0 ? Math.round(sumaPrecios / preciosConfigurados) : 0;
    
    document.getElementById('preciosConfigurados').textContent = preciosConfigurados;
    document.getElementById('precioPromedio').textContent = `₡${promedio.toLocaleString()}`;
}
</script>

<style>
.input-group-sm .form-control {
    font-size: 0.875rem;
}

.table th, .table td {
    vertical-align: middle;
}

.input-group-text {
    background-color: #e9ecef;
    border-color: #ced4da;
    font-size: 0.875rem;
}

.card.border-primary {
    border-width: 2px;
}

.card.border-success {
    border-width: 2px;
}
</style>