# ucp_halloween
Estimados alumnos, tenemos un desafío para hacerlo en la semana de halloween

Aquí tienen un desafío de programación para Halloween que puedes desarrollar utilizando PHP y MySQL. El objetivo es crear una aplicación web simple que permita a los usuarios registrar y votar por sus disfraces de Halloween favoritos. 

Pasos:

1. Configuración de la base de datos: Crea una base de datos MySQL llamada "halloween" y configura las siguientes tablas:
   
--
-- Estructura de tabla para la tabla `disfraces`
--

CREATE TABLE `disfraces` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `votos` int(11) NOT NULL,
  `foto` varchar(20) NOT NULL,
  `foto_blob` blob NOT NULL,
  `eliminado` int(11) NOT NULL DEFAULT 0
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `clave` text NOT NULL
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

CREATE TABLE `votos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_disfraz` int(11) NOT NULL
);

-- --------------------------------------------------------

2. Desarrollo de la aplicación web:

   a. Crea una página principal (index.php) que muestre una lista de disfraces disponibles con sus nombres, descripciones y la cantidad de votos que han recibido.

   b. Agrega un botón "Votar" junto a cada disfraz para permitir a los usuarios votar por su disfraz favorito. Debes prevenir votos duplicados de un mismo usuario.

   c. Crea una página de registro (registro.php) que permita a los usuarios registrarse con un nombre de usuario y una contraseña.

   d. Implementa un sistema de autenticación para asegurarte de que solo los usuarios registrados puedan votar.

   e. Crea una página de inicio de sesión (login.php) que permita a los usuarios iniciar sesión con su nombre de usuario y contraseña.

   f. Desarrolla una página de administración (admin.php) que solo sea accesible para un usuario administrador. En esta página, el administrador puede agregar nuevos disfraces a la base de datos.

3. Personalización:

   a. Añade estilos CSS para darle un toque de Halloween a tu aplicación.

   b. Puedes permitir que los usuarios carguen imágenes de sus disfraces junto con la descripción.

¡Este desafío debería ser un proyecto interesante para desarrollar durante Halloween! 

Asegúrate de investigar y aprender sobre autenticación, seguridad y buenas prácticas de desarrollo web en PHP y MySQL mientras lo construyes. 

¡Diviértete programando!

