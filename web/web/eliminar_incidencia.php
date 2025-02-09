<?php
require_once './conectar.php';


//Eliminar incidencia de la base de datos 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //obtiene el id de la incidencia
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        
        try {
            $c = conectar();
            $sql = "DELETE FROM incidencias WHERE id = :id";
            $stmt = $c->prepare($sql);
            $stmt->execute([':id' => $id]);

            echo "<script>
                    alert('Incidencia eliminada con éxito.');
                    window.location.href = 'admin_dashboard.php';
                  </script>";

        } catch (PDOException $e) {
            echo "Error al eliminar la incidencia: " . $e->getMessage();
        }
    }
}
?>