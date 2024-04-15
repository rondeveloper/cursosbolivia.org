<?php

error_reporting(1);

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

if (!isset_administrador()) {
    exit;
}

exit;

$arr_busc_1 = array(
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO:</span> [DESCUENTO-UNO]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO : </span>[DESCUENTO-UNO]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO</span> : [DESCUENTO-UNO]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO :</span> [DESCUENTO-UNO]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO:</span> [DESCUENTO-UNO] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO : </span>[DESCUENTO-UNO] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO</span> : [DESCUENTO-UNO] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO :</span> [DESCUENTO-UNO]</span>',
    'DESCUENTO: <span style="color: #000000;">[DESCUENTO-UNO]</span>',
    '<span style="color: #ff0000;">DESCUENTO : </span>[DESCUENTO-UNO]',
    '<span style="color: #ff0000;">DESCUENTO</span> : [DESCUENTO-UNO]',
    '<span style="color: #ff0000;">DESCUENTO :</span> [DESCUENTO-UNO]',
    'DESCUENTO: [DESCUENTO-UNO]',
    'DESCUENTO : [DESCUENTO-UNO]'
);
$arr_busc_2 = array(
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO:</span> [DESCUENTO-DOS]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO : </span>[DESCUENTO-DOS]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO</span> : [DESCUENTO-DOS]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO :</span> [DESCUENTO-DOS]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO:</span> [DESCUENTO-DOS] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO : </span>[DESCUENTO-DOS] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO</span> : [DESCUENTO-DOS] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">DESCUENTO :</span> [DESCUENTO-DOS]</span>',
    'DESCUENTO: <span style="color: #000000;">[DESCUENTO-DOS]</span>',
    '<span style="color: #ff0000;">DESCUENTO : </span>[DESCUENTO-DOS]',
    '<span style="color: #ff0000;">DESCUENTO</span> : [DESCUENTO-DOS]',
    '<span style="color: #ff0000;">DESCUENTO :</span> [DESCUENTO-DOS]',
    'DESCUENTO: [DESCUENTO-DOS]',
    'DESCUENTO : [DESCUENTO-DOS]'
);
$arr_busc_3 = array(
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES:</span> [COSTO-ESTUDIANTES]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES : </span>[COSTO-ESTUDIANTES]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES</span> : [COSTO-ESTUDIANTES]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES :</span> [COSTO-ESTUDIANTES]</span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES:</span> [COSTO-ESTUDIANTES] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES : </span>[COSTO-ESTUDIANTES] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES</span> : [COSTO-ESTUDIANTES] </span>',
    '<span style="color: #000000;"><span style="color: #ff0000;">ESTUDIANTES :</span> [COSTO-ESTUDIANTES]</span>',
    'ESTUDIANTES: <span style="color: #000000;">[COSTO-ESTUDIANTES]</span',
    '<span style="color: #ff0000;">ESTUDIANTES : </span>[COSTO-ESTUDIANTES]',
    '<span style="color: #ff0000;">ESTUDIANTES</span> : [COSTO-ESTUDIANTES]',
    '<span style="color: #ff0000;">ESTUDIANTES :</span> [COSTO-ESTUDIANTES]',
    'ESTUDIANTES: [COSTO-ESTUDIANTES]',
    'ESTUDIANTES : [COSTO-ESTUDIANTES]'
);
$arr_busc_4 = array(
    '<span style="color: #ff0000;">ESTUDIANTES:</span>',
    '<span style="color: #ff0000;">ESTUDIANTES : </span>',
    '<span style="color: #ff0000;">ESTUDIANTES</span> :',
    '<span style="color: #ff0000;">ESTUDIANTES :</span>',
    '<span style="color: #ff0000;">ESTUDIANTES : </span>',
    'ESTUDIANTES: ',
    'ESTUDIANTES : ',
    '<span style="color: #ff0000;">DESCUENTOS(mediante dep&oacute;sito Bancarios, Transferencias y/o Giro TigoMoney):</span>'
);

$remp_1 = '[DESCUENTO-UNO]';
$remp_2 = '[DESCUENTO-DOS]';
$remp_3 = '[COSTO-ESTUDIANTES]';
$remp_4 = '';



$rqdc1 = query("SELECT id,contenido FROM cursos WHERE estado IN (0) AND contenido LIKE '%>[DESCUENTO-UNO]</span>%' ORDER BY id DESC ");
while($rqdc2 = fetch($rqdc1)){
    $cont = str_replace($arr_busc_3,$remp_3,str_replace($arr_busc_2,$remp_2,str_replace($arr_busc_1,$remp_1,$rqdc2['contenido'])));
    $id_cur = $rqdc2['id'];
    query("UPDATE cursos SET contenido='$cont' WHERE id='$id_cur' ORDER BY id DESC limit 1 ");
    echo "CUR $id_cur - Actualizado!<br>";
}
