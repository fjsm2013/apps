<div class="container" style='max-width: 720px;background:transparent'>
    <?php
    include_once('../../config/libraries.php');
    $dbName = 'cars';
    //echo getcwd();
    //var_dump($_POST);
    /**
     * ************************************************************
     * Desactivar usuarios
     * ************************************************************
     */
    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = urldecode($value);
        }
    }
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = Encrypter($_POST['ID'], false);
        $query = sprintf("DELETE FROM %s.usuarios WHERE id = (?)", $dbName);
        EjecutarSQL($link, $query, [$id]);
        $infoMessage = "Usuario Eliminado con Exito!";
    }
    /**
     * ************************************************************
     * Crear usuario
     * ************************************************************
     */
    if (isset($_POST['action']) && $_POST['action'] == 'agregar') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $permisos = $_POST['permisos'];
        //NEED FIX TO ENCRYP IT THE PWD
        $password = generateRandomPassword($length = 12);
        $query = sprintf("INSERT INTO %s.usuarios (name,email,permiso,password) VALUES (?,?,?,?)", $dbName);
        EjecutarSQL($link, $query, [$name, $email, $permisos, $password]);
        $subject = "Detalles de tu nueva cuenta";
        $params = [
            'NAME' => $name,
            'EMAIL' => $email,
            'PASSWORD' => $password
        ];

        // Call the function and get the modified email content
        $message = generateEmailContent('../../layouts/emailTemplates/createUser.htm', $params);
        //echo $message;
        //EmailSenderDFT($subject, $message, [$email, $name]);
        //SEND EMAIL HERE
        //LOGIC
        $infoMessage = "Usuario Creado Exitosamente!";
    }
    /**
     * ************************************************************
     * Actualizar Usuario
     * ************************************************************
     */
    if (isset($_POST['action']) && $_POST['action'] == 'actualizar') {
        $id = Encrypter($_POST['ID'], false);
        $name = $_POST['name'];
        $email = $_POST['email'];
        $permisos = $_POST['permisos'];

        $query = sprintf("UPDATE %s.usuarios SET name=?,email=?,permiso=? WHERE id=?", $dbName);
        EjecutarSQL($link, $query, [$name, $email, $permisos, $id]);
        $infoMessage = "Usuario Actualizado Exitosamente!";
    }


    /**
     *************************************************************
     *Screens to Create, Edit and diplay all usuarios
     ************************************************************* 
     *************************************************************
     * Edit usuarios
     ************************************************************* 
     */
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = Encrypter($_POST['ID'], false);
        $query = sprintf("SELECT id, `name`, email,permiso FROM %s.usuarios where id = ?", $dbName);
        $result = CrearConsulta($link, $query, [$id]);
        $row = $result->fetch_assoc();

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
        $query = sprintf("SELECT * FROM %s.roles", $dbName, $dbName, $dbName);
        $result = CrearConsulta($link, $query, []);
        while ($prow = $result->fetch_array()) {
            if ($prow['ID'] == $row['permiso']) {
                printf("<option value='%s' selected>%s</option>", $prow['ID'], $prow['Descripcion']);
            } else {
                printf("<option value='%s'>%s</option>", $prow['ID'], $prow['Descripcion']);
            }
        }
        echo "</select></td>
			</tr>
			</table>
				<input type='hidden' name='ID' id='ID' value='{$_POST['ID']}'>
				<br>
		</form>";

        echo "<p style='text-align:center'><button class='btn btn-primary' onclick=\"submitForm('iAddRecord', 'src/views/administracion/usuarios.php', 'actualizar', 'mainContainer')\" ><i class='fas fa-user-edit'></i>  Actualizar Usuario</button> <a href='javascript:void(0)' onclick=\"gotoPage('view', '', 'src/views/administracion/usuarios.php', 'mainContainer')\"><button class='btn btn-dark'><i class='fa fa-chevron-left'></i> Go Back</button></a></p>";
    } elseif (isset($_POST['action']) && $_POST['action'] == 'add') {
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
				<td data-label='Nombre'><input class='form-control'  type='text' name='name' id='name' placeholder='Nombre Completo' required></td>
			</tr>
			<tr>
				<th>Email</th>
				<td data-label='Email'><input  class='form-control' type='text' name='email' id='email' placeholder='Correo Electronico' required></td>
			</tr>			
            <tr>
				<th>Permisos</th>
				<td data-label='Permisos'><select class='form-select' id='permisos' name='permisos' required>";
        $query = sprintf("SELECT * FROM %s.roles", $dbName, $dbName, $dbName);
        $result = CrearConsulta($link, $query, []);
        while ($row = $result->fetch_array()) {
            printf("<option value='%s'>%s</option>", $row['ID'], $row['Descripcion']);
        }
        echo "</select></td>
			</tr>


                 
			</table>
				<input type='hidden' name='action' id='action' value='crearUsuario'>
				<br>
		</form>";
        echo "<p style='text-align:center'><a href='javascript:void(0)' onclick=\"gotoPage('view', '', 'src/views/administracion/usuarios.php', 'mainContainer')\"><button class='btn btn-dark'><i class='fa fa-chevron-left'></i> Go Back</button></a> <button class='btn btn-primary' onclick=\"submitForm('iAddRecord', 'src/views/administracion/usuarios.php', 'agregar', 'mainContainer')\"><i class='fa fa-user-plus'></i> Agregar Usuario</button></p>";
    } else {
        /**
         *************************************************************
         * Display all usuarios
         ************************************************************* 
         */
        $query = sprintf("SELECT `id`, `name`,`email`,(SELECT Descripcion FROM cars.roles where ID=permiso) as permiso, `active` FROM %s.usuarios where active=1", $dbName);
        $result = CrearConsulta($link, $query, []);

        echo "<h2 style='text-align:center'>Administracion de Usuarios</h2> ";
        echo "<p style='text-align:center'><a href='javascript:void(0)' onclick=\"gotoPage('add', '', 'src/views/administracion/usuarios.php','mainContainer')\" class='btn btn-outline-warning'><b><i class='fas fa-user-plus'></i> Agregar Usuario <i class='icon-user-add'></i></b></a> <a href='javascript:void(0)' onclick=\"gotoPage('view', '', 'src/views/administracion/index.php', 'mainContainer')\"><button class='btn btn-outline-dark'><i class='fa fa-chevron-left'></i> Administracion</button></a></p>";

        if (isset($infoMessage)) {
            echo "<p style='text-align:center'>" . $infoMessage . "</p>";
        }

        echo "<table class='table table-bordered'>";
        echo "<tr>";
        echo "<th>Name</th><th>Email</th><th>Permisos</th><th>Actions</th>";
        echo "</tr>";
        while ($row = $result->fetch_array()) {
            $id = Encrypter($row['id']);
            $active = $row['active'] == 1 ? "Active" : "Inactive";
            echo "<tr>";
            printf("<td data-label='Nombre'>%s</td>", $row['name']);
            printf("<td data-label='Email'>%s</td>", $row['email']);
            printf("<td data-label='Email'>%s</td>", $row['permiso']);
            if ($row['active'] === 1) {
                printf("<td><a href='javascript:void(0)' class='btn btn-outline-info btn-sm' onclick=\"gotoPage('edit', '%s', 'src/views/administracion/usuarios.php','mainContainer')\"><i class='fas fa-user-edit'></i> Editar <i class='icon-edit'></i></a> 
			 <a href='javascript:void(0)' class='btn btn-outline-danger btn-sm' onclick=\"gotoPage('delete', '%s', 'src/views/administracion/usuarios.php','mainContainer')\"><i class='fas fa-user-times'></i> Desactivar <i class='icon-minus4'></i></a></td>", $id, $id);
            } else {
                echo "<td><strong>INACTIVE</strong></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    ?>
</div>