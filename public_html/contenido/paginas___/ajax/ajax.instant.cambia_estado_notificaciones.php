<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* internal data */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}
$fecha_registro = date("Y-m-d H:i");

/* token */
$token = urldecode(post('token'));
$state = post('state');

switch ($state) {
    case '1':
        query("UPDATE cursos_suscnav SET estado='1' WHERE token='$token' ");
        echo "<a><i class='fa fa-thumbs-up'></i> Notificaciones activadas</a>";
        break;
    case '0':
        query("UPDATE cursos_suscnav SET estado='0' WHERE token='$token' ");
        echo "<a><i class='fa fa-thumbs-up'></i> Notificaciones des-activadas</a>";
        break;
    default:
        break;
}
