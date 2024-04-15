<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$cupon = post('dat');

$rqc1 = query("SELECT id FROM cupones WHERE codigo='$cupon' AND estado='9' ORDER BY id LIMIT 1 ");
if (num_rows($rqc1) > 0) {
    $rqc2 = fetch($rqc1);
    $id_cupon = $rqc2['id'];
    query("UPDATE cupones SET estado='0',id_administrador_activador='" . administrador('id') . "' WHERE id='$id_cupon' ORDER BY id DESC limit 1 ");
    echo "<b><span style='color:green;'>$cupon</span> activado!</b><br/><br/>";
}

$rqcupones = query("SELECT codigo FROM cupones WHERE id_administrador_activador='" . administrador('id') . "' AND estado='0' ORDER BY id DESC ");
while ($rqcupon = fetch($rqcupones)) {
    echo $rqcupon['codigo'] . "<br/>";
}
