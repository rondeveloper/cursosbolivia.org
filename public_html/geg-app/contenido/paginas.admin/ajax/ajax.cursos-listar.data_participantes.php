<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* datos de control de consulta */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = mysql_real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
}
$user_agent = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);

/* data */
$id_curso = post('id_curso');
$hash = post('hash');

/* verificacion */
if(md5(md5("7".$ip_coneccion."d".date("Y-m-d H")."0".$user_agent))!==$hash){
    echo "DENEGADO";
    exit;
}

$resultado1first = query("SELECT "
        . "fecha,"
        . "id_modalidad,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' order by id desc)cnt_participantes "
        . " FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto_first = mysql_fetch_array($resultado1first);

$id_modalidad_curso = $producto_first['id_modalidad'];
$fecha_curso = $producto_first['fecha'];
$cnt_participantes_curso = $producto_first['cnt_participantes'];

$cnt_participantes_2 = 0;
$cnt_participantes_3 = 0;
$cnt_participantes_4 = 0;
$cnt_participantes_dia_curso = 0;
$cnt_participantes_deposito = 0;
$cnt_participantes_tigomoney = 0;

if ($cnt_participantes_curso > 0) {

    $resultado1 = query("SELECT "
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='transferencia' order by id desc)cnt_participantes_2,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='oficina' order by id desc)cnt_participantes_3,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='khipu' order by id desc)cnt_participantes_4,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='dia_curso' order by id desc)cnt_participantes_dia_curso,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='deposito' order by id desc)cnt_participantes_deposito,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='tigomoney' order by id desc)cnt_participantes_tigomoney"
            . " FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $producto = mysql_fetch_array($resultado1);

    $cnt_participantes_2 = $producto['cnt_participantes_2'];
    $cnt_participantes_3 = $producto['cnt_participantes_3'];
    $cnt_participantes_4 = $producto['cnt_participantes_4'];
    $cnt_participantes_dia_curso = $producto['cnt_participantes_dia_curso'];
    $cnt_participantes_deposito = $producto['cnt_participantes_deposito'];
    $cnt_participantes_tigomoney = $producto['cnt_participantes_tigomoney'];
}

$content_data1 = '';

$content_data1 .= "<div style='width:120px;'>";

$content_data1 .= "<div style='float:left;width:35px;text-align:center;padding-top:20px;'>";
if ($cnt_participantes_curso > 0) {
    $content_data1 .= '<b style="font-size:12pt;color:#00789f;">' . $cnt_participantes_curso . '</b>';
} else {
    $content_data1 .= '<span style="font-size:10pt;">' . $cnt_participantes_curso . '</span>';
}
$content_data1 .= "</div>";

$content_data1 .= "<div style='float:left;width:85px;'>";

$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_2 . " transferencia</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_3 . " oficina</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_4 . " khipu</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_dia_curso . " dia del curso</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_deposito . " deposito</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_tigomoney . " tigomoney</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . ($cnt_participantes_curso - $cnt_participantes_2 - $cnt_participantes_3 - $cnt_participantes_4 - $cnt_participantes_dia_curso - $cnt_participantes_deposito - $cnt_participantes_tigomoney) . " sin pago</span>";

/* fuera de fecha */
$rqdpff1 = query("SELECT count(*) AS total FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_curso' AND cp.estado='1' AND date(pr.fecha_registro)>'$fecha_curso' ");
$rqdpff2 = mysql_fetch_array($rqdpff1);
$cnt_fuera_de_fecha = $rqdpff2['total'];
if ($cnt_fuera_de_fecha > 0) {
    $content_data1 .= "<br/>";
    $content_data1 .= "<span style='font-size:9pt;color:red;'>" . $cnt_fuera_de_fecha . " fuera de fecha</span>";
}

/* con-pago sin activacion */
if($id_modalidad_curso=='2' || $id_modalidad_curso=='3'){
    $ids_parts_conpago_sinactivacion = '0';
    $cnt_parts_conpago_sinactivacion = 0;
    $rqdpffs1 = query("SELECT cp.id,cp.id_usuario FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_curso' AND cp.estado='1' AND pr.sw_pago_enviado='1' ");
    while($rqdpffs2 = mysql_fetch_array($rqdpffs1)){
        $id_part = $rqdpffs2['id'];
        $id_usuario_part = $rqdpffs2['id_usuario'];
        if($id_usuario_part=='0'){
            $ids_parts_conpago_sinactivacion .= ','.$id_part;
            $cnt_parts_conpago_sinactivacion++;
        }else{
            $rqdpca1 = query("SELECT count(1) AS total FROM cursos_onlinecourse_acceso a INNER JOIN cursos_rel_cursoonlinecourse oc ON a.id_onlinecourse=oc.id_onlinecourse WHERE a.id_usuario='$id_usuario_part' AND a.sw_acceso='1' AND oc.id_curso='$id_curso' ");
            $rqdpca2 = mysql_fetch_array($rqdpca1);
            if($rqdpca2['total']==0){
                $ids_parts_conpago_sinactivacion .= ','.$id_part;
                $cnt_parts_conpago_sinactivacion++;
            }
        }
    }
    if ($cnt_parts_conpago_sinactivacion > 0) {
        $content_data1 .= "<br/>";
        $content_data1 .= "<a style='font-size:10pt;color:red;font-weight:bold;' href='cursos-participantes/$id_curso/no-turn/__ids_$ids_parts_conpago_sinactivacion.adm' class='btn btn-xs btn-default' target='_blank'>" . $cnt_parts_conpago_sinactivacion . " SIN ACTIVACION</a>";
    }
}

$content_data1 .= "</div>";

$content_data1 .= "<div style='clear:both;'></div>";

$content_data1 .= "</div>";



/* DATA2 */
$resultado1b = query("SELECT (select count(*) from cursos_rel_notifcuremail where id_curso=c.id)c_envios,(select count(*) from cursos_rel_notifsuscpush where id_curso=c.id)push_envios FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto_b = mysql_fetch_array($resultado1b);
$content_data2 = '';
if ($producto_b['c_envios'] > 0) {
    $content_data2 .= "<br/><br/>";
    $content_data2 .= '<b style="color:#247ca2;">' . $producto_b['c_envios'] . '</b> <i style="color:gray;">correos</i>';
}
if ($producto_b['push_envios'] > 0) {
    $content_data2 .= "<br/>";
    $content_data2 .= '<b style="color:#189835;">' . $producto_b['push_envios'] . '</b> <i style="color:gray;">Notif. push</i>';
}


/* DATA3 */
$content_data3 = '';
if ((int) $cnt_participantes_curso > 0) {
    $resultadoc1 = query("SELECT DISTINCT pr.id_administrador FROM cursos_participantes p INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE pr.id_curso='$id_curso' AND pr.id_administrador<>0 ");
    if (mysql_num_rows($resultadoc1) > 0) {
        $content_data3 .= '<br/><br/><b style="color:#ca410c;font-size:7pt;">REGISTRANTES</b><br/>';
    }
    while ($resultadoc2 = mysql_fetch_array($resultadoc1)) {
        if ($resultadoc2['id_administrador'] == '0') {
            //$content_data3 .= '<span style="color:#e08b32;font-size:7.5pt;">Sistema</span><br/>';
        } else {
            $rqda1 = query("SELECT nombre FROM administradores WHERE id='" . $resultadoc2['id_administrador'] . "' LIMIT 1 ");
            $rqda2 = mysql_fetch_array($rqda1);
            $content_data3 .= '<span style="color:#e08b32;font-size:8pt;">' . $rqda2['nombre'] . '<span style="color:blue;font-size:7pt;"><br/>';
        }
    }
}

$array_respuesta = array();
$array_respuesta['data1'] = $content_data1;
$array_respuesta['data2'] = $content_data2;
$array_respuesta['data3'] = $content_data3;


echo json_encode($array_respuesta);
