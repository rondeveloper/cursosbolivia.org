<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



query("UPDATE administradores SET cookie='" . substr(md5(rand(100, 1000)), 1, 9) . "' WHERE id='" . administrador('id') . "' ");

session_unset();
session_destroy();

setcookie("hsygbaj", "", time() - 1);
setcookie("stedfyc", "", time() - 1);

unset($_COOKIE['hsygbaj']);
unset($_COOKIE['stedfyc']);

header("location: ../../../");
