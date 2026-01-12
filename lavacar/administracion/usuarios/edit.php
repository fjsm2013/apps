<?php
echo "<h2 style='text-align:center'>Editar Usuario</h2>
<form id='iAddRecord'>
<table  class='table table-bordered'>
    <tr>
        <th>Nombre</th>
        <td data-label='Nombre'><input class='form-control' type='text' name='name' id='name' placeholder='Nombre' value='{$row['name']}' required></td>
    </tr>
    <tr>
        <th>Email</th>
        <td  data-label='Email'><input class='form-control' type='text' name='email' id='email' placeholder='Email' value='{$row['email']}' required></td>
    </tr>
    <tr>
        <th>Permisos</th>
        <td data-label='Permisos'><select class='form-select' id='permisos' name='permisos' required>";
foreach ($roles as $role) {
    if ($role['ID'] == $row['permiso']) {
        printf("<option value='%s' selected>%s</option>", $role['ID'], $role['Descripcion']);
    } else {
        printf("<option value='%s'>%s</option>", $role['ID'], $role['Descripcion']);
    }
}
echo "</select></td>
    </tr>
    </table>
        <input type='hidden' name='ID' id='ID' value='{$_POST['ID']}'>
        <br>
</form>";

echo "<p style='text-align:center'>
        <button class='btn btn-primary' 
                onclick=\"submitForm('iAddRecord', 'src/controllers/usuarios.php', 'actualizarUsuario', 'mainContainer')\">
            <i class='fas fa-user-edit'></i> Actualizar Usuario
        </button>
        
        <a href='javascript:void(0)' 
           onclick=\"gotoPage('view', '', 'src/controllers/usuarios.php', 'mainContainer')\">
            <button class='btn btn-dark'>
                <i class='fa fa-chevron-left'></i> Go Back
            </button>
        </a>
    </p>";
