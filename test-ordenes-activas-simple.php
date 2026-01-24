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

echo "<h2>Test Order Editing - Simple Version</h2>";

// Test if the AJAX files exist and work
echo "<h3>Testing AJAX Files</h3>";

$testOrderId = 1; // Use a test order ID

echo "<div style='margin: 20px 0;'>";
echo "<button onclick='testEditOrder()' class='btn btn-primary'>Test Edit Order</button> ";
echo "<button onclick='testCalculator()' class='btn btn-warning'>Test Calculator</button>";
echo "</div>";

echo "<div id='testResults' style='margin: 20px 0; padding: 20px; border: 1px solid #ccc; background: #f9f9f9;'>";
echo "Click buttons above to test functionality...";
echo "</div>";

// Check if orders exist
$stmt = $conn->prepare("SELECT ID, Estado, ClienteID FROM `{$dbName}`.ordenes LIMIT 5");
$stmt->execute();
$orders = $stmt->get_result();

echo "<h3>Available Orders for Testing</h3>";
if ($orders->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Order ID</th><th>Estado</th><th>Actions</th></tr>";
    
    while ($order = $orders->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$order['ID']}</td>";
        echo "<td>{$order['Estado']}</td>";
        echo "<td>";
        
        if ($order['Estado'] < 4) {
            echo "<button onclick='testEditOrderReal({$order['ID']})' class='btn btn-sm btn-warning'>Edit</button> ";
        }
        if ($order['Estado'] == 3) {
            echo "<button onclick='testCalculatorReal({$order['ID']})' class='btn btn-sm btn-success'>Calculator</button>";
        }
        
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found. Create some orders first to test the functionality.</p>";
}
?>

<style>
.btn {
    padding: 8px 16px;
    margin: 4px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}
.btn-primary { background: #007bff; color: white; }
.btn-warning { background: #ffc107; color: black; }
.btn-success { background: #28a745; color: white; }
.btn-sm { padding: 4px 8px; font-size: 12px; }
</style>

<script>
function testEditOrder() {
    const testData = { orden_id: 1 };
    
    fetch('lavacar/ajax/obtener-orden-editar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(testData)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Edit Order Test Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Edit Order Test Error:</h4><p style="color: red;">' + error.message + '</p>';
    });
}

function testCalculator() {
    const testData = { orden_id: 1 };
    
    fetch('lavacar/ajax/obtener-orden-editar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(testData)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Calculator Test Result:</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Calculator Test Error:</h4><p style="color: red;">' + error.message + '</p>';
    });
}

function testEditOrderReal(orderId) {
    const testData = { orden_id: orderId };
    
    fetch('lavacar/ajax/obtener-orden-editar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(testData)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Real Edit Order Test (ID: ' + orderId + '):</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Real Edit Order Test Error:</h4><p style="color: red;">' + error.message + '</p>';
    });
}

function testCalculatorReal(orderId) {
    const testData = { orden_id: orderId };
    
    fetch('lavacar/ajax/obtener-orden-editar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(testData)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Real Calculator Test (ID: ' + orderId + '):</h4><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('testResults').innerHTML = 
            '<h4>Real Calculator Test Error:</h4><p style="color: red;">' + error.message + '</p>';
    });
}
</script>

<p><a href="lavacar/reportes/ordenes-activas.php">Go to Active Orders (Original)</a></p>
<p><a href="test-order-editing.php">Go to Full Test Suite</a></p>