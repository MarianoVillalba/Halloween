<?php
// includes/conexion.php
$servername = "localhost"; // Cambia esto si tu servidor es diferente
$username = "root";         // Nombre de usuario de la base de datos
$password = "";             // Contraseña de la base de datos
$dbname = "halloween";      // Nombre de tu base de datos creada

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8 (opcional)
$conn->set_charset("utf8");
?>
