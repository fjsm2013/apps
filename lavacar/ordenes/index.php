<?php
session_start();
require_once '../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

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
}

.wizard-step span {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
}

.wizard-step.active .circle {
    background: #4facfe;
    color: #fff;
}

.wizard-step.completed .circle {
    background: #10b981;
    color: #fff;
}

.wizard-step.active span,
.wizard-step.completed span {
    color: #111827;
}

.line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 6px;
}

.line.completed {
    background: #10b981;
}

.vehicle-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: all .2s ease;
    cursor: pointer;
}

.vehicle-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
}

.vehicle-card.active {
    border-color: #111827;
    box-shadow:
        0 0 0 2px rgba(17, 24, 39, .25),
        0 8px 20px rgba(0, 0, 0, .12);
}

.vehicle-card.active i {
    color: #111827;
}

/*Button Slider fro CheckBox */
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
<script src="wizard.js"></script>