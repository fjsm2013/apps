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
$dbName = $user['company']['db'];

// Obtener ID de la orden
$ordenId = $_GET['id'] ?? 23; // Default to 23 for testing

echo "<h2>DEBUG: Order Editing System</h2>";
echo "<h3>Order ID: {$ordenId}</h3>";
echo "<h3>Database: {$dbName}</h3>";

// Check if ServicioPersonalizado column exists
echo "<h4>1. Database Column Check</h4>";
$result = $conn->query("
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = '{$dbName}' 
      AND TABLE_NAME = 'orden_servicios' 
      AND COLUMN_NAME = 'ServicioPersonalizado'
");

if ($result->num_rows > 0) {
    echo "<span style='color: green;'>✓ ServicioPersonalizado column exists</span><br>";
} else {
    echo "<span style='color: red;'>✗ ServicioPersonalizado column missing</span><br>";
    echo "<p><strong>Adding column now...</strong></p>";
    try {
        $conn->query("ALTER TABLE `{$dbName}`.orden_servicios ADD ServicioPersonalizado VARCHAR(255) NULL AFTER Precio");
        echo "<span style='color: blue;'>→ Column added successfully</span><br>";
    } catch (Exception $e) {
        echo "<span style='color: red;'>→ Error adding column: " . $e->getMessage() . "</span><br>";
    }
}

// Get order data
echo "<h4>2. Order Data</h4>";
$stmt = $conn->prepare("
    SELECT o.*, 
           c.NombreCompleto as ClienteNombre,
           v.Placa, v.Marca, v.Modelo
    FROM `{$dbName}`.ordenes o
    LEFT JOIN `{$dbName}`.clientes c ON o.ClienteID = c.ID
    LEFT JOIN `{$dbName}`.vehiculos v ON o.VehiculoID = v.ID
    WHERE o.ID = ?
");

$stmt->bind_param("i", $ordenId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<span style='color: red;'>Order not found!</span>";
    exit;
}

$orden = $result->fetch_assoc();
echo "<p><strong>Cliente:</strong> " . htmlspecialchars($orden['ClienteNombre']) . "</p>";
echo "<p><strong>Vehículo:</strong> " . htmlspecialchars($orden['Placa']) . "</p>";
echo "<p><strong>Estado:</strong> {$orden['Estado']}</p>";

// Get current services
echo "<h4>3. Current Services</h4>";
$stmt = $conn->prepare("
    SELECT os.*, s.Descripcion as ServicioNombre
    FROM `{$dbName}`.orden_servicios os
    LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
    WHERE os.OrdenID = ?
    ORDER BY os.ID
");

$stmt->bind_param("i", $ordenId);
$stmt->execute();
$serviciosResult = $stmt->get_result();

$servicios = [];
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>ServicioID</th><th>Nombre</th><th>Precio</th><th>Personalizado</th></tr>";

while ($servicio = $serviciosResult->fetch_assoc()) {
    $servicios[] = [
        'id' => $servicio['ServicioID'] ?: 'custom_' . $servicio['ID'],
        'nombre' => ($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre'],
        'precio' => floatval($servicio['Precio']),
        'personalizado' => !empty($servicio['ServicioPersonalizado'] ?? '')
    ];
    
    echo "<tr>";
    echo "<td>{$servicio['ID']}</td>";
    echo "<td>{$servicio['ServicioID']}</td>";
    echo "<td>" . htmlspecialchars(($servicio['ServicioPersonalizado'] ?? '') ?: $servicio['ServicioNombre']) . "</td>";
    echo "<td>₡" . number_format($servicio['Precio'], 2) . "</td>";
    echo "<td>" . (!empty($servicio['ServicioPersonalizado'] ?? '') ? 'Sí' : 'No') . "</td>";
    echo "</tr>";
}
echo "</table>";

?>

<h4>4. Test AJAX Update</h4>
<div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
    <h5>Add Test Service:</h5>
    <input type="text" id="test-name" placeholder="Service Name" value="Servicio de Prueba">
    <input type="number" id="test-price" placeholder="Price" value="15000">
    <button onclick="testAjaxUpdate()" style="background: green; color: white; padding: 10px;">Test AJAX Update</button>
    
    <h5>Current Services in Form:</h5>
    <div id="services-list"></div>
    
    <h5>AJAX Response:</h5>
    <div id="ajax-response" style="background: #f0f0f0; padding: 10px; margin-top: 10px;"></div>
</div>

<script>
// Display current services
const currentServices = <?= json_encode($servicios) ?>;
console.log('Current services:', currentServices);

function displayServices() {
    const list = document.getElementById('services-list');
    list.innerHTML = '';
    
    currentServices.forEach((service, index) => {
        const div = document.createElement('div');
        div.innerHTML = `
            <strong>${service.nombre}</strong> - ₡${service.precio.toLocaleString()} 
            ${service.personalizado ? '(Personalizado)' : ''}
        `;
        list.appendChild(div);
    });
}

function testAjaxUpdate() {
    const name = document.getElementById('test-name').value;
    const price = parseFloat(document.getElementById('test-price').value);
    
    if (!name || !price) {
        alert('Please enter both name and price');
        return;
    }
    
    // Add the new service to current services
    const newService = {
        id: 'custom_test',
        nombre: name,
        precio: price,
        personalizado: true
    };
    
    const allServices = [...currentServices, newService];
    
    const datos = {
        orden_id: <?= $ordenId ?>,
        servicios: allServices,
        observaciones: 'Test update from debug page - ' + new Date().toLocaleString()
    };
    
    console.log('Sending data:', datos);
    
    const responseDiv = document.getElementById('ajax-response');
    responseDiv.innerHTML = '<p>Sending request...</p>';
    
    fetch('lavacar/ajax/actualizar-orden.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text(); // Get as text first to see what we're getting
    })
    .then(text => {
        console.log('Raw response:', text);
        responseDiv.innerHTML = `
            <h6>Raw Response:</h6>
            <pre>${text}</pre>
        `;
        
        // Try to parse as JSON
        try {
            const data = JSON.parse(text);
            responseDiv.innerHTML += `
                <h6>Parsed JSON:</h6>
                <pre>${JSON.stringify(data, null, 2)}</pre>
            `;
            
            if (data.success) {
                responseDiv.innerHTML += '<p style="color: green;"><strong>SUCCESS!</strong> Now refresh the page to see if changes persisted.</p>';
            } else {
                responseDiv.innerHTML += `<p style="color: red;"><strong>ERROR:</strong> ${data.message}</p>`;
            }
        } catch (e) {
            responseDiv.innerHTML += `<p style="color: red;"><strong>JSON Parse Error:</strong> ${e.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        responseDiv.innerHTML = `<p style="color: red;"><strong>Fetch Error:</strong> ${error.message}</p>`;
    });
}

// Display services on load
displayServices();
</script>

<hr>
<h4>5. Manual Database Test</h4>
<?php
if (isset($_POST['manual_test'])) {
    try {
        $conn->begin_transaction();
        
        // Delete existing services
        $stmt = $conn->prepare("DELETE FROM `{$dbName}`.orden_servicios WHERE OrdenID = ?");
        $stmt->bind_param("i", $ordenId);
        $stmt->execute();
        echo "<p>✓ Deleted existing services</p>";
        
        // Add test service
        $stmt = $conn->prepare("
            INSERT INTO `{$dbName}`.orden_servicios 
            (OrdenID, ServicioID, Precio, ServicioPersonalizado) 
            VALUES (?, NULL, ?, ?)
        ");
        $testPrice = 25000;
        $testName = "Manual Test Service " . date('H:i:s');
        $stmt->bind_param("ids", $ordenId, $testPrice, $testName);
        $stmt->execute();
        echo "<p>✓ Added test service: {$testName}</p>";
        
        $conn->commit();
        echo "<p style='color: green;'><strong>Manual test completed successfully!</strong></p>";
        echo "<a href=''>Refresh page to see changes</a>";
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color: red;'>Manual test error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<form method='POST'>";
    echo "<button type='submit' name='manual_test' style='background: blue; color: white; padding: 10px;'>Run Manual Database Test</button>";
    echo "</form>";
}
?>

<hr>
<h4>Links:</h4>
<ul>
<li><a href="lavacar/reportes/editar-orden.php?id=<?= $ordenId ?>">Original Edit Page</a></li>
<li><a href="lavacar/reportes/ordenes-activas.php">Orders List</a></li>
<li><a href="debug-editar-orden.php?id=<?= $ordenId ?>">Refresh This Debug Page</a></li>
</ul>