/* =========================
   STATE
========================= */
const wizardState = {
    step: 1,

    vehiculo: {
        id: null,
        placa: null,
        categoria_id: null,
        categoria_nombre: null,
        existente: false
    },

    servicios: [],
    cliente: {},
    totales: {
        subtotal: 0,
        iva: 0,
        total: 0
    },
    
    // Para detectar cambios de categoría
    lastCategoriaId: null
};

/* =========================
   NAVIGATION
========================= */
function goToStep(step) {

    console.log('➡️ goToStep:', step);

    const allSteps = document.querySelectorAll('.wizard-step-content');
    console.log('🧩 wizard-step-content encontrados:', allSteps.length);

    allSteps.forEach(s => s.classList.add('d-none'));

    const target = document.querySelector(
        `.wizard-step-content[data-step="${step}"]`
    );

    console.log('🎯 target:', target);

    if (!target) {
        console.error('❌ NO EXISTE PASO', step);
        return;
    }

    target.classList.remove('d-none');

    wizardState.step = step;
    updateWizardProgress(step);

    if (step === 2) cargarServicios();
    if (step === 3) cargarDatosVehiculo();
    if (step === 4) cargarPasoCliente();
    if (step === 5) cargarResumenConfirmacion();
}


function nextStep() {
    if (!validateStep(wizardState.step)) return;
    goToStep(wizardState.step + 1);
}

function prevStep() {
    goToStep(wizardState.step - 1);
}

/* =========================
   VALIDATION
========================= */
function validateStep(step) {

    switch (step) {
        case 1:
            if (!wizardState.vehiculo.categoria_id) {
                showAlert({
                    type: 'warning',
                    message: 'Busque una placa o seleccione una categoría'
                });
                return false;
            }
            break;

        case 2:
            if (wizardState.servicios.length === 0) {
                showAlert({
                    type: 'warning',
                    message: 'Seleccione al menos un servicio'
                });
                return false;
            }
            break;
        
        case 3:
            const v = wizardState.vehiculo;
            
            // Si es vehículo existente, deshabilitar algunos campos
            if (wizardState.vehiculo.existente) {
                const campos = ['vehiculoPlaca', 'vehiculoMarca', 'vehiculoModelo', 'vehiculoYear', 'vehiculoColor'];
                campos.forEach(campo => {
                    const element = document.getElementById(campo);
                    if (element) element.disabled = false; // Permitir edición para actualizar datos
                });
            }

            // Validar campos requeridos
            if (!v.placa || !v.marca || !v.modelo || !v.year || !v.color) {
                showAlert({
                    type: 'warning',
                    message: 'Complete todos los datos del vehículo (placa, marca, modelo, año y color)'
                });
                return false;
            }

            // Validar formato de placa (básico)
            if (v.placa.length < 6) {
                showAlert({
                    type: 'warning',
                    message: 'La placa debe tener al menos 6 caracteres'
                });
                return false;
            }

            // Validar año
            const currentYear = new Date().getFullYear();
            if (v.year < 1990 || v.year > currentYear + 1) {
                showAlert({
                    type: 'warning',
                    message: 'Ingrese un año válido para el vehículo'
                });
                return false;
            }
        break;

        case 4:
            if (!wizardState.cliente.id) {
                showAlert({
                    type: 'warning',
                    message: 'Seleccione un cliente'
                });
                return false;
            }
            break;
    }

    return true;
}

/* =========================
   PROGRESS BAR
========================= */
function updateWizardProgress(step) {

    document.querySelectorAll('.wizard-step').forEach(s => {
        const sStep = parseInt(s.dataset.step);
        s.classList.remove('active', 'completed');

        if (sStep < step) s.classList.add('completed');
        if (sStep === step) s.classList.add('active');
    });

    document.querySelectorAll('.line').forEach((line, index) => {
        line.classList.toggle('completed', index < step - 1);
    });
}

/* =========================
   STEP 1 – VEHÍCULO
========================= */
function buscarPlaca() {

    const placa = document.getElementById('busquedaPlaca').value.trim().toUpperCase();

    if (placa.length < 5) {
        showAlert({ type: 'warning', message: 'Placa inválida' });
        return;
    }

    // Actualizar placa en el estado
    wizardState.vehiculo.placa = placa;
    // También actualizar el campo visual
    document.getElementById('busquedaPlaca').value = placa;

    fetch(`buscar_vehiculo.php?placa=${placa}`)
        .then(r => r.json())
        .then(data => {

            if (!data.ok) {
                showAlert({
                    type: 'info',
                    message: 'Placa no encontrada, seleccione categoría'
                });
                return;
            }

            // Actualizar estado del vehículo con todos los datos
            wizardState.vehiculo = {
                id: data.vehiculo.ID,
                placa: data.vehiculo.Placa,
                categoria_id: data.vehiculo.CategoriaVehiculo,
                categoria_nombre: data.vehiculo.TipoVehiculo,
                marca: data.vehiculo.Marca || '',
                modelo: data.vehiculo.Modelo || '',
                year: data.vehiculo.Year || '',
                color: data.vehiculo.Color || '',
                existente: true
            };

            // Si el vehículo tiene cliente asociado, pre-seleccionarlo
            if (data.cliente) {
                // Guardar referencia del cliente en sesión para el paso 4
                wizardState.cliente = {
                    id: data.cliente.ID,
                    email: data.cliente.Correo
                };
                
                // Guardar en sesión para que el backend lo use
                fetch('ajax/set-session-cliente.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cliente_id: data.cliente.ID })
                }).catch(e => console.log('Info: No se pudo pre-seleccionar cliente'));
                
                showAlert({
                    type: 'success',
                    message: `Vehículo encontrado (${data.vehiculo.TipoVehiculo}) - Cliente: ${data.cliente.NombreCompleto}`
                });
            } else {
                showAlert({
                    type: 'success',
                    message: `Vehículo encontrado (${data.vehiculo.TipoVehiculo})`
                });
            }

            marcarCategoriaSeleccionada(data.vehiculo.CategoriaVehiculo);

            console.log('🚗 Vehículo completo cargado:', wizardState.vehiculo);
        });
}

function seleccionarCategoria(id, nombre) {

    const placaActual = wizardState.vehiculo.placa || document.getElementById('busquedaPlaca')?.value;

    wizardState.vehiculo = {
        id: null,
        placa: placaActual,
        categoria_id: id,
        categoria_nombre: nombre,
        marca: '',
        modelo: '',
        year: '',
        color: '',
        existente: false
    };

    marcarCategoriaSeleccionada(id);
}

/* =========================
   UI – CATEGORY HIGHLIGHT
========================= */
function marcarCategoriaSeleccionada(catId) {

    document.querySelectorAll('.card-admin')
        .forEach(card => card.classList.remove('active'));

    const selected = document.querySelector(
        `.card-admin[data-cat-id="${catId}"]`
    );

    if (selected) selected.classList.add('active');
}

/* =========================
   STEP 2 – SERVICIOS
========================= */
function cargarServicios() {
    
    // Verificar si cambió la categoría del vehículo
    const categoriaActual = wizardState.vehiculo.categoria_id;
    const categoriaAnterior = wizardState.lastCategoriaId;
    
    console.log('🔍 Verificando categoría:', {
        actual: categoriaActual,
        anterior: categoriaAnterior,
        serviciosActuales: wizardState.servicios.length
    });
    
    // Si cambió la categoría, limpiar servicios seleccionados
    if (categoriaAnterior && categoriaAnterior !== categoriaActual) {
        console.log('🔄 Categoría cambió, limpiando servicios seleccionados');
        wizardState.servicios = [];
        
        // También limpiar servicios personalizados
        serviciosPersonalizados = [];
        contadorServiciosPersonalizados = 0;
        
        // Limpiar las filas de servicios personalizados de la tabla
        const serviciosPersonalizadosExistentes = document.querySelectorAll('.servicio-personalizado-row');
        serviciosPersonalizadosExistentes.forEach(row => row.remove());
        
        showAlert({
            type: 'info',
            message: 'Categoría de vehículo cambió. Seleccione nuevamente los servicios.'
        });
    }
    
    // Guardar la categoría actual para futuras comparaciones
    wizardState.lastCategoriaId = categoriaActual;

    fetch('get_servicios_por_categoria.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            categoria_id: wizardState.vehiculo.categoria_id
        })
    })
    .then(r => r.json())
    .then(renderServicios);
}

function renderServicios(servicios) {

    console.log('🟢 renderServicios()', servicios);

    const tbody = document.getElementById('services-body');

    if (!tbody) {
        console.error('❌ services-body NO existe en el DOM');
        return;
    }

    tbody.innerHTML = '';

    servicios.forEach(s => {
        // Verificar si este servicio ya está seleccionado
        const servicioSeleccionado = wizardState.servicios.find(sel => sel.id === parseInt(s.ID));
        const isChecked = servicioSeleccionado ? 'checked' : '';
        const precioMostrar = servicioSeleccionado ? servicioSeleccionado.precio : 0;
        
        tbody.innerHTML += `
            <tr>
                <td>${s.Descripcion}</td>
                <td class="text-center">
                    <label class="switch">
                        <input type="checkbox"
                            data-id="${s.ID}"
                            data-precio="${s.Precio}"
                            data-nombre="${s.Descripcion}"
                            onchange="toggleServicio(this)"
                            ${isChecked}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td class="text-end" id="price-${s.ID}">
                    ${formatCurrency(precioMostrar)}
                </td>
            </tr>
        `;
    });
    
    // Recalcular totales después de renderizar
    recalcularTotales();
}


function toggleServicio(el) {

    const servicioId = parseInt(el.dataset.id);
    const precio = parseFloat(el.dataset.precio);
    const nombre = el.dataset.nombre;

    if (el.checked) {
        // Verificar si ya existe para evitar duplicados
        const existeIndex = wizardState.servicios.findIndex(s => s.id === servicioId);
        
        if (existeIndex === -1) {
            // No existe, agregarlo
            wizardState.servicios.push({ 
                id: servicioId, 
                precio: precio,
                nombre: nombre 
            });
        } else {
            // Ya existe, actualizar precio por si acaso
            wizardState.servicios[existeIndex].precio = precio;
        }
        
        document.getElementById(`price-${servicioId}`)
            .textContent = formatCurrency(precio);
    } else {
        // Remover del estado
        wizardState.servicios =
            wizardState.servicios.filter(s => s.id !== servicioId);

        document.getElementById(`price-${servicioId}`)
            .textContent = formatCurrency(0);
    }

    console.log('🔄 Servicios actualizados:', wizardState.servicios);
    recalcularTotales();
}

/* =========================
   STEP 3 – DATOS VEHÍCULO
========================= */
function cargarDatosVehiculo() {
    console.log('🚗 cargarDatosVehiculo()', wizardState.vehiculo);
    
    // Llenar campos con datos existentes
    const campos = {
        'vehiculoPlaca': wizardState.vehiculo.placa || '',
        'vehiculoCategoria': wizardState.vehiculo.categoria_nombre || '',
        'vehiculoMarca': wizardState.vehiculo.marca || '',
        'vehiculoModelo': wizardState.vehiculo.modelo || '',
        'vehiculoYear': wizardState.vehiculo.year || '',
        'vehiculoColor': wizardState.vehiculo.color || ''
    };
    
    Object.entries(campos).forEach(([id, valor]) => {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.value = valor;
        }
    });
    
    // Mostrar información si es vehículo existente
    const infoExistente = document.getElementById('vehiculoExistenteInfo');
    if (infoExistente) {
        if (wizardState.vehiculo.existente) {
            infoExistente.classList.remove('d-none');
        } else {
            infoExistente.classList.add('d-none');
        }
    }
    
    // Si no hay placa pero se ingresó en el paso 1, usar esa
    if (!wizardState.vehiculo.placa) {
        const placaPaso1 = document.getElementById('busquedaPlaca')?.value;
        if (placaPaso1) {
            wizardState.vehiculo.placa = placaPaso1.toUpperCase();
            const campoPlaca = document.getElementById('vehiculoPlaca');
            if (campoPlaca) {
                campoPlaca.value = wizardState.vehiculo.placa;
            }
        }
    }
    
    // Si hay cliente pre-seleccionado, cargar sus datos en el paso 4
    if (wizardState.cliente.id && !document.getElementById('clienteId').value) {
        preCargarClienteEncontrado();
    }
}

function preCargarClienteEncontrado() {
    // Esta función se ejecutará cuando se llegue al paso 4
    // Los datos del cliente ya están en wizardState.cliente
    console.log('👤 Cliente pre-seleccionado disponible:', wizardState.cliente);
}

/* =========================
   STEP 4 – CLIENTE
========================= */
function cargarPasoCliente() {
    console.log('👤 cargarPasoCliente()', wizardState.cliente);
    
    // Si hay un cliente pre-seleccionado del vehículo encontrado, cargarlo
    if (wizardState.cliente.id && !document.getElementById('clienteId').value) {
        // Buscar el cliente por ID para cargar todos sus datos
        fetch(`ajax/cliente-handler.php?action=get&id=${wizardState.cliente.id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fillClienteForm(data.cliente);
                    showAlert({
                        type: 'info',
                        message: 'Cliente del vehículo cargado automáticamente'
                    });
                }
            })
            .catch(error => {
                console.log('Info: No se pudo cargar cliente automáticamente');
            });
    }
}

/* =========================
   TOTALS
========================= */
function recalcularTotales() {

    const subtotal = wizardState.servicios
        .reduce((sum, s) => sum + s.precio, 0);

    const iva = subtotal * 0.13;
    const total = subtotal + iva;

    document.getElementById('subtotal').textContent = formatCurrency(subtotal);
    document.getElementById('iva').textContent = formatCurrency(iva);
    document.getElementById('total').textContent = formatCurrency(total);
}

/* =========================
   CURRENCY FORMAT
========================= */
function formatCurrency(value) {
    return value.toLocaleString('es-CR', {
        style: 'currency',
        currency: 'CRC'
    });
}

/* =========================
   ALERTS & NOTIFICATIONS
========================= */
function showAlert({ type, message }) {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-custom');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create alert element (toast style in top-right corner)
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${getBootstrapAlertType(type)} alert-dismissible fade show alert-custom`;
    alertDiv.style.cssText = `
        position: fixed; 
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 320px; 
        max-width: 400px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        border: none;
        font-weight: 500;
        animation: slideInRight 0.3s ease-out;
    `;
    
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fa-solid ${getAlertIcon(type)} me-2" style="font-size: 1.1em;"></i>
            <span class="flex-grow-1">${message}</span>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Add CSS animation keyframes if not already added
    if (!document.querySelector('#toast-animations')) {
        const style = document.createElement('style');
        style.id = 'toast-animations';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Add to page
    document.body.appendChild(alertDiv);
    
    // Fast close on close button click
    const closeBtn = alertDiv.querySelector('.btn-close');
    closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.style.animation = 'slideOutRight 0.2s ease-in';
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) alertDiv.remove();
            }, 200);
        }
    });
    
    // Auto-remove after 3 seconds with slide-out animation
    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) alertDiv.remove();
            }, 300);
        }
    }, 3000);
}

function getBootstrapAlertType(type) {
    const typeMap = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };
    return typeMap[type] || 'info';
}

function getAlertIcon(type) {
    const iconMap = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-triangle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    return iconMap[type] || 'fa-info-circle';
}
function setCliente(cliente) {
    wizardState.cliente = {
        id: cliente.id || null,
        email: cliente.email || null
    };
    
    // Enable/disable next button
    const nextBtn = document.getElementById('siguienteBtn');
    if (nextBtn) {
        nextBtn.disabled = !cliente.id;
    }
}

/* =========================
   CLIENT MANAGEMENT
========================= */
function buscarClientePorCedula() {
    const cedula = document.getElementById('busquedaCedula').value.trim();
    
    if (!cedula) {
        showAlert({
            type: 'warning',
            message: 'Ingrese una cédula para buscar'
        });
        return;
    }
    
    // Show loading state
    const searchBtn = document.querySelector('button[onclick="buscarClientePorCedula()"]');
    const originalText = searchBtn.innerHTML;
    searchBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Buscando...';
    searchBtn.disabled = true;
    
    console.log('🔍 Buscando cliente con cédula:', cedula);
    
    fetch(`ajax/cliente-handler.php?action=searchByCedula&cedula=${encodeURIComponent(cedula)}`)
        .then(response => {
            console.log('📡 Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('📦 Response data:', data);
            
            if (data.success) {
                if (data.found) {
                    // Cliente encontrado - llenar formulario
                    fillClienteForm(data.cliente);
                    showAlert({
                        type: 'success',
                        message: 'Cliente encontrado'
                    });
                } else {
                    // Cliente no encontrado - limpiar formulario y poner cédula
                    clearClienteForm();
                    document.getElementById('cedula').value = cedula;
                    showAlert({
                        type: 'info',
                        message: 'Cliente no encontrado. Complete el formulario para crear uno nuevo.'
                    });
                }
            } else {
                showAlert({
                    type: 'error',
                    message: data.message || 'Error en la búsqueda'
                });
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            showAlert({
                type: 'error',
                message: 'Error al buscar cliente: ' + error.message
            });
        })
        .finally(() => {
            // Reset button state
            searchBtn.innerHTML = originalText;
            searchBtn.disabled = false;
        });
}

function fillClienteForm(cliente) {
    document.getElementById('clienteId').value = cliente.ID;
    document.getElementById('nombreCompleto').value = cliente.NombreCompleto || '';
    document.getElementById('cedula').value = cliente.Cedula || '';
    document.getElementById('cedula').readOnly = true; // Lock cedula when editing
    document.getElementById('correo').value = cliente.Correo || '';
    document.getElementById('telefono').value = cliente.Telefono || '';
    document.getElementById('empresa').value = cliente.Empresa || '';
    document.getElementById('direccion').value = cliente.Direccion || '';
    document.getElementById('distrito').value = cliente.Distrito || '';
    document.getElementById('canton').value = cliente.Canton || '';
    document.getElementById('provincia').value = cliente.Provincia || '';
    document.getElementById('pais').value = cliente.Pais || 'CR';
    document.getElementById('iva').value = cliente.IVA || '13';
    
    // Set client in wizard state
    setCliente({
        id: cliente.ID,
        email: cliente.Correo
    });
    
    // Update button text for editing
    document.getElementById('submitBtnText').textContent = 'Actualizar Cliente';
    
    // Load client vehicles
    cargarVehiculosCliente(cliente.ID);
}

function clearClienteForm() {
    const form = document.getElementById('clienteForm');
    form.reset();
    document.getElementById('clienteId').value = '';
    document.getElementById('cedula').readOnly = false; // Allow editing cedula for new client
    document.getElementById('pais').value = 'CR';
    document.getElementById('iva').value = '13';
    document.getElementById('submitBtnText').textContent = 'Guardar Cliente';
    
    // Hide vehicles section
    ocultarVehiculosCliente();
    
    // Clear wizard state
    setCliente({});
}

/* =========================
   CLIENT VEHICLES
========================= */
function cargarVehiculosCliente(clienteId) {
    console.log('🚗 Cargando vehículos del cliente:', clienteId);
    
    fetch(`ajax/cliente-handler.php?action=getVehiculos&clienteId=${clienteId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.vehiculos.length > 0) {
                mostrarVehiculosCliente(data.vehiculos);
            } else {
                ocultarVehiculosCliente();
            }
        })
        .catch(error => {
            console.error('Error cargando vehículos:', error);
            ocultarVehiculosCliente();
        });
}

function mostrarVehiculosCliente(vehiculos) {
    const container = document.getElementById('vehiculosCliente');
    const grid = document.getElementById('vehiculosGrid');
    
    if (!container || !grid) return;
    
    grid.innerHTML = '';
    
    vehiculos.forEach(vehiculo => {
        const vehiculoCard = document.createElement('div');
        vehiculoCard.className = 'col-md-6 col-lg-4 mb-3';
        vehiculoCard.innerHTML = `
            <div class="card vehicle-card h-100" onclick="seleccionarVehiculoCliente(${vehiculo.ID}, '${vehiculo.Placa}', ${vehiculo.CategoriaVehiculo}, '${vehiculo.TipoVehiculo}', '${vehiculo.Marca}', '${vehiculo.Modelo}', '${vehiculo.Year}', '${vehiculo.Color}')">
                <div class="card-body text-center">
                    <i class="fa-solid fa-car fa-2x mb-2 text-primary"></i>
                    <h6 class="card-title">${vehiculo.Placa}</h6>
                    <p class="card-text small mb-1">
                        <strong>${vehiculo.Marca} ${vehiculo.Modelo}</strong><br>
                        ${vehiculo.Year} - ${vehiculo.Color}<br>
                        <span class="badge bg-secondary">${vehiculo.TipoVehiculo}</span>
                    </p>
                </div>
            </div>
        `;
        grid.appendChild(vehiculoCard);
    });
    
    container.classList.remove('d-none');
}

function ocultarVehiculosCliente() {
    const container = document.getElementById('vehiculosCliente');
    if (container) {
        container.classList.add('d-none');
    }
}

function seleccionarVehiculoCliente(id, placa, categoriaId, categoriaNombre, marca, modelo, year, color) {
    // Update wizard state with selected vehicle
    wizardState.vehiculo = {
        id: id,
        placa: placa,
        categoria_id: categoriaId,
        categoria_nombre: categoriaNombre,
        marca: marca,
        modelo: modelo,
        year: year,
        color: color,
        existente: true
    };
    
    // Visual feedback
    document.querySelectorAll('.vehicle-card').forEach(card => {
        card.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    showAlert({
        type: 'success',
        message: `Vehículo ${placa} seleccionado`
    });
    
    console.log('🚗 Vehículo seleccionado:', wizardState.vehiculo);
}

/* =========================
   NAVIGATION HELPERS
========================= */
function verOrdenesActivas() {
    // Open orders list in new tab/window
    window.open('../reportes/ordenes-activas.php', '_blank');
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('clienteForm');
    if (form) {
        form.addEventListener('submit', handleClienteSubmit);
    }
    
    // Allow Enter key in search field
    const searchField = document.getElementById('busquedaCedula');
    if (searchField) {
        searchField.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarClientePorCedula();
            }
        });
    }
});

function handleClienteSubmit(e) {
    e.preventDefault();
    
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submitBtnText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.classList.add('d-none');
    submitSpinner.classList.remove('d-none');
    
    const formData = new FormData(e.target);
    const clienteId = formData.get('clienteId');
    
    // Determine action
    formData.append('action', clienteId ? 'update' : 'create');
    
    fetch('ajax/cliente-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert({
                type: 'success',
                message: data.message
            });
            
            // Update wizard state
            setCliente({
                id: data.cliente.ID,
                email: data.cliente.Correo
            });
            
            // Update form with saved data (in case of new client)
            if (!clienteId) {
                document.getElementById('clienteId').value = data.cliente.ID;
                document.getElementById('submitBtnText').textContent = 'Actualizar Cliente';
            }
            
        } else {
            showAlert({
                type: 'error',
                message: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert({
            type: 'error',
            message: 'Error al procesar la solicitud'
        });
    })
    .finally(() => {
        // Reset loading state
        submitBtn.disabled = false;
        submitText.classList.remove('d-none');
        submitSpinner.classList.add('d-none');
    });
}

// Remove old modal functions that are no longer needed
function openClienteModal() { /* deprecated */ }
function editCliente() { /* deprecated */ }
function loadClienteData() { /* deprecated */ }
function updateClienteSelect() { /* deprecated */ }
function setOptionData() { /* deprecated */ }

/* =========================
   STEP 5 - CONFIRMATION
========================= */
function cargarResumenConfirmacion() {
    // Información del vehículo
    document.getElementById('cPlaca').textContent = wizardState.vehiculo.placa || 'Nueva placa';
    document.getElementById('cCategoria').textContent = wizardState.vehiculo.categoria_nombre || '-';
    document.getElementById('cMarca').textContent = wizardState.vehiculo.marca || '-';
    document.getElementById('cModelo').textContent = wizardState.vehiculo.modelo || '-';
    document.getElementById('cYear').textContent = wizardState.vehiculo.year || '-';
    document.getElementById('cColor').textContent = wizardState.vehiculo.color || '-';
    
    // Información del cliente
    const clienteForm = document.getElementById('clienteForm');
    if (clienteForm) {
        document.getElementById('cClienteNombre').textContent = 
            document.getElementById('nombreCompleto').value || '-';
        document.getElementById('cClienteCedula').textContent = 
            document.getElementById('cedula').value || '-';
        document.getElementById('cClienteCorreo').textContent = 
            document.getElementById('correo').value || '-';
        document.getElementById('cClienteTelefono').textContent = 
            document.getElementById('telefono').value || '-';
        document.getElementById('cClienteEmpresa').textContent = 
            document.getElementById('empresa').value || 'N/A';
    }
    
    // Servicios seleccionados
    cargarServiciosConfirmacion();
}

function cargarServiciosConfirmacion() {
    const tbody = document.getElementById('cServiciosTable');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    let subtotal = 0;
    
    wizardState.servicios.forEach(servicio => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${servicio.nombre || 'Servicio'}</td>
            <td class="text-end">${formatCurrency(servicio.precio)}</td>
        `;
        tbody.appendChild(row);
        subtotal += servicio.precio;
    });
    
    const iva = subtotal * 0.13;
    const total = subtotal + iva;
    
    document.getElementById('cSubtotal').textContent = formatCurrency(subtotal);
    document.getElementById('cIva').textContent = formatCurrency(iva);
    document.getElementById('cTotal').textContent = formatCurrency(total);
    
    // Guardar totales en el estado para el guardado
    wizardState.totales = {
        subtotal: subtotal,
        iva: iva,
        total: total
    };
}

function guardarOrden() {
    // Validar que tenemos toda la información necesaria
    if (!wizardState.vehiculo.categoria_id) {
        showAlert({
            type: 'error',
            message: 'Error: Información del vehículo incompleta'
        });
        return;
    }
    
    if (wizardState.servicios.length === 0) {
        showAlert({
            type: 'error',
            message: 'Error: No hay servicios seleccionados'
        });
        return;
    }
    
    if (!wizardState.cliente.id) {
        showAlert({
            type: 'error',
            message: 'Error: No hay cliente seleccionado'
        });
        return;
    }
    
    // Mostrar estado de carga
    const btn = document.getElementById('confirmarBtn');
    const btnText = document.getElementById('confirmarBtnText');
    const spinner = document.getElementById('confirmarSpinner');
    
    btn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');
    
    // Preparar datos para enviar
    const ordenData = {
        vehiculo: {
            id: wizardState.vehiculo.id,
            placa: wizardState.vehiculo.placa || document.getElementById('placaInput')?.value,
            categoria_id: wizardState.vehiculo.categoria_id,
            marca: wizardState.vehiculo.marca,
            modelo: wizardState.vehiculo.modelo,
            year: wizardState.vehiculo.year,
            color: wizardState.vehiculo.color,
            existente: wizardState.vehiculo.existente
        },
        cliente: {
            id: wizardState.cliente.id,
            email: wizardState.cliente.email
        },
        servicios: wizardState.servicios.map(s => ({
            id: s.id,
            precio: s.precio,
            nombre: s.nombre || null,
            personalizado: s.personalizado || false
        })),
        observaciones: document.getElementById('observaciones').value.trim()
    };
    
    console.log('📤 Enviando orden:', ordenData);
    
    // Enviar orden al servidor
    fetch('guardar_orden.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(ordenData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert({
                type: 'success',
                message: 'Orden creada exitosamente'
            });
            
            // Redirigir después de un momento
            setTimeout(() => {
                // Limpiar estado del wizard
                resetWizard();
                window.location.href = 'index.php';
            }, 2000);
            
        } else {
            throw new Error(data.message || 'Error al crear la orden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert({
            type: 'error',
            message: 'Error al crear la orden: ' + error.message
        });
    })
    .finally(() => {
        // Restaurar botón
        btn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    });
}

/* =========================
   WIZARD RESET
========================= */
function resetWizard() {
    // Resetear estado
    wizardState.step = 1;
    wizardState.vehiculo = {
        id: null,
        placa: null,
        categoria_id: null,
        categoria_nombre: null,
        marca: '',
        modelo: '',
        year: '',
        color: '',
        existente: false
    };
    wizardState.servicios = [];
    wizardState.cliente = {};
    wizardState.totales = {
        subtotal: 0,
        iva: 0,
        total: 0
    };
    wizardState.lastCategoriaId = null;
    
    // Limpiar formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => form.reset());
    
    // Limpiar campos específicos
    const busquedaPlaca = document.getElementById('busquedaPlaca');
    if (busquedaPlaca) busquedaPlaca.value = '';
    
    const observaciones = document.getElementById('observaciones');
    if (observaciones) observaciones.value = '';
    
    // Resetear checkboxes de servicios
    const serviceCheckboxes = document.querySelectorAll('#services-body input[type="checkbox"]');
    serviceCheckboxes.forEach(cb => cb.checked = false);
    
    // Volver al paso 1
    goToStep(1);
}

/* =========================
   SERVICIOS PERSONALIZADOS
========================= */

// Array para almacenar servicios personalizados
let serviciosPersonalizados = [];
let contadorServiciosPersonalizados = 0;

function mostrarFormularioServicioPersonalizado() {
    // En lugar de mostrar un formulario separado, agregar una fila editable directamente en la tabla
    const tbody = document.getElementById('services-body');
    
    // Verificar si ya hay una fila de edición activa
    const filaEdicionExistente = tbody.querySelector('.fila-edicion-servicio');
    if (filaEdicionExistente) {
        // Si ya existe, hacer focus en el campo de nombre
        filaEdicionExistente.querySelector('.input-nombre-servicio').focus();
        return;
    }
    
    // Crear nueva fila editable
    contadorServiciosPersonalizados++;
    const row = document.createElement('tr');
    row.className = 'servicio-personalizado-row fila-edicion-servicio';
    row.innerHTML = `
        <td>
            <input type="text" 
                   class="form-control input-nombre-servicio" 
                   placeholder="Nombre del servicio adicional"
                   maxlength="100">
            <small class="text-success">
                <i class="fa-solid fa-plus-circle me-1"></i>Nuevo servicio
            </small>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-success btn-sm" onclick="guardarServicioPersonalizado(this)" title="Guardar">
                    <i class="fa-solid fa-check"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelarFilaServicioPersonalizado(this)" title="Cancelar">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" 
                       class="form-control input-precio-servicio text-end" 
                       placeholder="0.00"
                       min="0"
                       step="0.01">
            </div>
        </td>
    `;
    
    // Agregar la fila al final del tbody
    tbody.appendChild(row);
    
    // Focus en el campo de nombre
    row.querySelector('.input-nombre-servicio').focus();
    
    // Agregar event listener para Enter
    const nombreInput = row.querySelector('.input-nombre-servicio');
    const precioInput = row.querySelector('.input-precio-servicio');
    
    nombreInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            precioInput.focus();
        }
    });
    
    precioInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            guardarServicioPersonalizado(row.querySelector('.btn-success'));
        }
    });
}

function cancelarFilaServicioPersonalizado(btn) {
    const row = btn.closest('tr');
    row.remove();
}

function guardarServicioPersonalizado(btn) {
    const row = btn.closest('tr');
    const nombre = row.querySelector('.input-nombre-servicio').value.trim();
    const precio = parseFloat(row.querySelector('.input-precio-servicio').value) || 0;
    
    // Validaciones
    if (!nombre) {
        showAlert({
            type: 'warning',
            message: 'Ingrese el nombre del servicio'
        });
        row.querySelector('.input-nombre-servicio').focus();
        return;
    }
    
    if (precio <= 0) {
        showAlert({
            type: 'warning',
            message: 'Ingrese un precio válido mayor a 0'
        });
        row.querySelector('.input-precio-servicio').focus();
        return;
    }
    
    // Crear servicio personalizado
    const servicioPersonalizado = {
        id: `custom_${contadorServiciosPersonalizados}`,
        nombre: nombre,
        precio: precio,
        personalizado: true
    };
    
    // Agregar al array
    serviciosPersonalizados.push(servicioPersonalizado);
    
    // Agregar al estado del wizard
    wizardState.servicios.push({
        id: servicioPersonalizado.id,
        nombre: servicioPersonalizado.nombre,
        precio: servicioPersonalizado.precio,
        personalizado: true
    });
    
    // Reemplazar la fila de edición con la fila final
    row.className = 'servicio-personalizado-row';
    row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-star text-warning me-2"></i>
                <div>
                    <strong>${nombre}</strong>
                    <br>
                    <small class="text-muted">
                        <span class="badge bg-success">Personalizado</span>
                    </small>
                </div>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarServicioPersonalizado('${servicioPersonalizado.id}')" title="Eliminar">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
        <td class="text-end">
            <strong class="text-success">₡${precio.toLocaleString('es-CR', {minimumFractionDigits: 2})}</strong>
        </td>
    `;
    
    // Actualizar totales
    recalcularTotales();
    
    // Mostrar mensaje de éxito
    showAlert({
        type: 'success',
        message: `Servicio "${nombre}" agregado exitosamente`
    });
}

function cancelarServicioPersonalizado() {
    // Esta función ya no se usa, pero la mantenemos por compatibilidad
    const filaEdicion = document.querySelector('.fila-edicion-servicio');
    if (filaEdicion) {
        filaEdicion.remove();
    }
}

function agregarServicioPersonalizado() {
    // Función legacy - redirigir a la nueva implementación
    mostrarFormularioServicioPersonalizado();
}

// Función legacy - ya no se usa con la nueva implementación inline
function renderServiciosPersonalizados() {
    // Esta función ya no es necesaria porque los servicios se agregan directamente como filas
    // Se mantiene por compatibilidad pero no hace nada
    return;
}

function eliminarServicioPersonalizado(servicioId) {
    // Confirmar eliminación
    if (!confirm('¿Está seguro de eliminar este servicio personalizado?')) {
        return;
    }
    
    // Encontrar el servicio
    const servicioIndex = serviciosPersonalizados.findIndex(s => s.id === servicioId);
    if (servicioIndex === -1) return;
    
    const servicio = serviciosPersonalizados[servicioIndex];
    
    // Eliminar del array de servicios personalizados
    serviciosPersonalizados.splice(servicioIndex, 1);
    
    // Eliminar del estado del wizard
    const wizardServiceIndex = wizardState.servicios.findIndex(s => s.id === servicioId);
    if (wizardServiceIndex !== -1) {
        wizardState.servicios.splice(wizardServiceIndex, 1);
    }
    
    // Eliminar la fila de la tabla
    const tbody = document.getElementById('services-body');
    const rows = tbody.querySelectorAll('.servicio-personalizado-row');
    rows.forEach(row => {
        const deleteBtn = row.querySelector('button[onclick*="' + servicioId + '"]');
        if (deleteBtn) {
            row.remove();
        }
    });
    
    // Actualizar totales
    recalcularTotales();
    
    // Mostrar mensaje
    showAlert({
        type: 'info',
        message: `Servicio "${servicio.nombre}" eliminado`
    });
}

// Modificar la función resetWizard para limpiar servicios personalizados
function resetWizardWithCustomServices() {
    // Limpiar servicios personalizados
    serviciosPersonalizados = [];
    contadorServiciosPersonalizados = 0;
    
    // Limpiar las filas de servicios personalizados de la tabla (incluyendo filas de edición)
    const serviciosPersonalizadosExistentes = document.querySelectorAll('.servicio-personalizado-row, .fila-edicion-servicio');
    serviciosPersonalizadosExistentes.forEach(row => row.remove());
    
    // Llamar al reset original
    resetWizard();
}

// Event listeners ya no son necesarios - se agregan dinámicamente en mostrarFormularioServicioPersonalizado()
// Esta sección se mantiene vacía por compatibilidad
document.addEventListener('DOMContentLoaded', function() {
    // Los event listeners ahora se agregan dinámicamente cuando se crea cada fila
});