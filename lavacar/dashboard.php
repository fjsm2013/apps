<?php
session_start();
/* =====================================================
   DEBUG (REMOVE LATER)
===================================================== */
//var_dump($_COOKIE);
//var_dump($_SESSION);
require_once '../lib/config.php';
require_once 'lib/Auth.php';
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

require 'partials/header.php';
?>

<div class="container" id="mainContainer">

    <?php require 'partials/menu.php'; ?>

</div>

<?php require 'partials/footer.php'; ?>