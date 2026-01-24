<div class="card">
    <div class="card-body">

        <h6 class="mb-3">Paso 2 de 5 - Servicios</h6>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th class="text-center">Seleccionar</th>
                    <th class="text-end">Precio</th>
                </tr>
            </thead>

            <tbody id="services-body">

            <tfoot>
                <tr>
                    <td colspan="2">Subtotal</td>
                    <td class="text-end" id="subtotal">₡0.00</td>
                </tr>
                <tr>
                    <td colspan="2">IVA (13%)</td>
                    <td class="text-end" id="iva">₡0.00</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td class="text-end"><strong id="total">₡0.00</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Sección de Servicios Personalizados -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fa-solid fa-plus-circle me-2 text-primary"></i>
                    Servicios Adicionales
                </h6>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="mostrarFormularioServicioPersonalizado()">
                    <i class="fa-solid fa-plus me-1"></i>
                    Agregar Servicio
                </button>
            </div>
            
            <!-- Mensaje informativo -->
            <div class="alert alert-info py-2" id="info-servicios-personalizados">
                <i class="fa-solid fa-info-circle me-2"></i>
                <small>Los servicios adicionales aparecerán en la tabla de arriba junto con los servicios regulares.</small>
            </div>
            
            <!-- CSS para servicios personalizados en la tabla -->
            <style>
                .servicio-personalizado-row {
                    background-color: #f8fff8 !important;
                    border-left: 4px solid #28a745;
                }
                
                .servicio-personalizado-row:hover {
                    background-color: #f0fff0 !important;
                }
                
                .servicio-personalizado-row .badge {
                    font-size: 0.7rem;
                }
                
                @media (max-width: 768px) {
                    .servicio-personalizado-row .btn-sm {
                        padding: 0.25rem 0.5rem;
                        font-size: 0.75rem;
                    }
                }
            </style>
            
            <!-- Formulario para agregar servicio personalizado (inicialmente oculto) -->
            <div id="formulario-servicio-personalizado" class="card border-primary" style="display: none;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fa-solid fa-edit me-2"></i>
                        Nuevo Servicio Adicional
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="servicio-personalizado-nombre" class="form-label">
                                <i class="fa-solid fa-tag me-1"></i>
                                Nombre del Servicio *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="servicio-personalizado-nombre" 
                                   placeholder="Ej: Limpieza de tapicería especial"
                                   maxlength="100">
                            <div class="form-text">Describe brevemente el servicio adicional</div>
                        </div>
                        <div class="col-md-4">
                            <label for="servicio-personalizado-precio" class="form-label">
                                <i class="fa-solid fa-dollar-sign me-1"></i>
                                Precio *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₡</span>
                                <input type="number" 
                                       class="form-control" 
                                       id="servicio-personalizado-precio" 
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01">
                            </div>
                            <div class="form-text">Precio sin IVA</div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="button" class="btn btn-success" onclick="agregarServicioPersonalizado()">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="cancelarServicioPersonalizado()">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-outline-dark" onclick="prevStep()">Atrás</button>
            <button class="btn btn-dark" onclick="nextStep()">Siguiente</button>
        </div>

    </div>
</div>