<?php
session_start();
/* =====================================================
   DEBUG (REMOVE LATER)
===================================================== */
//var_dump($_COOKIE);
//var_dump($_SESSION);
require_once '../../lib/config.php';
require_once 'lib/Auth.php';
autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
$user = userInfo();

if (!$user) {
    logout();
    header("Location: login.php");
    exit;
}


//var_dump($user);
$userID = $user['id'];
//var_dump($_SESSION);
require  'lavacar/partials/header.php';


?>
<main class="container my-5">
    <div class="mb-4">
        <h2 class="dashboard-title">Administración</h2>
        <p class="text-muted">Accesos rápidos del sistema</p>
    </div>

    <div class="row g-4">

        <!-- CLIENTES -->
        <div class="col-6 col-md-3">
            <a href="clientes/." class="text-decoration-none">
                <button class="dash-card admin">
                    <i class="fa-solid fa-users icon-primary"></i>
                    <span>CLIENTES</span>
                </button>
            </a>
        </div>

        <!-- VEHÍCULOS -->
        <div class="col-6 col-md-3">
            <a href="vehiculos/vehiculos.php" class="text-decoration-none">
                <button class="dash-card order">
                    <i class="fa-solid fa-car icon-success"></i>
                    <span>VEHÍCULOS</span>
                </button>
            </a>
        </div>

        <!-- CATEGORÍA VEHÍCULOS -->
        <div class="col-6 col-md-3">
            <a href="vehiculos/." class="text-decoration-none">
                <button class="dash-card active">
                    <i class="fa-solid fa-car-side icon-warning"></i>
                    <span>CATEGORÍAS</span>
                </button>
            </a>
        </div>

        <!-- SERVICIOS Y PRECIOS -->
        <div class="col-6 col-md-3">
            <a href="servicios/." class="text-decoration-none">
                <button class="dash-card reports">
                    <i class="fa-solid fa-tags icon-info"></i>
                    <span>SERVICIOS Y PRECIOS</span>
                </button>
            </a>
        </div>

    </div>
</main>



<?php
require  'lavacar/partials/footer.php';