INSERT INTO usuarios (nombre, apellido1, apellido2, email, contrasenya, rol_tipo, fecha_creacion, fecha_modificacion, estado)
VALUES 
('Jose Manuel', 'Campos', 'Guillen', 'jose@gmail.com', SHA2('1234', 256), 'administrador', NOW(), NOW(), 1),
('Juan', 'Perez', 'Garcia', 'juan.perez@gmail.com', SHA2('1234', 256), 'administrador', NOW(), NOW(), 1),
('Ana', 'Lopez', 'Martinez', 'ana.lopez@gmail.com', SHA2('1234', 256), 'usuario', NOW(), NOW(), 1),
('Carlos', 'Gomez', 'Sanchez', 'carlos.gomez@gmail.com', SHA2('1234', 256), 'usuario', NOW(), NOW(), 1);


INSERT INTO categoria (id, nombre) 
VALUES
(1, 'Hardware'),
(2, 'Software'),
(3, 'Sistemas informaticos'),
(4, 'Redes')
(5, 'Otros');


INSERT INTO incidencias (usuario_id, categoria_id, titulo, descripcion, estado)
VALUES 
(4, 1, 'Fallo de conexion a la red', 'El usuario no puede conectarse a la red desde su PC.', 'pendiente'),
(5, 3, 'Problema con la instalacion del software', 'Al intentar instalar el software, aparece un error inesperado.', 'pendiente'),
(6, 2, 'PC no arranca', 'El equipo no enciende a pesar de estar correctamente conectado.', 'pendiente'),
(4, 4, 'Posible brecha de seguridad', 'El sistema ha detectado accesos sospechosos en la red interna.', 'pendiente'),
(7, 3, 'Error en la base de datos', 'El sistema reporta un error al intentar acceder a los datos en la base de datos.', 'pendiente');

