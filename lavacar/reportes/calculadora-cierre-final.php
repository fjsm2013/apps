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

// Handle form submission
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'cerrar') {
    try {
        $conn->begin_transaction();
        
        // Get current services
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
        
        // Process services (same logic as edit)
        if (isset($_POST['servicios']) && is_array($_POST['servicios'])) {
            foreach ($_POST['servicios'] as $servicio) {
                $nombre = trim($servicio['nombre'] ?? '');
                $precio = floatval($servicio['precio'] ?? 0);
                $serviceDbId = intval($servicio['db_id'] ?? 0);
                
                if ($nombre && $precio > 0) {
                    $subtotal += $precio;
                    
                    $serviciosJson[] = [
                        'id' => $serviceDbId ?: 'custom_' . uniqid(),
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'personalizado' => true
                    ];
                    
                    if ($serviceDbId && in_array($serviceDbId, $currentServiceIds)) {
                        // Update existing
                        $stmt = $conn->prepare("
                            UPDATE `{$dbName}`.orden_servicios 
                            SET Precio = ?, ServicioPersonalizado = ?, Subtotal = ?
                            WHERE ID = ? AND OrdenID = ?
                        ");
                        $stmt->bind_param("dsdii", $precio, $nombre, $precio, $serviceDbId, $ordenId);
                        $stmt->execute();
                        $processedServiceIds[] = $serviceDbId;
                    } else {
                        // Insert new
                        $stmt = $conn->prepare("
                            INSERT INTO `{$dbName}`.orden_servicios 
                            (OrdenID, ServicioID, Precio, ServicioPersonalizado, Subtotal, Cantidad) 
                            VALUES (?, NULL, ?, ?, ?, 1)
                        ");
                        $stmt->bind_param("idsd", $ordenId, $precio, $nombre, $precio);
                        $stmt->execute();
                        $processedServiceIds[] = $conn->insert_id;
                    }
                }
            }
        }
        
        // Delete removed services
        $servicesToDelete = array_diff($currentServiceIds, $processedServiceIds);
        if (!empty($servicesToDelete)) {
            foreach ($servicesToDelete as $serviceId) {
                $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE ID = ? AND OrdenID = ?");
                $stmt->bind_param("ii", $serviceId, $ordenId);
                $stmt->execute();
            }
        }
        
        // Calculate totals with discount
        $descuento = floatval($_POST['descuento'] ?? 0);
        $subtotalConDescuento = $subtotal - $descuento;
        $impuesto = $subtotalConDescuento * 0.13;
        $total = $subtotalConDescuento + $impuesto;
        
        // Update order and close it
        $observaciones = trim($_POST['observaciones'] ?? '');
        $serviciosJsonStr = json_encode($serviciosJson);
        
        $stmt = $conn->prepare("
            UPDATE `{$dbName}`.ordenes 
            SET Observaciones = ?, ServiciosJson = ?, Monto = ?, Descuento = ?, Estado = 4, FechaCierre = NOW()
            WHERE ID = ?
        ");
        $stmt->bind_param("ssddi", $observaciones, $serviciosJsonStr, $total, $descuento, $ordenId);
        $stmt->execute();
        
        $conn->commit();
        
        // Enviar correo de notificación al cliente
        require_once 'lavacar/ordenes/enviar_correo_estado.php';
        
        // Get order data with client email
        $stmt = $conn->prepare("
            SELECT o.*, c.Correo as ClienteCorreo
            FROM `{$dbName}`.ordenes o
            LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
            WHERE o.ID = ?
        ");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        $ordenData = $stmt->get_result()->fetch_assoc();
        
        $emailEnviado = false;
        if (!empty($ordenData['ClienteCorreo'])) {
            try {
                $emailEnviado = enviarCorreoEstado($ordenId, 4); // Estado 4 = Cerrado
            } catch (Exception $e) {
                error_log("Error enviando email de cierre: " . $e->getMessage());
                // No fallar la operación si el email falla
            }
        }
        
        $successMessage = 'Orden cerrada exitosamente';
        if ($emailEnviado) {
            $successMessage .= ' - Notificación enviada al cliente';
        } elseif (!empty($ordenData['ClienteCorreo'])) {
            $successMessage .= ' - Error enviando notificación';
        }
        
        $_SESSION['success'] = $successMessage;
        header("Location: ordenes-activas.php?updated=" . time());
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error al cerrar la orden: ' . $e->getMessage();
        error_log("Error in calculadora-cierre-final.php: " . $e->getMessage());
    }
}

// Get order data
$stmt = $conn->prepare("
    SELECT o.*, 
           c.NombreCompleto as ClienteNombre,
           c.Correo as ClienteCorreo,
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

// Verify order is in Terminado state
if ($orden['Estado'] != 3) {
    $_SESSION['error'] = 'Solo se pueden cerrar órdenes en estado Terminado';
    header("Location: ordenes-activas.php");
    exit;
}

// Get services from orden_servicios table
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

<style>
.pos-container {
    max-width: 800px;
    margin: 0 auto;
}
.pos-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 15px 15px 0 0;
    text-align: center;
}
.pos-body {
    background: white;
    border: 2px solid #e0e0e0;
    border-top: none;
}
.service-item {
    padding: 15px;
    border-bottom: 1px dashed #ddd;
    transition: background 0.2s;
}
.service-item:hover {
    background: #f8f9fa;
}
.total-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 0 0 15px 15px;
}
.total-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 16px;
}
.total-row.final {
    border-top: 2px solid #333;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 24px;
    font-weight: bold;
    color: #28a745;
}
.btn-close-order {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    padding: 15px 40px;
    font-size: 18px;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}
.btn-close-order:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}
</style>

<main class="container my-4">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="pos-container">
        <form method="POST" id="closeForm">
            <input type="hidden" name="action" value="cerrar">
            
            <!-- POS Header -->
            <div class="pos-header">
                <h2><i class="fa-solid fa-cash-register me-2"></i>Cierre de Orden</h2>
                <h3 class="mb-0">#<?= $orden['ID'] ?></h3>
                <p class="mb-0 mt-2 opacity-75">
                    <?= htmlspecialchars($orden['ClienteNombre']) ?> | 
                    <?= htmlspecialchars($orden['Placa']) ?>
                </p>
            </div>

            <!-- POS Body -->
            <div class="pos-body">
                <!-- Services List -->
                <div id="servicios-container">
                    <?php foreach ($servicios as $index => $servicio): ?>
                    <div class="service-item servicio-row">
                        <input type="hidden" name="servicios[<?= $index ?>][db_id]" value="<?= $servicio['db_id'] ?>">
                        <div class="row align-items-center">
                            <div class="col-7">
                                <input type="text" class="form-control form-control-lg border-0" 
                                       name="servicios[<?= $index ?>][nombre]" 
                                       value="<?= htmlspecialchars($servicio['nombre']) ?>" required>
                            </div>
                            <div class="col-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text border-0 bg-transparent">₡</span>
                                    <input type="number" class="form-control border-0 precio-input text-end fw-bold" 
                                           name="servicios[<?= $index ?>][precio]" 
                                           value="<?= $servicio['precio'] ?>" min="0" step="0.01" required 
                                           onchange="recalcularTotales()">
                                </div>
                            </div>
                            <div class="col-1 text-center">
                                <button type="button" class="btn btn-link text-danger" onclick="eliminarServicio(this)" title="Eliminar">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add Service Button -->
                <div class="p-3 border-bottom">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="agregarServicio()">
                        <i class="fa-solid fa-plus me-2"></i>Agregar Item
                    </button>
                </div>

                <!-- Discount Section -->
                <div class="p-3 border-bottom bg-light">
                    <label class="form-label fw-bold">
                        <i class="fa-solid fa-percent me-2"></i>Descuento
                    </label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">₡</span>
                        <input type="number" class="form-control" id="descuento" name="descuento"
                               value="<?= $orden['Descuento'] ?? 0 ?>" min="0" step="0.01" 
                               onchange="recalcularTotales()">
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="p-3 border-bottom">
                    <label class="form-label fw-bold">
                        <i class="fa-solid fa-comment me-2"></i>Notas de Cierre
                    </label>
                    <textarea class="form-control" name="observaciones" rows="2" 
                              placeholder="Observaciones adicionales"><?= htmlspecialchars($orden['Observaciones'] ?? '') ?></textarea>
                </div>

                <!-- Totals Section -->
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span id="subtotal" class="fw-bold">₡0.00</span>
                    </div>
                    <div class="total-row">
                        <span>Descuento:</span>
                        <span id="descuento-display" class="fw-bold text-danger">₡0.00</span>
                    </div>
                    <div class="total-row">
                        <span>IVA (13%):</span>
                        <span id="iva" class="fw-bold">₡0.00</span>
                    </div>
                    <div class="total-row final">
                        <span>TOTAL:</span>
                        <span id="total">₡0.00</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-4 text-center">
                    <a href="ordenes-activas.php" class="btn btn-outline-secondary btn-lg me-3">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </a>
                    <button type="button" class="btn btn-success btn-close-order" onclick="mostrarConfirmacion()">
                        <i class="fa-solid fa-lock me-2"></i>Cerrar Orden
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width:500px; margin:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--report-warning); color: white; padding: 15px;">
                <h6 class="modal-title mb-0">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>Confirmar Cierre
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3">
                <div class="mb-2">
                    <i class="fa-solid fa-lock fa-2x" style="color: var(--report-success);"></i>
                </div>
                <p class="mb-2"><strong>¿Cerrar esta orden?</strong></p>
                <p class="text-muted small mb-2">Esta acción no se puede deshacer</p>
                <div class="p-2 bg-light rounded">
                    <small class="text-muted">Total:</small>
                    <div id="modal-total" class="fw-bold fs-5" style="color: var(--report-success);">₡0.00</div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 10px;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-sm btn-success" onclick="confirmarCierre()">
                    <i class="fa-solid fa-check me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let contadorServicios = <?= count($servicios) ?>;

function agregarServicio() {
    const container = document.getElementById('servicios-container');
    const index = contadorServicios++;
    
    const div = document.createElement('div');
    div.className = 'service-item servicio-row';
    div.innerHTML = `
        <input type="hidden" name="servicios[${index}][db_id]" value="0">
        <div class="row align-items-center">
            <div class="col-7">
                <input type="text" class="form-control form-control-lg border-0" 
                       name="servicios[${index}][nombre]" 
                       placeholder="Nuevo item" required>
            </div>
            <div class="col-4">
                <div class="input-group input-group-lg">
                    <span class="input-group-text border-0 bg-transparent">₡</span>
                    <input type="number" class="form-control border-0 precio-input text-end fw-bold" 
                           name="servicios[${index}][precio]" 
                           value="0" min="0" step="0.01" required onchange="recalcularTotales()">
                </div>
            </div>
            <div class="col-1 text-center">
                <button type="button" class="btn btn-link text-danger" onclick="eliminarServicio(this)" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(div);
    div.querySelector('input[type="text"]').focus();
    recalcularTotales();
}

function eliminarServicio(btn) {
    if (confirm('¿Eliminar este item?')) {
        btn.closest('.servicio-row').remove();
        setTimeout(recalcularTotales, 10);
    }
}

function recalcularTotales() {
    let subtotal = 0;
    
    document.querySelectorAll('.servicio-row .precio-input').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const subtotalConDescuento = subtotal - descuento;
    const iva = subtotalConDescuento * 0.13;
    const total = subtotalConDescuento + iva;
    
    document.getElementById('subtotal').textContent = subtotal.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
    document.getElementById('descuento-display').textContent = descuento.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
    document.getElementById('iva').textContent = iva.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
    document.getElementById('total').textContent = total.toLocaleString('es-CR', {style: 'currency', currency: 'CRC'});
}

document.addEventListener('DOMContentLoaded', recalcularTotales);

// Mostrar modal de confirmación
function mostrarConfirmacion() {
    // Actualizar el total en el modal
    const totalText = document.getElementById('total').textContent;
    document.getElementById('modal-total').textContent = totalText;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}

// Confirmar y enviar formulario
function confirmarCierre() {
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
    modal.hide();
    
    // Enviar formulario
    document.getElementById('closeForm').submit();
}
</script>

<?php require 'lavacar/partials/footer.php'; ?>