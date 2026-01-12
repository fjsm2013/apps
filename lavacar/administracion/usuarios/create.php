<?php

/**
 *************************************************************
 * Agregar usuarios
 ************************************************************* 
 */
echo "<h2 style='text-align:center'>Registrar Usuario</h2>
		<form id='iAddRecord'>
		<table class='table table-bordered'>
			<tr>
				<th>Nombre</th>
				<td data-label='Nombre'><input class='form-control'  type='text' name='nombre' id='nombre' placeholder='Nombre Completo' required></td>
			</tr>
			<tr>
				<th>Email</th>
				<td data-label='Email'><input  class='form-control' type='text' name='email' id='email' placeholder='Correo Electronico' required></td>
			</tr>			
            <tr>
				<th>Permisos</th>
				<td data-label='Permisos'><select class='form-select' id='permisos' name='permisos' required>";
foreach ($roles as $row) {
	printf("<option value='%s'>%s</option>", $row['ID'], $row['Descripcion']);
}
echo "</select></td>
			</tr>                 
			</table>
				<input type='hidden' name='action' id='action' value='crearUsuario'>
				<br>
		</form>";
//finish Form
printf(
	"<p style='text-align:center'>
        <a href='javascript:void(0)' onclick=\"gotoPage('view', '', 'src/controllers/usuarios.php', 'mainContainer')\">
            <button class='btn btn-dark'>
                <i class='fa fa-chevron-left'></i> Go Back
            </button>
        </a>
        <button class='btn btn-primary' onclick=\"submitForm('iAddRecord', 'src/controllers/usuarios.php', 'crearUsuario', 'mainContainer')\">
            <i class='fa fa-user-plus'></i> Agregar Usuario
        </button>
    </p>"
);
