 <?php
    /**
     *************************************************************
     * Display all usuarios
     ************************************************************* 
     */
    echo <<<HTML
<h2 style="text-align:center">Administración de Usuarios</h2>
<p style="text-align:center">
    <a href="javascript:void(0)" 
       onclick="gotoPage('create', '', 'src/controllers/usuarios.php', 'mainContainer')" 
       class="btn btn-outline-warning">
        <b>
            <i class="fas fa-user-plus"></i> Agregar Usuario 
            <i class="icon-user-add"></i>
        </b>
    </a>
    <a href="javascript:void(0)" 
       onclick="gotoPage('view', '', 'src/views/administracion/index.php', 'mainContainer')">
        <button class="btn btn-outline-dark">
            <i class="fa fa-chevron-left"></i> Administración
        </button>
    </a>
</p>
HTML;
    if (isset($infoMessage)) {
        echo "<p style='text-align:center'>" . $infoMessage . "</p>";
    }
    echo "<table class='table table-bordered'>";
    echo "<tr>";
    echo "<th>Name</th><th>Email</th><th>Permisos</th><th>Actions</th>";
    echo "</tr>";
    foreach ($usuarios as $usuario) {
        $id = $usuario['id'];
        printf(
            "<tr>
            <td>%s</td>
            <td>%s</td>
            <td>%s</td>
            <td>
                <a href='javascript:void(0)' class='btn btn-outline-info btn-sm' onclick=\"gotoPage('edit', '%s', 'src/controllers/usuarios.php', 'mainContainer')\">
                    <i class='fas fa-user-edit'></i> Editar <i class='icon-edit'></i>
                </a> 
                <a href='javascript:void(0)' class='btn btn-outline-danger btn-sm' onclick=\"gotoPage('delete', '%s', 'src/controllers/usuarios.php', 'mainContainer')\">
                    <i class='fas fa-user-times'></i> Eliminar <i class='icon-minus4'></i>
                </a>
            </td>
        </tr>",
            $usuario['name'],
            $usuario['email'],
            $usuario['permiso'],
            $id,
            $id
        );
    }
    echo "</table>";

    ?>