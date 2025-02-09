<?php
/**
 * Archivo de configuracion para la conexion a la base de datos
 */

define ('DB_HOSTNAME', 'db'); //nombre del servidor, en este caso de  xampp
define ('DB_USER', 'helpdesk_user');     //nombre del usuario para utilizar la base de datos
define ('DB_PASSWORD', 'userpassword');     //contraseña del usuario a la base de datos
define ('DB_NAME', 'helpdesk'); //nombre de la base de datos

define('DB_DSN', 'mysql:host='.DB_HOSTNAME.';dbname='.DB_NAME.';charset=utf8mb4');
  //definicion del dsn de la base de datos