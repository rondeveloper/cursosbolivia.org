<?php
session_start();
include_once '../contenido/configuracion/config.php';
include_once '../contenido/configuracion/funciones.php';
include_once '../contenido/librerias/correo/class.phpmailer.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    include_once 'pages/login.php';
} else {
    include_once 'pages/templates/header.php';
    include_once 'pages/principal.php';
    include_once 'pages/templates/footer.php';
}
