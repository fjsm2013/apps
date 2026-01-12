<?php
require_once 'lavacar/backend/ClientesManager.php';

$dbName = $user['company']['db'];
$clientesManager = new ClientesManager($conn, $dbName);
$clientes = $clientesManager->all();

/*
   Si el vehículo fue encontrado,
  asumimos que backend ya puso ClienteID en sesión
*/
$clienteSeleccionado = $_SESSION['orden']['cliente_id'] ?? null;
?>

<div class="card">
    <div class="card-body">

        <h6 class="mb-3">Paso 4 de 5 - Cliente</h6>

        <!-- Búsqueda por Cédula -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Buscar por Cédula</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="busquedaCedula" placeholder="Ingrese cédula del cliente">
                    <button class="btn btn-outline-primary" type="button" onclick="buscarClientePorCedula()">
                        <i class="fa-solid fa-search"></i> Buscar
                    </button>
                </div>
                <small class="text-muted">Si no existe, complete el formulario para crear un nuevo cliente</small>
            </div>
        </div>

        <!-- Formulario de Cliente -->
        <form id="clienteForm">
            <input type="hidden" id="clienteId" name="clienteId">
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Correo *</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono *</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Empresa</label>
                    <input type="text" class="form-control" id="empresa" name="empresa">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Distrito</label>
                    <input type="text" class="form-control" id="distrito" name="distrito">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cantón</label>
                    <input type="text" class="form-control" id="canton" name="canton">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="provincia" name="provincia">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">País</label>
                    <select class="form-select" id="pais" name="pais">
                        <option value="CR" selected>Costa Rica</option>
                        <option value="GT">Guatemala</option>
                        <option value="NI">Nicaragua</option>
                        <option value="PA">Panamá</option>
                        <option value="HN">Honduras</option>
                        <option value="SV">El Salvador</option>
                        <option value="US">Estados Unidos</option>
                        <option value="MX">México</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">IVA (%)</label>
                    <input type="number" class="form-control" id="iva" name="iva" value="13" min="0" max="100">
                </div>
            </div>

            <!-- Vehículos del Cliente -->
            <div id="vehiculosCliente" class="d-none">
                <hr>
                <h6 class="mb-3"><i class="fa-solid fa-car me-2"></i>Vehículos del Cliente</h6>
                <div class="row" id="vehiculosGrid">
                    <!-- Vehículos se cargan dinámicamente -->
                </div>
                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    Seleccione un vehículo existente o continúe para agregar uno nuevo.
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-dark" onclick="prevStep()">
                    <i class="fa-solid fa-arrow-left me-1"></i> Atrás
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="guardarClienteBtn">
                        <span id="submitBtnText">Guardar Cliente</span>
                        <i class="fa-solid fa-spinner fa-spin d-none" id="submitSpinner"></i>
                    </button>
                    
                    <button type="button" class="btn btn-dark" onclick="nextStep()" id="siguienteBtn" disabled>
                        Siguiente <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
