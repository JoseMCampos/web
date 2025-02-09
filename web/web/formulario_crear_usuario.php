<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="./css/admin_style.css">

    
</head>

<body>
    <div class="container">
        <form class="formulario-usuario" action="usuario_comprobacion.php" method="POST">
            <h2>Crear Usuario</h2>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido1">Primer Apellido:</label>
            <input type="text" id="apellido1" name="apellido1" required>

            <label for="apellido2">Segundo Apellido:</label>
            <input type="text" id="apellido2" name="apellido2" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="usuario">Usuario</option>
                <option value="administrador">Administrador</option>
            </select>
            
            <button type="submit">Crear Usuario</button>
        </form>
        <a class="boton-inicio" href="admin_dashboard.php">Inicio</a>
    </div>
</body>

</html>
