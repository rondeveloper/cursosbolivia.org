<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
/* recepcion de datos POST */
$correo = post('correo');
$user = post('user');
$password = post('password');
$nombre_remitente = post('nombre_remitente');
$descripcion = post('descripcion');
$cifrado = post('cifrado');
$puerto = post('puerto');
$host = post('host');
$id_correo_notificador = post('id_correo_notificador');
// editar correo notificador
    query("UPDATE notificadores_de_correo SET correo='$correo', user='$user', password='$password', nombre_remitente='$nombre_remitente', descripcion='$descripcion', cifrado='$cifrado', puerto='$puerto', host='$host' WHERE id = '$id_correo_notificador' LIMIT 1");
