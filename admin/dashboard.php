<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verify session
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once '../lib/config.php';

// Get admin info
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_role = $_SESSION['admin_role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Frosh Systems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
        }
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: var(--primary-color);
            color: white;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }
        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            padding: 15px 25px;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: var(--secondary-color);
            color: white;
            border-left: 4px solid var(--accent-color);
        }
        .content {
            margin-left: 250px;
            padding: 30px;
        }
        .card-stat {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
        .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); }
        .bg-gradient-info { background: linear-gradient(45deg, #36b9cc, #258391); }
        .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-4 mb-4">
            <h4>Frosh Systems</h4>
            <small class="text-muted">Admin Panel</small>
        </div>
        <a href="dashboard.php" class="active"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a href="plans.php"><i class="fa-solid fa-list me-2"></i> Planes / Clientes</a>
        <a href="#"><i class="fa-solid fa-users me-2"></i> Usuarios</a>
        <a href="#"><i class="fa-solid fa-cogs me-2"></i> Configuración</a>
        <div class="mt-5 border-top border-secondary pt-3">
            <a href="logout.php" class="text-danger"><i class="fa-solid fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bienvenido, <?php echo htmlspecialchars($admin_name); ?></h2>
            <div>
                <span class="badge bg-secondary"><?php echo ucfirst($admin_role); ?></span>
                <span class="text-muted ms-2"><?php echo date('d/m/Y'); ?></span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Stats Cards -->
            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-gradient-primary me-3">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Empresas</h5>
                            <small class="text-muted">Total Registradas</small>
                            <h3>--</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-gradient-success me-3">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Activas</h5>
                            <small class="text-muted">Suscripciones vigentes</small>
                            <h3>--</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stat p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-gradient-warning me-3">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Pendientes</h5>
                            <small class="text-muted">Pagos pendientes</small>
                            <h3>--</h3>
                        </div>
                    </div>
                </div>
            </div>

             <div class="col-md-3">
                <div class="card card-stat p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-gradient-info me-3">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Usuarios</h5>
                            <small class="text-muted">Total usuarios sistema</small>
                            <h3>--</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Table Placeholder -->
        <div class="card mt-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Actividad Reciente / Clientes</h5>
            </div>
            <div class="card-body">
                <p class="text-muted text-center py-5">
                    <i class="fa-solid fa-chart-line fa-3x mb-3 d-block"></i>
                    Aquí se mostrará el resumen de actividad o lista rápida de clientes.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
