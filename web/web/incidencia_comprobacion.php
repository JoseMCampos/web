<?php
// Incluir la configuración de la base de datos
require_once './conectar.php';

session_start();

// Obtener la conexión a la base de datos
$c = conectar();

//Si la sesion no guarda el id del usuario redirecciona al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit;
}

// Obtener el id del usuario según el origen del formulario, segun si es del administrador o del usuario
if (isset($_POST['usuario_id']) && $_SESSION['rol'] === 'administrador') {

    // Si el usuario es administrador, usa el id enviado en el formulario
    $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_NUMBER_INT);

} else {
    // Si el usuario es normal, usa el id almacenado en la sesión
    $usuario_id = $_SESSION['usuario_id'];
}


// Recoger y sanitizar los datos del formulario
$categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
$descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
$estado = 'pendiente';


// Validar que los campos requeridos no estén vacíos
if (empty($usuario_id) || empty($categoria_id) || empty($titulo) || empty($descripcion)) {
    echo "Todos los campos son obligatorios.";
    exit;
}


// Validar longitud del título
if (strlen($titulo) > 25) {
    echo "El título no puede tener más de 25 caracteres.";
    exit;
}

// Validar si el usuario existe
$sql = "SELECT id FROM usuarios WHERE id = :usuario_id";
$stmt = $c->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();

if (!$stmt->fetch()) {
    echo "El usuario no existe o está inactivo.";
    exit;
}

// Validar si la categoría existe
if (!$categoria_id || $categoria_id <= 0 || $categoria_id > 5) {
    echo "El ID de la categoría no es válido.";
}

try {
    // Insertar la incidencia en la base de datos
    $sql = "INSERT INTO incidencias (usuario_id, categoria_id, titulo, descripcion, estado) 
            VALUES (:usuario_id, :categoria_id, :titulo, :descripcion, 'pendiente')";
    $stmt = $c->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);


    //si la consulta se ejecutamuestra un mensaje u otro, y segun el rol de quien envio el formulario
    // redirecciona a una pagina u otra
    if ($stmt->execute()) {
        echo "<script>
            alert('Incidencia creada correctamente.');
            window.location.href = '" . ($_SESSION['rol'] === 'administrador' ? "formulario_crear_incidencia.php" : "usuario_dashboard.php") . "';
        </script>";
    } else {
        echo "<script>
            alert('Error al crear la incidencia.');
            window.location.href = 'formulario_crear_incidencia.php';
        </script>";
    }
    exit;

} catch (PDOException $e) {
    echo "Error al crear la incidencia: " . $e->getMessage();
}
