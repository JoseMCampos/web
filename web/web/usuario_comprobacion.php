<?php

//Comprueba los datos del usuario y se guardan en  la base de datos

// Incluir la configuración de la base de datos
require_once './conectar.php';

// Obtener la conexión a la base de datos
$c = conectar();

// Recoger y sanitizar los datos del formulario
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$apellido1 = filter_input(INPUT_POST, 'apellido1', FILTER_SANITIZE_STRING);
$apellido2 = filter_input(INPUT_POST, 'apellido2', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$contrasenya = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);

// Validar que los campos requeridos no estén vacíos
if (empty($nombre) || empty($apellido1) || empty($apellido2) || empty($email) || empty($contrasenya) || empty($rol)) {
    echo "Todos los campos son obligatorios.";
    exit;
}

// Validar longitud de los campos
if (strlen($nombre) > 50) {
    echo "El nombre no puede tener más de 50 caracteres.";
    exit;
}

if (strlen($apellido1) > 50) {
    echo "El primer apellido no puede tener más de 50 caracteres.";
    exit;
}

if (strlen($apellido2) > 50) {
    echo "El segundo apellido no puede tener más de 50 caracteres.";
    exit;
}

if (strlen($email) > 50) {
    echo "El correo electrónico no puede tener más de 50 caracteres.";
    exit;
}

//Validar el formato de la contraseña. Debe tener al menos una mayuscula, un numero y menos de 12 caracteres. 
if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{1,12}$/', $contrasenya)) {
    echo "La contraseña debe tener máximo 12 caracteres, al menos una mayúscula y un número.";
    exit;
}

// Validar el formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "El correo electrónico no tiene un formato válido.";
    exit;
}

// Verificar que el rol sea válido
if ($rol !== 'usuario' && $rol !== 'administrador') {
    echo "Rol inválido.";
    exit;
}

// Verificar si el email ya está registrado
$sql = "SELECT id FROM usuarios WHERE email = :email";
$stmt = $c->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->fetch()) {
    echo "El correo electrónico ya está registrado.";
    exit;
}

// Encriptar la contraseña con SHA-256
$contrasenya_hash = hash('sha256', $contrasenya);


try {
    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, email, contrasenya, rol_tipo, fecha_creacion, fecha_modificacion, estado) 
        VALUES (:nombre, :apellido1, :apellido2, :email, :password, :rol, NOW(), NOW(), 1)";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':apellido1', $apellido1, PDO::PARAM_STR);
    $stmt->bindParam(':apellido2', $apellido2, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $contrasenya_hash, PDO::PARAM_STR);
    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "<script>
            alert('Usuario creado exitosamente.');
            window.location.href = 'formulario_crear_usuario.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al crear el usuario.');
            window.location.href = 'formulario_crear_usuario.php';
        </script>";
    }

} catch (PDOException $e) {
    echo "Error al crear la incidencia: " . $e->getMessage();
}
