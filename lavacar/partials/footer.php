<!-- FOOTER -->
<footer>
    © 2025 Frosh Systems
</footer>

<!-- froshOverLay -->
<div id="froshOverLay">
    <div class="overlay-content">
        <button class="close-btn" onclick="hideFroshOverLay()">
            <i class="fas fa-times"></i>
        </button>

        <div class="text-center" id='froshOverLayContent'>
            <h2 class="overlay-title display-5"></h2>
            <p class="overlay-body"></p>
            <ul class="feature-list"></ul>
        </div>

    </div>
</div>
<!-- GLOBAL ALERT MODAL - Comentado temporalmente, ahora usamos toast -->
<!-- 
<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="alertTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <i id="alertIcon" class="display-6 mb-3"></i>
                <p id="alertMessage" class="mb-0"></p>
                <p id="alertSubMessage" class="text-muted small"></p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn" id="alertConfirmBtn">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>
-->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Frosh Components JS -->
<script src="<?= LAVACAR_BASE_URL ?>/../lib/js/frosh-components.js"></script>

<!-- App JS -->
<script>
const ALERT_TYPES = {
    danger: {
        title: 'Confirmar acción',
        icon: 'fa-solid fa-triangle-exclamation',
        color: 'text-danger',
        button: 'btn-danger',
        defaultConfirm: 'Eliminar'
    },
    warning: {
        title: 'Atención',
        icon: 'fa-solid fa-circle-exclamation',
        color: 'text-warning',
        button: 'btn-warning',
        defaultConfirm: 'Continuar'
    },
    success: {
        title: 'Confirmación',
        icon: 'fa-solid fa-circle-check',
        color: 'text-success',
        button: 'btn-success',
        defaultConfirm: 'Aceptar'
    },
    info: {
        title: 'Información',
        icon: 'fa-solid fa-circle-info',
        color: 'text-info',
        button: 'btn-primary',
        defaultConfirm: 'Ok'
    }
};

// Legacy support for existing showAlert function - Comentado, ahora usamos toast
// let alertModal;

document.addEventListener('DOMContentLoaded', function() {
    // alertModal = new bootstrap.Modal(
    //     document.getElementById('alertModal')
    // );
    console.log('Toast system initialized - no more modal alerts');
});

function showAlert({
    type = 'info',
    title = '',
    message = '',
    subMessage = '',
    confirmText = '',
    onConfirm = null
}) {
    // Si hay una función onConfirm, significa que necesita confirmación
    // En ese caso, usamos un toast de confirmación especial
    if (typeof onConfirm === 'function') {
        // Para acciones que requieren confirmación, usamos un toast con botones
        showConfirmationToast(message || title, type, onConfirm);
    } else {
        // Para mensajes informativos, usamos el toast normal
        showToast(message || title, type);
    }
}

// Función para mostrar toast normal (solo informativo)
function showToast(message, type = 'info') {
    // Mapear tipos a Bootstrap
    const getBootstrapAlertType = (type) => {
        const typeMap = {
            'success': 'success',
            'danger': 'danger',
            'warning': 'warning',
            'info': 'info'
        };
        return typeMap[type] || 'info';
    };
    
    // Mapear tipos a iconos
    const getAlertIcon = (type) => {
        const iconMap = {
            'success': 'fa-check-circle',
            'danger': 'fa-exclamation-triangle',
            'warning': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        };
        return iconMap[type] || 'fa-info-circle';
    };
    
    // Remover alertas existentes
    const existingAlert = document.querySelector('.global-toast-alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Crear nueva alerta (toast style)
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${getBootstrapAlertType(type)} alert-dismissible fade show global-toast-alert`;
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
            <button type="button" class="btn-close ms-2" onclick="closeGlobalToast(this)"></button>
        </div>
    `;
    
    // Agregar animaciones CSS si no existen
    if (!document.querySelector('#global-toast-animations')) {
        const style = document.createElement('style');
        style.id = 'global-toast-animations';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alertDiv);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) alertDiv.remove();
            }, 300);
        }
    }, 3000);
}

// Función para mostrar toast de confirmación (con botones)
function showConfirmationToast(message, type, onConfirm) {
    // Para confirmaciones críticas, mantenemos un modal pequeño pero mejorado
    // O podríamos usar un toast con botones inline
    
    // Por ahora, ejecutamos la confirmación directamente y mostramos toast
    if (typeof onConfirm === 'function') {
        onConfirm();
    }
    showToast(message, type);
}

// Función para cerrar toast manualmente
function closeGlobalToast(button) {
    const alertDiv = button.closest('.global-toast-alert');
    if (alertDiv && alertDiv.parentNode) {
        alertDiv.style.animation = 'slideOutRight 0.2s ease-in';
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) alertDiv.remove();
        }, 200);
    }
}

function gotoPage(page) {
    console.log("Navigate to:", page);
    // Replace with your real routing logic
    // Example:
    // window.location.href = page + ".php";
}
</script>

</body>

</html>