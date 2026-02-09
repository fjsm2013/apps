<?php
// Script to create an initial admin user
require_once '../lib/config.php';

$email = 'admin@frosh.com';
$password = 'admin'; // Change this immediately after login!
$nombre = 'Super Admin';
$rol = 'superadmin';

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Check if user exists
$check = $conn->query("SELECT id_admin FROM administradores WHERE email = '$email'");
if ($check->num_rows > 0) {
    echo "User $email already exists.<br>";
}
else {
    // Insert user
    $sql = "INSERT INTO administradores (nombre, email, password_hash, rol, estado, fecha_creacion) 
            VALUES ('$nombre', '$email', '$password_hash', '$rol', 'activo', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New admin user created successfully.<br>";
        echo "Email: $email<br>";
        echo "Password: $password<br>";
    }
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
