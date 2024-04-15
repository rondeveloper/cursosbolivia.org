<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_ingresoegreso = post('id_ingresoegreso');
query("UPDATE contabilidad SET estado='0' WHERE id='$id_ingresoegreso' ORDER BY id DESC limit 1 ");
logcursos('Eliminacion de I/E [ID:'.$id_ingresoegreso.']', 'ingr-egr-eliminacion', 'ingreso-egreso', $id_ingresoegreso);
?>
Ok