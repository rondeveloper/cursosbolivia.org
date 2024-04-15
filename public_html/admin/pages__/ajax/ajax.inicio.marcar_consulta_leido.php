<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

$id_consulta = post('id_consulta');

$rqdc1 = query("SELECT sw_leido FROM consultas WHERE id='$id_consulta' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$sw_leido = $rqdc2['sw_leido'];
if ($sw_leido == '0') {
    query("UPDATE consultas SET sw_leido='1' WHERE id='$id_consulta' ORDER BY id DESC limit 1 ");
} else {
    query("UPDATE consultas SET sw_leido='0' WHERE id='$id_consulta' ORDER BY id DESC limit 1 ");
}
