<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_curso = post('id_curso');

/* datos de configuracion */
$sw_facturacion_modulos = $__CONFIG_MANAGER->getSw('sw_facturacion_modulos');

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado,fecha,id_modalidad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$fecha_curso = $rqvhc2['fecha'];
$id_modalidad_curso = (int) $rqvhc2['id_modalidad'];

/* sw_curso_virtual */
$sw_curso_virtual = false;
$qrcoe1 = query("SELECT count(*) AS total FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' AND estado='1' ORDER BY id DESC limit 1 ");
$qrcoe2 = fetch($qrcoe1);
$cnt_cursos_cirtuales_asociados = (int) $qrcoe2['total'];
if ($cnt_cursos_cirtuales_asociados > 0 && ($id_modalidad_curso == 2 || $id_modalidad_curso == 3 || $id_modalidad_curso == 4)) {
    $sw_curso_virtual = true;
}

/* sw de curso presencial */
$SW_curso_presencial = false;
if($id_modalidad_curso=='1'){
    $SW_curso_presencial = true;
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('busc') && post('busc')!='no-id' && post('busc')!='') {
    $busqueda = post('busc');
    if(strpos($busqueda,'ids_')>0){
        $qr_busqueda = " AND ( p.id IN (".str_replace('__ids_','',$busqueda).") ) ";
    }else{
        $qr_busqueda = " AND ( p.id='$busqueda' OR p.nombres LIKE '%$busqueda%' OR p.apellidos LIKE '%$busqueda%' OR p.correo LIKE '%$busqueda%' OR p.id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ) ";
    }
    $vista = 1;
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset_post('id_turno') && (post('id_turno') > 0)) {
    $id_turno_curso = (int) post('id_turno');
    $qr_turno = " AND p.id_turno='$id_turno_curso' ";
}


/* datos de curso */
$rqc1 = query("SELECT id_certificado,id_certificado_2,id_certificado_3,id_material,titulo,mailto_subject,mailto_content,sw_askfactura,sw_ipelc,mod_notas,costo FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_curso = $rqc2['titulo'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$id_certificado_3_curso = $rqc2['id_certificado_3'];
$sw_askfactura_curso = $rqc2['sw_askfactura'];
$sw_ipelc_curso = $rqc2['sw_ipelc'];
$mod_notas = $rqc2['mod_notas'];
$id_material_curso = $rqc2['id_material'];
$mailto_subject = str_replace(' ','%20',$rqc2['mailto_subject']);
$mailto_content = str_replace(' ','%20',str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqc2['mailto_content']));
$whatsto_subject = $rqc2['mailto_subject'].'%0D%0A'.str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqc2['mailto_content']);

/* SW es curso gratuito */
$_SW_es_curso_gratuito = false;
if ($rqc2['costo']==0) {
    $_SW_es_curso_gratuito = true;
}

/* sw_turno */
$sw_turnos = false;
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso'  ");
if (num_rows($rqtc1) > 0) {
    $sw_turnos = true;
    while ($rqtc2 = fetch($rqtc1)) {
        $turno[$rqtc2['id']] = $rqtc2['titulo'];
    }
}

/* pago */
$qr_pago = "";
if (isset_post('pago')) {
    $pago = post('pago');
    switch ($pago) {
        case 'conpago':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' AND pr.id_modo_pago<>'10' ";
            break;
        case 'sinpago':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='0' ";
            $pago = '';
            break;
        case 'gratuito':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' AND pr.id_modo_pago='10' ";
            $pago = '';
            break;
        case 'habilitados':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' ";
            $pago = '';
            break;
        default:
            break;
    }
}

/* sw cursos_cupones_infosicoes */
$sw_cupon_infosicoes = false;
$id_cupon_infosicoes = 0;
$rqveci1 = query("SELECT id FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
if (num_rows($rqveci1) > 0) {
    $sw_cupon_infosicoes = true;
    $rqveci2 = fetch($rqveci1);
    $id_cupon_infosicoes = $rqveci2['id'];
}

/* query principal */
$resultado1 = query("SELECT 
p.*,
(pr.id)dr_id_proceso_registro,
pr.codigo,pr.fecha_registro,pr.celular_contacto,pr.correo_contacto,pr.id_modo_pago,pr.id_emision_factura,pr.monto_deposito,pr.imagen_deposito,pr.razon_social,pr.nit,pr.cnt_participantes,pr.id_cobro_khipu,pr.sw_pago_enviado,pr.id_administrador,pr.imagen_matricula,
(d.nombre)dr_nombre_departamento,(mp.titulo)dr_modo_pago,(rvp.id)dr_id_verifpago 
FROM cursos_participantes p 
INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id 
LEFT JOIN departamentos d ON p.id_departamento=d.id 
LEFT JOIN modos_de_pago mp ON p.id_modo_pago=mp.id 
LEFT JOIN rel_pagosverificados rvp ON rvp.id_participante=p.id 
WHERE p.id NOT IN (select id_participante from cursos_part_apartados) 
AND p.id_curso='$id_curso' 
AND p.estado='1' $qr_busqueda $qr_turno $qr_pago 
ORDER BY p.id DESC ");

if (isset_post('pago')) {
    if(post('pago') == 'asistentes'){
    $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' ";
    $resultado1 = query("SELECT 
        p.*,
        (pr.id)dr_id_proceso_registro,
        pr.codigo,pr.fecha_registro,pr.celular_contacto,pr.correo_contacto,pr.id_modo_pago,pr.id_emision_factura,pr.monto_deposito,pr.imagen_deposito,pr.razon_social,pr.nit,pr.cnt_participantes,pr.id_cobro_khipu,pr.sw_pago_enviado,pr.id_administrador,pr.imagen_matricula,
        (d.nombre)dr_nombre_departamento,(mp.titulo)dr_modo_pago,(rvp.id)dr_id_verifpago 
        FROM cursos_participantes p 
        INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id 
        LEFT JOIN departamentos d ON p.id_departamento=d.id 
        LEFT JOIN modos_de_pago mp ON p.id_modo_pago=mp.id 
        LEFT JOIN rel_pagosverificados rvp ON rvp.id_participante=p.id 
        WHERE p.id IN (select id_participante from cursos_asistencia) 
        AND p.id_curso='$id_curso' 
        AND p.estado='1' $qr_busqueda $qr_turno $qr_pago 
        ORDER BY p.id DESC ");
    }
}
/* contador */
$cnt = num_rows($resultado1);

/* aux ids almacenador */
$aux_idsalmacenador = '0';

/* habilitador de cursos gratuitos asociados */
$sw_curso_gratuito_asociado = false;
$rqvcga1 = query("SELECT id FROM cursos_rel_cursofreecur WHERE id_curso='$id_curso' ORDER BY id DESC limit 1");
if(num_rows($rqvcga1)>0){
    $sw_curso_gratuito_asociado = true;
}

/* $sw_col_material */
$sw_col_material = false;
$rqcmc1 = query("SELECT count(*) AS total FROM materiales_curso WHERE id_curso='$id_curso' ");
$rqcmc2 = fetch($rqcmc1);
if ($id_modalidad_curso==4 && ((int)$rqcmc2['total']>0) ) {
    $sw_col_material = true;
}

/* $sw_col_act_suev */
$sw_col_act_suev = false;
if ($id_modalidad_curso==4) {
    $sw_col_act_suev = true;
}

/* sw_col_szoom */
$sw_col_szoom = false;
$rqvsz1 = query("SELECT id FROM sesiones_zoom WHERE id_curso='$id_curso' AND estado=1 ");
if(num_rows($rqvsz1)>0){
    $sw_col_szoom = true;
}

/* sw modalidades multiples */
$sw_modalidades_multiples = false;
$rqmm1 = query("SELECT * FROM rel_curso_modalidades WHERE id_curso='$id_curso' AND estado=1 LIMIT 1 ");
if (num_rows($rqmm1)>0) {
    $sw_modalidades_multiples  = true;
}
?>

<style>
    .reg-fuerafecha .simple-td{
        background: #faf2f2 !important;
    }
</style>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;"># <a class='btn btn-xs btn-default' onclick="$('input:checkbox').removeAttr('checked');">.</a></th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Nombres</th>
                <!-- <td class="" style="padding-top: 2px;padding-bottom: 2px;width: 40px;"><b class="btn btn-xs btn-default" onclick="invertir_nombre_apellido();"><i class="fa fa-retweet"></i></b></td> -->
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
                <?php if ($mod_notas=='1') { ?>
                <th class="simple-td text-center" style="padding-top: 2px;padding-bottom: 2px;">Nota</th>
                <?php } ?>
                <?php if ($sw_ipelc_curso=='1') { ?>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Doc. IPLC</th>
                <?php } ?>
                
                <?php if (!$_SW_es_curso_gratuito) { ?>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">M/Pago</th>
                <?php } ?>

                <?php if ($sw_col_act_suev || $sw_col_material || $sw_col_szoom) { ?>
                    <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;"></th>
                <?php } ?>
                
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Registro</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">C-vir/Cert</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;width:100px;text-align:right;"> 
                    <a class='btn btn-xs btn-default' onclick="reporte_cierre_p1();" data-toggle="modal" data-target="#MODAL-generar-reporte" style="font-weight: bold;color: #15862c;">
                        <i class="fa fa-file-text sidebar-nav-icon"></i> &nbsp; REPORTE
                    </a>
                </th>
            </tr>
        </thead>

        <tbody>
            <?php
            $sw_exist_cert_uno = false;
            $sw_exist_cert_dos = false;
            $sw_exist_cert_tres = false;
            $sw_existencia_facturas = false;

            if (num_rows($resultado1) == 0) {
                echo "<tr><td colspan='11'>No existen participantes registrados.</td></tr>";
            }

            $aux_line_correos = '<button onclick="copyToClipboard(\'agr10-1\')">Copiar</button> <span id="agr10-1">';
            $aux_cnt_line_correos = 0;
            $aux_cnt_line_correos_block = 1;
            $cnt_nomap = 0;
            
            while ($participante = fetch($resultado1)) {
                $id_proceso_de_registro = $participante['dr_id_proceso_registro'];
                $codigo_de_registro = $participante['codigo'];
                $fecha_de_registro = $participante['fecha_registro'];
                $celular_de_registro = $participante['celular_contacto'];
                $correo_de_registro = $participante['correo_contacto'];
                $nro_participantes_de_registro = $participante['cnt_participantes'];
                $id_emision_factura = $participante['id_emision_factura'];

                $id_modo_pago = $participante['id_modo_pago'];
                $monto_de_pago = $participante['monto_deposito'];
                $imagen_de_deposito = $participante['imagen_deposito'];
                $imagen_matricula = $participante['imagen_matricula'];

                $razon_social_de_registro = $participante['razon_social'];
                $nit_de_registro = $participante['nit'];

                $id_cobro_khipu = $participante['id_cobro_khipu'];

                $aux_idsalmacenador .= ',' . $participante['id'];

                $tr_class = '';
                $text_msj = '';
                if (strtotime(date("Y-m-d", strtotime($participante['fecha_registro']))) > strtotime($fecha_curso)) {
                    $tr_class = 'reg-fuerafecha';
                    $text_msj = '<br/><i style="color:red;font-size:7pt;">Fuera de fecha</i>';
                }
                
                $aux_line_correos .= $participante['correo'].', ';
                if(++$aux_cnt_line_correos==10){
                    $aux_cnt_line_correos=0;
                    $aux_line_correos .= '</span><br><hr><br>'.'<button onclick="copyToClipboard(\'agr10-'.++$aux_cnt_line_correos_block.'\')">Copiar</button> <span id="agr10-'.$aux_cnt_line_correos_block.'">';
                }
                
                $id_pais = $participante['id_pais'];
                
                /* codigo pais */
                $rqdcw1 = query("SELECT codigo FROM paises WHERE id='$id_pais' LIMIT 1 ");
                $rqdcw2 = fetch($rqdcw1);
                $codigo_pais = $rqdcw2['codigo'];

                /* verificacion de pago */
                $_SW_pago_verificado = false;
                if(($participante['sw_pago_enviado']=='1' && $participante['dr_id_verifpago']!="") || $_SW_es_curso_gratuito){
                    $_SW_pago_verificado = true;
                }
                ?>
                <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>" class="<?php echo $tr_class; ?>">
                    <td class="simple-td text-center">
                        <?php echo $cnt--; ?>
                        <br>
                        <br>
                        <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                        </b>
                        <?php
                        if ($sw_habilitacion_procesos && $participante['sw_notif']=='1') {
                            if (true) {
                                $ckecked = ' checked="" ';
                            } else {
                                $ckecked = ' disabled ';
                            }
                            ?>
                            <br>
                            <br>
                            <input type="checkbox" id="<?php echo $participante['id']; ?>" name="" <?php echo $ckecked; ?> style="width:17px;height:17px;"/>
                            <?php
                        }
                        ?>
                        <br>
                        <br>
                        <span class="btn btn-xs btn-block btn-default" onclick="participante_cursos_registrados('<?php echo $participante['id']; ?>');">(H)</span>
                    </td>
                    <td class="nombre-td" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                        <span id="box-nomap-a-<?php echo ++$cnt_nomap; ?>" onclick="nom_to_busc(<?php echo $cnt_nomap; ?>);"><?php echo trim($participante['nombres']); ?></span> <span id="box-nomap-b-<?php echo $cnt_nomap; ?>" onclick="nom_to_busc(<?php echo $cnt_nomap; ?>);"><?php /*echo trim($participante['apellidos']);*/ ?></span>
                        <br/>
                        <b style="font-size:7pt;color:#2180be;">No. <?php echo $participante['numeracion']; ?></b>
                        <?php
                        echo "<br/>";
                        echo '<span style="color:gray;font-size:8pt;"><a href="mailto:' . $participante['correo'] . '?subject='. $mailto_subject . '&body='.$mailto_content.'">' . $participante['correo'] . '</a><br/><a target="_blank" href="https://api.whatsapp.com/send?phone=' . $codigo_pais .$participante['celular'] . '&text='.($mailto_content).'" id="c'.$participante['celular'].'">' . $participante['celular'] . '</a></span>';
                        if($participante['sw_notif']=='0'){
                            echo '<div class="alert alert-danger">
                            <strong>CORREO INVALIDO</strong><br>No realizar ningun envio de correo, material, certificado etc.
                          </div>';
                        }
                        echo '<br>';
                        
                        if ($sw_habilitacion_procesos) {
                                ?>
                                <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(<?php echo $participante['id']; ?>, '<?php echo ($cnt + 1); ?>');" class="btn btn-xs btn-info active"> <i class="fa fa-edit"></i></a>
                                <?php
                        }
                        
                        echo ' &nbsp;&nbsp; <b class="btn btn-xs btn-default" onclick="copyToClipboard(\'c'.$participante['celular'].'\')">C</b>';
                        if($_SW_pago_verificado){
                            echo ' &nbsp;&nbsp; <a class="btn btn-xs btn-default" onclick="cvirtual_send_mailto_accesos('.$participante['id'].')"><i class="fa fa-envelope"></i></a>';
                            echo '<br><br>';
                            
                            /* msj hola */
                            $cod_action = 'msj_hola';
                            $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                            $rqdccl2 = fetch($rqdccl1);
                            $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                            echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/wapicons/wap-init-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/></span>';
                            
                            /* msj accesos */
                            $cod_action = 'msj_accesos';
                            $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                            $rqdccl2 = fetch($rqdccl1);
                            $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                            echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/wapicons/wap-llave-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/></span>';
                            
                            /* msj cont */
                            $cod_action = 'msj_cont';
                            $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                            $rqdccl2 = fetch($rqdccl1);
                            $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                            echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/wapicons/wap-hoja-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/></span>';
                            
                            /* msj verif */
                            $cod_action = 'msj_verif';
                            $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                            $rqdccl2 = fetch($rqdccl1);
                            $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                            echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');">[<img src="'. $dominio_www.'contenido/imagenes/wapicons/wap-money-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/>]</span>';

                            /* msj zoom */
                            if($sw_col_szoom){
                                $cod_action = 'msj_zoom';
                                $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                                $rqdccl2 = fetch($rqdccl1);
                                $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                                echo '<br><br>';
                                echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/images/ic_whatssap_zoom-'.$cnt_button.'.png" style="height: 35px;border-radius: 20%;curor:pointer;"/></span>';
                            }

                            /* msj simulador */
                            $rqsim1 = query("SELECT id FROM simulador_accesos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                            if(num_rows($rqsim1)>0){
                                $cod_action = 'msj_simulador';
                                $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                                $rqdccl2 = fetch($rqdccl1);
                                $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                                echo '&nbsp;&nbsp;&nbsp;';
                                echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/images/ic_whatssap_sicoes-'.$cnt_button.'.png" style="height: 35px;border-radius: 20%;curor:pointer;"/></span>';
                            }
                            
                            
                        }else{
                            $txt_wap_formadepago = 'Hola '.$participante['nombres'].' '.$participante['apellidos'].'

Para completar tu registro al curso *'.($nombre_curso).'* es necesario realizar el pago correspondiente.

A continuación te detallamos las formas de pago: 

*CUENTA BANCARIAS*
Banco UNION A nombre de : NEMABOL   cuenta 124033833
BANCO DE CREDITO BCP A nombre de : Evangelina Sardon Tintaya    cuenta 201-50853966-3-23 
BANCO SOL  A nombre de : Evangelina Sardon Tintaya cuenta 1166531-000-001
BANCO NACIONAL BNB   A nombre de : Evangelina Sardon Tintaya cuenta 1501512288
BANCO MERCANTIL SANTA CRUZ A nombre de : Evangelina Sardon Tintaya cuenta 4066860455
BANCO FIE  A nombre de : Evangelina Sardon Tintaya cuenta 40004725631


*TIGO MONEY:* 
A la linea 69714008 el costo sin recargo (Titular Edgar Aliaga)

*TRANSFERENCIA BANCARIA:*
Datos cuenta JURIDICA NEMABOL(Caja de Ahorro, CI 2044323 LP, NIT 2044323014 CIUDAD LA PAZ)
Datos cuenta PERSONA NATURAL EVANGELINA SARDON TINTAYA (Caja de Ahorro, CI 6845644 LP CIUDAD LA PAZ

Luego de hacer el pago tiene que reportar el pago subiendo la imagen del comprobante al siguiente Link:
'.$dominio.'registro-curso-p5c/'.(md5('idr-' . $id_proceso_de_registro)).'/'.$id_proceso_de_registro.'.html
';
                            echo ' &nbsp;&nbsp; <a class="btn btn-xs btn-default" href="mailto:'.$participante['correo'].'?subject='.str_replace('+','%20',urlencode('Registro para '.$nombre_curso)).'&body='.str_replace('+','%20',urlencode(($txt_wap_formadepago))).'"><i class="fa fa-envelope"></i></a>';
                            echo '<br><br>';
                            /* msj hola */
                            $cod_action = 'msj_hola';
                            $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                            $rqdccl2 = fetch($rqdccl1);
                            $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                            echo '<span id="clic_action-msj_hola-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/wapicons/wap-init-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/></span>';
                            
                            /* msj pago */
                            $cod_action = 'msj_pago';
                            $rqdccl1 = query("SELECT count(*) AS total FROM clicaction_log WHERE id_participante='".$participante['id']."' AND cod_action='$cod_action' ");
                            $rqdccl2 = fetch($rqdccl1);
                            $cnt_button = ((int)$rqdccl2['total']>5?5:$rqdccl2['total']);
                            echo '<span id="clic_action-'.$cod_action.'-'.$participante['id'].'" onclick="clic_action_wap('.$participante['id'].',\''.$cod_action.'\');"><img src="'. $dominio_www.'contenido/imagenes/wapicons/wap-money-'.$cnt_button.'.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"/></span>';                            
                        }
                        ?>
                    </td>
                    <td class="nombre-td" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                        <?php
                        echo trim($participante['apellidos']);
                        ?>
                        <br/>
                        <br/>
                        <b style="font-size: 10pt;color: #505050;"><?php echo $participante['ci'] . ' <span style="font-weight:normal">' . $participante['ci_expedido'] . '</span>'; ?></b>
                        <br>
                        <br>
                        <b style="color:orange;"><?php echo strtoupper($participante['dr_nombre_departamento']); ?></b>
                    </td>
                    <?php 
                    if ($mod_notas=='1') { 
                        $rqvne1 = query("SELECT nota FROM participantes_notas_manuales WHERE id_participante='".$participante['id']."' ORDER BY id DESC limit 1 ");
                        if (num_rows($rqvne1) > 0) {
                            $rqvne2 = fetch($rqvne1);
                            $current_nota = $rqvne2['nota'];
                        } else {
                            $current_nota = 0;
                        }
                        ?>
                        <td class="text-center">
                            <br><b class="btn btn-info" style='font-size: 20pt;' onclick="nota_participante('<?php echo $participante['id']; ?>')" id="aux-udp-<?php echo $participante['id']; ?>"><?php echo $current_nota; ?></b>
                        </td>
                        <?php 
                    } 
                    ?>
                    <?php if ($sw_ipelc_curso=='1') { ?>
                    <td class="">
                        <i style="color:gray;font-size: 7pt;"><?php echo strtoupper($participante['dr_nombre_departamento']); ?></i>
                        <br>
                        <?php 
                        $busc_aux51 = array('ci-anverso','ci-reverso','titulo','dep-iplc','fotocarnet');
                        $remm_aux51 = array('C.I. anverso','C.I. reverso','Titulo','Deposito','Foto Carnet');
                        $imgfotocarnet = '';
                        $rqdds1 = query("SELECT nombre,codigo,fecha_upload FROM documentos_usuario WHERE id_usuario='".$participante['id_usuario']."' ");
                        while($rqdds2 = fetch($rqdds1)){
                            echo '<a href="'.$dominio_www.'contenido/imagenes/doc-usuarios/'.$rqdds2['nombre'].'" target="_blank">'.str_replace($busc_aux51,$remm_aux51,$rqdds2['codigo']).'</a> '.($rqdds2['fecha_upload']=='0000-00-00 00:00:00'?'sin fecha':date("d-m-Y",strtotime($rqdds2['fecha_upload']))).'<br>';
                            if($rqdds2['codigo']=='fotocarnet'){
                                if(strpos($rqdds2['nombre'],'.pdf')>0){
                                    $imgfotocarnet = '<br><a href="'.$dominio_www.'contenido/imagenes/doc-usuarios/'.$rqdds2['nombre'].'" target="_blank" style="color: red;text-decoration: underline;font-size: 15pt;font-weight:bold;">Foto en PDF</a>';
                                }else{
                                    $imgfotocarnet = '<br><img src="'.$dominio_www.'contenido/imagenes/doc-usuarios/'.$rqdds2['nombre'].'" style="width: 120px;"/>';
                                }
                            }
                        }
                        
                        $rqddnf1 = query("SELECT nota_nota_final FROM cursos_onlinecourse_notas WHERE id_usuario='".$participante['id_usuario']."' LIMIT 1 ");
                        if(num_rows($rqddnf1)>0){
                            $rqddnf2 = fetch($rqddnf1);
                            echo "<br>Nota final: <b style='font-size: 15pt;color: green;'>".$rqddnf2['nota_nota_final'].'</b>';
                        }
                        
                        echo $imgfotocarnet;
                        ?>
                    </td>
                    <?php } ?>

                    <?php if(!$_SW_es_curso_gratuito){ ?>
                    <td class="simple-td text-center">
                        <?php
                        if ($monto_de_pago !== '' && $participante['id_modo_pago'] != '0' && $participante['sw_pago_enviado']=='1') {
                            echo "<b style='color:#884908;font-size:9pt;'>" . $participante['dr_modo_pago'] . "</b>";
                            echo "<br>";
                            echo "<b style='font-size:12pt;color:#107fc7;'>$monto_de_pago BS</b>";
                            ?>
                            <br/>
                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-default">
                                <i class="fa fa-info"></i> INFO PAGO
                            </a>
                            &nbsp; 
                            <span class="btn btn-xs btn-default" onclick="fecha_hora_pago('<?php echo $participante['id']; ?>');"><i class="fa fa-clock-o"></i> Fecha Hora</span>
                            <div id="ajaxcont-fecha_hora_pago-<?php echo $participante['id']; ?>"></div>
                            <?php
                        } elseif ($sw_habilitacion_procesos) {
                            $enlace_pago = $dominio.'registro-curso-p5c/'.md5('idr-' . $participante['id_proceso_registro']).'/'.$participante['id_proceso_registro'].'.html';
                            $txt_whatsapp = 'Hola ' . ($participante['nombres']) . '__te hacemos el envío del enlace donde debe reportar el pago para el curso__*'.($nombre_curso).'*:__ __Enlace de pago:__'.$enlace_pago;
                            $txt_whatsapp = (str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
                            ?>
                            <b style='color:#e74c3c;'>PAGO NO DEFINIDO</b>
                            <br/>
                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-danger">
                                <i class="fa fa-info"></i> INFO PAGO
                            </a>
                            <?php if(strlen(trim($participante['celular']))==8){ ?>
                            <br/>
                            <a href="https://api.whatsapp.com/send?phone=<?php echo $codigo_pais.trim($participante['celular']); ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                                <img src="https://cdn.iconscout.com/icon/free/png-256/whatsapp-circle-1868968-1583132.png" style="height: 30px;border: 1px solid #ff6868;border-radius: 50%;"/>
                            </a>
                            <?php } ?>
                            <?php
                        }
                        if ($imagen_de_deposito != '') {
                            echo "<br/><img src='".$dominio_www."depositos/$imagen_de_deposito.size=2.img' onclick='window.open(\"".$dominio_www."depositos/$imagen_de_deposito.size=6.img\" , \"ventana1\" , \"width=800,height=800,scrollbars=NO\");' style='max-height: 120px;max-width: 120px;cursor:pointer;'/>";
                        }
                        if ($imagen_matricula != null) {
                            echo " &nbsp; <img src='".$dominio_www."depositos/$imagen_matricula.size=2.img' onclick='window.open(\"".$dominio_www."depositos/$imagen_matricula.size=6.img\" , \"ventana1\" , \"width=800,height=800,scrollbars=NO\");' style='max-height: 120px;max-width: 120px;cursor:pointer;'/>";
                        }
                        if ($id_cobro_khipu != '0') {
                            $rqdckv1 = query("SELECT payment_id FROM khipu_cobros WHERE id='$id_cobro_khipu' AND estado='1' ");
                            if(num_rows($rqdckv1)>0){
                                $rqdckv2 = fetch($rqdckv1);
                                echo "<br/><a href='https://khipu.com/payment/info/".$rqdckv2['payment_id']."' target='_blank' class='btn btn-xs btn-success active small'>Respaldo cobro</span>";
                            }
                        }
                        ?>
                        <?php 
                        if ($sw_askfactura_curso=='1' && ($_SW_pago_verificado || $SW_curso_presencial) && $sw_facturacion_modulos) {
                            echo "<br><br><b>Factura:</b> &nbsp; ";
                            if ($id_emision_factura == '99'){
                                echo "<br><b style='color:red;'>FACTURA DES-HABILITADA</b><br>";
                            } elseif ($id_emision_factura != '0') {
                                $sw_existencia_facturas = true;
                                echo '<i class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">Emitida</i>';
                                if ($participante['correo'] !== '') {
                                    $rqefaux1 = query("SELECT id,nro_factura FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                                    $rqefaux2 = fetch($rqefaux1);
                                    echo '&nbsp;&nbsp;<span id="ffl-' . $rqefaux2['id'] . '"><i class="btn btn-xs btn-default" onclick="enviar_factura2(\'' . $rqefaux2['id'] . '\');"><b class="fa fa-envelope"></b></i></span>';
                                    echo '<input type="hidden" id="correo-de-envio-' . $rqefaux2['id'] . '" value="' . $participante['correo'] . '"/>';
                                }
                                echo '</br>';
                            } else {
                                if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                    echo '<i class="btn btn-xs btn-warning" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">No solicitada</i></br>';
                                } else {
                                    echo '<i class="btn btn-xs btn-danger" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">No emitida</i></br>';
                                }
                            }
                            echo ($nit_de_registro==''?'<br>':$razon_social_de_registro.' / '.$nit_de_registro.'<br><br>');
                        }else{
                            echo '<br><br>';
                        }
                        ?>
                        <?php 
                        /* estado verificacion */
                        if ($_SW_pago_verificado) {
                            ?>
                            <div style="background: #26c526;padding: 5px;margin-bottom: 15px;color: white;"><i class="fa fa-check"></i> VERIFICADO</div>
                            <?php
                        }else {
                            ?>
                            <div id="ajaxcont-verifpago-<?php echo $participante['id']; ?>">
                                <div style="background: #a0a0a0;padding: 5px;margin-bottom: 15px;color: white;cursor:pointer;" onclick="verificar_pago_participante('<?php echo $participante['id']; ?>');"> NO VERIFICADO</div>
                            </div>
                            <?php
                        } 
                        ?>
                    </td>
                    <?php } ?>
                    
                    <?php if ($sw_col_act_suev || $sw_col_material || $sw_col_szoom) { ?>
                        <td class="simple-td">
                            <?php if ($sw_col_szoom && $_SW_pago_verificado) { ?>
                                <b>Sesion ZOOM:</b>
                                <div id="td-envszoom-<?php echo $participante['id']; ?>" style="margin-top:10px;">
                                    <?php
                                    $rqdmev1 = query("SELECT COUNT(*) AS total FROM rel_partszoom WHERE id_participante='".$participante['id']."' ");
                                    $rqdmev2 = fetch($rqdmev1);
                                    if($rqdmev2['total']==0){
                                        ?>
                                        <i class="btn btn-danger btn-xs" style="background: #f36e61;border-color: #dadada;padding: 5px 10px;">Sin invitaci&oacute;n</i>
                                        <?php
                                    }else{
                                        ?>
                                        <b class="text-success" style="background: #2b9ae8;color: #FFF;padding: 5px;">Inv. ZOOM enviados: (<?php echo $rqdmev2['total']; ?>)</b>
                                        <?php
                                    }
                                    ?>
                                    <br>
                                    <br>
                                    <?php if($sw_habilitacion_procesos){ ?>
                                    <b class="btn btn-xs btn-success" onclick="enviar_invitacion_individual_zoom('<?php echo $participante['id']; ?>');">Enviar inv. ZOOM</b>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($sw_col_material && $_SW_pago_verificado) { ?>
                                <hr>
                                <b>Material:</b>
                                <div id="td-envmat-<?php echo $participante['id']; ?>">
                                    <?php
                                    $rqdmev1 = query("SELECT id FROM rel_partmaterialcur WHERE id_curso='$id_curso' AND id_participante='".$participante['id']."' ORDER BY id DESC limit 1 ");
                                    if(num_rows($rqdmev1)==0){
                                        ?>
                                        <i>No enviado</i>
                                        <br>
                                        <b class="btn btn-xs btn-primary" onclick="enviar_material_curso('<?php echo $participante['id']; ?>');">Enviar material</b>
                                        <?php
                                    }else{
                                        ?>
                                        <b class="text-success">Enviado anteriormente</b>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <br>
                            <?php } ?>
                            <?php if ($sw_col_act_suev && $_SW_pago_verificado) { ?>
                                <hr>
                                <b>Confirmaci&oacute;n:</b>
                                <div class="text-left" id="td-suev-<?php echo $participante['id']; ?>">
                                    <?php
                                    $rqdnesuev1 = query("SELECT id FROM rel_partnotifsuev WHERE id_curso='$id_curso' AND id_participante='".$participante['id']."' ORDER BY id DESC limit 1 ");
                                    if(num_rows($rqdnesuev1)==0){
                                        ?>
                                        <b>Sin env&iacute;o</b>
                                        <br>
                                        <b class="btn btn-default btn-xs" onclick="enviar_notificacion_suev('<?php echo $participante['id']; ?>');">Enviar notificaci&oacute;n</b>
                                        <?php
                                    }else{
                                        echo '<b class="text-success">Enviado anteriormente</b>';
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                        </td>
                    <?php } ?>
                    <td class="simple-td">
                        <?php
                        echo date("d / M H:i", strtotime($fecha_de_registro));
                        echo "<br/>";
                        echo "<span style='color:gray;font-size:8pt;'>" . $codigo_de_registro . "</span>";
                        /*
                        if ($participante['ultima_impresion_certificado'] !== '0000-00-00 00:00:00') {
                            echo "<br/>";
                            echo "<span style='color:#AAA;font-size:7pt;'>" . date("d-m-y H:i", strtotime($participante['ultima_impresion_certificado'])) . "</span>";
                        }
                        */
                        echo "<br/>";
                        echo "<b style='color:#444;'>ADMIN</b>";
                        echo "<br/>";
                        if ($participante['id_administrador'] == '0') {
                            echo "<span style='color:gray;'>Sistema</span>";
                        } else {
                            $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $participante['id_administrador'] . "' LIMIT 1 ");
                            $rqadr2 = fetch($rqadr1);
                            echo "<span style='color:gray;font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                        }
                        echo $text_msj;
                        if ($sw_turnos) {
                            echo '<br><br><b>Turno:</b><br>' . $turno[$participante['id_turno']];
                        }

                        /* modalidades multiples */
                        if($sw_modalidades_multiples){
                            echo "<br><br><b>MODALIDADES</b><br>";
                            $rqmdc1 = query("SELECT * FROM rel_participante_modalidades WHERE id_participante='".$participante['id']."' AND estado=1 ");
                            if (num_rows($rqmdc1) > 0) {
                                $rqva2 = fetch($rqmdc1);
                                $sw_mod_presencial = $rqva2['sw_mod_presencial']=='1';
                                $sw_mod_vivo = $rqva2['sw_mod_vivo']=='1';
                                $sw_mod_grabado = $rqva2['sw_mod_grabado']=='1';
                                if($sw_mod_presencial){
                                    echo "A) Curso Presencial <br>";
                                }
                                if($sw_mod_vivo){
                                    echo "B) Curso Virtual por Zoom en vivo <br>";
                                }
                                if($sw_mod_grabado){
                                    echo "C) Curso Virtual Grabado <br>";
                                }
                            }else{
                                echo "Sin seleccion<br>";
                            }
                        }
                        ?>
                    </td>
                    <td class="simple-td">
                        <div id="TD-cvir-<?php echo $participante['id']; ?>">
                            <?php
                            $sw_show_certificados = true;
                            if ($sw_curso_virtual && $_SW_pago_verificado) {
                                if ($cnt_cursos_cirtuales_asociados == 1) {


                                    $rqvac1 = query("SELECT id FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' AND id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso') AND estado=1 ORDER BY id DESC limit 1 ");
                                    //if ($participante['sw_cvirtual'] == 1) {
                                    if (num_rows($rqvac1)>0) {
                                            ?>
                                            <div style="color:green;background: #e3efd5;padding: 7px;text-align: center;border: 1px solid #9cbf73;">HABILITADO</div>
                                            <?php if ($sw_habilitacion_procesos) { ?>
                                            <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                                                Des-habilitar: <b class="btn btn-danger btn-xs" data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_cvirtual_p1(<?php echo $participante['id']; ?>);">X</b>
                                            </div>
                                            <?php } ?>
                                            <b class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#MODAL-avance-cvirtual" onclick="avance_cvirtual(<?php echo $participante['id']; ?>);">PANEL C-vir</b>
                                            <?php
                                            $rqdacvap1 = query("SELECT fecha_activacion,id_administrador_activacion FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' ORDER BY id DESC limit 1 ");
                                            $rqdacvap2 = fetch($rqdacvap1);
                                            $rqdpadm1 = query("SELECT nombre FROM administradores WHERE id='".$rqdacvap2['id_administrador_activacion']."' LIMIT 1 ");
                                            $rqdpadm2 = fetch($rqdpadm1);
                                            echo "<div class='text-center' style='margin: 15px 0px;background: #f1f1f1;'>";
                                            if($rqdacvap2['fecha_activacion']!='0000-00-00 00:00:00'){
                                                echo "<b>".$rqdpadm2['nombre']."</b>";
                                                echo "<br>";
                                                echo "<i>".date("d/M H:i",strtotime($rqdacvap2['fecha_activacion']))."</i>";
                                            }else{
                                                echo "...";
                                            }
                                            echo "</div>";
                                            ?>
                                        <?php
                                    } else {
                                        $sw_show_certificados = false;
                                        ?>
                                            <div style="color:#FFF;background: #ef8a80;padding: 7px;text-align: center;border: 1px solid #e74c3c;">NO HABILITADO</div>
                                            <?php if ($sw_habilitacion_procesos) { ?>
                                            <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                                                Haabilitar: &nbsp; <b class="btn btn-success btn-xs" onclick="habilita_participante_cvirtual_p1(<?php echo $participante['id']; ?>);"><i class="fa fa-check"></i></b>
                                            </div>
                                            <?php } ?>
                                            <b class="btn btn-default btn-xs btn-block" data-toggle="modal" data-target="#MODAL-avance-cvirtual" onclick="avance_cvirtual(<?php echo $participante['id']; ?>);">PANEL C-vir</b>
                                        <?php
                                    }
                                } else {
                                    $rqdcntcva1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' AND id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso') and estado='1' ");
                                    $rqdcntcva2 = fetch($rqdcntcva1);
                                    $aux_cnt_asignados = $rqdcntcva2['total'];
                                    ?>
                                        <b class="btn btn-xs btn-block btn-default" onclick="$('#AJAXCONTENT-acceso_cursos_virtuales').html('Cargando...');acceso_cursos_virtuales('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-acceso_cursos_virtuales">
                                            Cursos-VIRTUALES
                                        </b>
                                        <?php 
                                        for($i=1;$i<=$cnt_cursos_cirtuales_asociados;$i++){
                                            if($i<=$aux_cnt_asignados){
                                                echo "<div style='background: #73ab2c;margin-right: 2px;margin-top: 2px;float: left;width: 20px;height: 5px;border-radius: 5px;'></div>";
                                            }else{
                                                echo "<div style='background: #cecece;margin-right: 2px;margin-top: 2px;float: left;width: 20px;height: 5px;border-radius: 5px;'></div>";
                                            }
                                        }
                                        ?>
                                        <br>
                                        <?php
                                        if($aux_cnt_asignados>0){
                                            $rqdacvap1 = query("SELECT fecha_activacion,id_administrador_activacion FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' ORDER BY id DESC limit 1 ");
                                            $rqdacvap2 = fetch($rqdacvap1);
                                            $rqdpadm1 = query("SELECT nombre FROM administradores WHERE id='".$rqdacvap2['id_administrador_activacion']."' LIMIT 1 ");
                                            $rqdpadm2 = fetch($rqdpadm1);
                                            echo "<div class='text-center' style='margin: 15px 0px;background: #f1f1f1;'>";
                                            if($rqdacvap2['fecha_activacion']!='0000-00-00 00:00:00'){
                                                echo "<b>".$rqdpadm2['nombre']."</b>";
                                                echo "<br>";
                                                echo "<i>".date("d/M H:i",strtotime($rqdacvap2['fecha_activacion']))."</i>";
                                            }else{
                                                echo "...";
                                            }
                                            echo "</div>";
                                        }
                                        ?>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <br>
                        <?php 
                        if(($sw_show_certificados && $_SW_pago_verificado) || $SW_curso_presencial){
                            $cont_enlaces_descarga = '*DESCARGA DE CERTIFICADOS: '.trim($participante['nombres'] . ' ' . $participante['apellidos']).'*<br><br>';
                            $rqmcemcrt1 = query("SELECT certificado_id,texto_qr FROM cursos_emisiones_certificados WHERE id_participante='".$participante['id']."' AND id_curso='$id_curso' ");
                            $num_emisiones = num_rows($rqmcemcrt1);
                            while ($rqmcemcrt2 = fetch($rqmcemcrt1)) {
                                $certificado_id = $rqmcemcrt2['certificado_id'];
                                $texto_qr = $rqmcemcrt2['texto_qr'];
                                $cont_enlaces_descarga .= '===========================<br>*'.$certificado_id.':* '.$dominio.'C/'.$certificado_id.'/<br>'.$texto_qr.'<br><br>';
                            }
                            ?>
                            <div id="cont-enlaces-descarga-cert-<?php echo $participante['id']; ?>" style="display: none;"><?php echo $cont_enlaces_descarga; ?></div>

                            <b onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 0);" class="btn btn-xs btn-warning btn-block">CERTIFICADOS</b>
                            <div id="AJAXCONTENT-enviar_emitidos_por_correo_fromlist-<?php echo $participante['id']; ?>">
                                <span style="font-size: 8pt;">[Emisiones: <b><?php echo $num_emisiones; ?></b> ]</span>&nbsp;
                                <span style="font-size: 8pt;">[Impresiones: <b><?php echo $participante['cnt_impresion_certificados']; ?></b> ]</span>
                            </div>
                            <?php if($num_emisiones>0){ ?>
                            <br>
                            <b class="btn btn-success btn-xs" onclick="enviar_emitidos_por_correo_fromlist(<?php echo $participante['id']; ?>);"><i class="fa fa-send"></i> Enviar por correo</b>&nbsp;
                            Descarga: <b class="btn btn-default" onclick="copyToClipboard('cont-enlaces-descarga-cert-<?php echo $participante['id']; ?>');"><i class="fa fa-copy"></i></b> &nbsp;&nbsp;&nbsp; <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 35px;border-radius: 20%;cursor: pointer;border: 1px solid #d4d4d4;" onclick="enviar_enlaces_por_wap_fromlist(<?php echo $participante['id']; ?>,'<?php echo $participante['celular']; ?>');">
                            <div id="AJAXCONTENT-aux_ajax_message_1-<?php echo $participante['id']; ?>"></div>
                            <?php } ?>

                            <?php 
                            if(false){
                                $rqddcdenv1 = query("SELECT direccion FROM direnvio_certs_datapart WHERE id_participante='".$participante['id']."' ORDER BY id DESC limit 1 ");
                                if(num_rows($rqddcdenv1)>0){ 
                                    $rqddcdenv2 = fetch($rqddcdenv1);
                                    ?>
                                    <br>
                                    <br>
                                    <b>ENVIO FISICO DE CERTIFICADO SOLICITADO</b>
                                    <br>
                                    <?php echo str_replace(', Persona','<br>Persona',$rqddcdenv2['direccion']); ?>
                                    <?php 
                                } 
                            }
                            ?>


                            <?php
                        }
                        ?>
                    </td>
                    <td class="simple-td">
                        <?php
                        if ($sw_habilitacion_procesos) {
                            ?>
                            <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(<?php echo $participante['id']; ?>, '<?php echo ($cnt + 1); ?>');" class="btn btn-xs btn-default btn-block" style="color: #2da210;"> <i class="fa fa-edit"></i> Editar</a>
                            <?php
                        }
                        ?>
                        <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-default btn-block" style="color: #2da210;"><i class="fa fa-eye"></i> Ficha</a>
                        <?php
                        if($participante['id_usuario']!='0'){
                            ?>
                            <span class="btn btn-xs btn-default btn-block" onclick="usuario_data('<?php echo $participante['id_usuario']; ?>');" data-toggle="modal" data-target="#MODAL-usuario_data" style="color: #2da210;"><i class="fa fa-user"></i> Usuario</span>
                            <?php
                        }
                        ?>
                        <span class="btn btn-xs btn-block btn-default" onclick="coincidencias_registro('<?php echo $participante['id']; ?>');" style="color: #2da210;">Coincidencias</span>
                        <?php
                        if ($sw_habilitacion_procesos && (!$_SW_pago_verificado) || ($sw_habilitacion_procesos && ($id_administrador=='10' || $id_administrador=='11'))) {
                            ?>
                            <br>
                            <span id="ajaxbox-button-eliminar-participante-<?php echo $participante['id']; ?>">
                                <a data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_p1(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-danger btn-block"><i class="fa fa-trash-o"></i> Eliminar</a>
                            </span>
                            <?php
                        }
                        ?>
                        <?php if ($sw_curso_gratuito_asociado) { ?>
                            <br>
                            <br>
                            <b>Curso gratuito:</b>
                            <div id="td-aux-us-<?php echo $participante['id']; ?>">
                                <?php 
                                if($_SW_pago_verificado){
                                    $rqvcgau1 = query("SELECT estado FROM cursos_rel_usuariocurfreecur WHERE id_participante='".$participante['id']."' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                                    if(num_rows($rqvcgau1)==0){
                                        ?>
                                        <b class="btn btn-block btn-xs btn-default active" onclick="info_curso_gratuito('<?php echo $participante['id']; ?>');" style="color: #2da210;"><i class="fa fa-flag-o"></i> No solicitado</b>
                                        <?php
                                    }else{
                                        $rqvcgau2 = fetch($rqvcgau1);
                                        if($rqvcgau2['estado']=='0'){
                                            ?>
                                            <b class="btn btn-block btn-xs btn-default" onclick="info_curso_gratuito('<?php echo $participante['id']; ?>');" style="color: #2da210;"><i class="fa fa-flag-o"></i> Sin asignaci&oacute;n</b>
                                            <?php
                                        }else{
                                            ?>
                                            <b class="btn btn-block btn-xs btn-success active" onclick="info_curso_gratuito('<?php echo $participante['id']; ?>');"><i class="fa fa-check"></i> Asignado</b>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                        <?php if ($sw_cupon_infosicoes && ($_SW_pago_verificado || $SW_curso_presencial)) { ?>
                            <br>
                            <br>
                            <b>Cup&oacute;n_Infosicoes:</b>
                            <?php
                                $rqve1 = query("SELECT id FROM cursos_emisiones_cupones_infosicoes WHERE id_participante='" . $participante['id'] . "' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                                if (num_rows($rqve1)==0) {
                                    ?>
                                    <b class="btn btn-block btn-xs btn-default" onclick="cupon_infosicoes('<?php echo $participante['id']; ?>');" style="color: #2da210;"><i class="fa fa-flag-o"></i> Sin cup&oacute;n</b>
                                    <?php
                                    }else{
                                    ?>
                                    <b class="btn btn-block btn-xs btn-success active" onclick="cupon_infosicoes('<?php echo $participante['id']; ?>');"><i class="fa fa-check"></i> Con cup&oacute;n</b>
                                    <?php
                                }
                            ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<hr>
<b>Correos agrupados en 10</b>
<br>
<br>
<?php echo $aux_line_correos.'</span>'; ?>
<hr>

<?php
if ($sw_habilitacion_procesos) {
    ?>
    <hr/>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">EMISION / IMPRESION MULTIPLE DE CERTIFICADOS</div>
                <div class="panel-body">
                    <?php
                    /* certificados adicionales */
                    $rqmc1 = query("SELECT * FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC ");
                    if (num_rows($rqmc1) > 0) {
                        ?>
                        <hr/>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CERTIFICADOS GENERALES</h4>
                        <table class="table table-bordered">
                            <?php
                            $cnt = 0;
                            while ($rqmc2 = fetch($rqmc1)) {
                                $rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id='" . $rqmc2['id_certificado'] . "' LIMIT 1 ");
                                $rqdcrt2 = fetch($rqdcrt1);
                                ?>
                                <tr>
                                    <td class="visible-lg">
                                        <?php
                                        echo "<b>" . $rqdcrt2['codigo'] . "</b>";
                                        echo '<div style="background: #f8f8f8;border: 1px solid gainsboro;padding: 10px 5px;border-radius: 10px;text-align: center;font-size: 10pt;margin: 10px 0px;;">'.$rqdcrt2['texto_qr'].'</div>';
                                        echo '<b>Fecha 1:</b><i>(Desde)</i> &nbsp;&nbsp; '.date("d-m-Y",strtotime($rqdcrt2['fecha_qr']));
                                        echo "<br/>";
                                        echo '<b>Fecha 2:</b><i>(Hasta)</i> &nbsp;&nbsp;&nbsp; '.date("d-m-Y",strtotime($rqdcrt2['fecha2_qr']));
                                        ?> 
                                    </td>
                                    <td class="visible-lg" style="width:120px;">
                                        <?php if(acceso_cod('adm-cf-masivo')){ ?>
                                        <a class="btn btn-xs btn-primary btn-block" onclick="imprimir_certificados_multiple('<?php echo $rqdcrt2['id']; ?>');"> <i class="fa fa-print"></i> Impresos</a> 
                                        <br/>
                                        <?php } ?>
                                        <a class="btn btn-xs btn-primary btn-block" onclick="imprimir_certificados_multiple_digitales('<?php echo $rqdcrt2['id']; ?>');"> <i class="fa fa-print"></i> Digitales</a> 
                                        <br/>
                                        <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emite_certificados_multiple('<?php echo $rqdcrt2['id']; ?>', '<?php echo $id_curso; ?>', '0');" class="btn btn-xs btn-primary btn-block"> <i class="fa fa-send"></i> Emitir</a>
                                        <br/>
                                        <a onclick="envia_certificados_multiple('<?php echo $rqdcrt2['id']; ?>');" class="btn btn-xs btn-primary btn-block"> <i class="fa fa-envelope"></i> Enviar</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php if(acceso_cod('adm-cf-masivo')){ ?>
                        <hr>
                        <a onclick="envio_todos_los_certificados('<?php echo $id_curso; ?>');" class="btn btn-lg btn-primary btn-block"> <i class="fa fa-send"></i> ENVIO DE TODOS LOS CERTIFICADOS</a>
                        <br>
                        <a class="btn btn-lg btn-default btn-block" onclick="imprimir_certificados_multiple('1234');"> <i class="fa fa-print"></i> Imprimir todos por numeraci&oacute;n </a> 
                        <br>
                        <a class="btn btn-lg btn-default btn-block" onclick="imprimir_certificados_multiple('abcd');"> <i class="fa fa-print"></i> Imprimir todos alfabeticamente </a> 
                        <?php } ?>
                        <?php
                    }
                    ?>

                    <?php
                    /* certificados de culminacion */
                    $rqmcc1 = query("SELECT id FROM certificados_culminacion WHERE id_curso='$id_curso' ORDER BY id DESC ");
                    if (num_rows($rqmcc1) > 0) {
                        $rqmcc2 = fetch($rqmcc1);
                        ?>
                        <hr/>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CERTIFICADO CULMINACI&Oacute;N</h4>
                        <table class="table table-bordered">
                            <tr>
                                <td class="visible-lg">
                                    CERTIFICADO CULMINACI&Oacute;N
                                    <br>
                                    IPELC - CERT-C-<?php echo $rqmcc2['id']; ?>
                                </td>
                                <td class="visible-lg" style="width:120px;">
                                    <a class="btn btn-xs btn-primary btn-block" onclick="imprimir_certificados_culminacion_multiple('<?php echo $rqmcc2['id']; ?>');"> <i class="fa fa-EYE"></i> Digitales</a> 
                                    <br/>
                                    <a onclick="emite_certificados_culminacion_multiple('<?php echo $rqmcc2['id']; ?>');" class="btn btn-xs btn-primary btn-block"> <i class="fa fa-send"></i> Emitir</a>
                                    <br/>
                                    <a onclick="envia_certificados_culminacion_multiple('<?php echo $rqmcc2['id']; ?>');" class="btn btn-xs btn-primary btn-block"> <i class="fa fa-envelope"></i> Enviar</a>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <?php
                    }








                    if ($id_certificado_curso !== '0') {
                        $rqdcca1 = query("SELECT texto_qr,cont_tres FROM cursos_certificados WHERE id='$id_certificado_curso' ORDER BY id DESC LIMIT 1 ");
                        $rqdcca2 = fetch($rqdcca1);
                        $texto_qr_certificado = $rqdcca2['texto_qr'];
                        $cont_tres_certificado = $rqdcca2['cont_tres'];
                        ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>PRIMER CERTIFICADO</h4>
                        <h3 style='color: #000;font-weight: bold;text-align: center;'><?php echo $texto_qr_certificado; ?></h3>
                        <b style='color: #555;'><?php echo $cont_tres_certificado; ?></b>
                        <br/>
                        <br/>
                        <?php
                        if ($sw_exist_cert_uno) {
                            ?>
                            <input type="checkbox" checked="" disabled=""/>
                            &nbsp;&nbsp;&nbsp; 
                            <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('1');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                            <br/>
                            <br/>
                            <?php
                        }
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emite_certificados_multiple('<?php echo $id_certificado_curso; ?>', '<?php echo $id_curso; ?>', '1');" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                        <?php
                    }
                    ?>
                    <br/>
                    <br/>
                    <?php
                    if ($id_certificado_2_curso !== '0' && $sw_habilitacion_procesos) {
                        $rqdcca1 = query("SELECT texto_qr,cont_tres FROM cursos_certificados WHERE id='$id_certificado_2_curso' ORDER BY id DESC LIMIT 1 ");
                        $rqdcca2 = fetch($rqdcca1);
                        $texto_qr_certificado = $rqdcca2['texto_qr'];
                        $cont_tres_certificado = $rqdcca2['cont_tres'];
                        ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>SEGUNDO CERTIFICADO</h4>
                        <h3 style='color: #000;font-weight: bold;text-align: center;'><?php echo $texto_qr_certificado; ?></h3>
                        <b style='color: #555;'><?php echo $cont_tres_certificado; ?></b>
                        <br/>
                        <br/>
                        <?php
                        if ($sw_exist_cert_dos) {
                            ?>
                            <input type="checkbox" checked="" disabled=""/>
                            &nbsp;&nbsp;&nbsp; 
                            <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('2');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                            <br/>
                            <br/>
                            <?php
                        }
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emite_certificados_multiple('<?php echo $id_certificado_2_curso; ?>', '<?php echo $id_curso; ?>', '2');" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                        <br/>
                        <br/>
                        <?php
                    }
                    ?>
                    <br/>
                    <br/>
                    <?php
                    if ($id_certificado_3_curso !== '0' && $sw_habilitacion_procesos) {
                        $rqdcca1 = query("SELECT texto_qr,cont_tres FROM cursos_certificados WHERE id='$id_certificado_3_curso' ORDER BY id DESC LIMIT 1 ");
                        $rqdcca2 = fetch($rqdcca1);
                        $texto_qr_certificado = $rqdcca2['texto_qr'];
                        $cont_tres_certificado = $rqdcca2['cont_tres'];
                        ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>TERCER CERTIFICADO</h4>
                        <h3 style='color: #000;font-weight: bold;text-align: center;'><?php echo $texto_qr_certificado; ?></h3>
                        <b style='color: #555;'><?php echo $cont_tres_certificado; ?></b>
                        <br/>
                        <br/>
                        <?php
                        if ($sw_exist_cert_tres) {
                            ?>
                            <input type="checkbox" checked="" disabled=""/>
                            &nbsp;&nbsp;&nbsp; 
                            <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('3');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                            <br/>
                            <br/>
                            <?php
                        }
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emite_certificados_multiple('<?php echo $id_certificado_3_curso; ?>', '<?php echo $id_curso; ?>', '3');" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                        <br/>
                        <br/>
                        <?php
                    }
                    ?>
                    <br/>
                    <br/>
                    <?php
                    if ($id_certificado_1_curso !== '0' && $id_certificado_2_curso !== '0' && $sw_habilitacion_procesos) {
                        $rqdcca1 = query("SELECT texto_qr FROM cursos_certificados WHERE id='$id_certificado_3_curso' ORDER BY id DESC LIMIT 1 ");
                        $rqdcca2 = fetch($rqdcca1);
                        $texto_qr_certificado = $rqdcca2['texto_qr'];
                        ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CERTIFICADOS EN CONJUNTO</h4>
                        <h3 style='color: #000;font-weight: bold;text-align: center;'>Todos los certificados emitidos</h3>
                        <div class="text-center">
                            <br/>
                            <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('1234');"> <i class="fa fa-print"></i> Imprimir por numeraci&oacute;n </a> 
                            <br/>
                            <br/>
                            <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('abcd');"> <i class="fa fa-print"></i> Imprimir por alfabeticamente </a> 
                            <br/>
                            <br/>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">FACTURAS / CURSOS VIRTUALES</div>
                <div class="panel-body">
                    <?php if($sw_facturacion_modulos){ ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>FACTURAS</h4>
                        <br/>
                        <?php
                        if ($sw_existencia_facturas) {
                            ?>
                            <input type="checkbox" checked="" disabled=""/>
                            &nbsp;&nbsp;&nbsp; 
                            <a class="btn btn-xs btn-default" onclick="imprimir_facturas();"> <i class="fa fa-print"></i> Imprimir facturas </a> 
                            <br/>
                            <br/>
                            <?php
                        }
                        ?>
                        <input type="checkbox" checked="" disabled=""/>
                        &nbsp;&nbsp;&nbsp; 
                        <a data-toggle="modal" data-target="#MODAL-emite-facturas-multiple" onclick="emision_multiple_facturas();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir facturas </a>
                        <br/>
                        <br/>
                    <?php } ?>
                    <br>
                    <?php if($sw_curso_virtual){ ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CURSOS VIRTUALES</h4>
                        <br/>
                        <b onclick="activacion_multiple_cvirtuales();" class="btn btn-md btn-default"> <i class="fa fa-group"></i> Activaci&oacute;n multiple </b>
                        <br/>
                        <br/>
                    <?php } ?>
                    <br>
                    <?php if($sw_col_szoom){ ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>SESIONES ZOOM</h4>
                        <br/>
                        <b onclick="envio_zoom_multiple();" class="btn btn-md btn-default"> <i class="fa fa-group"></i> Env&iacute;o multiple de invitaci&oacute;n</b>
                        <br/>
                        <br/>
                    <?php } ?>
                    <br>
                    <?php if(true){ ?>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CORREO DE ASISTENCIA</h4>
                        <br/>
                        <b onclick="enviar_link_asistencia();" class="btn btn-md btn-default"> <i class="fa fa-group"></i> Env&iacute;o multiple de link de asistencia</b>
                        <br/>
                        <br/>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">PROCESOS PARTICULARES</div>
                <div class="panel-body">
                    <!--                    <button class="btn btn-info" onclick="procesa_checked_participantes();">MOSTRAR SELECCIONADOS</button>-->
                    <!--                    <hr/>-->
                    <i>Deshabilitaci&oacute;n a los participantes no seleccionados.</i>
                    <br/>
                    <br/>
                    <button class="btn btn-danger active" onclick="deshabilita_participantes_no_seleccionados();" data-toggle="modal" data-target="#MODAL-deshabilita_participantes_no_seleccionados">
                        DESHABILITAR PARTICIPANTES
                    </button>

                    <?php
                    $rqecd1 = query("SELECT id FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' ");
                    if (num_rows($rqecd1) > 0) {
                        $link_generacion_cupon = $dominio.'aut-generar-cupon/'.$id_curso.'/'.md5(md5('autgencupon1011' . $id_curso)).'.html';
                        ?>
                        <hr/>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CUPONES INFOSICOES</h4>
                        <b>ADMINISTRACI&Oacute;N DE CUPONES</b>
                        <br/>
                        <i>Generado de cupones Infosicoes para participantes de este curso.</i>
                        <br/>
                        <br/>
                        <a data-toggle="modal" data-target="#MODAL-emite-cupones-descuento" onclick="emision_cupones_infosicoes();" class="btn btn-info"> CUPONES INFOSICOES</a>
                        <br/>
                        <br/>
                        <b>Link de generaci&oacute;n de cup&oacute;n:</b>
                        <br/>
                        <a href="<?php echo $link_generacion_cupon; ?>" target="_blank"><?php echo $link_generacion_cupon; ?></a>
                        <?php
                    }
                    ?>

                            <?php
                            /* MATERIAL DIGITAL */
                            $rqmddc1 = query("SELECT * FROM materiales_curso WHERE id_curso='$id_curso' AND estado='1' ");
                            if (num_rows($rqmddc1) > 0) {
                                ?>
                                <br/>
                                <hr/>
                                <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>MATERIAL DE CURSO</h4>
                                <br>
                                <i>Se muestra el listado de archivos del material asignado.</i>
                                <br/>
                                <table class="table table-striped table-bordered">
                                    <?php
                                    $cnt = 1;
                                    while ($rqda2 = fetch($rqmddc1)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $cnt++; ?>
                                            </td>
                                            <td>
                                                <?php echo $rqda2['nombre']; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo $dominio_www; ?>contenido/archivos/material/<?php echo $rqda2['nombre_fisico']; ?>" target="_blank">VISUALIZAR</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                                <br/>
                                <span id="AJAXCONTENT-enviar_material_digital">
<!--                                    <a onclick="enviar_material_digital();" class="btn btn-primary"> ENVIAR POR CORREO</a>-->
                                        <a onclick="envio_todos_los_materiales('<?php echo $id_curso; ?>');" class="btn btn-primary"> <i class="fa fa-envelope"></i> ENVIAR TODOS LOS MATERIALES</a>
                                </span>
                                <?php
                            }
                            ?>

                    <hr>
                    <b class="btn btn-warning btn-block btn-sm" onclick="mensaje_usuarios_multiple();" data-toggle="modal" data-target="#MODAL-mensaje_usuarios_multiple"><i class="fa fa-envelope"></i> ENVIAR MENSAJE A USUARIOS</b>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="documentos_de_usuarios();" data-toggle="modal" data-target="#MODAL-documentos_de_usuarios"><i class="fa fa-file"></i> DOCUMENTOS DE USUARIOS</b>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="aux_recibo_documentos();"><i class="fa fa-file"></i> Recibos de documentacion</b>
                    <?php if($sw_ipelc_curso=='1') {?>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="aux_remitentes_ipelc();"><i class="fa fa-file"></i> Hoja remitente</b>
                    <?php } ?>
                    <?php if($sw_ipelc_curso=='1') {?>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="confirmacion_correo_departamento();"><i class="fa fa-envelope"></i> Enviar correo confirmaci&oacute;n departamento</b>
                    <?php } ?>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="enviar_accesos();"><i class="fa fa-user"></i> ENVIAR ACCESOS</b>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="enviar_invitacion_zoom();"><i class="fa fa-envelope"></i> Enviar invitaci&oacute;n ZOOM</b>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="trasladar_participantes();"><i class="fa fa-send"></i> TRASLADAR participantes <br> (se remueven de este curso)</b>
                    <hr>
                    <b class="btn btn-default btn-block btn-sm" onclick="copiar_participantes();"><i class="fa fa-send"></i> COPIAR participantes <br> (permanecen en este curso)</b>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
    
<!-- enviar_link_asistencia -->
<script>
    function enviar_link_asistencia() {
        $("#TITLE-modgeneral").html('ENVIO DE LINK DE ASISTENCIA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_link_asistencia.php',
                data: {id_curso: '<?php echo $id_curso; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- clic_action_wap -->
<script>
    function clic_action_wap(id_participante,cod_action) {
        $("#clic_action-"+cod_action+"-"+id_participante).html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/loading.gif" style="height: 25px;border-radius: 20%;cursor:no-drop;padding:0px 4px;"/>');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.clic_action_wap.php',
                data: {id_participante: id_participante, cod_action: cod_action},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    var obj = JSON.parse(data);
                    if(obj.estado==='1'){
                        window.open(obj.url, '_blank');
                        $("#clic_action-"+cod_action+"-"+id_participante).html(obj.htm);
                    }else if(obj.estado==='2'){
                        window.open(obj.url, '_blank');
                    }else{
                        alert('Error');
                    }
                }
        });
    }
</script>
    
<!-- coincidencias de registro -->
<script>
    function coincidencias_registro(id_participante) {
        $("#TITLE-modgeneral").html('COINCIDENCIAS DE REGISTRO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.coincidencias_registro.php',
                data: {id_participante: id_participante},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- enviar_accesos -->
<script>
    function enviar_accesos() {
        $("#TITLE-modgeneral").html('ENVIO DE ACCESOS');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_accesos.php',
                data: {id_curso: '<?php echo $id_curso; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- enviar_invitacion_zoom -->
<script>
    function enviar_invitacion_zoom() {
        $("#TITLE-modgeneral").html('ENVIO DE INVITACI&Oacute;N ZOOM');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_invitacion_zoom.php',
                data: {id_curso: '<?php echo $id_curso; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

    
<!-- ajax aux_recibo_documentos -->
<script>
    function aux_recibo_documentos() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/recibos-documentos.php?ids_participante='+ids.join(','), 'popup', 'width=700,height=500');
    }
</script>

<!-- ajax aux_remitentes_ipelc -->
<script>
    function aux_remitentes_ipelc() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/remitentes-ipelc.php?ids_participante='+ids.join(','), 'popup', 'width=700,height=500');
    }
</script>


<script>
    var array_check_participante = new Object();
    var aux_idsalmacenador = "<?php echo $aux_idsalmacenador; ?>";
</script>

<script>
    function reporte_cierre_p1() {
        $("#AJAXBOX-reporte_cierre").html(text__loading_uno);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reporte_cierre_p1.php',
            data: {id_curso: '<?php echo $id_curso; ?>', id_turno: '<?php echo $id_turno_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-reporte_cierre").html(data);
            }
        });
    }
    function reporte_cierre_p2(dat) {
        var data_form = $("#FORM-reporte_cierre").serialize();
        $("#AJAXBOX-reporte_cierre").html(text__loading_uno);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reporte_cierre_p2.php?dat=' + dat,
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-reporte_cierre").html(data);
            }
        });
    }
</script>


<script>
    function enviar_material_digital() {
        if (confirm("CONFIRMACION DE ENVIO DE MATERIALES DIGITALES VIA CORREO ELECTRONICO")) {
            $("#AJAXCONTENT-enviar_material_digital").html('Enviando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_material_digital.php',
                data: {id_curso: '<?php echo $id_curso; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-enviar_material_digital").html(data);
                }
            });
        }
    }
</script>


<!--asignar_curso_gratuito-->
<script>
    function asignar_curso_gratuito(id_participante) {
        if (confirm("DESEA ASIGNAR CURSO GRATUITO ?")) {
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.asignar_curso_gratuito.php',
                data: {id_curso: '<?php echo $id_curso; ?>', id_participante: id_participante},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#td-aux-us-"+id_participante).html(data);
                    alert('ASIGNACION EXITOSA');
                }
            });
        }
    }
</script>

<!--enviar_material_curso-->
<script>
    function enviar_material_curso(id_participante) {
        if (confirm("DESEA ENVIAR EL MATERIAL Y LA BIENVENIDA AL CURSO ?")) {
            $("#td-envmat-"+id_participante).html('Enviando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_material_curso.php',
                data: {id_curso: '<?php echo $id_curso; ?>', id_participante: id_participante},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#td-envmat-"+id_participante).html(data);
                }
            });
        }
    }
</script>

<!--enviar_notificacion_suev-->
<script>
    function enviar_notificacion_suev(id_participante) {
        if (confirm("DESEA ENVIAR NOTIFICACION DE ACTIVACION ?")) {
            $("#td-suev-"+id_participante).html('Enviando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_notificacion_suev.php',
                data: {id_curso: '<?php echo $id_curso; ?>', id_participante: id_participante},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#td-suev-"+id_participante).html(data);
                }
            });
        }
    }
</script>


<script>
    function cvirtual_send_mailto_accesos(id_participante) {     
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cvirtual_send_mailto_accesos.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                window.location.href = "mailto:"+data;
            }
        });
    }
</script>

<script>
    function copyToClipboard(phoneid) {
        var container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        container.innerHTML = document.getElementById(phoneid).innerHTML;
        /*
        var activeSheets = Array.prototype.slice.call(document.styleSheets).filter(function(sheet) {
            return !sheet.disabled;
        });
        */
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        /*
        for (var i = 0; i < activeSheets.length; i++)
            activeSheets[i].disabled = true;
        document.execCommand('copy');
        for (var i = 0; i < activeSheets.length; i++)
            activeSheets[i].disabled = false;
        */
        document.body.removeChild(container);
    }
</script>




<!-- Modal usuario_data -->
<div id="MODAL-usuario_data" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">DATOS DE USUARIO</h4>
      </div>
      <div class="modal-body">
          <div id="AJAXCONTENT-usuario_data"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!--usuario_data-->
<script>
    function usuario_data(id_usuario) {
        $("#AJAXCONTENT-usuario_data").html('Cargando...');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.usuario_data.php',
                data: {id_usuario: id_usuario, id_curso: '<?php echo $id_curso; ?>' },
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-usuario_data").html(data);
                }
        });
    }
</script>

<!-- envia_certificados_multiple -->
<script>
    function envia_certificados_multiple(id_certificado) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ENVIAR CERTIFICADOS POR CORREO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.envia_certificados_multiple.php',
                data: {id_certificado: id_certificado, dat: ids.join(',')},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- envia_certificados_culminacion_multiple -->
<script>
    function envia_certificados_culminacion_multiple(id_certificado) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ENVIAR CERTIFICADOS POR CORREO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.envia_certificados_culminacion_multiple.php',
                data: {id_certificado: id_certificado, dat: ids.join(',')},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- envio_todos_los_certificados -->
<script>
    function envio_todos_los_certificados(id_curso) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ENVIO DE TODOS LOS CERTIFICADOS');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.envio_todos_los_certificados_p1.php',
                data: {id_curso: id_curso, dat: ids.join(',')},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- envio_todos_los_materiales -->
<script>
    function envio_todos_los_materiales(id_curso) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ENVIO DE TODOS LOS MATERIALES');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.envio_todos_los_materiales.php',
                data: {id_curso: id_curso, dat: ids.join(',')},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- INFO CURSO GRATUTITO -->
<script>
    function info_curso_gratuito(id_participante) {
        $("#TITLE-modgeneral").html('INFO CURSO GRATUITO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.info_curso_gratuito.php',
                data: {id_participante: id_participante},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- nota_participante -->
<script>
    function nota_participante(id_participante) {
        $("#TITLE-modgeneral").html('NOTA DE PARTICIPANTE');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.nota_participante.php',
                data: {id_participante: id_participante},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>


<style>
    th{
        cursor: pointer;
    }
    th:hover{
        background: #dffbf3;
    }
</style>
<script>
$('th').click(function(){
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
})
function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
</script>

<script>
function invertir_nombre_apellido(){
    var total_nomap = parseInt('<?php echo $cnt_nomap; ?>');
    for(var i=1; i<=total_nomap; i++){
        var cont_a = $("#box-nomap-a-"+i).html();
        var cont_b = $("#box-nomap-b-"+i).html();
        $("#box-nomap-a-"+i).html(cont_b);
        $("#box-nomap-b-"+i).html(cont_a);
    }
}
</script>

<script>
function nom_to_busc(num){
    var cont_a = $("#box-nomap-a-"+num).html();
    var cont_b = $("#box-nomap-b-"+num).html();
    $("#input-busca-participante").val(cont_a+' '+cont_b);
}
</script>




<!-- confirmacion_correo_departamento -->
<script>
    function confirmacion_correo_departamento() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ENVIAR CORREO CONFIRMACION DE DEPARTAMENTO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.confirmacion_correo_departamento.php',
                data: {dat: ids.join(',')},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
        });
    }
</script>

<!-- enviar_emitidos_por_correo_fromlist -->
<script>
    function enviar_emitidos_por_correo_fromlist(id_participante) {
        if (confirm('Desea enviar todos los certificados emitidos por correo ? (COMO CERTIFICADOS DIGITALES)')) {
            $("#AJAXCONTENT-enviar_emitidos_por_correo_fromlist-"+id_participante).html('Procesando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_emitidos_por_correo.php',
                data: {
                    id_participante: id_participante
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-enviar_emitidos_por_correo_fromlist-"+id_participante).html(data);
                    $("#AJAXCONTENT-aux_ajax_message_1-"+id_participante).html('<br><h4>CERTIFICADOS ENVIADOS CORRECTAMENTE</h4>');
                }
            });
        }
    }
</script>

<script>
    function enviar_enlaces_por_wap_fromlist(id_participante,cel_participante){
        var cont = $("#cont-enlaces-descarga-cert-"+id_participante).html();
        window.open('https://api.whatsapp.com/send?phone=591'+cel_participante+'&text='+cont.replace(/<br>/g, '%0A').replace(/ /g, '%20'),'blank');
    }
</script>

<!-- cupon_infosicoes -->
<script>
    function cupon_infosicoes(id_participante) {
        $("#TITLE-modgeneral").html('CUP&Oacute;N INFOSICOES');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cupon_infosicoes.php',
            data: {id_participante: id_participante,id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- trasladar_participantes -->
<script>
    function trasladar_participantes() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('TRASLADO DE PARTICIPANTES');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.trasladar_participantes.php',
            data: {id_curso: '<?php echo $id_curso; ?>',dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- copiar_participantes -->
<script>
    function copiar_participantes() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('COPIAR PARTICIPANTES');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.trasladar_participantes.php?accion-alternativa=copiar',
            data: {id_curso: '<?php echo $id_curso; ?>',dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- enviar_invitacion_individual_zoom -->
<script>
    function enviar_invitacion_individual_zoom(id_participante) {
        if(confirm('DESEA ENVIAR LA INVITACION ZOOM ?')){
            $("#td-envszoom-"+id_participante).html('Procesando...');
            $.ajax({
                url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_invitacion_zoom.php',
                data: {id_participante: id_participante, keyaccess: '5rw4t6gd1', id_administrador: '<?php echo administrador('id'); ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    console.log(data);
                    $("#td-envszoom-"+id_participante).html('<b class="text-success">Inv. ZOOM enviado correctamente</b>');
                }
            });            
        }
    }
</script>

<!-- verificar_pago_participante -->
<script>
    function verificar_pago_participante(id_participante) {
        $("#ajaxcont-verifpago-"+id_participante).html('<h4>Procesando...</h4>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.verificar_pago_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxcont-verifpago-"+id_participante).html(data);
            }
        });            
    }
</script>

<!-- fecha_hora_pago -->
<script>
    function fecha_hora_pago(id_participante) {
        $("#ajaxcont-fecha_hora_pago-"+id_participante).html('...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.fecha_hora_pago.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxcont-fecha_hora_pago-"+id_participante).html(data);
            }
        });            
    }
</script>


<!-- participante_cursos_registrados -->
<script>
    function participante_cursos_registrados(id_participante) {
        $("#ajaxbox-lista_participantes").html('<h4>Procesando...</h4>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.participante_cursos_registrados.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxbox-lista_participantes").html(data);
                $("#ajaxbox-lista_participantes_eliminados").html('');
            }
        });            
    }
</script>

<!-- activacion_multiple_cvirtuales -->
<script>
    function activacion_multiple_cvirtuales() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ACTIVACION MULTIPLE DE CURSOS VIRTUALES');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.activacion_multiple_cvirtuales.php',
            data: {id_curso: '<?php echo $id_curso; ?>',dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<!-- envio_zoom_multiple -->
<script>
    function envio_zoom_multiple() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#TITLE-modgeneral").html('ENVIO MULTIPLE DE SESIONES ZOOM');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.envio_zoom_multiple.php',
            data: {id_curso: '<?php echo $id_curso; ?>',dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
