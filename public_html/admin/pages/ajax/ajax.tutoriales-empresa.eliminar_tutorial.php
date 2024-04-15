<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_tutorial = post('id_tutorial');

query("DELETE FROM tutoriales_empresa WHERE id='$id_tutorial' ORDER BY id LIMIT 1 ");

echo "Registro eliminado";