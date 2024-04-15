<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {


    $cupon = post('dat');

    $rqc1 = query("SELECT id FROM cupones WHERE codigo='$cupon' AND estado='9' ORDER BY id LIMIT 1 ");
    if (mysql_num_rows($rqc1) > 0) {
        $rqc2 = mysql_fetch_array($rqc1);
        $id_cupon = $rqc2['id'];
        query("UPDATE cupones SET estado='0',id_administrador_activador='" . administrador('id') . "' WHERE id='$id_cupon' ORDER BY id DESC limit 1 ");
        echo "<b><span style='color:green;'>$cupon</span> activado!</b><br/><br/>";
    }

    $rqcupones = query("SELECT codigo FROM cupones WHERE id_administrador_activador='" . administrador('id') . "' AND estado='0' ORDER BY id DESC ");
    while ($rqcupon = mysql_fetch_array($rqcupones)) {
        echo $rqcupon['codigo'] . "<br/>";
    }
}
?>
