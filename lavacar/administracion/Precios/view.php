<div class="container">
    <header>
        <h1>Sistema de Gestión de Precios</h1>
        <p class="subtitle">Gestiona los precios de todos los servicios</p>
    </header>

    <?php if (!empty($mensaje)) : ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <div class="card mt-3">
        <h2>Lista de Servicios y Precios</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Precio</th>
                    <th>Servicio</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Impuesto (%)</th>
                    <th>Precio Final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicios as $servicio) :
                    $precioExistente = $preciosExistentes[$servicio['ID']] ?? ['Precio' => 0, 'Descuento' => 0, 'Impuesto' => 13, 'Descripcion' => ''];
                    $precioFinal = $precioExistente['Precio'] - $precioExistente['Descuento'];
                    $precioFinal += $precioFinal * ($precioExistente['Impuesto'] / 100);
                ?>
                    <tr>
                        <td><?= $precioExistente['ID'] ?? 'N/A' ?></td>
                        <td><?= htmlspecialchars($servicio['Descripcion']) ?></td>
                        <td><?= htmlspecialchars($servicio['CategoriaDesc'] ?: 'General') ?></td>
                        <td>$<?= number_format($precioExistente['Precio'], 2) ?></td>
                        <td>$<?= number_format($precioExistente['Descuento'], 2) ?></td>
                        <td><?= number_format($precioExistente['Impuesto'], 2) ?>%</td>
                        <td><strong>$<?= number_format($precioFinal, 2) ?></strong></td>
                        <td>
                            <a href="?editar_id=<?= $precioExistente['ID'] ?? $servicio['ID'] ?>"
                                class="btn btn-sm btn-outline-primary">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
    // Mostrar formulario de edición si se hizo clic en "Editar"
    if (isset($_GET['editar_id'])):
        $editarId = $_GET['editar_id'];
        $servicioEditar = null;
        foreach ($servicios as $s) {
            if ($s['ID'] == $editarId) {
                $servicioEditar = $s;
                break;
            }
        }
        $precioEditar = $preciosExistentes[$editarId] ?? ['Precio' => 0, 'Descuento' => 0, 'Impuesto' => 13, 'Descripcion' => ''];
    ?>
        <div class="card mt-3">
            <h3>Editar Precio: <?= htmlspecialchars($servicioEditar['Descripcion'] ?? 'Desconocido') ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="guardar">
                <input type="hidden" name="precio_id" value="<?= $editarId ?>">

                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" step="0.01" min="0" name="servicios[<?= $editarId ?>][Precio]"
                        value="<?= $precioEditar['Precio'] ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Descuento</label>
                    <input type="number" step="0.01" min="0" name="servicios[<?= $editarId ?>][Descuento]"
                        value="<?= $precioEditar['Descuento'] ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Impuesto (%)</label>
                    <input type="number" step="0.01" min="0" name="servicios[<?= $editarId ?>][Impuesto]"
                        value="<?= $precioEditar['Impuesto'] ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <input type="text" name="servicios[<?= $editarId ?>][Descripcion]"
                        value="<?= $precioEditar['Descripcion'] ?>" class="form-control">
                    <input type="hidden" name="servicios[<?= $editarId ?>][PackageID]" value="0">
                </div>
                <button type="submit" class="btn-success">Guardar Cambios</button>
                <a href="?" class="btn btn-outline-secondary">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>
</div>