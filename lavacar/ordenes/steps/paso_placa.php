<?php
require_once 'lavacar/backend/CategoriaVehiculoManager.php';

$dbName = $user['company']['db'];
$catManager = new CategoriaVehiculoManager($conn, $dbName);

// Todas las categorías activas
$categorias = $catManager->all(true);
?>
<div class="container">

    <div class="row justify-content-center mb-2">

        <!-- BUSCAR POR PLACA -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="form-group" style="padding:5px; border-radius:8px;">

                <p class="fw-bold mb-2">
                    Buscar por Placa (6 dígitos)
                </p>

                <div class="d-flex">
                    <div></div><input type="text" class="form-control me-2" id="busquedaPlaca" placeholder="ABC123"
                        oninput="wizardState.vehiculo.placa = this.value.toUpperCase(); this.value = this.value.toUpperCase();">

                    <button class="btn btn-dark" onclick="buscarPlaca()">
                        <i class="fa-solid fa-magnifying-glass me-1"></i>
                    </button>
                </div>
                <p><small class="text-muted d-block text-center">
                        Puede buscar por placa o seleccionar la categoría del vehículo
                    </small></p>

            </div>



            <div id="resultadoPlaca" class="mt-2 small"></div>
        </div>
    </div>

</div>

<!-- O SELECCIONAR CATEGORÍA -->
<div class="row">

    <?php foreach ($categorias as $cat): ?>
    <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card card-admin h-100 vehicle-card" data-cat-id="<?= (int)$cat['ID'] ?>" style="cursor:pointer"
            onclick="seleccionarCategoria(
            <?= (int)$cat['ID'] ?>,
            '<?= htmlspecialchars($cat['TipoVehiculo']) ?>'
         )">

            <div class="card-body text-center">
                <i class="fa-solid fa-car-side fa-2x mb-2"></i>
                <h6 class="card-title mb-0">
                    <?= htmlspecialchars($cat['TipoVehiculo']) ?>
                </h6>
            </div>

        </div>
    </div>

    <?php endforeach; ?>

</div>

<!-- CONTROLES -->
<div class="text-center mt-3">
    <button class="btn btn-dark" onclick="nextStep()">
        Siguiente <i class="fa-solid fa-arrow-right ms-1"></i>
    </button>
</div>

</div>