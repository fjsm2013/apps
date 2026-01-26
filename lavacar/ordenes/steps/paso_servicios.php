<div class="card">
    <div class="card-body">

        <h6 class="mb-3">Paso 2 de 5 - Servicios</h6>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th class="text-center">Acción</th>
                    <th class="text-end">Precio</th>
                </tr>
            </thead>

            <tbody id="services-body">

            <tfoot>
                <tr>
                    <td colspan="3" class="bg-light">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="mostrarFormularioServicioPersonalizado()">
                            <i class="fa-solid fa-plus me-1"></i>
                            Agregar Servicio Adicional
                        </button>
                    </td>
                </tr>
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
         <!--<div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fa-solid fa-plus-circle me-2 text-primary"></i>
                    Servicios Adicionales
                </h6>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="mostrarFormularioServicioPersonalizado()">
                    <i class="fa-solid fa-plus me-1"></i>
                    Agregar Servicio
                </button>
            </div>-->
            
            <!-- Mensaje informativo -->
             <!--<div class="alert alert-info py-2" id="info-servicios-personalizados">
                <i class="fa-solid fa-info-circle me-2"></i>
                <small>Haz clic en "Agregar Servicio" para añadir servicios personalizados directamente en la tabla.</small>
            </div>
            
    
        </div>-->

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-outline-dark" onclick="prevStep()">Atrás</button>
            <button class="btn btn-dark" onclick="nextStep()">Siguiente</button>
        </div>

    </div>
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
                
                .fila-edicion-servicio {
                    background-color: #fff8e1 !important;
                    border-left: 4px solid #ffc107;
                }
                
                .fila-edicion-servicio input {
                    border-color: #ffc107;
                }
                
                .fila-edicion-servicio input:focus {
                    border-color: #ff9800;
                    box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.25);
                }
                
                @media (max-width: 768px) {
                    .servicio-personalizado-row .btn-sm {
                        padding: 0.25rem 0.5rem;
                        font-size: 0.75rem;
                    }
                }
            </style>