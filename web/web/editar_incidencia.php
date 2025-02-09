<?php
require_once './conectar.php';

// Verificar si se ha enviado el formulario de edición (usamos POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitizar los datos de entrada
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

    // Verificar si los datos son válidos

    //si el id es valido y la descripcion no esta vacia se actualiza la incidencia 
    if ($id && $id > 0 && !empty($descripcion)) {

        try {
            // Obtener la conexión a la base de datos
            $c = conectar();
            
            // Preparar y ejecutar la actualización en la base de datos
            $sql = "UPDATE incidencias SET descripcion = :descripcion WHERE id = :id";
            $stmt = $c->prepare($sql);
            $stmt->execute([':descripcion' => $descripcion, ':id' => $id]);

            // Redirigir con un mensaje de éxito
            echo "<script>
                    alert('Descripción actualizada con éxito.');
                    window.location.href = 'admin_dashboard.php'; 
                  </script>";

        } catch (PDOException $e) {
            //mensaje en caso de error
            echo "Error al actualizar la incidencia: " . $e->getMessage();
        }
    } else {
        //mensaje en caso de no obtener los datos correctos
        echo "Datos inválidos.";
    }

} else {
    // Si no se ha enviado el formulario, mostrar el formulario para editar la incidencia

    // Asegurar que el ID de la incidencia se pasa correctamente
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // Verificar si el id es válido
    if ($id && $id > 0) {

        try {
            // Obtener la conexión a la base de datos
            $c = conectar();
            
            // Consultar la incidencia para obtener la descripción
            $sql = "SELECT * FROM incidencias WHERE id = :id";
            $stmt = $c->prepare($sql);
            $stmt->execute([':id' => $id]);
            $incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si la incidencia existe
            if ($incidencia) {

                // Mostrar el formulario de edición con los datos actuales de la incidencia
                echo '<link rel="stylesheet" href="./css/editar_style.css">';

                echo '<form action="editar_incidencia.php" method="POST">

                        <h2>Editar Incidencia</h2>
                        <label for="descripcion">Nueva Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required>' . htmlspecialchars($incidencia['descripcion']) . '</textarea>
                        <input type="hidden" name="id" value="' . $incidencia['id'] . '">

                        <button type="submit">Guardar Cambios</button>
                    </form>';
                echo '<br>';
                echo  '<a class="boton-inicio" href="admin_dashboard.php">volver</a>';

            } else {
                echo "Incidencia no encontrada.";
            }

        } catch (PDOException $e) {
            //mensaje en caso de error
            echo "Error al obtener la incidencia: " . $e->getMessage();
        }
    } else {
        //mensaje en caso de que el id no sea correcto
        echo "ID de incidencia no válido.";
    }
}
?>