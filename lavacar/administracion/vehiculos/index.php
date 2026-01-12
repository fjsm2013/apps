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
                    <a class="btn btn-sm btn-outline-frosh-gray" href="?action=toggle&id=<?= $c['ID'] ?>">
                        <i class="fa fa-power-off"></i>
                    </a>
                    <a class="btn btn-sm btn-outline-danger" href="?action=delete&id=<?= $c['ID'] ?>"
                        onclick="return confirm('Eliminar categoría?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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

document.addEventListener('DOMContentLoaded', function() {
    modal = new bootstrap.Modal(document.getElementById('catModal'));
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