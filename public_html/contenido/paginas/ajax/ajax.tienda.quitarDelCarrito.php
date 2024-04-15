<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* recepcion de datos POST */
$Carrito = new Carrito();
$id_curso = (int)post('id_curso');
if ($id_curso != 0) {
    $Carrito->remove($id_curso);
    echo "Quitado $id_curso";
}
