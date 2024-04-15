<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_usuario()) {
    unset($_SESSION['user_id']);
    /* cookie principal */
    setcookie("hash_usuario", "", time() - 100 , "/");
    setcookie("id_usuario", "", time() - 100 , "/");
    /* cookie curso-online (borrado auxiliar) */
    setcookie("hash_usuario", "", time() - 100 , "/curso-online/");
    setcookie("id_usuario", "", time() - 100 , "/curso-online/");
}
if (isset_docente()) {
    unset($_SESSION['doc_id']);
    /* cookie principal */
    setcookie("hash_usuario", "", time() - 100 , "/");
    setcookie("id_usuario", "", time() - 100 , "/");
    /* cookie curso-online (borrado auxiliar) */
    setcookie("hash_usuario", "", time() - 100 , "/curso-online/");
    setcookie("id_usuario", "", time() - 100 , "/curso-online/");
}
session_unset();
session_destroy();
header("location: ../../");
