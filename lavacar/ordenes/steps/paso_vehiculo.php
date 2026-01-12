<div class="card">
    <div class="card-body">

        <h6 class="mb-3">Paso 3 de 5 - Datos del Vehículo</h6>

        <!-- Información del vehículo -->
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Placa *</label>
                <input type="text" class="form-control" id="vehiculoPlaca" 
                       placeholder="Ej: ABC123" 
                       oninput="wizardState.vehiculo.placa = this.value.toUpperCase(); this.value = this.value.toUpperCase();">
                <small class="text-muted">Formato: 3 letras + 3 números</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <input type="text" class="form-control" id="vehiculoCategoria" readonly>
                <small class="text-muted">Seleccionada en el paso anterior</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Marca *</label>
                <input type="text" class="form-control" id="vehiculoMarca" 
                       placeholder="Ej: Toyota, Honda, Ford"
                       oninput="wizardState.vehiculo.marca = this.value">
            </div>

            <div class="col-md-6">
                <label class="form-label">Modelo *</label>
                <input type="text" class="form-control" id="vehiculoModelo" 
                       placeholder="Ej: Corolla, Civic, Focus"
                       oninput="wizardState.vehiculo.modelo = this.value">
            </div>

            <div class="col-md-4">
                <label class="form-label">Año *</label>
                <input type="number" class="form-control" id="vehiculoYear" 
                       placeholder="2020" min="1990" max="2030"
                       oninput="wizardState.vehiculo.year = this.value">
            </div>

            <div class="col-md-8">
                <label class="form-label">Color *</label>
                <input type="text" class="form-control" id="vehiculoColor" 
                       placeholder="Ej: Blanco, Negro, Azul"
                       oninput="wizardState.vehiculo.color = this.value">
            </div>
        </div>

        <!-- Información adicional para vehículos existentes -->
        <div id="vehiculoExistenteInfo" class="alert alert-success mt-3 d-none">
            <i class="fa-solid fa-check-circle me-2"></i>
            <strong>Vehículo existente encontrado.</strong> 
            Los datos han sido cargados automáticamente. Puede actualizarlos si es necesario.
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-outline-dark" onclick="prevStep()">
                <i class="fa-solid fa-arrow-left me-1"></i> Atrás
            </button>
            <button class="btn btn-dark" onclick="nextStep()">
                Siguiente <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </div>

    </div>
</div>