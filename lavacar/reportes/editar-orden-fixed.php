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

// Check what columns exist in ordenes table
$result = $conn->query("SHOW COLUMNS FROM `{$dbName}`.ordenes");
$ordenesColumns = [];
while ($row = $result->fetch_assoc()) {
    $ordenesColumns[] = $row['Field'];
}

// Handle form submission
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'save') {
    try {
        $conn->begin_transaction();
        
        // Delete existing services
        $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        
        $subtotal = 0;
        
        // Process services
        if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
            foreach ($_POST['servicios'] as $servicio) {
                $nombre = trim($servicio['nombre'] ?? '');
                $precio = floatval($servicio['precio'] ?? 0);
                
                if ($nombre && $precio > 0) {
                    $subtotal += $precio;
                    
                    // Insert service (all as custom for simplicity)
                    $stmt = $conn->prepare("
                        INSERT INTO `{$dbName}`.orden_servicios 
                        (OrdenID, ServicioID, Precio, ServicioPersonalizado) 
                        VALUES (?, NULL, ?, ?)
                    ");
                    $stmt->bind_param("ids", $ordenId, $precio, $nombre);
                    $stmt->execute();
                }
            }
        }
        
        // Update order - only update columns that exist
        $observaciones = trim($_POST['observaciones'] ?? '');
        $impuesto = $subtotal * 0.13;
        $total = $subtotal + $impuesto;
        
        // Prepare services JSON for ServiciosJson column
        $serviciosJson = [];
        if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
            foreach ($_POST['servicios'] as $servicio) {
                $nombre = trim($servicio['nombre'] ?? '');
                $precio = floatval($servicio['precio'] ?? 0);
                
                if ($nombre && $precio > 0) {
                    $serviciosJson[] = [
                        'id' => 'custom_' . uniqid(),
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'personalizado' => true
                    ];
                }
            }
        }
        
        // Build dynamic update query based on available columns
        $updateFields = ['Observaciones = ?'];
        $updateValues = [$observaciones];
        $updateTypes = 's';
        
        // Always try to update ServiciosJson - this is the key fix!
        if (in_array('ServiciosJson', $ordenesColumns)) {
            $updateFields[] = 'ServiciosJson = ?';
            $updateValues[] = json_encode($serviciosJson);
            $updateTypes .= 's';
        }
        
        if (in_array('Subtotal', $ordenesColumns)) {
            $updateFields[] = 'Subtotal = ?';
            $updateValues[] = $subtotal;
            $updateTypes .= 'd';
        }
        
        if (in_array('Impuesto', $ordenesColumns)) {
            $updateFields[] = 'Impuesto = ?';
            $updateValues[] = $impuesto;
            $updateTypes .= 'd';
        }
        
        if (in_array('Monto', $ordenesColumns)) {
            $updateFields[] = 'Monto = ?';
            $updateValues[] = $total;
            $updateTypes .= 'd';
        } elseif (in_array('Total', $ordenesColumns)) {
            $updateFields[] = 'Total = ?';
            $updateValues[] = $total;
            $updateTypes .= 'd';
        }
        
        $updateValues[] = $ordenId;
        $updateTypes .= 'i';
        
        $sql = "UPDATE `{$dbName}`.ordenes SET " . implode(', ', $updateFields) . " WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($updateTypes, ...$updateValues);
        $stmt->execute();
        
        $conn->commit();
        
        $_SESSION['success'] = 'Orden actualizada exitosamente';
        header("Location: ordenes-activas.php");
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error al actualizar la orden: ' . $e->getMessage();
    }
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
        'nombre' => ($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre'],
        'precio' => floatval($servicio['Precio']),
        'personalizado' => !empty($servicio['ServicioPersonalizado'] ?? '')
    ];
}

require 'lavacar/partials/header.php';
?>

<main class="container my-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-edit me-2"></i>Editar Orden #<?= $orden['ID'] ?> (FIXED)</h2>
            <p class="text-muted mb-0">Modificar servicios y detalles de la orden</p>
        </div>
        <div>
            <a href="ordenes-activas.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <!-- Debug info -->
    <div class="alert alert-info">
        <strong>Debug Info:</strong> 
        Columns available: <?= implode(', ', $ordenesColumns) ?>
    </div>

    <form method="POST" id="editForm">
        <input type="hidden" name="action" value="save">
        
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
                        <textarea class="form-control" name="observaciones" rows="4" placeholder="Observaciones adicionales"><?= htmlspecialchars($orden['Observaciones'] ?? '') ?></textarea>
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
                        <div id="servicios-container">
                            <?php foreach ($servicios as $index => $servicio): ?>
                            <div class="row mb-3 servicio-row">
                                <div class="col-md-6">
                                    <label class="form-label">Servicio</label>
                                    <input type="text" class="form-control" name="servicios[<?= $index ?>][nombre]" 
                                           value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
                                    <?php if ($servicio['personalizado']): ?>
                                    <small class="text-success"><i class="fa-solid fa-star"></i> Personalizado</small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Precio</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₡</span>
                                        <input type="number" class="form-control precio-input" name="servicios[<?= $index ?>][precio]" 
                                               value="<?= $servicio['precio'] ?>" min="0" step="0.01" required onchange="recalcularTotales()">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="eliminarServicio(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-end"><strong id="subtotal">₡0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>IVA (13%):</strong></td>
                                        <td class="text-end"><strong id="iva">₡0.00</strong></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end"><strong id="total">₡0.00</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="ordenes-activas.php" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-1"></i>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<script>
let contadorServicios = <?= count($servicios) ?>;

function agregarServicio() {
    const container = document.getElementById('servicios-container');
    const index = contadorServicios++;
    
    const div = document.createElement('div');
    div.className = 'row mb-3 servicio-row';
    div.innerHTML = `
        <div class="col-md-6">
            <label class="form-label">Servicio</label>
            <input type="text" class="form-control" name="servicios[${index}][nombre]" 
                   placeholder="Nombre del servicio personalizado" required>
            <small class="text-success"><i class="fa-solid fa-star"></i> Personalizado</small>
        </div>
        <div class="col-md-4">
            <label class="form-label">Precio</label>
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" class="form-control precio-input" name="servicios[${index}][precio]" 
                       value="0" min="0" step="0.01" required onchange="recalcularTotales()">
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="eliminarServicio(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(div);
    
    // Focus en el nombre del nuevo servicio
    div.querySelector('input[type="text"]').focus();
    recalcularTotales();
}

function eliminarServicio(btn) {
    if (confirm('¿Está seguro de eliminar este servicio?')) {
        btn.closest('.servicio-row').remove();
        recalcularTotales();
    }
}

function recalcularTotales() {
    let subtotal = 0;
    
    // Sumar todos los precios
    const precios = document.querySelectorAll('.precio-input');
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

// Calcular totales iniciales
document.addEventListener('DOMContentLoaded', function() {
    recalcularTotales();
});

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
    const servicios = document.querySelectorAll('.servicio-row');
    if (servicios.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un servicio');
        return false;
    }
    
    // Check if all services have name and price
    let valid = true;
    servicios.forEach(row => {
        const nombre = row.querySelector('input[type="text"]').value.trim();
        const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
        
        if (!nombre || precio <= 0) {
            valid = false;
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Todos los servicios deben tener nombre y precio válido');
        return false;
    }
});
</script>

<?php require 'lavacar/partials/footer.php'; ?>