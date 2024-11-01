<?php
session_start(); // Iniciar la sesión
include("includes/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['login-username']);
    $password = $_POST['login-password'];

    // Aquí deberías verificar el nombre de usuario en la base de datos
    $query = "SELECT * FROM usuarios WHERE username = ?"; // Cambiar 'nombre_usuario' a 'username'
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($password, $user['password'])) { // Cambiar 'contrasena' a 'password'
            $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión
            $_SESSION['role'] = $user['role']; // Opcional: guardar el rol en la sesión
            header("Location: index.php"); // Redirigir a la página principal
            exit();
        } else {
            echo '<p>Nombre de usuario o contraseña incorrectos.</p>';
        }
    } else {
        echo '<p>Nombre de usuario o contraseña incorrectos.</p>';
    }

    $stmt->close(); // Cerrar la sentencia
}

$conn->close(); // Cerrar la conexión
?>
