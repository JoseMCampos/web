<?php

require_once './conectar.php';

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redirige al login si no está logueado
    exit;
}

// Obtener el id del usuario
$usuario_id = $_SESSION['usuario_id'];

$c = conectar();


// Obtener el nombre del usuario
try {
    $sql = "SELECT nombre FROM usuarios WHERE id = :usuario_id";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    $nombre_usuario = $usuario ? $usuario['nombre'] : 'Usuario';

} catch (PDOException $e) {
    echo "Ha ocurrido un error";
}



// Obtener las notificaciones sin leer
try {
    $sql = "SELECT id, mensaje FROM notificaciones WHERE usuario_id = ? AND leido = 0";
    $stmt = $c->prepare($sql);
    $stmt->execute([$usuario_id]);
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ha ocurrido un error.";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario Dashboard</title>
    <link rel="stylesheet" href="./css/usuario_style.css">

</head>

<body>

    <header>
        <!-- Logo de la empresa (video en lugar de imagen) -->
        <div class="logo-container">
            <video class="logo" autoplay loop muted>
                <source src="./img/JMCG.mp4" type="video/mp4">
                Tu navegador no soporta el formato de video.
            </video>
        </div>

        <div class="header-content">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h1>
            <a href="logout.php" class="logout-button">Cerrar sesión</a>
        </div>
    </header>



    <!--Notificaciones del usuario -->
    <div id="notificaciones" class="notificaciones-container <?= count($notificaciones) > 0 ? 'con-notificaciones' : '' ?>">
        <h3>Notificaciones</h3>

        <!-- Si la cuenta de las notificaciones es mayor a 0 muestra el contenedor-->
        <?php if (count($notificaciones) > 0): ?>
            <ul class="notificaciones-lista">
                <!-- bucle para mostrar las notificaciones una a una-->
                <?php foreach ($notificaciones as $notificacion): ?>
                    <li class="notificacion-item">
                        <span class="mensaje"><?= htmlspecialchars($notificacion['mensaje']) ?></span>
                        <button class="marcar-leida" data-id="<?= $notificacion['id'] ?>">✔</button>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php else: ?>
            <p>No tienes notificaciones nuevas.</p>
        <?php endif; ?>
        
    </div>





    <!-- Formulario para crear incidencias -->
    <h2>Crear una nueva incidencia</h2>
    <form action="incidencia_comprobacion.php" method="POST">
        <label for="categoria_id">Categoría:</label>
        <select id="categoria_id" name="categoria_id" required>
            <!--Consulta para mostrar las categorias en el formulario -->
            <?php
            $sql = "SELECT id, nombre FROM categoria";
            $stmt = $c->query($sql);
            while ($linea = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($linea['id']) . "'>" . htmlspecialchars($linea['nombre']) . "</option>";
            }
            ?>
        </select>

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

        <button type="submit">Crear Incidencia</button>
    </form>



    <!-- Tabla para mostrar incidencias del usuario -->
    <h2>Mis Incidencias</h2>
    <div class="table-container">
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categoría</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT incidencias.id, categoria.nombre AS categoria_nombre, incidencias.titulo, incidencias.descripcion, incidencias.estado 
                    FROM incidencias
                    INNER JOIN categoria ON incidencias.categoria_id = categoria.id
                    WHERE incidencias.usuario_id = :usuario_id";

                $stmt = $c->prepare($sql);
                $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
                $stmt->execute();

                while ($linea = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($linea['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['categoria_nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['titulo']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['descripcion']) . "</td>";
                    echo "<td>" . htmlspecialchars($linea['estado']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>


<!--Actualizar notificaciones del usuario-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!--Libreria jquery para ajax -->
<script>
    $(document).ready(function() {      //Asegura que el codigo se ejecute cuando el DOM se ha cargado
        $(".marcar-leida").click(function() {       //Camptura el evento click del elemento marcar-leida
            let notificacionId = $(this).data("id");    //se obtiene el id de la notificacion, $(this) hace referencia al boton clickeado, y recoge el id que manda el boton

            $.ajax({
                url: "marcar_notificacion.php",
                type: "POST",
                data: {
                    id: notificacionId      //envia el id de la notificacion al servidor
                },
                success: function(response) {   //Si la solicitud es exitosa, la respuesta del servidor se coloca en el contenedor con el ID notificaciones, actualizando las notificaciones mostradas al usuario
                    $("#notificaciones").html(response);    
                },
                error: function(error) {
                    alert("Error al marcar la notificación como leída.");   //mensaje en caso de error
                }
            });
        });
    });
</script>