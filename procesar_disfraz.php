<?php
include("includes/conexion.php"); // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['disfraz-nombre'];
    $descripcion = $_POST['disfraz-descripcion'];

    // Manejo de la carga del archivo
    $foto = $_FILES['disfraz-foto'];
    $ruta_destino = 'imagenes/' . basename($foto['name']);

    if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
        $query = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $descripcion, $ruta_destino);
            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error al agregar el disfraz: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    } else {
        echo "Error al subir la foto.";
    }
}

$conn->close();
?>
