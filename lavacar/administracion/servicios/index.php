<?php
session_start();

require_once '../../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/backend/ServiciosManager.php';
require_once 'lavacar/backend/CategoriaVehiculoManager.php';

/* =========================
   AUTHENTICATION
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

$dbName  = $user['company']['db'];
$manager = new ServiciosManager($conn, $dbName);
$catManager = new CategoriaVehiculoManager($conn, $dbName);

// Listar
$categorias = $catManager->all();

// Crear
//$catManager->create('SUV', 'suv.png', 1, 2);

// Editar
//$catManager->update(3, 'Pickup', 'pickup.png', 1, 3);

// Activar / desactivar
//$catManager->toggleStatus(3);

/* =========================
   INPUT HANDLING
========================= */
$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);
$message = '';

/* =========================
   DELETE ACTION
========================= */
if ($action === 'delete' && $id) {
    try {
        $manager->delete($id);
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => '✅ Servicio eliminado correctamente'
        ];
    } catch (Exception $e) {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'text' => '❌ Error al eliminar: ' . $e->getMessage()
        ];
    }
    header("Location: index.php");
    exit;
}

/* =========================
   FORM SUBMISSION
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id = (int)($_POST['id'] ?? 0);

    if (empty($descripcion)) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'text' => '⚠️ La descripción no puede estar vacía'
        ];

        // Store form data for re-population
        $_SESSION['form_data'] = [
            'id' => $id,
            'descripcion' => $descripcion,
            'is_edit' => $id > 0
        ];

        header("Location: index.php" . ($id ? "?action=edit&id=$id" : "?action=create"));
        exit;
    }

    try {
        if ($id) {
            $manager->update($id, $descripcion);
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => '✅ Servicio actualizado correctamente'
            ];
        } else {
            $manager->create($descripcion);
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => '✅ Servicio creado correctamente'
            ];
        }

        // Clear any stored form data
        unset($_SESSION['form_data']);
    } catch (Exception $e) {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'text' => '❌ Error: ' . $e->getMessage()
        ];

        // Store form data on error
        $_SESSION['form_data'] = [
            'id' => $id,
            'descripcion' => $descripcion,
            'is_edit' => $id > 0
        ];
    }

    header("Location: index.php");
    exit;
}

/* =========================
   DATA FETCHING
========================= */
$servicios = $manager->all();
$editRow = null;
$isEditMode = false;

// Check if we need to show edit modal
if ($action === 'edit' && $id) {
    $editRow = $manager->find($id);
    $isEditMode = true;
}

// Check if we need to show create modal
$isCreateMode = ($action === 'create');

// Check for flash messages
$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

// Check for stored form data
$formData = $_SESSION['form_data'] ?? null;
if ($formData) {
    $editRow = $formData;
    $isEditMode = $formData['is_edit'] ?? false;
    $isCreateMode = !$isEditMode;
    unset($_SESSION['form_data']);
}

/* =========================
   UI RENDERING
========================= */
require 'lavacar/partials/header.php';
?>

<div class="container py-4">
    <!-- Header with Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Servicios</h2>
            <p class="text-muted mb-0">Gestión de servicios de lavado</p>
        </div>
        <button type="button" class="btn btn-frosh-primary" onclick="openCreateModal()">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Servicio
        </button>
    </div>

    <!-- Flash Messages -->
    <?php if ($flashMessage): ?>
    <div class="alert alert-<?= $flashMessage['type'] ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flashMessage['text']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Services Table -->
    <?php if (empty($servicios)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
            <h4>No hay servicios registrados</h4>
            <p class="text-muted mb-4">Comienza agregando tu primer servicio</p>
            <button type="button" class="btn btn-frosh-primary" onclick="openCreateModal()">
                <i class="bi bi-plus-circle me-1"></i> Agregar Servicio
            </button>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Descripción</th>
                            <th width="120" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicios as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['Descripcion']) ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-frosh-dark"
                                        onclick="openEditModal(<?= $s['ID'] ?>, '<?= htmlspecialchars(addslashes($s['Descripcion'])) ?>')">
                                        <i class="fa-solid fa-pen-to-square"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-outline-danger"
                                        onclick="confirmDelete(<?= $s['ID'] ?>)">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- CRUD Modal -->
<div class="modal fade" id="crudModal" tabindex="-1" style="z-index: 10050;">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin: 1.75rem auto; max-width: 800px;">
        <div class="modal-content" style="position: relative; display: flex; flex-direction: column; width: 100%; pointer-events: auto; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0,0,0,.2); border-radius: 0.3rem; outline: 0;">

            <form id="serviceForm">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <input type="hidden" id="serviceId" value="0">

                    <!-- PASO 1 -->
                    <div class="step step-1">

                        <h6>Paso 1 de 3</h6>
                        <div class="mb-3">
                            <label class="form-label">
                                Descripción <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="descripcion" placeholder="Ej. Lavado Interior"
                                required>
                        </div>
                    </div>

                    <!-- PASO 2 -->
                    <div class="step step-2 d-none">
                        <h6>Paso 2 de 3</h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Categoría</th>
                                        <th class="text-end">Precio</th>
                                    </tr>
                                </thead>
                                <tbody id="priceTable">
                                    <!-- PHP o JS -->
                                    <?php foreach ($categorias as $cat): ?>
                                    <tr>
                                        <td><?= $cat['TipoVehiculo'] ?></td>
                                        <td class="text-end">
                                            <input type="number" class="form-control text-end price-input"
                                                data-id="<?= $cat['ID'] ?>" min="0" step="0.01">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PASO 3 -->
                    <div class="step step-3 d-none">
                        <h6>Paso 3 de 3</h6>
                        <h6 class="mb-3">Confirmación</h6>

                        <p><strong>Servicio:</strong> <span id="confirmServicio"></span></p>

                        <ul class="list-group" id="confirmPrecios"></ul>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">

                    <button type="button" class="btn btn-frosh-light" id="btnBack" onclick="prevStep()"
                        style="display:none">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </button>

                    <button type="button" class="btn btn-frosh-primary" id="btnNext" onclick="nextStep()">
                        Siguiente
                    </button>

                    <button type="submit" class="btn btn-frosh-primary d-none" id="btnSave">
                        <i class="fas fa-save me-1"></i> Guardar
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" style="z-index: 10050;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle text-danger display-6 mb-3"></i>
                <p class="mb-0">¿Está seguro de eliminar este servicio?</p>
                <p class="text-muted small">Esta acción no se puede deshacer</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-frosh-light" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-outline-danger">
                    Eliminar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const VEHICLE_CATEGORIES = <?= json_encode(
                                    array_column($categorias, 'TipoVehiculo', 'ID'),
                                    JSON_UNESCAPED_UNICODE
                                ) ?>;
/* =====================================================
   GLOBAL STATE
===================================================== */
let crudModal, deleteModal;
let currentStep = 1;
const totalSteps = 3;
let prices = {};

/* =====================================================
   INIT
===================================================== */
document.addEventListener('DOMContentLoaded', function() {

    crudModal = new bootstrap.Modal(document.getElementById('crudModal'), {
        backdrop: 'static',
        keyboard: false,
        focus: true
    });
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    showStep(1);

    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        guardarServicio();
    });
    
    // Prevent event bubbling on modal content
    document.querySelectorAll('.modal-content').forEach(content => {
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});

/* =====================================================
   MODAL CONTROL
===================================================== */
function openCreateModal() {
    // Ensure no overlay conflicts
    document.body.classList.add('modal-open');
    
    document.getElementById('modalTitle').textContent = 'Nuevo Servicio';
    document.getElementById('serviceId').value = 0;
    document.getElementById('descripcion').value = '';

    prices = {};
    document.querySelectorAll('.price-input').forEach(i => {
        i.value = '0.00';
    });

    currentStep = 1;
    showStep(1);
    
    // Show modal and ensure proper centering
    crudModal.show();
    
    // Force center the modal after it's shown
    setTimeout(() => {
        const modalDialog = document.querySelector('#crudModal .modal-dialog');
        if (modalDialog) {
            modalDialog.style.marginLeft = 'auto';
            modalDialog.style.marginRight = 'auto';
            modalDialog.style.transform = 'none';
        }
    }, 100);
}

function openEditModal(id, descripcion) {
    // Ensure no overlay conflicts
    document.body.classList.add('modal-open');
    
    document.getElementById('modalTitle').textContent = 'Editar Servicio';
    document.getElementById('serviceId').value = id;
    document.getElementById('descripcion').value = descripcion;

    prices = {};
    document.querySelectorAll('.price-input').forEach(i => {
        i.value = '0.00';
    });

    currentStep = 1;
    showStep(1);
    
    // Show modal and ensure proper centering
    crudModal.show();
    
    // Force center the modal after it's shown
    setTimeout(() => {
        const modalDialog = document.querySelector('#crudModal .modal-dialog');
        if (modalDialog) {
            modalDialog.style.marginLeft = 'auto';
            modalDialog.style.marginRight = 'auto';
            modalDialog.style.transform = 'none';
        }
    }, 100);

    /* LOAD EXISTING PRICES */
    fetch(`../../backend/get_precios_servicio.php?id=${id}`)
        .then(r => r.json())
        .then(data => {
            document.querySelectorAll('.price-input').forEach(input => {
                const catId = input.dataset.id;
                if (data[catId] !== undefined) {
                    input.value = parseFloat(data[catId]).toFixed(2);
                }
            });
        })
        .catch(error => {
            console.error('Error loading prices:', error);
        });
}

/* =====================================================
   WIZARD
===================================================== */
function showStep(step) {

    document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
    document.querySelector('.step-' + step).classList.remove('d-none');

    document.getElementById('btnBack').style.display =
        step > 1 ? 'inline-block' : 'none';

    document.getElementById('btnNext').classList.toggle(
        'd-none', step === totalSteps
    );

    document.getElementById('btnSave').classList.toggle(
        'd-none', step !== totalSteps
    );

    const titles = {
        1: 'Nuevo Servicio',
        2: 'Precios por Categoría',
        3: 'Confirmar Servicio'
    };

    document.getElementById('modalTitle').textContent = titles[step];
}

function nextStep() {

    /* STEP 1 → VALIDATE SERVICE */
    if (currentStep === 1) {
        if (!descripcion.value.trim()) {
            //alert('Ingrese la descripción');
            showAlert({
                type: 'info',
                message: 'Ingrese la descripción'
            });
            return;
        }
    }

    /* STEP 2 → COLLECT PRICES */
    if (currentStep === 2) {

        prices = {};
        let hasAnyNonZero = false;

        document.querySelectorAll('.price-input').forEach(input => {

            const val = parseFloat(input.value);
            const price = isNaN(val) ? 0 : val;

            prices[input.dataset.id] = price;

            if (price !== 0) {
                hasAnyNonZero = true;
            }
        });

        if (!hasAnyNonZero) {
            //alert('Ingrese al menos un precio distinto de 0');

            showAlert({
                type: 'info',
                message: 'Ingrese al menos un precio distinto de 0'
            });

            return;
        }

        buildConfirmation();
    }

    currentStep++;
    showStep(currentStep);
}

function prevStep() {
    currentStep--;
    showStep(currentStep);
}

/* =====================================================
   CONFIRMATION
===================================================== */
function buildConfirmation() {

    document.getElementById('confirmServicio').textContent =
        document.getElementById('descripcion').value;

    const ul = document.getElementById('confirmPrecios');
    ul.innerHTML = '';

    Object.entries(prices).forEach(([catId, price]) => {

        const nombreCategoria =
            VEHICLE_CATEGORIES[catId] ?? `Categoría ${catId}`;

        ul.innerHTML += `
            <li class="list-group-item d-flex justify-content-between">
                ${nombreCategoria}
                <span>₡ ${formatPrice(price)}</span>
            </li>`;
    });
}

/* =====================================================
   UTILITY FUNCTIONS
===================================================== */
function formatPrice(amount) {
    if (amount === null || amount === undefined || amount === '') return '0';
    return Number(amount).toLocaleString('es-CR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

/* =====================================================
   SAVE
===================================================== */
function guardarServicio() {

    const descripcion = document.getElementById('descripcion').value.trim();
    const serviceId = document.getElementById('serviceId').value;

    const precios = [];
    Object.entries(prices).forEach(([catId, price]) => {
        precios.push({
            categoria_id: catId,
            precio: price
        });
    });

    fetch('../../backend/guardar_servicio.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: serviceId,
                descripcion: descripcion,
                precios: precios
            })
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.ok) {
                //alert('Servicio guardado correctamente');
                showAlert({
                    type: 'success',
                    message: 'Servicio guardado correctamente'
                });
                crudModal.hide();
                location.reload();
            } else {
                //alert(resp.error);
                showAlert({
                    type: 'danger',
                    message: resp.error
                });
            }
        })
        .catch(() => alert('Error de conexión'));
}

/* =====================================================
   DELETE
===================================================== */
function confirmDelete(id) {
    showAlert({
        type: 'danger',
        message: '¿Está seguro de eliminar este servicio?',
        subMessage: 'Esta acción no se puede deshacer',
        confirmText: 'Eliminar',
        onConfirm: () => {
            window.location.href = `index.php?action=delete&id=${id}`;
        }
    });
}

// Clean up modal state when hidden
document.addEventListener('DOMContentLoaded', function() {
    const crudModalEl = document.getElementById('crudModal');
    if (crudModalEl) {
        crudModalEl.addEventListener('hidden.bs.modal', function() {
            document.body.classList.remove('modal-open');
        });
    }
    
    // Debug: Log when page is ready
    console.log('Servicios page loaded successfully');
    console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? 'loaded' : 'not loaded');
    console.log('Modal elements found:', document.querySelectorAll('.modal').length);
    
    // Ensure all buttons are clickable
    document.addEventListener('click', function(e) {
        // Stop event propagation issues
        if (e.target.closest('.btn') || e.target.closest('button')) {
            console.log('Button clicked:', e.target);
        }
    }, true);
    
    // Fix any overlay conflicts
    const froshOverlay = document.getElementById('froshOverLay');
    if (froshOverlay) {
        froshOverlay.style.pointerEvents = 'none';
    }
    
    // Test button clicks
    document.querySelectorAll('.btn').forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            console.log(`Button ${index} clicked:`, this);
        });
    });
});
</script>


<?php require 'lavacar/partials/footer.php'; ?>