<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    
    $id_factura = post('id');
    $rqf1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id='$id_factura' ORDER BY id DESC limit 1 ");
    $rqf2 = mysql_fetch_array($rqf1);
    query("UPDATE facturas_emisiones SET estado='2' WHERE id='$id_factura' ORDER BY id DESC limit 1 ");
    movimiento('Anulacion de factura Nro:' . $rqf2['nro_factura'], 'anulacion-factura', 'factura', $id_factura);
} else {
    echo "Denegado!";
}
?>
