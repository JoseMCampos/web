CREATE TABLE usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido1 VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50) NOT NULL,
  email VARCHAR(50) UNIQUE NOT NULL,
  contrasenya VARCHAR(64) NOT NULL,
  rol_tipo enum('administrador','usuario') NOT NULL,
  fecha_creacion DATETIME NOT NULL,
  fecha_modificacion DATETIME NOT NULL,
  fecha_eliminacion DATETIME NULL,
  estado INT(11) NOT NULL
);

CREATE TABLE categoria (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

CREATE TABLE incidencias (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED,
  categoria_id INT UNSIGNED,
  titulo VARCHAR(250) NOT NULL,
  descripcion MEDIUMTEXT NOT NULL,
  estado ENUM('pendiente', 'resuelta') NOT NULL DEFAULT 'pendiente',
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (categoria_id) REFERENCES categoria(id)
);


CREATE TABLE notificaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  mensaje TEXT NOT NULL,
  leido TINYINT(1) NOT NULL DEFAULT 0,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
