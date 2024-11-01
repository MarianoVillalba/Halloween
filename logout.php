<?php
session_start(); // Iniciar la sesión
session_unset(); // Limpiar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir a la página de inicio o a la página de inicio de sesión
header("Location: index.php"); // Cambia 'index.php' a la página deseada
exit();
?>
