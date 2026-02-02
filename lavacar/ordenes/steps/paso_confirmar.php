<div class="card">
    <div class="card-body">
        <h6 class="mb-4">Paso 5 de 5 - Confirmar Orden</h6>

        <!-- Resumen de la Orden -->
        <div class="row">
            <!-- Información del Vehículo -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-car me-2"></i>Información del Vehículo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <strong>Placa:</strong><br>
                                <span id="cPlaca" class="text-primary fs-5">-</span>
                            </div>
                            <div class="col-6">
                                <strong>Categoría:</strong><br>
                                <span id="cCategoria">-</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-4">
                                <strong>Marca:</strong><br>
                                <span id="cMarca">-</span>
                            </div>
                            <div class="col-4">
                                <strong>Modelo:</strong><br>
                                <span id="cModelo">-</span>
                            </div>
                            <div class="col-4">
                                <strong>Año:</strong><br>
                                <span id="cYear">-</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <strong>Color:</strong><br>
                                <span id="cColor">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Cliente -->
            <div class="col-md-6 mb-4">
                <div class="card bg-light">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa-solid fa-user me-2"></i>Información del Cliente</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Nombre:</strong><br>
                            <span id="cClienteNombre">-</span>
                        </div>
                        <div class="mb-2">
                            <strong>Cédula:</strong><br>
                            <span id="cClienteCedula">-</span>
                        </div>
                        <div class="mb-2">
                            <strong>Correo:</strong><br>
                            <span id="cClienteCorreo">-</span>
                        </div>
                        <div class="mb-2">
                            <strong>Teléfono:</strong><br>
                            <span id="cClienteTelefono">-</span>
                        </div>
                        <div class="mb-0">
                            <strong>Empresa:</strong><br>
                            <span id="cClienteEmpresa">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Servicios Seleccionados -->
        <div class="card bg-light mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fa-solid fa-tags me-2"></i>Servicios Seleccionados</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody id="cServiciosTable">
                            <!-- Servicios se llenan dinámicamente -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Subtotal</th>
                                <th class="text-end" id="cSubtotal">₡0.00</th>
                            </tr>
                            <tr>
                                <th>IVA (13%)</th>
                                <th class="text-end" id="cIva">₡0.00</th>
                            </tr>
                            <tr class="table-success">
                                <th>Total</th>
                                <th class="text-end" id="cTotal">₡0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="mb-4">
            <label class="form-label">Observaciones (Opcional)</label>
            <textarea class="form-control" id="observaciones" rows="3" placeholder="Ingrese cualquier observación especial para esta orden..."></textarea>
        </div>

        <!-- Botones de Acción -->
        <div class="d-flex justify-content-between">
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark" onclick="prevStep()">
                    <i class="fa-solid fa-arrow-left me-1"></i> Atrás
                </button>
                
                <!--<button class="btn btn-outline-info" onclick="verOrdenesActivas()">
                    <i class="fa-solid fa-list me-1"></i> Ver Órdenes Activas
                </button>-->
            </div>

            <button class="btn btn-success btn-lg" onclick="guardarOrden()" id="confirmarBtn">
                <i class="fa-solid fa-check me-2"></i>
                <span id="confirmarBtnText">Confirmar Orden</span>
                <i class="fa-solid fa-spinner fa-spin d-none" id="confirmarSpinner"></i>
            </button>
        </div>
    </div>
</div>
