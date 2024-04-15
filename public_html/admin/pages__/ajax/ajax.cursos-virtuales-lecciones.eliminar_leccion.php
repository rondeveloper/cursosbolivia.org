<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_leccion = post('id_leccion');
$id_onlinecourse = post('id_onlinecourse');

query("DELETE FROM cursos_onlinecourse_lecciones WHERE id='$id_leccion' ORDER BY id LIMIT 1 ");

logcursos('Eliminacion de leccion [L:' . $id_leccion . ']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);

echo "Registro eliminado";