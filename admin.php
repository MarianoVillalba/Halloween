<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión y tiene permisos de administrador
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirigir si no está autenticado
    exit();
}

include("includes/conexion.php");
$mensaje = ""; // Variable para almacenar mensajes de notificación

// Procesar la eliminación de un disfraz
if (isset($_POST['eliminar'])) {
    $id_disfraz = $_POST['id_disfraz'];
    $deleteQuery = "DELETE FROM disfraces WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id_disfraz);
    $stmt->execute();
    $mensaje = "Disfraz eliminado correctamente."; // Mensaje de éxito
}

// Procesar la adición de un nuevo disfraz
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $nombre = $_POST['disfraz-nombre'];
    $descripcion = $_POST['disfraz-descripcion'];
    $foto = $_FILES['disfraz-foto'];

    // Mover la imagen a la carpeta de imágenes
    $targetDir = "imagenes/";
    $targetFile = $targetDir . basename($foto["name"]);
    move_uploaded_file($foto["tmp_name"], $targetFile);

    $insertQuery = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sss", $nombre, $descripcion, $targetFile);
    $stmt->execute();
    $mensaje = "Disfraz agregado correctamente."; // Mensaje de éxito
}

// Procesar la edición de un disfraz
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar'])) {
    $id_disfraz = $_POST['id_disfraz'];
    $nombre = $_POST['disfraz-nombre'];
    $descripcion = $_POST['disfraz-descripcion'];

    // Verificar si se ha subido una nueva foto
    if (!empty($_FILES['disfraz-foto']['name'])) {
        $foto = $_FILES['disfraz-foto'];
        $targetDir = "imagenes/";
        $targetFile = $targetDir . basename($foto["name"]);
        move_uploaded_file($foto["tmp_name"], $targetFile);
        
        $updateQuery = "UPDATE disfraces SET nombre = ?, descripcion = ?, foto = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $nombre, $descripcion, $targetFile, $id_disfraz);
    } else {
        $updateQuery = "UPDATE disfraces SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_disfraz);
    }
    
    $stmt->execute();
    $mensaje = "Disfraz editado correctamente."; // Mensaje de éxito
}

// Consulta para obtener los disfraces de la base de datos
$query = "SELECT id, nombre, descripcion, foto FROM disfraces";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Concurso de Disfraces</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="login.php">Inicio de Sesión</a></li>
                <li><a href="registro.php">Registro</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        <h1>Panel de Administración - Concurso de Disfraces</h1>
    </header>
    <main>
        <?php if ($mensaje): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <section>
            <h2>Agregar Disfraz</h2>
            <form action="admin.php" method="POST" enctype="multipart/form-data">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>

                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>

                <label for="disfraz-foto">Foto:</label>
                <input type="file" id="disfraz-foto" name="disfraz-foto" required>

                <button type="submit" name="agregar">Agregar Disfraz</button>
            </form>
        </section>
        <section>
            <h2>Lista de Disfraces</h2>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="disfraz">';
                    echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
                    echo '<img src="' . htmlspecialchars($row['foto']) . '" width="100%">';

                    // Formulario para eliminar disfraz
                    echo '<form action="admin.php" method="POST">';
                    echo '<input type="hidden" name="id_disfraz" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<button type="submit" name="eliminar">Eliminar Disfraz</button>';
                    echo '</form>';
                    
                    // Formulario para editar disfraz
                    echo '<form action="admin.php" method="POST" enctype="multipart/form-data">';
                    echo '<input type="hidden" name="id_disfraz" value="' . htmlspecialchars($row['id']) . '">';
                    echo '<label for="disfraz-nombre">Nombre:</label>';
                    echo '<input type="text" name="disfraz-nombre" value="' . htmlspecialchars($row['nombre']) . '" required>';
                    echo '<label for="disfraz-descripcion">Descripción:</label>';
                    echo '<textarea name="disfraz-descripcion" required>' . htmlspecialchars($row['descripcion']) . '</textarea>';
                    echo '<label for="disfraz-foto">Nueva Foto (opcional):</label>';
                    echo '<input type="file" name="disfraz-foto">'; // Permitir subir una nueva foto

                    echo '<button type="submit" name="editar">Editar Disfraz</button>';
                    echo '</form>';

                    echo '</div>';
                    echo '<hr>';
                }
            } else {
                echo '<p>No hay disfraces disponibles.</p>';
            }
            ?>
        </section>
    </main>
</body>
</html>

<?php
$conn->close(); // Cerrar la conexión
?>
