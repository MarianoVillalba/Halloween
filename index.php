<?php
session_start(); // Iniciar la sesión
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Concurso de disfraces de Halloween</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <li><a href="#registro">Registro</a></li>
            <li><a href="#login">Iniciar Sesión</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="admin.php">Panel de Administración</a></li> <!-- Enlace al panel de administración -->
                <li><a href="logout.php">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <header>
        <h1>Concurso de disfraces de Halloween</h1>
    </header>
    <main>
        <section id="disfraces-list" class="section">
            <h2>Disfraces</h2>
            <?php
            include("includes/conexion.php");

            // Consulta para obtener los disfraces de la base de datos
            $query = "SELECT nombre, descripcion, foto FROM disfraces";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="disfraz">';
                    echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
                    echo '<img src="' . htmlspecialchars($row['foto']) . '" width="100%">';
                    // Verificar si el usuario está autenticado antes de mostrar el botón de votar
                    if (isset($_SESSION['username'])) {
                        echo '<button class="votar" onclick="votar(\'' . htmlspecialchars($row['nombre']) . '\')">Votar</button>';
                    } else {
                        echo '<button class="votar" disabled>No puedes votar sin iniciar sesión</button>';
                    }
                    echo '</div>';
                    echo '<hr>';
                }
            } else {
                echo '<p>No hay disfraces disponibles.</p>';
            }

            $conn->close(); // Cerrar la conexión
            ?>
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
        
        <?php if (isset($_SESSION['username'])): ?> <!-- Mostrar el panel de administración solo si el usuario está autenticado -->
        <section id="admin" class="section">
            <h2>Panel de Administración</h2>
            <form action="procesar_disfraz.php" method="POST" enctype="multipart/form-data">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>
                
                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>
                
                <label for="disfraz-foto">Foto:</label>
                <input type="file" id="disfraz-foto" name="disfraz-foto" required>

                <button type="submit">Agregar Disfraz</button>
            </form>
        </section>
        <?php endif; ?>
    </main>

    <script>
        const votarButtons = document.querySelectorAll(".votar");

        votarButtons.forEach((button) => {
            button.addEventListener("click", () => {
                const disfrazNombre = button.parentElement.querySelector("h3").textContent; // Obtener el nombre del disfraz
                const data = { disfraz: disfrazNombre }; // Crear un objeto con el nombre del disfraz

                // Simulación de respuesta exitosa (ya que no hay un archivo votar.php)
                alert("Gracias por su voto!"); // Mensaje de agradecimiento
                button.disabled = true; // Deshabilitar el botón después de votar
            });
        });

        // Lógica para deshabilitar los botones de votar si el usuario no está autenticado
        const isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true' : 'false'; ?>;

        if (!isLoggedIn) {
            votarButtons.forEach(button => {
                button.disabled = true; // Deshabilitar los botones de votar
                button.textContent = "Inicia sesión para votar"; // Cambiar texto del botón
            });
        }
    </script>
</body>
</html>
