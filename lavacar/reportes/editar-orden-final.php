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

// Handle form submission - ONLY work with orden_servicios table
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'save') {
    try {
        $conn->begin_transaction();
        
        // Get ONLY services for THIS order
        $stmt = $conn->prepare("SELECT ID FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $currentServiceIds = [];
        while ($row = $result->fetch_assoc()) {
            $currentServiceIds[] = $row['ID'];
        }
        
        $subtotal = 0;
        $serviciosJson = [];
        $processedServiceIds = [];
        
        // Process submitted services
        if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
            // Debug: Log what we received
            error_log("Received " . count($_POST['servicios']) . " services in POST");
            error_log("POST servicios: " . print_r($_POST['servicios'], true));
            
            foreach ($_POST['servicios'] as $index => $servicio) {
                $nombre = trim($servicio['nombre'] ?? '');
                $precio = floatval($servicio['precio'] ?? 0);
                $serviceDbId = intval($servicio['db_id'] ?? 0);
                
                error_log("Processing service {$index}: db_id={$serviceDbId}, nombre={$nombre}, precio={$precio}");
                
                if ($nombre && $precio > 0) {
                    $subtotal += $precio;
                    
                    $serviciosJson[] = [
                        'id' => $serviceDbId ?: 'custom_' . uniqid(),
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'personalizado' => true
                    ];
                    
                    if ($serviceDbId && in_array($serviceDbId, $currentServiceIds)) {
                        // Update existing service - VERIFY it belongs to this order
                        error_log("Attempting to UPDATE service ID {$serviceDbId}");
                        $stmt = $conn->prepare("
                            UPDATE `{$dbName}`.orden_servicios 
                            SET Precio = ?, ServicioPersonalizado = ?, Subtotal = ?
                            WHERE ID = ? AND OrdenID = ?
                        ");
                        $stmt->bind_param("dsdii", $precio, $nombre, $precio, $serviceDbId, $ordenId);
                        $stmt->execute();
                        
                        // ALWAYS add to processed IDs (even if no rows affected - data might be identical)
                        $processedServiceIds[] = $serviceDbId;
                        
                        if ($stmt->affected_rows > 0) {
                            error_log("✓ Updated service ID {$serviceDbId} successfully");
                        } else {
                            error_log("ℹ Service ID {$serviceDbId} - no changes (data identical)");
                        }
                    } else {
                        // Insert new service - ALWAYS set OrdenID
                        error_log("Attempting to INSERT new service: {$nombre}");
                        $stmt = $conn->prepare("
                            INSERT INTO `{$dbName}`.orden_servicios 
                            (OrdenID, ServicioID, Precio, ServicioPersonalizado, Subtotal, Cantidad) 
                            VALUES (?, NULL, ?, ?, ?, 1)
                        ");
                        $stmt->bind_param("idsd", $ordenId, $precio, $nombre, $precio);
                        $stmt->execute();
                        $newId = $conn->insert_id;
                        $processedServiceIds[] = $newId;
                        error_log("✓ Inserted new service with ID {$newId}");
                    }
                }
            }
        }
        
        // Delete services that were removed - ONLY for this order
        $servicesToDelete = array_diff($currentServiceIds, $processedServiceIds);
        error_log("Current service IDs: " . implode(', ', $currentServiceIds));
        error_log("Processed service IDs: " . implode(', ', $processedServiceIds));
        error_log("Services to delete: " . implode(', ', $servicesToDelete));
        
        if (!empty($servicesToDelete)) {
            foreach ($servicesToDelete as $serviceId) {
                // Double-check: only delete if it belongs to this order
                error_log("Deleting service ID {$serviceId}");
                $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE ID = ? AND OrdenID = ?");
                $stmt->bind_param("ii", $serviceId, $ordenId);
                $stmt->execute();
                error_log("Deleted service ID {$serviceId}, affected rows: " . $stmt->affected_rows);
            }
        }
        
        // Update order - sync ServiciosJson and Monto
        $observaciones = trim($_POST['observaciones'] ?? '');
        $serviciosJsonStr = json_encode($serviciosJson);
        
        // Calculate total with IVA
        $impuesto = $subtotal * 0.13;
        $total = $subtotal + $impuesto;
        
        $stmt = $conn->prepare("
            UPDATE `{$dbName}`.ordenes 
            SET Observaciones = ?, ServiciosJson = ?, Monto = ?
            WHERE ID = ?
        ");
        $stmt->bind_param("ssdi", $observaciones, $serviciosJsonStr, $total, $ordenId);
        $stmt->execute();
        
        $conn->commit();
        
        $_SESSION['success'] = 'Orden actualizada exitosamente';
        // Redirect with timestamp to prevent caching
        header("Location: ordenes-activas.php?updated=" . time());
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error al actualizar la orden: ' . $e->getMessage();
        error_log("Error in editar-orden-final.php: " . $e->getMessage());
    }
}

// Obtener datos de la orden
$stmt = $conn->prepare("
    SELECT o.*, 
           c.NombreCompleto as ClienteNombre,
           v.Placa, v.Marca, v.Modelo
    FROM `{$dbName}`.ordenes o
    LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
    LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
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

// ALWAYS read from orden_servicios table (single source of truth)
$stmt = $conn->prepare("
    SELECT os.ID as DbID, os.*, s.Descripcion as ServicioNombre
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
        'db_id' => $servicio['DbID'],
        'nombre' => ($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre'],
        'precio' => floatval($servicio['Precio']),
        'personalizado' => !empty($servicio['ServicioPersonalizado'] ?? '')
    ];
}

require 'lavacar/partials/header.php';
?>

<main class="container my-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fa-solid fa-edit me-2"></i>Editar Orden #<?= $orden['ID'] ?></h2>
            <p class="text-muted mb-0">Modificar servicios de la orden</p>
        </div>
        <div>
            <a href="ordenes-activas.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <form method="POST" id="editForm">
        <input type="hidden" name="action" value="save">
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Información</h6>
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
                        <textarea class="form-control" name="observaciones" rows="4"><?= htmlspecialchars($orden['Observaciones'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fa-solid fa-list me-2"></i>Servicios (<?= count($servicios) ?>)</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarServicio()">
                            <i class="fa-solid fa-plus me-1"></i>Agregar
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="servicios-container">
                            <?php foreach ($servicios as $index => $servicio): ?>
                            <div class="row mb-3 servicio-row">
                                <input type="hidden" name="servicios[<?= $index ?>][db_id]" value="<?= $servicio['db_id'] ?>">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="servicios[<?= $index ?>][nombre]" 
                                           value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">₡</span>
                                        <input type="number" class="form-control precio-input" name="servicios[<?= $index ?>][precio]" 
                                               value="<?= $servicio['precio'] ?>" min="0" step="0.01" required onchange="recalcularTotales()">
                                    </div>
                                </div>
                                <div class="col-md-2">
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
                                        <td>Subtotal:</td>
                                        <td class="text-end"><strong id="subtotal">₡0.00</strong></td>
                                    </tr>
                                    <tr>
                                        <td>IVA (13%):</td>
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
                                <i class="fa-solid fa-save me-1"></i>Guardar Cambios
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
        <input type="hidden" name="servicios[${index}][db_id]" value="0">
        <div class="col-md-6">
            <input type="text" class="form-control" name="servicios[${index}][nombre]" 
                   placeholder="Servicio personalizado" required>
            <small class="text-success">Nuevo</small>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" class="form-control precio-input" name="servicios[${index}][precio]" 
                       value="0" min="0" step="0.01" required onchange="recalcularTotales()">
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="eliminarServicio(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(div);
    div.querySelector('input[type="text"]').focus();
    recalcularTotales();
}

function eliminarServicio(btn) {
    if (confirm('¿Eliminar este servicio?')) {
        const row = btn.closest('.servicio-row');
        row.remove();
        // Recalculate immediately after removal
        setTimeout(recalcularTotales, 10);
    }
}

function recalcularTotales() {
    let subtotal = 0;
    
    // Get all visible price inputs (not from deleted rows)
    const precios = document.querySelectorAll('.servicio-row .precio-input');
    precios.forEach(input => {
        const valor = parseFloat(input.value) || 0;
        subtotal += valor;
    });
    
    const iva = subtotal * 0.13;
    const total = subtotal + iva;
    
    // Update display
    document.getElementById('subtotal').textContent = subtotal.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
    document.getElementById('iva').textContent = iva.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
    document.getElementById('total').textContent = total.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
    
    // Debug log
    console.log('Recalculated:', {
        servicios: precios.length,
        subtotal: subtotal,
        iva: iva,
        total: total
    });
}

document.addEventListener('DOMContentLoaded', recalcularTotales);
</script>

<?php require 'lavacar/partials/footer.php'; ?>