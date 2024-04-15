<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador() && !isset_organizador()) {
    echo "DENEGADO";
    exit;
}

$id_curso = post('id_curso');

$resultado1 = query("SELECT "
        . "fecha,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' order by id desc)cnt_participantes,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='4' order by id desc)cnt_participantes_2,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='1' order by id desc)cnt_participantes_3,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='6' order by id desc)cnt_participantes_4,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='1' order by id desc)cnt_participantes_dia_curso,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='3' order by id desc)cnt_participantes_deposito,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='5' order by id desc)cnt_participantes_tigomoney"
        . " FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = fetch($resultado1);

$fecha_curso = $producto['fecha'];

$content_data1 = '';

$content_data1 .= "<div style='width:120px;'>";

$content_data1 .= "<div style='float:left;width:35px;text-align:center;padding-top:20px;'>";
if ($producto['cnt_participantes'] > 0) {
    $content_data1 .= '<b style="font-size:12pt;color:#00789f;">' . $producto['cnt_participantes'] . '</b>';
} else {
    $content_data1 .= '<span style="font-size:10pt;">' . $producto['cnt_participantes'] . '</span>';
}
$content_data1 .= "</div>";

$content_data1 .= "<div style='float:left;width:85px;'>";

$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_2'] . " transferencia</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_3'] . " oficina</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_4'] . " khipu</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_dia_curso'] . " dia del curso</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_deposito'] . " deposito</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_tigomoney'] . " tigomoney</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . ($producto['cnt_participantes'] - $producto['cnt_participantes_2'] - $producto['cnt_participantes_3'] - $producto['cnt_participantes_4'] - $producto['cnt_participantes_dia_curso'] - $producto['cnt_participantes_deposito'] - $producto['cnt_participantes_tigomoney'] - $producto['cnt_fuera_de_fecha']) . " sin pago</span>";

/* fuera de fecha */
$rqdpff1 = query("SELECT count(*) AS total FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_curso' AND cp.estado='1' AND date(pr.fecha_registro)>'$fecha_curso' ");
$rqdpff2 = fetch($rqdpff1);
$cnt_fuera_de_fecha = $rqdpff2['total'];
if ($cnt_fuera_de_fecha > 0) {
    $content_data1 .= "<br/>";
    $content_data1 .= "<span style='font-size:9pt;color:red;'>" . $cnt_fuera_de_fecha . " fuera de fecha</span>";
}


$content_data1 .= "</div>";

$content_data1 .= "<div style='clear:both;'></div>";

$content_data1 .= "</div>";


$content_data2 = '';

$array_respuesta = array();
$array_respuesta['data1'] = $content_data1;
$array_respuesta['data2'] = $content_data2;


echo json_encode($array_respuesta);
