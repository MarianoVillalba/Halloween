<?php
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT);

    // Verificar si el nombre de usuario ya existe
    $checkQuery = "SELECT * FROM usuarios WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Nombre de usuario ya existe
        echo "<p>Error: El nombre de usuario ya está en uso.</p>";
    } else {
        // Si el nombre de usuario no existe, proceder con el registro
        $query = "INSERT INTO usuarios (username, password, role) VALUES (?, ?, 'admin')"; // Establecer rol como admin
        $stmt = $conn->prepare($query);
        
        // Verifica si la preparación fue exitosa
        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);
            
            if ($stmt->execute()) {
                // Redirigir a index.php después de un registro exitoso
                header("Location: index.php");
                exit(); // Asegúrate de usar exit después de header
            } else {
                echo "<p>Error al registrar usuario. Intente nuevamente.</p>";
            }
        } else {
            echo "<p>Error en la preparación de la consulta.</p>";
        }
    }

    // Cerrar sentencias
    $checkStmt->close();
    if (isset($stmt)) {
        $stmt->close();
    }
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <section id="registro" class="section">
        <h2>Registro</h2>
        <form action="registro.php" method="POST">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Registrarse</button>
        </form>
    </section>
</body>
</html>

