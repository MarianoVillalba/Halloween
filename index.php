<?php
session_start(); // Iniciar la sesión
include("includes/conexion.php"); // Conectar a la base de datos

// Manejo de la carga del disfraz
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disfraz-nombre'])) {
    $nombre = $_POST['disfraz-nombre'];
    $descripcion = $_POST['disfraz-descripcion'];

    // Manejo de la carga del archivo
    $foto = $_FILES['disfraz-foto'];
    $ruta_destino = 'imagenes/' . basename($foto['name']); // Guardar en la carpeta "imagenes"

    if (move_uploaded_file($foto['tmp_name'], $ruta_destino)) {
        $query = "INSERT INTO disfraces (nombre, descripcion, foto) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $descripcion, basename($foto['name'])); // Guardamos solo el nombre del archivo
            if ($stmt->execute()) {
                $_SESSION['message'] = "Disfraz agregado exitosamente.";
                header("Location: index.php"); // Redirigir después de agregar el disfraz
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

// Obtener los disfraces existentes
$query = "SELECT d.id, d.nombre, d.descripcion, d.foto, COUNT(v.id) AS total_votos 
          FROM disfraces d 
          LEFT JOIN votaciones v ON d.id = v.disfraz_id 
          GROUP BY d.id"; // Agrupamos por el ID del disfraz
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Concurso de Disfraces de Halloween</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="admin.php">Panel de Administración</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <header>
        <h1>Concurso de Disfraces de Halloween</h1>
    </header>
    <main>
        <?php
        // Mostrar notificación si existe
        if (isset($_SESSION['message'])) {
            echo '<div class="mensaje">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']); // Limpiar el mensaje después de mostrarlo
        }
        ?>
        
        <section id="disfraces-list" class="section">
            <h2>Disfraces</h2>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="disfraz">';
                    echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';

                    $imagen = htmlspecialchars($row['foto']); // Asegúrate que esto contenga solo el nombre del archivo
                    $ruta_imagen = 'imagenes/' . $imagen;

                    // Verificar si la imagen existe
                    if (file_exists($ruta_imagen)) {
                        echo '<img src="' . $ruta_imagen . '" alt="' . htmlspecialchars($row['nombre']) . '" width="100%">';
                    } else {
                        echo '<p>Error: La imagen no existe.</p>';
                    }

                    echo '<p>Votos: ' . htmlspecialchars($row['total_votos']) . '</p>'; // Mostrar el contador de votos
                    // Verificar si el usuario está autenticado antes de mostrar el botón de votar
                    if (isset($_SESSION['user_id'])) {
                        echo '<form action="votar.php" method="POST">';
                        echo '<input type="hidden" name="disfraz_id" value="' . htmlspecialchars($row['id']) . '">';
                        echo '<button type="submit" class="votar">Votar</button>';
                        echo '</form>';
                    } else {
                        echo '<button class="votar" disabled>No puedes votar sin iniciar sesión</button>';
                    }
                    echo '</div>';
                    echo '<hr>';
                }
            } else {
                echo '<p>No hay disfraces disponibles.</p>';
            }
            ?>
        </section>
        
        <section id="agregar-disfraz" class="section">
            <h2>Agregar Disfraz</h2>
            <?php if (isset($_SESSION['username'])): ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
                
                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
                
                <label for="disfraz-foto">Foto:</label>
                <input type="file" id="disfraz-foto" name="disfraz-foto" required>

                <button type="submit">Agregar Disfraz</button>
            </form>
            <?php else: ?>
                <p>Debes iniciar sesión para agregar un disfraz.</p>
            <?php endif; ?>
        </section>
        
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
        
        <section id="login" class="section">
            <h2>Iniciar Sesión</h2>
            <form action="login.php" method="POST">
                <label for="login-username">Nombre de Usuario:</label>
                <input type="text" id="login-username" name="login-username" required>
                
                <label for="login-password">Contraseña:</label>
                <input type="password" id="login-password" name="login-password" required>
                
                <button type="submit">Iniciar Sesión</button>
            </form>
        </section>
    </main>
</body>
</html>
