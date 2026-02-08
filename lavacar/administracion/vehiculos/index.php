<?php
session_start();

require_once '../../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/CategoriaVehiculoManager.php';

/* =========================
   AUTH
========================= */
autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
if (!$user || !$user['Usuarios']) {
    header("Location: lavacar/dashboard.php");
    exit;
}

$dbName = $user['company']['db'];
$manager = new CategoriaVehiculoManager($conn, $dbName);

/* =========================
   ACTIONS
========================= */
$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

/* DELETE */
if ($action === 'delete' && $id) {
    try {
        $manager->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Categoría eliminada'];
    } catch (Exception $e) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => $e->getMessage()];
    }
    header("Location: index.php");
    exit;
}

/* TOGGLE */
if ($action === 'toggle' && $id) {
    $manager->toggleStatus($id);
    header("Location: index.php");
    exit;
}

/* CREATE / UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id     = (int)($_POST['id'] ?? 0);
    $tipo   = trim($_POST['tipo'] ?? '');
    $imagen = trim($_POST['imagen'] ?? '');
    $estado = (int)($_POST['estado'] ?? 1);
    $orden  = (int)($_POST['orden'] ?? 0);

    if ($tipo === '') {
        $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Tipo requerido'];
        header("Location: index.php");
        exit;
    }

    if ($id) {
        $manager->update($id, $tipo, $imagen, $estado, $orden);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Categoría actualizada'];
    } else {
        $manager->create($tipo, $imagen, $estado, $orden);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Categoría creada'];
    }

    header("Location: index.php");
    exit;
}

/* =========================
   DATA
========================= */
$categorias = $manager->all(false);

// Check which categories can be deleted
foreach ($categorias as &$categoria) {
    $categoria['can_delete'] = $manager->canDelete($categoria['ID']);
}
unset($categoria);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require 'lavacar/partials/header.php';

// Define breadcrumbs
$breadcrumbs = array(
    array('title' => 'Administración', 'url' => '../index.php'),
    array('title' => 'Categorías de Vehículos', 'url' => '')
);
?>

<?php include 'lavacar/partials/breadcrumb.php'; ?>

<div class="container py-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Administración de Vehículos</h3>
        <div class="btn-group">
            <a href="vehiculos.php" class="btn btn-frosh-primary">
                <i class="fa fa-car"></i> Gestionar Vehículos
            </a>
            <button class="btn btn-outline-frosh-primary" onclick="openCreate()">
                <i class="fa fa-plus"></i> Nueva Categoría
            </button>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fa-solid fa-info-circle me-2"></i>
        <strong>Nuevo:</strong> Ahora puedes gestionar vehículos individuales desde 
        <a href="vehiculos.php" class="alert-link">Gestionar Vehículos</a>. 
        Esta página es para administrar las categorías de vehículos.
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
        <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['TipoVehiculo']) ?></td>
                <td>
                    <span class="badge <?= $c['Estado'] ? 'badge-frosh-dark' : 'badge-frosh-light' ?>">
                        <?= $c['Estado'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-frosh-dark"
                        onclick="openEdit(<?= htmlspecialchars(json_encode($c)) ?>)">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-frosh-gray" 
                        onclick="confirmarToggleCategoria(<?= $c['ID'] ?>, '<?= htmlspecialchars($c['TipoVehiculo']) ?>', <?= $c['Estado'] ?>)">
                        <i class="fa fa-power-off"></i>
                    </button>
                    <?php if ($c['can_delete']['can_delete']): ?>
                        <button class="btn btn-sm btn-outline-danger" 
                            onclick="confirmarEliminarCategoria(<?= $c['ID'] ?>, '<?= htmlspecialchars($c['TipoVehiculo']) ?>')">
                            <i class="fa fa-trash"></i>
                        </button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary" 
                            disabled
                            title="No se puede eliminar: <?= htmlspecialchars($c['can_delete']['reason']) ?>"
                            data-bs-toggle="tooltip"
                            data-bs-placement="left">
                            <i class="fa fa-trash"></i>
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Confirmación de Toggle -->
<div class="modal fade" id="toggleCategoriaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width:500px; margin:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--ordenes-warning, #D3AF37); color: white; padding: 15px;">
                <h6 class="modal-title mb-0">
                    <i class="fa-solid fa-power-off me-2"></i>Cambiar Estado
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3">
                <div class="mb-2">
                    <i class="fa-solid fa-power-off fa-2x" style="color: var(--ordenes-warning, #D3AF37);"></i>
                </div>
                <p class="mb-2"><strong id="toggleActionText">¿Desactivar esta categoría?</strong></p>
                <p class="text-muted small mb-2">Puedes reactivarla en cualquier momento</p>
                <div class="p-2 bg-light rounded">
                    <small class="text-muted">Categoría</small>
                    <div id="toggleCategoriaNombre" class="fw-bold"></div>
                </div>
            </div>
            <div class="modal-footer" style="padding: 10px;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-sm" id="confirmarToggleBtn" style="background: var(--ordenes-warning, #D3AF37); color: white; border: none;">
                    <i class="fa-solid fa-power-off me-1"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="eliminarCategoriaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width:500px; margin:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background: #dc3545; color: white; padding: 15px;">
                <h6 class="modal-title mb-0">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>Eliminar Categoría
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-3">
                <div class="mb-2">
                    <i class="fa-solid fa-trash fa-2x" style="color: #dc3545;"></i>
                </div>
                <p class="mb-2"><strong>¿Eliminar esta categoría?</strong></p>
                <p class="text-muted small mb-2">Esta acción no se puede deshacer</p>
                <div class="p-2 bg-light rounded">
                    <small class="text-muted">Categoría</small>
                    <div id="deleteCategoriaNombre" class="fw-bold"></div>
                </div>
                <div id="deleteWarningDetails" class="alert alert-warning mt-3" style="display: none;">
                    <small><strong>⚠️ No se puede eliminar:</strong></small>
                    <ul id="deleteWarningList" class="mb-0 mt-2 text-start" style="font-size: 0.85rem;"></ul>
                </div>
            </div>
            <div class="modal-footer" style="padding: 10px;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-sm btn-danger" id="confirmarEliminarBtn">
                    <i class="fa-solid fa-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <form class="modal-content" method="post">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Categoría</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="id">

                <div class="mb-3">
                    <label class="form-label">Tipo de Vehículo <span class="text-danger">*</span></label>
                    <input class="form-control" name="tipo" id="tipo" required placeholder="Ej. Sedán, SUV, Pickup">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado" id="estado">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-frosh-light" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-frosh-primary">
                    <i class="fa-solid fa-save me-1"></i>Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let modal;
// Store categories data for validation
const categoriasData = <?= json_encode($categorias) ?>;

document.addEventListener('DOMContentLoaded', function() {
    modal = new bootstrap.Modal(document.getElementById('catModal'));
    
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

/* =========================
   MODAL HANDLERS
========================= */
function openCreate() {
    document.getElementById('modalTitle').textContent = 'Nueva Categoría';
    document.getElementById('id').value = 0;
    document.getElementById('tipo').value = '';
    //document.getElementById('imagen').value = '';
    //document.getElementById('orden').value = 0;
    document.getElementById('estado').value = 1;
    modal.show();
}

function openEdit(cat) {
    document.getElementById('modalTitle').textContent = 'Editar Categoría';
    document.getElementById('id').value = cat.ID;
    document.getElementById('tipo').value = cat.TipoVehiculo;
    //document.getElementById('imagen').value = cat.Imagen;
    //document.getElementById('orden').value = cat.OrdenClasificacion;
    document.getElementById('estado').value = cat.Estado;
    modal.show();
}

/* =========================
   TOGGLE CATEGORIA
========================= */
function confirmarToggleCategoria(categoriaId, categoriaNombre, estadoActual) {
    document.getElementById('toggleCategoriaNombre').textContent = categoriaNombre;
    
    // Cambiar texto según el estado actual
    const actionText = estadoActual == 1 ? '¿Desactivar esta categoría?' : '¿Activar esta categoría?';
    document.getElementById('toggleActionText').textContent = actionText;
    
    const modal = new bootstrap.Modal(document.getElementById('toggleCategoriaModal'));
    modal.show();
    
    // Configurar botón de confirmación
    const confirmarBtn = document.getElementById('confirmarToggleBtn');
    confirmarBtn.onclick = () => ejecutarToggleCategoria(categoriaId);
}

function ejecutarToggleCategoria(categoriaId) {
    const confirmarBtn = document.getElementById('confirmarToggleBtn');
    const originalContent = confirmarBtn.innerHTML;
    
    // Mostrar loading
    confirmarBtn.disabled = true;
    confirmarBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Procesando...';
    
    // Redirigir con acción toggle
    window.location.href = '?action=toggle&id=' + categoriaId;
}

/* =========================
   ELIMINAR CATEGORIA
========================= */
function confirmarEliminarCategoria(categoriaId, categoriaNombre) {
    document.getElementById('deleteCategoriaNombre').textContent = categoriaNombre;
    
    // Find category data
    const categoria = categoriasData.find(c => c.ID == categoriaId);
    const warningDiv = document.getElementById('deleteWarningDetails');
    const warningList = document.getElementById('deleteWarningList');
    const confirmarBtn = document.getElementById('confirmarEliminarBtn');
    
    // Check if can delete
    if (categoria && !categoria.can_delete.can_delete) {
        // Show warning
        warningDiv.style.display = 'block';
        warningList.innerHTML = '';
        
        categoria.can_delete.details.forEach(detail => {
            const li = document.createElement('li');
            li.textContent = detail;
            warningList.appendChild(li);
        });
        
        // Disable delete button
        confirmarBtn.disabled = true;
        confirmarBtn.innerHTML = '<i class="fa-solid fa-ban me-1"></i>No se puede eliminar';
        confirmarBtn.className = 'btn btn-sm btn-secondary';
    } else {
        // Hide warning
        warningDiv.style.display = 'none';
        
        // Enable delete button
        confirmarBtn.disabled = false;
        confirmarBtn.innerHTML = '<i class="fa-solid fa-trash me-1"></i>Eliminar';
        confirmarBtn.className = 'btn btn-sm btn-danger';
        confirmarBtn.onclick = () => ejecutarEliminarCategoria(categoriaId);
    }
    
    const modal = new bootstrap.Modal(document.getElementById('eliminarCategoriaModal'));
    modal.show();
}

function ejecutarEliminarCategoria(categoriaId) {
    const confirmarBtn = document.getElementById('confirmarEliminarBtn');
    const originalContent = confirmarBtn.innerHTML;
    
    // Mostrar loading
    confirmarBtn.disabled = true;
    confirmarBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Eliminando...';
    
    // Redirigir con acción delete
    window.location.href = '?action=delete&id=' + categoriaId;
}
</script>

<style>
/* Modal optimizations for vehicle categories */
#catModal .modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

@media (max-width: 576px) {
    #catModal .modal-dialog {
        max-width: 95%;
        margin: 1rem auto;
    }
}

/* Form improvements */
#catModal .form-label {
    font-weight: 600;
    color: var(--frosh-gray-800);
}

#catModal .form-control:focus,
#catModal .form-select:focus {
    border-color: var(--frosh-black);
    box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.1);
}
</style>
<?php require 'lavacar/partials/footer.php'; ?>