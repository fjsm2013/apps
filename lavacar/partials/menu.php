<!-- MAIN -->
<main class="container my-5">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="dashboard-title">Panel Principal</h2>
                <p class="text-muted mb-0">Bienvenido, <strong><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></strong></p>
            </div>
            <div class="company-info">
                <span class="badge badge-report-info">
                    <i class="fas fa-building me-1"></i>
                    <?= htmlspecialchars($user['company']['name']) ?>
                </span>
            </div>
        </div>
        <p class="text-muted">Accesos rápidos del sistema</p>
    </div>

    <?php
    // Obtener conteo de órdenes activas
    $ordenesActivas = 0;
    try {
        require_once 'lavacar/backend/OrdenManager.php';
        $ordenManager = new OrdenManager($GLOBALS['conn'], $user['company']['db']);
        $ordenes = $ordenManager->getActive();
        $ordenesActivas = count($ordenes);
    } catch (Exception $e) {
        // Si hay error, mostrar 0
        $ordenesActivas = 0;
    }
    ?>

    <div class="row g-4" id="dashboardCards">
        <div class="col-6 col-md-3" style="animation-delay: 0.1s;">
            <a href="administracion/." class="text-decoration-none">
                <button class="dash-card admin">
                    <i class="fas fa-cogs"></i>
                    <span>ADMINISTRACIÓN</span>
                </button>
            </a>
        </div>

        <div class="col-6 col-md-3" style="animation-delay: 0.2s;">
            <a href="ordenes/." class="text-decoration-none">
                <button class="dash-card order">
                    <i class="fas fa-plus-circle"></i>
                    <span>CREAR ORDEN</span>
                </button>
            </a>
        </div>

        <div class="col-6 col-md-3" style="animation-delay: 0.3s;">
            <a href="reportes/ordenes-activas.php" class="text-decoration-none">
                <button class="dash-card active">
                    <i class="fas fa-tasks"></i>
                    <span>ÓRDENES ACTIVAS</span>
                    <?php if ($ordenesActivas > 0): ?>
                        <div class="badge-counter"><?= $ordenesActivas ?></div>
                    <?php endif; ?>
                </button>
            </a>
        </div>

        <?php /* Comentado temporalmente - Panel de Trabajo
        <div class="col-6 col-md-3" style="animation-delay: 0.4s;">
            <a href="empleados/panel-trabajo.php" class="text-decoration-none">
                <button class="dash-card work">
                    <i class="fas fa-tv"></i>
                    <span>PANEL TRABAJO</span>
                    <?php if ($ordenesActivas > 0): ?>
                        <div class="badge-counter"><?= $ordenesActivas ?></div>
                    <?php endif; ?>
                </button>
            </a>
        </div>
        */ ?>

        <div class="col-6 col-md-3" style="animation-delay: 0.4s;">
            <a href="reportes/." class="text-decoration-none">
                <button class="dash-card reports">
                    <i class="fas fa-chart-line"></i>
                    <span>REPORTES</span>
                </button>
            </a>
        </div>
    </div>
</main>