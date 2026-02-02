<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';
require_once 'lavacar/middleware/setup-check.php';

if (!isLoggedIn()) {
    header("Location: ../../login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

// Verificar configuración inicial antes de permitir crear órdenes
requireSetupCompletion($conn, $dbName, 'crear órdenes');

require 'lavacar/partials/header.php';
?>
<style>
.wizard-progress {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.wizard-step {
    text-align: center;
    flex: 1;
    position: relative;
}

.wizard-step .circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 6px;
    font-size: 16px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.wizard-step .circle i {
    color: inherit;
}

.wizard-step span {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.3s ease;
}

.wizard-step.active .circle {
    background: #274AB3 !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 3px rgba(39, 74, 179, 0.2);
    transform: scale(1.05);
}

.wizard-step.active .circle i {
    color: #ffffff !important;
}

/* Remover hover effects para pasos activos */
.wizard-step.active .circle:hover {
    background: #274AB3 !important;
    color: #ffffff !important;
    transform: scale(1.05);
    box-shadow: 0 0 0 3px rgba(39, 74, 179, 0.2);
}

.wizard-step.completed .circle {
    background: #10b981 !important;
    color: #ffffff !important;
    border-color: #10b981;
}

.wizard-step.completed .circle i {
    color: #ffffff !important;
}

/* Remover hover effects para pasos completados */
.wizard-step.completed .circle:hover {
    background: #10b981 !important;
    color: #ffffff !important;
    transform: none;
}

.wizard-step.active span,
.wizard-step.completed span {
    color: #111827 !important;
    font-weight: 600;
}

.line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 6px;
    transition: all 0.3s ease;
}

.line.completed {
    background: #10b981 !important;
}

.vehicle-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: all .3s ease;
    cursor: pointer;
    background: white;
}

.vehicle-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
    border-color: #d1d5db;
}

.vehicle-card.active {
    border-color: #274AB3 !important;
    box-shadow:
        0 0 0 2px rgba(39, 74, 179, .25),
        0 8px 20px rgba(39, 74, 179, .12);
    background: rgba(39, 74, 179, 0.02);
}

.vehicle-card.active i {
    color: #274AB3 !important;
}

/*Button Slider fro CheckBox */

/* ===== WIZARD RESPONSIVE IMPROVEMENTS ===== */
@media (max-width: 768px) {
    .wizard-progress {
        padding: 0 10px;
    }
    
    .wizard-step .circle {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    
    .wizard-step span {
        font-size: 11px;
    }
    
    .line {
        margin: 0 4px;
    }
}

@media (max-width: 576px) {
    .wizard-step span {
        display: none;
    }
    
    .wizard-step .circle {
        width: 32px;
        height: 32px;
        font-size: 12px;
        margin-bottom: 0;
    }
    
    .line {
        margin: 0 2px;
    }
}

/* ===== WIZARD STEP HOVER EFFECTS (solo para pasos inactivos) ===== */
.wizard-step:not(.active):not(.completed) .circle:hover {
    background: #d1d5db !important;
    color: #374151 !important;
    transform: scale(1.02);
}

.wizard-step:not(.active):not(.completed) .circle:hover i {
    color: #374151 !important;
}

/* ===== WIZARD ANIMATIONS ===== */
.wizard-step-content {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<main class="container my-5">

    <!-- STEP PROGRESS -->
    <div class="wizard-progress mb-4">

        <div class="wizard-step active" data-step="1">
            <div class="circle"><i class="fa-solid fa-car"></i></div>
            <span>Vehículo</span>
        </div>

        <div class="line"></div>

        <div class="wizard-step" data-step="2">
            <div class="circle"><i class="fa-solid fa-tags"></i></div>
            <span>Servicios</span>
        </div>

        <div class="line"></div>

        <div class="wizard-step" data-step="3">
            <div class="circle"><i class="fa-solid fa-car-rear"></i></div>
            <span>Datos Vehículo</span>
        </div>

        <div class="line"></div>

        <div class="wizard-step" data-step="4">
            <div class="circle"><i class="fa-solid fa-user"></i></div>
            <span>Cliente</span>
        </div>

        <div class="line"></div>

        <div class="wizard-step" data-step="5">
            <div class="circle"><i class="fa-solid fa-check"></i></div>
            <span>Confirmar</span>
        </div>

    </div>

    <!-- WIZARD CONTENT -->
    <div id="wizard">

        <div class="wizard-step-content" data-step="1">
            <?php include 'steps/paso_placa.php'; ?>
        </div>

        <div class="wizard-step-content d-none" data-step="2">
            <?php include 'steps/paso_servicios.php'; ?>
        </div>

        <div class="wizard-step-content d-none" data-step="3">
            <?php include 'steps/paso_vehiculo.php'; ?>
        </div>

        <div class="wizard-step-content d-none" data-step="4">
            <?php include 'steps/paso_cliente.php'; ?>
        </div>

        <div class="wizard-step-content d-none" data-step="5">
            <?php include 'steps/paso_confirmar.php'; ?>
        </div>

    </div>




</main>

<?php require 'lavacar/partials/footer.php'; ?>
<script src="wizard.js?v=<?= time() ?>"></script>