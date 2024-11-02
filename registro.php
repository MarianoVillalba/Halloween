<?php
include("includes/conexion.php");
session_start();

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
        $_SESSION['message'] = "El nombre de usuario ya está en uso. Intenta con otro.";
    } else {
        // Si el nombre de usuario no existe, proceder con el registro
        $query = "INSERT INTO usuarios (username, password, role) VALUES (?, ?, 'user')";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("ss", $username, $password);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "Registro exitoso. ¡Bienvenido!";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['message'] = "Error al registrar el usuario. Inténtalo de nuevo.";
            }
        }
    }

    $checkStmt->close();
    if (isset($stmt)) {
        $stmt->close();
    }
}

$conn->close();
header("Location: index.php");
?>
