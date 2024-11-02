<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["login-username"]);
    $password = trim($_POST["login-password"]);

    $query = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['message'] = "¡Inicio de sesión exitoso!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = "Contraseña incorrecta. Inténtalo de nuevo.";
        }
    } else {
        $_SESSION['message'] = "El nombre de usuario no existe.";
    }

    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
}
?>
