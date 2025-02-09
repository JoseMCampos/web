<?php

require_once './config/config.php';

/**
 * 
 * funcion para conectar a la base de datos 
 * @return $c en caso de que la conexion tenga exito
 * @return false en caso de no poder conectar a la base de datos 
 */

function conectar(){
    try{

        $conexion = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        $c=new PDO(DB_DSN,DB_USER,DB_PASSWORD, $conexion);   //nueva instancia de la clase pdo para conectarnos a la base de datos

    }catch(PDOException $e){
            return false;
    }

    return $c;
}
