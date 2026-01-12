<?php

/*********************************
 * AUTH + DB
 *********************************/
require_once 'lib/config.php';
//require_once '../../../lib/libraries.php';
require_once 'lib/Auth.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
//var_dump($auth);

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}
$userID = $auth->getUserId();
$userInfo = $auth->getCurrentUser();
//var_dump($userInfo);
// Using shared database approach - no need to switch databases
// All tenant data is separated by id_empresa
// $databaseName = $userInfo['empresa_db'];
// $db->exec("USE `$databaseName`");
//var_dump($_COOKIE);

/*********************************
 * HANDLE ACTIONS
 *********************************/
if ($_POST) {

    // CREATE
    if (isset($_POST['create_categoria'])) {
        $descripcion = trim($_POST['descripcion']);

        $stmt = $db->prepare("INSERT INTO categoriaservicio (Descripcion) VALUES (?)");
        $stmt->execute([$descripcion]);

        header("Location: servicios.php");
        exit();
    }

    // UPDATE
    if (isset($_POST['update_categoria'])) {
        $id = $_POST['id'];
        $descripcion = trim($_POST['descripcion']);

        $stmt = $db->prepare("UPDATE categoriaservicio SET Descripcion = ? WHERE ID = ?");
        $stmt->execute([$descripcion, $id]);

        header("Location: servicios.php");
        exit();
    }

    // DELETE
    if (isset($_POST['delete_categoria'])) {
        $id = $_POST['id'];

        $stmt = $db->prepare("DELETE FROM categoriaservicio WHERE ID = ?");
        $stmt->execute([$id]);

        header("Location: servicios.php");
        exit();
    }
}

/*********************************
 * FETCH DATA
 *********************************/
$categorias = $db
    ->query("SELECT ID, Descripcion FROM categoriaservicio ORDER BY Descripcion")
    ->fetchAll(PDO::FETCH_ASSOC);
require  'partials/header.php';
?>


<main class="col-md-9 col-lg-10 px-md-4">

    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4">Categorías de Servicio</h1>
        <button class="btn btn-frosh-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i>Nueva Categoría
        </button>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['Descripcion']) ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">

                                    <button class="btn btn-frosh-dark" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="<?= $cat['ID'] ?>"
                                        data-descripcion="<?= htmlspecialchars($cat['Descripcion']) ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="POST" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                        <input type="hidden" name="id" value="<?= $cat['ID'] ?>">
                                        <button name="delete_categoria" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- CREATE MODAL -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Categoría</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-frosh-light" data-bs-dismiss="modal">Cancelar</button>
                    <button name="create_categoria" class="btn btn-frosh-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoría</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Descripción</label>
                    <input type="text" class="form-control" name="descripcion" id="edit_descripcion" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-frosh-light" data-bs-dismiss="modal">Cancelar</button>
                    <button name="update_categoria" class="btn btn-frosh-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        document.getElementById('edit_id').value = button.dataset.id;
        document.getElementById('edit_descripcion').value = button.dataset.descripcion;
    });
</script>
<?php
require 'partials/footer.php';
