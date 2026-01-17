<?php
session_start();
/* =====================================================
   DEBUG (REMOVE LATER)
===================================================== */
//var_dump($_COOKIE);
//var_dump($_SESSION);
require_once '../lib/config.php';
require_once 'lib/Auth.php';
require_once 'middleware/setup-check.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: ../login.php");
    exit;
}
$user = userInfo();

if (!$user) {
    logout();
    header("Location: ../login.php");
    exit;
}

// Check setup completion
$dbName = $user['company']['db'];
$setupProgress = getSetupProgress($conn, $dbName);

require 'partials/header.php';
?>

<div class="container" id="mainContainer">

    <?php 
    // Show setup alert if not complete
    if (!$setupProgress['is_complete']): 
        showSetupAlert($conn, $dbName);
    endif;
    
    // Show success message if setup just completed
    if (isset($_GET['setup_complete'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h6 class="alert-heading">
            <i class="fas fa-check-circle me-2"></i>¡Configuración Completada!
        </h6>
        <p class="mb-0">
            Tu lavadero está listo para usar. Ya puedes crear órdenes y gestionar tu negocio.
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php require 'partials/menu.php'; ?>

</div>

<?php require 'partials/footer.php'; ?>