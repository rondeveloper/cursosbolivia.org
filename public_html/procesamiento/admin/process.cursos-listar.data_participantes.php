<?php
session_start();

include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));



/* datos de control de consulta */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}
$user_agent = real_escape_string($_SERVER['HTTP_USER_AGENT']);

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
$producto_first = fetch($resultado1first);

$id_modalidad_curso = $producto_first['id_modalidad'];
$fecha_curso = $producto_first['fecha'];
$cnt_participantes_curso = $producto_first['cnt_participantes'];

$cnt_participantes_transferencia = 0;
$cnt_participantes_efectivo = 0;
$cnt_participantes_khipu = 0;
$cnt_participantes_deposito = 0;
$cnt_participantes_tigomoney = 0;
$cnt_participantes_sinpago = 0;

if ($cnt_participantes_curso > 0) {

    $resultado1 = query("SELECT "
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='4' order by id desc)cnt_participantes_transferencia,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='1' order by id desc)cnt_participantes_efectivo,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='6' order by id desc)cnt_participantes_khipu,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='3' order by id desc)cnt_participantes_deposito,"
            . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='5' order by id desc)cnt_participantes_tigomoney"
            . " FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $producto = fetch($resultado1);

    $cnt_participantes_transferencia = $producto['cnt_participantes_transferencia'];
    $cnt_participantes_efectivo = $producto['cnt_participantes_efectivo'];
    $cnt_participantes_khipu = $producto['cnt_participantes_khipu'];
    $cnt_participantes_deposito = $producto['cnt_participantes_deposito'];
    $cnt_participantes_tigomoney = $producto['cnt_participantes_tigomoney'];
    
    $cnt_participantes_sinpago = ($cnt_participantes_curso - $cnt_participantes_transferencia - $cnt_participantes_efectivo - $cnt_participantes_khipu - $cnt_participantes_deposito - $cnt_participantes_tigomoney);
}

$content_data1 = '';

$content_data1 .= "<div style='width:120px;'>";

$content_data1 .= "<div style='float:left;width:35px;text-align:center;padding-top:20px;'>";
if ($cnt_participantes_curso > 0) {
    $content_data1 .= '<br><b style="font-size:14pt;color:#00789f;">' . ($cnt_participantes_curso-$cnt_participantes_sinpago) . '</b>';
    $content_data1 .= '<br><br><span style="font-size: 8pt;color: #ababab;background: white;padding: 2px 5px;border-radius: 2px;border: 1px solid #d8d8d8;">' . ($cnt_participantes_curso) . '</span>';
} else {
    $content_data1 .= '<span style="font-size:10pt;">' . ($cnt_participantes_curso-$cnt_participantes_sinpago) . '</span>';
}
$content_data1 .= "</div>";

$content_data1 .= "<div style='float:left;width:85px;'>";

$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_tigomoney . " TigoMoney</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_deposito . " Deposito</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_transferencia . " Transferencia</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_efectivo . " Efectivo</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_khipu . " Khipu</span>";
$content_data1 .= "<br/>";
$content_data1 .= "<span style='font-size:8pt;color:gray;'>" . $cnt_participantes_sinpago . " Sin pago</span>";

/* fuera de fecha */
$rqdpff1 = query("SELECT count(*) AS total FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_curso' AND cp.estado='1' AND date(pr.fecha_registro)>'$fecha_curso' ");
$rqdpff2 = fetch($rqdpff1);
$cnt_fuera_de_fecha = $rqdpff2['total'];
if ($cnt_fuera_de_fecha > 0) {
    $content_data1 .= "<br/>";
    $content_data1 .= "<span style='font-size:9pt;color:red;'>" . $cnt_fuera_de_fecha . " fuera de fecha</span>";
}

/* con-pago sin activacion */
$ids_parts_conpago_sinactivacion = '0';
$cnt_parts_conpago_sinactivacion = 0;
if($id_modalidad_curso!='1'){
    $rqvecv1 = query("SELECT id FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    if(num_rows($rqvecv1)>0){
        $rqdpffs1 = query("SELECT cp.id,cp.id_usuario FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_curso' AND cp.estado='1' AND pr.sw_pago_enviado='1' ");
        while($rqdpffs2 = fetch($rqdpffs1)){
            $id_part = $rqdpffs2['id'];
            $id_usuario_part = $rqdpffs2['id_usuario'];
            //$content_data1 .= "[$id_part/$id_usuario_part]";
            if($id_usuario_part=='0'){
                $ids_parts_conpago_sinactivacion .= ','.$id_part;
                $cnt_parts_conpago_sinactivacion++;
            }else{
                $rqdpca1 = query("SELECT count(1) AS total FROM cursos_onlinecourse_acceso a INNER JOIN cursos_rel_cursoonlinecourse oc ON a.id_onlinecourse=oc.id_onlinecourse WHERE a.id_usuario='$id_usuario_part' AND a.sw_acceso='1' AND oc.id_curso='$id_curso' ");
                $rqdpca2 = fetch($rqdpca1);
                if($rqdpca2['total']==0){
                    $ids_parts_conpago_sinactivacion .= ','.$id_part;
                    $cnt_parts_conpago_sinactivacion++;
                }
            }
        }
    }else{
        $rqvsz1 = query("SELECT id FROM sesiones_zoom WHERE id_curso='$id_curso' AND estado=1 ");
        if(num_rows($rqvsz1)>0){
            $rqdpffs1 = query("SELECT cp.id FROM cursos_participantes cp INNER JOIN cursos_proceso_registro pr ON cp.id_proceso_registro=pr.id WHERE cp.id_curso='$id_curso' AND cp.estado='1' AND pr.sw_pago_enviado='1' AND cp.id NOT IN (select id_participante from rel_partszoom where id_curso='$id_curso') ");
            while($rqdpffs2 = fetch($rqdpffs1)){
                $id_part = $rqdpffs2['id'];
                $ids_parts_conpago_sinactivacion .= ','.$id_part;
                $cnt_parts_conpago_sinactivacion++;
            }
        }
    }
}

$content_data1 .= "</div>";

$content_data1 .= "<div style='clear:both;'></div>";

$content_data1 .= "</div>";

if ($cnt_parts_conpago_sinactivacion > 0) {
    $content_data1 .= "<br/>";
    $content_data1 .= "<a style='font-size: 10pt;color: red;font-weight: bold;padding: 10px 5px;' href='cursos-participantes/$id_curso/no-turn/__ids_$ids_parts_conpago_sinactivacion.adm' class='btn btn-xs btn-default btn-block' target='_blank'><b style='font-size:12pt;'>" . $cnt_parts_conpago_sinactivacion . "</b><br>SIN ACTIVACI&Oacute;N</a>";
}



/* DATA2 */
$resultado1b = query("SELECT (select count(*) from cursos_rel_notifcuremail where id_curso=c.id)c_envios,(select count(*) from cursos_rel_notifsuscpush where id_curso=c.id)push_envios FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto_b = fetch($resultado1b);
$content_data2 = '';
if ($producto_b['c_envios'] > 0) {
    $content_data2 .= '<b style="color:#247ca2;">' . $producto_b['c_envios'] . '</b> <i style="color:gray;">correos</i><br>';
}
if ($producto_b['push_envios'] > 0) {
    $content_data2 .= '<b style="color:#189835;">' . $producto_b['push_envios'] . '</b> <i style="color:gray;">Notif. push</i>';
}


/* DATA3 */
$content_data3 = '';
if ((int) $cnt_participantes_curso > 0) {
    $resultadoc1 = query("SELECT DISTINCT pr.id_administrador FROM cursos_participantes p INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE pr.id_curso='$id_curso' AND pr.id_administrador<>0 ");
    if (num_rows($resultadoc1) > 0) {
        $content_data3 .= '<b style="color:#ca410c;font-size:7pt;">REGISTRANTES</b><br/>';
    }
    while ($resultadoc2 = fetch($resultadoc1)) {
        if ($resultadoc2['id_administrador'] == '0') {
            //$content_data3 .= '<span style="color:#e08b32;font-size:7.5pt;">Sistema</span><br/>';
        } else {
            $rqda1 = query("SELECT nombre FROM administradores WHERE id='" . $resultadoc2['id_administrador'] . "' LIMIT 1 ");
            $rqda2 = fetch($rqda1);
            $content_data3 .= '<span style="color:#e08b32;font-size:8pt;">' . $rqda2['nombre'] . '<span style="color:blue;font-size:7pt;"><br/>';
        }
    }
}

$array_respuesta = array();
$array_respuesta['data1'] = $content_data1;
$array_respuesta['data2'] = $content_data2;
$array_respuesta['data3'] = $content_data3;

echo json_encode($array_respuesta);
