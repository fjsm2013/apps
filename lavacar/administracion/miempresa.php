<div class="row">
    <!-- Crear Orden -->
    <div class="col-auto mb-3">
        <a href="./" class="mini-card">
            <i class="fas fa-home text-success"></i>
            <h6>Menu Principal</h6>
        </a>
    </div>

    <!-- Ordenes Activas -->
    <div class="col-auto mb-3">
        <a onclick="gotoPage('view', '', 'src/views/administracion/index.php', 'mainContainer')" class="mini-card">
            <i class="fas fa-cogs text-primary"></i>
            <h6>Administracion</h6>
        </a>
    </div>
</div>
<h1>MI EMPRESA</h1>
<div class="container mt-5">
    <div class="row">
        <!-- Mi Empresa -->
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
            <a onclick="gotoPage('view', '', 'src/views/administracion/perfil.php', 'mainContainer')">
                <div class="card card-company">
                    <div class="card-body text-center">
                        <i class="fas fa-user fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Mi Perfil</h5>
                    </div>
                </div>
            </a>
        </div>

        <!-- Clientes -->
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
            <a onclick="gotoPage('view', '', 'src/controllers/usuarios.php', 'mainContainer')">
                <div class="card card-clients">
                    <div class="card-body text-center">
                        <i class="fas fa-users-cog fa-3x mb-3 text-info"></i>
                        <h5 class="card-title">Administrar Usuarios</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>