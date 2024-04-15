<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* id_departamento */
$id_departamento = post('id_departamento');

/* token registrado */
if (isset($_COOKIE['token_nav'])) {
    $token_nav = $_COOKIE['token_nav'];
    query("UPDATE cursos_suscnav SET id_departamento='$id_departamento' WHERE token='$token_nav' ");
}

