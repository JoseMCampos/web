<?php

//Cambiar estado de la incidencia 

require_once './conectar.php';

session_start();

// Obtener la conexión a la base de datos
$c = conectar();

// Comprobar si se recibió el id de la incidencia
if (!isset($_POST['id'])) {
    echo "<script>alert('ID de incidencia no proporcionado.'); window.location.href='admin_dashboard.php';</script>";
    exit;
}

//guardar el id de la incidencia
$incidencia_id = $_POST['id'];

try {
    // Consultar el estado actual de la incidencia
    $sql = "SELECT estado, usuario_id FROM incidencias WHERE id = :id";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':id', $incidencia_id, PDO::PARAM_INT);
    $stmt->execute();
    $incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la incidencia, si no es asi se muestra un mensaje y se redirecciona al admin al inicio
    if (!$incidencia) {
        echo "<script>alert('Incidencia no encontrada.'); window.location.href='admin_dashboard.php';</script>";
        exit;
    }

    // Comprobar si el estado ya es "resuelta", si lo es se muestra un mensaje y recarga la pagina
    if ($incidencia['estado'] === 'resuelta') {
        echo "<script>alert('La incidencia ya está en estado \"resuelta\".'); window.location.href='admin_dashboard.php';</script>";
        exit;
    }

    // Si el estado es pendiente se actualiza el estado de la incidencia a "resuelta" en la base de datos
    $sql = "UPDATE incidencias SET estado = 'resuelta' WHERE id = :id";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':id', $incidencia_id, PDO::PARAM_INT);

        //mandar notificacion al usuario cuando se cambie el estado 
        $usuario_id = $incidencia['usuario_id'];
        $mensaje = "Tu incidencia con ID $incidencia_id ha sido marcada como Resuelta.";
        $sql_notificacion = "INSERT INTO notificaciones (usuario_id, mensaje, leido) VALUES (?, ?, 0)";
        $stmt_notificacion = $c->prepare($sql_notificacion);
        $stmt_notificacion->execute([$usuario_id, $mensaje]);    //Pasamos los valores por parametros para ejecutar la consulta


    //Segun si la consulta se ejecuta bien o no se muestra un mensaje u otro
    if ($stmt->execute()) {
        echo "<script>alert('Estado de la incidencia actualizado a \"resuelta\".'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('No se pudo actualizar el estado de la incidencia.'); window.location.href='admin_dashboard.php';</script>";
    }

} catch (PDOException $e) {
    //mensaje en caso de error
    echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='admin_dashboard.php';</script>";
    exit;
}
?>
