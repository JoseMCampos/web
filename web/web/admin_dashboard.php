<?php
session_start();
require_once './conectar.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit;
}

// Obtener la conexión a la base de datos
$c = conectar();

//obtener el id del usuario
$usuario_id = $_SESSION['usuario_id'];

// Obtener el nombre del usuario para mostrarlo en el mensaje de bienvenida
try {
    $sql = "SELECT nombre FROM usuarios WHERE id = :usuario_id";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $nombre_usuario = $usuario ? $usuario['nombre'] : 'Usuario';
} catch (PDOException $e) {
    echo "Ha ocurrido un error" . $e->getMessage();
}


// Consultar incidencias pendientes
try {
    $sql = "SELECT COUNT(*) as pendientes_contador FROM incidencias WHERE estado = 'pendiente'";
    $stmt = $c->query($sql);
    $incidencias = $stmt->fetch(PDO::FETCH_ASSOC);
    $pendientes_contador = $incidencias['pendientes_contador'];
} catch (PDOException $e) {
    echo "Ha ocurrido un error" . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/admin_style.css">

</head>

<body>

    <header>
            <!-- Logo de la empresa (video en lugar de imagen) -->
            <div class="logo-contenedor">
                <video class="logo" autoplay loop muted>
                    <source src="./img/JMCG.mp4" type="video/mp4">
                    Tu navegador no soporta el formato de video.
                </video>
            </div>

            <!-- Mensaje de bienvenida y el botón de cerrar sesión -->
            <div class="cabecera">
                <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h1>
                <a href="logout.php" class="boton-logout">Cerrar sesión</a>
            </div>
    </header>

    <!-- Menú lateral -->
    <div class="lateral">
        <h3>Menú</h3>
        <a href="formulario_crear_usuario.php">Crear Usuario</a>
        <a href="formulario_crear_incidencia.php">Crear Incidencia</a>
    </div>



    <!-- Contenedor principal para evitar que el contenido se esconda detrás del sidebar -->
    <div class="contenido-principal">

        <!-- Apartado de Notificaciones -->
        <div class="notificaciones" id="notificaciones" style="display: <?= $pendientes_contador > 0 ? 'block' : 'none' ?>;">
            <h3>Notificaciones</h3>

        <!--Si el contador de las incidencias pendientes es mayor a 0 se muestra el siguiente mensaje, sino no se mostrara el contenedor de notificaciones-->
            <?php if ($pendientes_contador > 0): ?>
                <p>Hay <?= $pendientes_contador ?> incidencias pendientes.</p>
            <?php endif; ?>
        </div>



        
        <!-- Tabla para mostrar los usuarios -->
        <h2>Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de Creación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para mostrar todos los usuarios
                $sql = "SELECT id, nombre, apellido1, apellido2, email, rol_tipo, fecha_creacion FROM usuarios";
                $stmt = $c->query($sql);

                //bucle para mostrar los datos de la consulta
                while ($linea = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($linea['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['apellido1']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['apellido2']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['rol_tipo']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['fecha_creacion']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>





        <!-- Tabla para mostrar todas las incidencias -->
        <h2>Incidencias</h2>

        <!-- Botones para ordenar la tabla-->
        <a href="?ordenar_por=categoria" class="ordenar-categoria">Ordenar por Categoría</a>
        <a href="?ordenar_por=id" class="ordenar-id">Ordenar por ID</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Categoría</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para mostrar las incidencias
                $ordenar_por = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : 'id'; // Si no se pasa el parámetro, ordenar por ID por defecto
                $sql = "SELECT incidencias.id, incidencias.usuario_id, incidencias.categoria_id, incidencias.titulo, incidencias.descripcion, incidencias.estado, usuarios.nombre as usuario_nombre, categoria.nombre as categoria_nombre
                FROM incidencias
                INNER JOIN usuarios ON incidencias.usuario_id = usuarios.id
                INNER JOIN categoria ON incidencias.categoria_id = categoria.id";

                // orden de la tabla segun se elija
                if ($ordenar_por == 'categoria') {
                    $sql .= " ORDER BY categoria.nombre"; // Ordenar por nombre de la categoría
                } else {
                    $sql .= " ORDER BY incidencias.id DESC"; // Ordenar por ID si no se pasa el parámetro o se pulsa el boton 
                }

                $stmt = $c->query($sql);

                //Bucle para mostrar los datos de la consulta
                while ($linea = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($linea['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['usuario_nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['categoria_nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['titulo']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['descripcion']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['estado']) . "</td>";
                    echo "<td>

                    <a href='editar_incidencia.php?id=" . $linea['id'] . "' style='margin-right: 5px;'>Editar</a>
                    
                    <form class='boton-accion' action='eliminar_incidencia.php' method='POST'>
                        <input type='hidden' name='id' value='" . $linea['id'] . "'>
                        <button type='submit' onclick='return confirm(\"¿Estás seguro de eliminar esta incidencia?\")'>Eliminar</button>
                    </form>
                    
                    <form class='boton-accion' action='cambiar_estado.php' method='POST'>
                        <input type='hidden' name='id' value='" . $linea['id'] . "'>
                        <button type='submit'>Cambiar Estado</button>
                    </form>
                </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>




<!--Ajax para mandar notificacion al usuario. Boton cambiar estado -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {      //Asegura que el codigo se ejecute cuando el DOM se ha cargado
        $(".cambiar-estado").click(function() {     //Camptura el evento click del elemento cambiar-estado
            let incidenciaId = $(this).data("id");      //se obtiene el id de la incidencia, $(this) hace referencia al boton clickeado, y recoge el id que manda el boton

            $.ajax({    //solicitud ajax 
                url: "cambiar_estado.php",
                type: "POST",
                data: {
                    id: incidenciaId  //envia el id de la incidencia al servidor
                },
                dataType: "json",       //formato de la respuesta
                success: function(response) {       //si la solicitud es exitosa ejecutamos la funcion
                    alert(response.message);        //mensaje recibido desde el servidor
                    if (response.success) {         //si el servidor responde true 
                        location.reload();          // Recarga la página para actualizar la tabla
                    }
                },
                error: function() {
                    alert("Error en la solicitud AJAX.");       //mensaje en caso de error
                }
            });
        });
    });
</script>