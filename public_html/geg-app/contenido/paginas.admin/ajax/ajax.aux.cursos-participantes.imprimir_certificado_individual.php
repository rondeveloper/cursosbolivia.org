<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $id_certificado = post('dat');
    $rqidc1 = query("SELECT (select formato from cursos_certificados where id=cc.id_certificado order by id desc limit 1)formato_certificado,certificado_id FROM cursos_emisiones_certificados cc WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqidc2 = mysql_fetch_array($rqidc1);
    $formato_certificado = $rqidc2['formato_certificado'];
    $certificado_id = $rqidc2['certificado_id'];
    
    echo "http://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-$formato_certificado.php?id_certificado=$certificado_id";
} else {
    echo "Denegado!";
}

?>
