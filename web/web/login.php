<?php

require_once './conectar.php';


// Obtener la conexión a la base de datos
$c = conectar();



// Recoger y sanitizar los datos del formulario
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$contrasenya = filter_input(INPUT_POST, 'contrasenya', FILTER_SANITIZE_STRING);


if (empty($email) || empty($contrasenya)) {
    echo "Por favor, complete todos los campos.";
    exit;
}



    // Consultar si el usuario está registrado
    $sql = "SELECT id, contrasenya, rol_tipo FROM usuarios WHERE email = :email AND estado = 1";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Verificar la contraseña
        $contrasenya_hash = hash('sha256', $contrasenya);

        if ($contrasenya_hash === $usuario['contrasenya']) {
            // Redirigir según el rol del usuario
            session_start();

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['rol'] = $usuario['rol_tipo'];

            if ($usuario['rol_tipo'] === 'administrador') {

                header("Location: admin_dashboard.php");

            } elseif ($usuario['rol_tipo'] === 'usuario') {

                header("Location: usuario_dashboard.php");

            } else {
                echo "Rol desconocido. Contacte con el administrador.";
            }
            exit;
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "El usuario no está registrado o está inactivo.";
    }

?>