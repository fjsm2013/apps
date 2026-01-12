<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'cars');
define('DB_USER', 'fmorgan');
define('DB_PASS', '4sf7xnah');

// Conexión a la base de datos
try {
	$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->exec("SET NAMES utf8");
} catch (PDOException $e) {
	die("Error de conexión: " . $e->getMessage());
}

// Funciones CRUD
function obtenerCategoriasVehiculo()
{
	global $pdo;
	$stmt = $pdo->query("SELECT * FROM categoriavehiculo WHERE Estado = 1 ORDER BY OrdenClasificacion");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerServicios()
{
	global $pdo;
	$stmt = $pdo->query("SELECT s.*, cs.Descripcion as CategoriaDesc 
                        FROM servicios s 
                        LEFT JOIN categoriaservicio cs ON s.CategoriaServicioID = cs.ID
                        ORDER BY cs.Descripcion, s.Descripcion");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerPreciosPorCategoria($categoriaId)
{
	global $pdo;
	$stmt = $pdo->prepare("SELECT p.*, s.Descripcion as ServicioDesc 
                          FROM precios p
                          JOIN servicios s ON p.ServicioID = s.ID
                          WHERE p.TipoCategoriaID = ?
                          ORDER BY s.Descripcion");
	$stmt->execute([$categoriaId]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerPrecio($categoriaId, $servicioId)
{
	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM precios 
                          WHERE TipoCategoriaID = ? AND ServicioID = ?");
	$stmt->execute([$categoriaId, $servicioId]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function guardarPrecios($datos)
{
	global $pdo;

	try {
		$pdo->beginTransaction();

		foreach ($datos as $precioData) {
			// Verificar si ya existe un precio para esta combinación
			$existente = obtenerPrecio($precioData['TipoCategoriaID'], $precioData['ServicioID']);

			if ($existente) {
				// Actualizar precio existente
				$stmt = $pdo->prepare("UPDATE precios SET 
                                      Precio = ?, Descuento = ?, Impuesto = ?, 
                                      Descripcion = ?, PackageID = ?
                                      WHERE ID = ?");
				$stmt->execute([
					$precioData['Precio'],
					$precioData['Descuento'],
					$precioData['Impuesto'],
					$precioData['Descripcion'],
					$precioData['PackageID'],
					$existente['ID']
				]);
			} else {
				// Insertar nuevo precio
				$stmt = $pdo->prepare("INSERT INTO precios 
                                      (TipoCategoriaID, ServicioID, Descripcion, Precio, Descuento, Impuesto, PackageID) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([
					$precioData['TipoCategoriaID'],
					$precioData['ServicioID'],
					$precioData['Descripcion'],
					$precioData['Precio'],
					$precioData['Descuento'],
					$precioData['Impuesto'],
					$precioData['PackageID']
				]);
			}
		}

		$pdo->commit();
		return true;
	} catch (Exception $e) {
		$pdo->rollBack();
		return false;
	}
}

// Procesar formularios
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['guardar_precios'])) {
		$categoriaId = $_POST['categoria_id'];
		$preciosData = [];

		foreach ($_POST['servicios'] as $servicioId => $datos) {
			$preciosData[] = [
				'TipoCategoriaID' => $categoriaId,
				'ServicioID' => $servicioId,
				'Precio' => $datos['Precio'],
				'Descuento' => $datos['Descuento'],
				'Impuesto' => $datos['Impuesto'],
				'Descripcion' => $datos['Descripcion'],
				'PackageID' => $datos['PackageID']
			];
		}

		if (guardarPrecios($preciosData)) {
			$mensaje = "Precios guardados correctamente";
		} else {
			$error = "Error al guardar los precios";
		}
	}
}

// Obtener datos
$categoriasVehiculo = obtenerCategoriasVehiculo();
$servicios = obtenerServicios();

// Obtener precios existentes si se ha seleccionado una categoría
$preciosExistentes = [];
$categoriaSeleccionada = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : (isset($_POST['categoria_id']) ? $_POST['categoria_id'] : null);

if ($categoriaSeleccionada) {
	$preciosExistentes = obtenerPreciosPorCategoria($categoriaSeleccionada);

	// Convertir a formato más fácil de usar
	$preciosIndexados = [];
	foreach ($preciosExistentes as $precio) {
		$preciosIndexados[$precio['ServicioID']] = $precio;
	}
	$preciosExistentes = $preciosIndexados;
}
?>

<div class="container">
	<header>
		<h1>Sistema de Gestión de Precios</h1>
		<p class="subtitle">Establece precios por categoría de vehículo</p>
	</header>

	<?php if ($mensaje): ?>
		<div class="alert alert-success"><?php echo $mensaje; ?></div>
	<?php endif; ?>

	<?php if ($error): ?>
		<div class="alert alert-error"><?php echo $error; ?></div>
	<?php endif; ?>

	<div class="card">
		<h2>Seleccionar Categoría de Vehículo</h2>
		<form method="GET" action="">
			<div class="form-group">
				<label for="categoria_id">Categoría de Vehículo</label>
				<select name="categoria_id" id="categoria_id" required onchange="this.form.submit()">
					<option value="">Seleccionar categoría</option>
					<?php foreach ($categoriasVehiculo as $categoria): ?>
						<option value="<?php echo $categoria['ID']; ?>"
							<?php echo ($categoriaSeleccionada == $categoria['ID']) ? 'selected' : ''; ?>>
							<?php echo $categoria['TipoVehiculo']; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</form>
	</div>

	<?php if ($categoriaSeleccionada): ?>
		<?php
		// Agrupar servicios por categoría
		$serviciosPorCategoria = [];
		foreach ($servicios as $servicio) {
			$categoria = $servicio['CategoriaDesc'] ?: 'General';
			$serviciosPorCategoria[$categoria][] = $servicio;
		}
		?>

		<form method="POST" action="">
			<input type="hidden" name="categoria_id" value="<?php echo $categoriaSeleccionada; ?>">

			<div class="card">
				<h2>Precios para <?php
									foreach ($categoriasVehiculo as $cat) {
										if ($cat['ID'] == $categoriaSeleccionada) {
											echo $cat['TipoVehiculo'];
											break;
										}
									}
									?></h2>

				<?php foreach ($serviciosPorCategoria as $categoriaNombre => $serviciosCategoria): ?>
					<div class="service-category">
						<h3><?php echo $categoriaNombre; ?></h3>

						<?php foreach ($serviciosCategoria as $servicio):
							$precioExistente = isset($preciosExistentes[$servicio['ID']]) ? $preciosExistentes[$servicio['ID']] : null;
						?>
							<div class="service-item">
								<div class="service-name">
									<?php echo $servicio['Descripcion']; ?>
								</div>

								<div>
									<label for="precio_<?php echo $servicio['ID']; ?>">Precio</label>
									<input type="number" step="0.01" min="0"
										name="servicios[<?php echo $servicio['ID']; ?>][Precio]"
										id="precio_<?php echo $servicio['ID']; ?>"
										value="<?php echo $precioExistente ? $precioExistente['Precio'] : '0.00'; ?>" required>
								</div>

								<div>
									<label for="descuento_<?php echo $servicio['ID']; ?>">Descuento</label>
									<input type="number" step="0.01" min="0"
										name="servicios[<?php echo $servicio['ID']; ?>][Descuento]"
										id="descuento_<?php echo $servicio['ID']; ?>"
										value="<?php echo $precioExistente ? $precioExistente['Descuento'] : '0.00'; ?>">
								</div>

								<div>
									<label for="impuesto_<?php echo $servicio['ID']; ?>">Impuesto (%)</label>
									<input type="number" step="0.01" min="0"
										name="servicios[<?php echo $servicio['ID']; ?>][Impuesto]"
										id="impuesto_<?php echo $servicio['ID']; ?>"
										value="<?php echo $precioExistente ? $precioExistente['Impuesto'] : '13.00'; ?>">
								</div>

								<div>
									<label for="descripcion_<?php echo $servicio['ID']; ?>">Descripción</label>
									<input type="text" name="servicios[<?php echo $servicio['ID']; ?>][Descripcion]"
										id="descripcion_<?php echo $servicio['ID']; ?>"
										value="<?php echo $precioExistente ? $precioExistente['Descripcion'] : ''; ?>"
										placeholder="Opcional">
									<input type="hidden" name="servicios[<?php echo $servicio['ID']; ?>][PackageID]" value="0">
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>

				<button type="submit" name="guardar_precios" class="btn-success">Guardar Todos los Precios</button>
			</div>
		</form>

		<div class="card">
			<h2>Resumen de Precios</h2>
			<table>
				<thead>
					<tr>
						<th>Servicio</th>
						<th>Precio</th>
						<th>Descuento</th>
						<th>Impuesto</th>
						<th>Precio Final</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($servicios as $servicio):
						$precioExistente = isset($preciosExistentes[$servicio['ID']]) ? $preciosExistentes[$servicio['ID']] : null;
						if ($precioExistente) {
							$precioFinal = $precioExistente['Precio'] - $precioExistente['Descuento'];
							$impuestoMonto = $precioFinal * ($precioExistente['Impuesto'] / 100);
							$precioFinal += $impuestoMonto;
						}
					?>
						<tr>
							<td><?php echo $servicio['Descripcion']; ?></td>
							<td>$<?php echo number_format($precioExistente['Precio'] ?? 0, 2); ?></td>
							<td>$<?php echo number_format($precioExistente['Descuento'] ?? 0, 2); ?></td>
							<td><?php echo number_format($precioExistente['Impuesto'] ?? 13, 2); ?>%</td>
							<td><strong>$<?php echo number_format($precioFinal ?? 0, 2); ?></strong></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>

<script>
	// Funcionalidad para mejorar la experiencia de usuario
	document.addEventListener('DOMContentLoaded', function() {
		// Limpiar mensajes después de 5 segundos
		setTimeout(function() {
			const alerts = document.querySelectorAll('.alert');
			alerts.forEach(alert => {
				alert.style.display = 'none';
			});
		}, 5000);

		// Calcular automáticamente el precio final al cambiar valores
		const inputs = document.querySelectorAll('input[type="number"]');
		inputs.forEach(input => {
			input.addEventListener('change', function() {
				// Esta función podría expandirse para calcular precios finales en tiempo real
				console.log('Valor cambiado:', this.name, this.value);
			});
		});
	});
</script>