<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

$ordenId = $_GET['id'] ?? 27;

echo "<h2>Testing OrdenManager::getServicios()</h2>";
echo "<h3>Order ID: {$ordenId}</h3>";
echo "<h3>Database: {$dbName}</h3>";

require_once 'lavacar/backend/OrdenManager.php';
$ordenManager = new OrdenManager($conn, $dbName);

echo "<h4>1. Test Direct SQL Query:</h4>";
try {
    $stmt = $conn->prepare("
        SELECT os.ID, os.ServicioID, os.Precio, os.ServicioPersonalizado, os.Cantidad, os.Subtotal,
               s.Descripcion as ServicioNombre
        FROM `{$dbName}`.orden_servicios os
        LEFT JOIN `{$dbName}`.servicios s ON os.ServicioID = s.ID
        WHERE os.OrdenID = ?
        ORDER BY os.ID
    ");
    $stmt->bind_param("i", $ordenId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<p style='color: green;'>✓ SQL query executed successfully</p>";
    echo "<p>Rows found: " . $result->num_rows . "</p>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>ServicioID</th><th>Precio</th><th>ServicioPersonalizado</th><th>ServicioNombre</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['ID']}</td>";
            echo "<td>{$row['ServicioID']}</td>";
            echo "<td>₡" . number_format($row['Precio'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['ServicioPersonalizado'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['ServicioNombre'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ SQL Error: " . $e->getMessage() . "</p>";
}

echo "<h4>2. Test OrdenManager::getServicios():</h4>";
try {
    $servicios = $ordenManager->getServicios($ordenId);
    
    echo "<p style='color: green;'>✓ getServicios() executed successfully</p>";
    echo "<p>Services returned: " . count($servicios) . "</p>";
    
    if (!empty($servicios)) {
        echo "<pre>" . json_encode($servicios, JSON_PRETTY_PRINT) . "</pre>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Personalizado</th></tr>";
        foreach ($servicios as $servicio) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($servicio['id'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($servicio['nombre'] ?? 'N/A') . "</td>";
            echo "<td>₡" . number_format($servicio['precio'] ?? 0, 2) . "</td>";
            echo "<td>" . (($servicio['personalizado'] ?? false) ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>No services returned</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h4>3. Test Full AJAX Endpoint:</h4>";
echo "<button onclick='testAjax()'>Test AJAX Call</button>";
echo "<div id='ajax-result'></div>";

?>

<script>
function testAjax() {
    const ordenId = <?= $ordenId ?>;
    const resultDiv = document.getElementById('ajax-result');
    resultDiv.innerHTML = '<p>Loading...</p>';
    
    fetch(`lavacar/reportes/ajax/detalle-orden.php?id=${ordenId}`)
        .then(response => {
            console.log('Response status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Raw response:', text);
            resultDiv.innerHTML = '<h5>Raw Response:</h5><pre>' + text + '</pre>';
            
            try {
                const data = JSON.parse(text);
                resultDiv.innerHTML += '<h5>Parsed JSON:</h5><pre>' + JSON.stringify(data, null, 2) + '</pre>';
                
                if (data.success) {
                    resultDiv.innerHTML += '<p style="color: green;">✓ AJAX call successful!</p>';
                    resultDiv.innerHTML += '<p>Services: ' + data.servicios.length + '</p>';
                } else {
                    resultDiv.innerHTML += '<p style="color: red;">✗ Error: ' + data.message + '</p>';
                }
            } catch (e) {
                resultDiv.innerHTML += '<p style="color: red;">✗ JSON Parse Error: ' + e.message + '</p>';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            resultDiv.innerHTML = '<p style="color: red;">✗ Fetch Error: ' + error.message + '</p>';
        });
}
</script>

<hr>
<h4>Links:</h4>
<ul>
<li><a href="lavacar/reportes/ordenes-activas.php">Back to Orders</a></li>
<li><a href="test-get-servicios.php?id=<?= $ordenId ?>">Refresh Test</a></li>
</ul>