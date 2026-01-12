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
<!-- GLOBAL ALERT MODAL -->
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

// Legacy support for existing showAlert function
let alertModal;

document.addEventListener('DOMContentLoaded', function() {
    alertModal = new bootstrap.Modal(
        document.getElementById('alertModal')
    );
});

function showAlert({
    type = 'info',
    title = '',
    message = '',
    subMessage = '',
    confirmText = '',
    onConfirm = null
}) {

    const cfg = ALERT_TYPES[type] || ALERT_TYPES.info;

    // Title
    document.getElementById('alertTitle').textContent =
        title || cfg.title;

    // Icon
    const icon = document.getElementById('alertIcon');
    icon.className = `display-6 mb-3 ${cfg.icon} ${cfg.color}`;

    // Messages
    document.getElementById('alertMessage').textContent = message;
    document.getElementById('alertSubMessage').textContent = subMessage;

    // Confirm button
    const btn = document.getElementById('alertConfirmBtn');
    btn.className = `btn ${cfg.button}`;
    btn.textContent = confirmText || cfg.defaultConfirm;

    btn.onclick = function() {
        alertModal.hide();
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
    };

    alertModal.show();
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