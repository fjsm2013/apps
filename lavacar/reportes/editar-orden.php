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
$dbName = $user['company']['db'];

// Obtener ID de la orden
$ordenId = $_GET['id'] ?? null;
if (!$ordenId) {
    header("Location: ordenes-activas.php");
    exit;
}

// Obtener datos de la orden
$stmt = $conn->prepare("
    SELECT o.*, 
           c.NombreCompleto as ClienteNombre,
           c.Correo as ClienteCorreo,
           v.Placa, v.Marca, v.Modelo, v.Year, v.Color,
           cv.TipoVehiculo
    FROM `{$dbName}`.ordenes o
    LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
    LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
    LEFT JOIN `{$dbName}`.categoriavehiculo cv ON v.CategoriaVehiculo = cv.ID
    WHERE o.ID = ?
");

$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ordenes-activas.php");
    exit;
}

$orden = $result->fetch_assoc();

// Verificar que la orden no esté cerrada
if ($orden['Estado'] == 4) {
    $_SESSION['error'] = 'No se puede editar una orden cerrada';
    header("Location: ordenes-activas.php");
    exit;
}

// Obtener servicios de la orden
$stmt = $conn->prepare("
    SELECT os.*, s.Descripcion as ServicioNombre
    FROM `{$dbName}`.orden_servicios os
    LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
    WHERE os.OrdenID = ?
    ORDER BY os.ID
");
$stmt->bind_param("i", $ordenId);
$stmt->execute();
$serviciosResult = $stmt->get_result();

$servicios = [];
while ($servicio = $serviciosResult->fetch_assoc()) {
    $servicios[] = [
        'id' => $servicio['ServicioID'] ?: 'custom_' . $servicio['ID'],
        'nombre' => ($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre'],
        'precio' => floatval($servicio['Precio']),
        'personalizado' => !empty($servicio['ServicioPersonalizado'] ?? '')
    ];
}
require 'lavacar/partials/header.php';
?>

<main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-edit me-2"></i>Editar Orden #<?= $orden['ID'] ?></h2>
            <p class="text-muted mb-0">Modificar servicios y detalles de la orden</p>
        </div>
        <div>
            <a href="ordenes-activas.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Información de la Orden</h6>
                </div>
                <div class="card-body">
                    <p><strong>Cliente:</strong><br><?= htmlspecialchars($orden['ClienteNombre']) ?></p>
                    <p><strong>Vehículo:</strong><br><?= htmlspecialchars($orden['Placa']) ?> - <?= htmlspecialchars($orden['Marca']) ?> <?= htmlspecialchars($orden['Modelo']) ?></p>
                    <p><strong>Estado:</strong><br>
                        <?php
                        $estados = [1 => 'Pendiente', 2 => 'En Proceso', 3 => 'Terminado', 4 => 'Cerrado'];
                        echo $estados[$orden['Estado']] ?? 'Desconocido';
                        ?>
                    </p>
                    <p><strong>Fecha:</strong><br><?= date('d/m/Y H:i', strtotime($orden['FechaIngreso'])) ?></p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fa-solid fa-comment me-2"></i>Observaciones</h6>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="observaciones" rows="4" placeholder="Observaciones adicionales"><?= htmlspecialchars($orden['Observaciones'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>Servicios</h6>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarServicio()">
                        <i class="fa-solid fa-plus me-1"></i>
                        Agregar Servicio
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th width="200">Precio</th>
                                    <th width="80" class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="servicios-body">
                                <?php foreach ($servicios as $index => $servicio): ?>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($servicio['nombre']??'') ?>" 
                                               id="servicio_nombre_<?= $index ?>" placeholder="Nombre del servicio">
                                        <?php if ($servicio['personalizado']): ?>
                                        <small class="text-success"><i class="fa-solid fa-star"></i> Personalizado</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">₡</span>
                                            <input type="number" class="form-control" value="<?= $servicio['precio'] ?>" 
                                                   id="servicio_precio_<?= $index ?>" min="0" step="0.01" onchange="recalcularTotales()">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicio(<?= $index ?>)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>Subtotal</strong></td>
                                    <td colspan="2" class="text-end"><strong id="subtotal">₡0.00</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>IVA (13%)</strong></td>
                                    <td colspan="2" class="text-end"><strong id="iva">₡0.00</strong></td>
                                </tr>
                                <tr class="table-success">
                                    <td><strong>Total</strong></td>
                                    <td colspan="2" class="text-end"><strong id="total">₡0.00</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="ordenes-activas.php" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="button" class="btn btn-primary" onclick="guardarCambios()">
                            <i class="fa-solid fa-save me-1"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let contadorServicios = <?= count($servicios) ?>;

function agregarServicio() {
    const tbody = document.getElementById('servicios-body');
    const index = contadorServicios++;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="text" class="form-control" value="" 
                   id="servicio_nombre_${index}" placeholder="Nombre del servicio">
            <small class="text-success"><i class="fa-solid fa-star"></i> Personalizado</small>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" class="form-control" value="0" 
                       id="servicio_precio_${index}" min="0" step="0.01" onchange="recalcularTotales()">
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicio(${index})">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    // Focus en el nombre del nuevo servicio
    document.getElementById(`servicio_nombre_${index}`).focus();
    recalcularTotales();
}

function eliminarServicio(index) {
    const row = document.querySelector(`#servicio_nombre_${index}`).closest('tr');
    if (row && confirm('¿Está seguro de eliminar este servicio?')) {
        row.remove();
        recalcularTotales();
    }
}

function recalcularTotales() {
    let subtotal = 0;
    
    // Sumar todos los precios de servicios
    const precios = document.querySelectorAll('[id^="servicio_precio_"]');
    precios.forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const iva = subtotal * 0.13;
    const total = subtotal + iva;
    
    // Actualizar UI
    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('iva').textContent = formatCurrency(iva);
    document.getElementById('total').textContent = formatCurrency(total);
}

function formatCurrency(value) {
    return value.toLocaleString('es-CR', {
        style: 'currency',
        currency: 'CRC'
    });
}

function guardarCambios() {
    const btn = event.target;
    const originalContent = btn.innerHTML;
    
    // Mostrar loading
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Guardando...';
    
    // Recopilar datos de TODOS los servicios (existentes y nuevos)
    const servicios = [];
    const tbody = document.getElementById('servicios-body');
    const rows = tbody.querySelectorAll('tr');
    
    let serviceCounter = 0;
    rows.forEach((row) => {
        const nombreInput = row.querySelector('[id^="servicio_nombre_"]');
        const precioInput = row.querySelector('[id^="servicio_precio_"]');
        
        if (nombreInput && precioInput && nombreInput.value.trim() && precioInput.value > 0) {
            // Determinar si es personalizado (buscar el texto "Personalizado" en la fila)
            const isPersonalizado = row.innerHTML.includes('Personalizado');
            
            servicios.push({
                id: isPersonalizado ? `custom_${serviceCounter}` : `service_${serviceCounter}`,
                nombre: nombreInput.value.trim(),
                precio: parseFloat(precioInput.value),
                cantidad: 1,
                personalizado: isPersonalizado
            });
            serviceCounter++;
        }
    });
    
    const datos = {
        orden_id: <?= $ordenId ?>,
        servicios: servicios,
        observaciones: document.getElementById('observaciones').value.trim()
    };
    
    // Enviar al servidor
    fetch('../ajax/actualizar-orden.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Orden actualizada exitosamente', 'success');
            
            // Redirigir después de un momento
            setTimeout(() => {
                window.location.href = 'ordenes-activas.php';
            }, 1500);
        } else {
            showAlert('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error al actualizar la orden', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}

function showAlert(message, type) {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-custom');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create alert element (toast style in top-right corner)
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${getBootstrapAlertType(type)} alert-dismissible fade show alert-custom`;
    alertDiv.style.cssText = `
        position: fixed; 
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 320px; 
        max-width: 400px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        border: none;
        font-weight: 500;
        animation: slideInRight 0.3s ease-out;
    `;
    
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fa-solid ${getAlertIcon(type)} me-2" style="font-size: 1.1em;"></i>
            <span class="flex-grow-1">${message}</span>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Add CSS animation keyframes if not already added
    if (!document.querySelector('#toast-animations')) {
        const style = document.createElement('style');
        style.id = 'toast-animations';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alertDiv);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) alertDiv.remove();
    }, 3000);
}

function getBootstrapAlertType(type) {
    const typeMap = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };
    return typeMap[type] || 'info';
}

function getAlertIcon(type) {
    const iconMap = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-triangle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    return iconMap[type] || 'fa-info-circle';
}

// Calcular totales iniciales
document.addEventListener('DOMContentLoaded', function() {
    recalcularTotales();
});
</script>

<?php require 'lavacar/partials/footer.php'; ?>