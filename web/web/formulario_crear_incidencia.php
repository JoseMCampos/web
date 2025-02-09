<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Incidencia</title>
    <link rel="stylesheet" href="./css/admin_style.css">

</head>

<body>
    <div class="container">
        
        <form  class="formulario-incidencia" action="incidencia_comprobacion.php" method="POST">
            
            <h2>Crear Incidencia</h2>
            <label for="usuario_id">ID del Usuario:</label>
            <input type="number" id="usuario_id" name="usuario_id" required>

            <label for="categoria_id">Categoría:</label>
            <select id="categoria_id" name="categoria_id" required>
                <?php
                require_once './conectar.php';
                $c = conectar();

                // Consultar las categorías disponibles en la base de datos para mostrarlas en el formulario
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
        <a class="boton-inicio" href="admin_dashboard.php">Inicio</a>
    </div>
</body>

</html>