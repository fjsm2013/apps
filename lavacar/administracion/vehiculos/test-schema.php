<?php
session_start();

require_once '../../../lib/config.php';
require_once 'lib/Auth.php';

autoLoginFromCookie();
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = userInfo();
$dbName = $user['company']['db'];

echo "<h2>Estructura de Tablas - {$dbName}</h2>";

// Verificar estructura de tabla clientes
echo "<h3>Tabla clientes:</h3>";
$result = $conn->query("DESCRIBE {$dbName}.clientes");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

// Verificar estructura de tabla vehiculos
echo "<h3>Tabla vehiculos:</h3>";
$result = $conn->query("DESCRIBE {$dbName}.vehiculos");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

// Verificar estructura de tabla categoriaservicio
echo "<h3>Tabla categoriaservicio:</h3>";
$result = $conn->query("DESCRIBE {$dbName}.categoriaservicio");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value ?? '') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

echo "<p><a href='vehiculos.php'>← Volver a vehículos</a></p>";
?>