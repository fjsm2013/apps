<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Servicios Fix</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Servicios Page Fix</h2>
        <p>User: <?= htmlspecialchars($user['name']) ?></p>
        <p>Company: <?= htmlspecialchars($user['company']['name']) ?></p>
        <p>Database: <?= htmlspecialchars($user['company']['db']) ?></p>
        
        <div class="alert alert-info">
            <h5>Fixed Issues:</h5>
            <ul>
                <li>✅ Z-index conflicts between FROSH overlay and Bootstrap modals</li>
                <li>✅ Event handling conflicts preventing clicks</li>
                <li>✅ Path issues in servicios/index.php</li>
                <li>✅ Backend get_precios_servicio.php path fixes</li>
                <li>✅ Modal backdrop and pointer events</li>
            </ul>
        </div>
        
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testModal">
            Test Modal
        </button>
        
        <a href="lavacar/administracion/servicios/index.php" class="btn btn-success">
            Go to Servicios Page
        </a>
    </div>

    <!-- Test Modal -->
    <div class="modal fade" id="testModal" tabindex="-1" style="z-index: 10050;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Test Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This modal should work properly now!</p>
                    <button type="button" class="btn btn-secondary">Test Button</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>