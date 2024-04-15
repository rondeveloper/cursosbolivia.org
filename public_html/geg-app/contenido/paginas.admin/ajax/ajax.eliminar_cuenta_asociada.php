<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador() && isset_post('dat')) {

    $id_cuenta = (int) post('dat');

    $rqaux1 = query("SELECT id_empresa,correo FROM cuentas WHERE id='$id_cuenta' ");
    $rqaux2 = mysql_fetch_array($rqaux1);
    $id_empresa = $rqaux2['id_empresa'];
    $correo_cuenta= $rqaux2['correo'];
    
    $rq = query("DELETE FROM cuentas WHERE id='$id_cuenta' ");
    if ($rq) {
        movimiento('Eliminación de cuenta asociada [' . $correo_cuenta . ']', 'eliminacion-cuenta ', 'usuario', $id_empresa);
        echo " <img src='contenido/imagenes/images/bien.png' style='width:25px;'>  Cuenta eliminada!!";
    } else {
        echo "Error";
    }
} else {
    echo "Denegado!";
}
?>
