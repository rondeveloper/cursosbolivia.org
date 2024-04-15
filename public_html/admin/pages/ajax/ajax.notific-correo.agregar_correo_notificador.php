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

// agregar correo notificador
    query("INSERT INTO notificadores_de_correo(correo, user, password, nombre_remitente, descripcion, cifrado, puerto, host, estado) VALUES('$correo', '$user', '$password', '$nombre_remitente', '$descripcion', '$cifrado', '$puerto', '$host', 1)");
