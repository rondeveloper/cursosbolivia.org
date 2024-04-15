<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
/* recepcion de datos POST */
$id_correo_notificador = post('id_correo_notificador');
// eliminar correo notificador
    query("DELETE FROM notificadores_de_correo  WHERE id = '$id_correo_notificador' LIMIT 1");
