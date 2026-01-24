<?php
session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

// Simple test to verify AJAX endpoint
echo "<h2>Simple AJAX Test</h2>";

if (!isLoggedIn()) {
    echo "<p style='color: red;'>Not logged in!</p>";
    echo "<a href='login.php'>Login first</a>";
    exit;
}

$user = userInfo();
echo "<p><strong>Logged in as:</strong> " . htmlspecialchars($user['nombre']) . "</p>";
echo "<p><strong>Database:</strong> " . htmlspecialchars($user['company']['db']) . "</p>";
?>

<button onclick="testAjax()">Test AJAX Call</button>
<div id="result"></div>

<script>
function testAjax() {
    const data = {
        orden_id: 23,
        servicios: [{
            id: 'test_1',
            nombre: 'Test Service',
            precio: 10000,
            personalizado: true
        }],
        observaciones: 'Simple test'
    };
    
    console.log('Testing AJAX call...');
    document.getElementById('result').innerHTML = 'Testing...';
    
    fetch('lavacar/ajax/actualizar-orden.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        document.getElementById('result').innerHTML = `
            <h3>Response:</h3>
            <pre>${text}</pre>
        `;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = `
            <h3>Error:</h3>
            <p style="color: red;">${error.message}</p>
        `;
    });
}
</script>