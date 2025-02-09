<?php
//marcar las notificaciones como leidas
require_once './conectar.php';

//Si el servidor recibe el id actualiza las notificaciones
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    try {
        $c = conectar();
        $notificacion_id = $_POST['id'];

        $sql = "UPDATE notificaciones SET leido = 1 WHERE id = :id";
        $stmt = $c->prepare($sql);
        $stmt->execute([':id' => $notificacion_id]);

        echo "Notificaciones actualizadas.";
        
    } catch(PDOException $e) {
        echo "Ha ocurrido un error: " . $e->getMessage();
    }
}
?>