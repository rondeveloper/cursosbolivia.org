<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_curso = post('id_curso');

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado,fecha,id_modalidad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = mysql_fetch_array($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$fecha_curso = $rqvhc2['fecha'];
$id_modalidad_curso = (int) $rqvhc2['id_modalidad'];

/* sw_curso_virtual */
$sw_curso_virtual = false;
$qrcoe1 = query("SELECT count(*) AS total FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
$qrcoe2 = mysql_fetch_array($qrcoe1);
$cnt_cursos_cirtuales_asociados = (int) $qrcoe2['total'];
if ($cnt_cursos_cirtuales_asociados > 0 && ($id_modalidad_curso == 2 || $id_modalidad_curso == 3)) {
    $sw_curso_virtual = true;
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('busc')) {
    $busqueda = post('busc');
    if(strpos($busqueda,'ids_')>0){
        $qr_busqueda = " AND ( id IN (".str_replace('__ids_','',$busqueda).") ) ";
    }else{
        $qr_busqueda = " AND ( id='$busqueda' OR nombres LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ) ";
    }
    $vista = 1;
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset_post('id_turno') && (post('id_turno') > 0)) {
    $id_turno_curso = (int) post('id_turno');
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}


/* datos de curso */
$rqc1 = query("SELECT id_certificado,id_certificado_2,id_certificado_3,id_material,titulo,mailto_subject,mailto_content FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_curso = $rqc2['titulo'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$id_certificado_3_curso = $rqc2['id_certificado_3'];
$id_material_curso = $rqc2['id_material'];
$mailto_subject = str_replace(' ','%20',$rqc2['mailto_subject']);
$mailto_content = str_replace(' ','%20',str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqc2['mailto_content']));
$whatsto_subject = $rqc2['mailto_subject'].'%0D%0A'.str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqc2['mailto_content']);

/* sw_turno */
$sw_turnos = false;
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso'  ");
if (mysql_num_rows($rqtc1) > 0) {
    $sw_turnos = true;
    while ($rqtc2 = mysql_fetch_array($rqtc1)) {
        $turno[$rqtc2['id']] = $rqtc2['titulo'];
    }
}

/* modo_pago */
$qr_modo_pago = "";
/*
if (isset_post('modo_pago') && post('modo_pago') !== 'todos' && post('modo_pago') !== '') {
    $modo_pago = post('modo_pago');
    if ($modo_pago == 'sinpago') {
        $modo_pago = '';
    }
    $qr_modo_pago = " AND modo_pago='$modo_pago' ";
}
*/
if (isset_post('modo_pago')) {
    $modo_pago = post('modo_pago');
    switch ($modo_pago) {
        case 'conpago':
            $qr_modo_pago = " AND id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='1' AND id_curso='$id_curso' ) ";
            break;
        case 'sinpago':
            $qr_modo_pago = " AND id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='0' AND id_curso='$id_curso' ) ";
            $modo_pago = '';
            break;
        default:
            break;
    }
}

/* sw cursos_cupones_infosicoes */
$sw_cupon_infosicoes = false;
$id_cupon_infosicoes = 0;
$rqveci1 = query("SELECT id FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
if (mysql_num_rows($rqveci1) > 0) {
    $sw_cupon_infosicoes = true;
    $rqveci2 = mysql_fetch_array($rqveci1);
    $id_cupon_infosicoes = $rqveci2['id'];
}

/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id NOT IN (select id_participante from cursos_part_apartados) AND id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno $qr_modo_pago ORDER BY id DESC ");

/* contador */
$cnt = mysql_num_rows($resultado1);

/* aux ids almacenador */
$aux_idsalmacenador = '0';
?>

<style>
    .reg-fuerafecha .simple-td{
        background: #faf2f2 !important;
    }
</style>

<div class="table-responsive">
    <table class="table users-table table-condensed table-hover">
        <thead>
            <tr>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">#</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Prof.</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Nombre</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Facturaci&oacute;n</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">M/Pago</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Registro</th>
                <?php
                if ($sw_turnos) {
                    echo '<th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Turno</th>';
                }
                ?>
                <?php if ($sw_curso_virtual) { ?>
                    <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Estado C-VIRTUAL</th>
                <?php } elseif (false) { ?>
                    <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Contacto</th>
                <?php } ?>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;width:45px;">
                    <a class='btn btn-xs btn-default' onclick="$('input:checkbox').removeAttr('checked');">.</a>
                </th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">
                    Certs
                </th>
                <?php
                if ($sw_cupon_infosicoes) {
                    ?>
                    <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">
                        Cup&oacute;n
                    </th>
                    <?php
                }
                ?>
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
            $sw_certificados_adicionales = false;

            /* sw certificados adiconales */
            $rqvca1 = query("SELECT id FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' LIMIT 1 ");
            if (mysql_num_rows($rqvca1) > 0) {
                $sw_certificados_adicionales = true;
            }

            if (mysql_num_rows($resultado1) == 0) {
                echo "<tr><td colspan='11'>No existen participantes registrados.</td></tr>";
            }

            while ($participante = mysql_fetch_array($resultado1)) {

                /* datos de registro */
                $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,sw_pago_enviado,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                $data_registro = mysql_fetch_array($rqrp1);
                $id_proceso_de_registro = $data_registro['id'];
                $codigo_de_registro = $data_registro['codigo'];
                $fecha_de_registro = $data_registro['fecha_registro'];
                $celular_de_registro = $data_registro['celular_contacto'];
                $correo_de_registro = $data_registro['correo_contacto'];
                $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                $id_modo_de_registro = $data_registro['id_modo_de_registro'];
                $id_emision_factura = $data_registro['id_emision_factura'];

                $metodo_de_pago = $data_registro['metodo_de_pago'];
                $monto_de_pago = $data_registro['monto_deposito'];
                $imagen_de_deposito = $data_registro['imagen_deposito'];

                $razon_social_de_registro = $data_registro['razon_social'];
                $nit_de_registro = $data_registro['nit'];

                $sw_pago_enviado = $data_registro['sw_pago_enviado'];
                $id_cobro_khipu = $data_registro['id_cobro_khipu'];

                $aux_idsalmacenador .= ',' . $participante['id'];

                $tr_class = '';
                $text_msj = '';
                if (strtotime(date("Y-m-d", strtotime($data_registro['fecha_registro']))) > strtotime($fecha_curso)) {
                    $tr_class = 'reg-fuerafecha';
                    $text_msj = '<br/><i style="color:red;font-size:7pt;">Fuera de fecha</i>';
                }
                ?>
                <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>" class="<?php echo $tr_class; ?>">
                    <td class="simple-td">
                        <?php echo $cnt--; ?>
                        <br/>
                        <br/>
                        <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                        </b>
                    </td>
                    <td class="nombre-td" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                        <?php
                        echo trim($participante['prefijo']);
                        ?>
                    </td>
                    <td class="nombre-td" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                        <?php
                        echo trim($participante['nombres']);
                        ?>
                        <br/>
                        <b style="font-size:7pt;color:#2180be;">No. <?php echo $participante['numeracion']; ?></b>
                        <?php
                        echo "<br/>";
                        echo '<span style="color:gray;font-size:8pt;"><a href="mailto:' . $participante['correo'] . '?subject='. $mailto_subject . '&body='.$mailto_content.'">' . $participante['correo'] . '</a><br/><a target="_blank" href="https://api.whatsapp.com/send?phone=591' . $participante['celular'] . '&text='.($mailto_content).'" id="c'.$participante['celular'].'">' . $participante['celular'] . '</a></span>';
                        echo ' &nbsp;&nbsp; <b class="btn btn-xs btn-default" onclick="copyToClipboard(\'c'.$participante['celular'].'\')">C</b>';
                        if($sw_pago_enviado){
                        echo ' &nbsp;&nbsp; <a class="btn btn-xs btn-default" onclick="cvirtual_send_mailto_accesos('.$participante['id'].')"><i class="fa fa-envelope"></i></a>';
                        echo ' &nbsp;&nbsp; <img onclick="cvirtual_send_whatsapp_accesos('.$participante['id'].')" src="https://cdn.iconscout.com/icon/free/png-256/whatsapp-circle-1868968-1583132.png" style="height: 25px;border: 1px solid #6db7e2;border-radius: 50%;"/>';
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
                    </td>
                    <td class="simple-td">
                        <?php
                        if ($id_emision_factura != '0') {
                            $sw_existencia_facturas = true;
                            echo '<i class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $participante['id'] . ');">Emitida</i>';
                            if ($participante['correo'] !== '') {
                                $rqefaux1 = query("SELECT id,nro_factura FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                                $rqefaux2 = mysql_fetch_array($rqefaux1);
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



                        echo $razon_social_de_registro;
                        echo "<br/>";
                        echo $nit_de_registro;
                        ?>
                    </td>
                    <td class="simple-td">
                        <?php
                        $sw_pago_definido = false;
                        if ($monto_de_pago !== '' && $participante['modo_pago'] !== '') {
                            $sw_pago_definido = true;
                            echo $monto_de_pago;
                            echo "<br/>";
                            echo "<span style='color:gray;font-size:8pt;'>" . $participante['modo_pago'] . "</span>";
                            ?>
                            <br/>
                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-default">
                                <i class="fa fa-info"></i> INFO PAGO
                            </a>
                            <?php
                        } elseif ($sw_habilitacion_procesos) {
                            $enlace_pago = 'https://cursos.bo/registro-curso-p5c/'.md5('idr-' . $participante['id_proceso_registro']).'/'.$participante['id_proceso_registro'].'.html';
                            $txt_whatsapp = 'Buen día ' . utf8_decode($participante['nombres']) . '__Le hacemos el envío del enlace donde debe reportar el pago para el curso__*'.utf8_decode($nombre_curso).'*:__ __Enlace de pago:__'.$enlace_pago;
                            $txt_whatsapp = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
                            ?>
                            <b style='color:#e74c3c;'>PAGO NO DEFINIDO</b>
                            <br/>
                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-danger">
                                <i class="fa fa-info"></i> INFO PAGO
                            </a>
                            <?php if(strlen(trim($participante['celular']))==8){ ?>
                            <br/>
                            <a href="https://api.whatsapp.com/send?phone=591<?php echo trim($participante['celular']); ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                                <img src="https://cdn.iconscout.com/icon/free/png-256/whatsapp-circle-1868968-1583132.png" style="height: 30px;border: 1px solid #ff6868;border-radius: 50%;"/>
                            </a>
                            <?php } ?>
                            <?php
                        }
                        if ($imagen_de_deposito != '') {
                            echo "<br/><span class='btn btn-xs btn-success active small' onclick='window.open(\"https://cursos.bo/depositos/$imagen_de_deposito.size=6.img\" , \"ventana1\" , \"width=800,height=800,scrollbars=NO\");'>Imagen respaldo</span>";
                        }
                        if ($id_cobro_khipu != '0') {
                            $rqdckv1 = query("SELECT payment_id FROM khipu_cobros WHERE id='$id_cobro_khipu' AND estado='1' ");
                            if(mysql_num_rows($rqdckv1)>0){
                                $rqdckv2 = mysql_fetch_array($rqdckv1);
                                echo "<br/><a href='https://khipu.com/payment/info/".$rqdckv2['payment_id']."' target='_blank' class='btn btn-xs btn-success active small'>Respaldo cobro</span>";
                            }
                        }
                        ?>
                    </td>
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
                        if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                            echo "SISTEMA";
                        } elseif ($id_modo_de_registro == '2') {
                            echo "<b style='color:#444;'>ADMIN</b>";
                            echo "<br/>";
                            if ($data_registro['id_administrador'] == '0') {
                                echo "<span style='color:gray;'>Sin datos del registrador</span>";
                            } else {
                                $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                                $rqadr2 = mysql_fetch_array($rqadr1);
                                echo "<span style='color:gray;font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                            }
                        }
                        echo $text_msj;
                        ?>
                    </td>
                    <?php
                    if ($sw_turnos) {
                        echo '<td class="simple-td">' . $turno[$participante['id_turno']] . '</td>';
                    }
                    ?>
                    <?php
                    if ($sw_curso_virtual) {
                        if ($cnt_cursos_cirtuales_asociados == 1) {
                            if ($participante['sw_cvirtual'] == 1) {
                                ?>
                                <td class="simple-td">
                                    <div style="color:green;background: #e3efd5;padding: 7px;text-align: center;border: 1px solid #9cbf73;">HABILITADO</div>
                                    <?php if ($sw_habilitacion_procesos) { ?>
                                    <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                                        Des-habilitar: <b class="btn btn-danger btn-xs" data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_cvirtual_p1(<?php echo $participante['id']; ?>);">X</b>
                                    </div>
                                    <?php } ?>
                                    <b class="btn btn-info btn-xs btn-block" data-toggle="modal" data-target="#MODAL-avance-cvirtual" onclick="avance_cvirtual(<?php echo $participante['id']; ?>);">PANEL C-vir</b>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td class="simple-td">
                                    <div style="color:#FFF;background: #ef8a80;padding: 7px;text-align: center;border: 1px solid #e74c3c;">NO HABILITADO</div>
                                    <?php if ($sw_habilitacion_procesos) { ?>
                                    <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                                        Habilitar: &nbsp; <b class="btn btn-success btn-xs" data-toggle="modal" data-target="#MODAL-habilita-participante" onclick="habilita_participante_cvirtual_p1(<?php echo $participante['id']; ?>);"><i class="fa fa-check"></i></b>
                                    </div>
                                    <?php } ?>
                                    <b class="btn btn-default btn-xs btn-block" data-toggle="modal" data-target="#MODAL-avance-cvirtual" onclick="avance_cvirtual(<?php echo $participante['id']; ?>);">PANEL C-vir</b>
                                </td>
                                <?php
                            }
                        } else {
                            $rqdcntcva1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' AND id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso') ");
                            $rqdcntcva2 = mysql_fetch_array($rqdcntcva1);
                            $aux_cnt_asignados = $rqdcntcva2['total'];
                            ?>
                            <td class="simple-td">
                                <b class="btn btn-xs btn-block btn-default" onclick="acceso_cursos_virtuales('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-acceso_cursos_virtuales">
                                    C-VIRTUALES
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
                            </td>
                            <?php
                        }
                    }
                    ?>
                    <td class="simple-td">
                        <?php
                        if ($sw_habilitacion_procesos) {
                            if ($sw_pago_definido) {
                                $ckecked = ' checked="" ';
                            } else {
                                $ckecked = ' disabled ';
                            }
                            ?>
                            <input type="checkbox" id="<?php echo $participante['id']; ?>" name="" <?php echo $ckecked; ?> style="width:17px;height:17px;"/>
                            &nbsp;
                            <?php
                        }
                        ?>
                        <span style="font-size: 10pt;"><?php echo $participante['cnt_impresion_certificados']; ?></span>
                    </td>
                    <td class="simple-td">
                        <?php
                        //$sw_habilitacion_procesos = true;
                        $sw_existencia_certificado_uno = $sw_existencia_certificado_dos = $sw_existencia_certificado_tres = false;
                        /* primer certificado */                        
                        if ($participante['id_emision_certificado'] == 0 && $sw_habilitacion_procesos && $id_certificado_curso != 0 && $sw_pago_definido) {
                            $sw_existencia_certificado_uno = false;
                            ?>
                            <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 1);" class="btn btn-xs btn-primary active">C1</a>
                            </span>
                            <?php
                        } elseif ($participante['id_emision_certificado'] !== '0') {
                            $sw_existencia_certificado_uno = $sw_exist_cert_uno = true;
                            ?>
                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 1);" class="btn btn-xs btn-warning active">C1</a>
                            <?php
                        }

                        /* segundo certificado */
                        if ($participante['id_emision_certificado_2'] == '0' && $sw_habilitacion_procesos && $id_certificado_2_curso !== '0' && $sw_pago_definido) {
                            $sw_existencia_certificado_dos = false;
                            ?>
                            <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 2);" class="btn btn-xs btn-primary active">C2</a>
                            </span>
                            <?php
                            if (!$sw_existencia_certificado_uno && $id_certificado_3_curso == '0') {
                                ?>
                                <span id='box-modal_emision_certificado-button-12-<?php echo $participante['id']; ?>'>
                                    <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 12);" class="btn btn-xs btn-primary">C12</a>
                                </span>
                                <?php
                            }
                        } elseif ($participante['id_emision_certificado_2'] !== '0') {
                            $sw_existencia_certificado_dos = $sw_exist_cert_dos = true;
                            ?>
                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 2);" class="btn btn-xs btn-warning active">C2</a>
                            <?php
                        }

                        /* tercer certificado */
                        if ($participante['id_emision_certificado_3'] == '0' && $sw_habilitacion_procesos && $id_certificado_3_curso !== '0' && $sw_pago_definido) {
                            $sw_existencia_certificado_tres = false;
                            ?>
                            <span id='box-modal_emision_certificado-button-3-<?php echo $participante['id']; ?>'>
                                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 3);" class="btn btn-xs btn-primary active">C3</a>
                            </span>
                            <?php
                            if (!$sw_existencia_certificado_uno && !$sw_existencia_certificado_dos) {
                                ?>
                                <span id='box-modal_emision_certificado-button-123-<?php echo $participante['id']; ?>'>
                                    <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 123);" class="btn btn-xs btn-primary btn-block">C123</a>
                                </span>
                                <?php
                            }
                        } elseif ($participante['id_emision_certificado_3'] !== '0') {
                            $sw_existencia_certificado_tres = $sw_exist_cert_tres = true;
                            ?>
                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 3);" class="btn btn-xs btn-warning active">C3</a>
                            <?php
                        }

                        if ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos && $sw_existencia_certificado_tres) {
                            ?>
                            <a onclick="imprimir_tres_certificados('<?php echo $participante['id_emision_certificado'] . ',' . $participante['id_emision_certificado_2'] . ',' . $participante['id_emision_certificado_3']; ?>');" class="btn btn-xs btn-warning btn-block">C123</a>
                            <?php
                        } elseif ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos && $id_certificado_3_curso == '0') {
                            ?>
                            <a onclick="imprimir_dos_certificados('<?php echo $participante['id_emision_certificado'] . ',' . $participante['id_emision_certificado_2']; ?>');" class="btn btn-xs btn-warning">C12</a>
                            <?php
                        }
                        ?>
                        <?php
                        if ($sw_certificados_adicionales) {
                            ?>
                            <br/><br/>       
                            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 0);" class="btn btn-xs btn-warning active btn-block">C adicionales</a>
                            <?php
                        }
                        ?>
                    </td>
                    <?php
                    if ($sw_cupon_infosicoes) {
                        ?>
                        <td class="simple-td">
                            <?php
                            $rqve1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id_participante='" . $participante['id'] . "' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
                            if (mysql_num_rows($rqve1) > 0) {
                                ?>
                                <button onclick="window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon_infosicoes; ?>&id_participante=<?php echo $participante['id']; ?>', 'popup', 'width=700,height=500');" class="btn btn-warning active btn-xs">VER</button>
                                <?php
                            } else {
                                echo "No";
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td class="simple-td">
                        <div style="width: 90px;float: right;">
                            <?php
                            if ($sw_habilitacion_procesos) {
                                ?>
                                <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(<?php echo $participante['id']; ?>, '<?php echo ($cnt + 1); ?>');" class="btn btn-xs btn-info active"> <i class="fa fa-edit"></i> </a>
                                &nbsp;
                                <?php
                            }
                            ?>
                            <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-default">R</a>
                            <?php
                            if ($sw_habilitacion_procesos) {
                                ?>
                                &nbsp         
                                <span id="ajaxbox-button-eliminar-participante-<?php echo $participante['id']; ?>">
                                    <a data-toggle="modal" data-target="#MODAL-elimina-participante" onclick="elimina_participante_p1(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-danger active"> X </a>
                                </span>
                                <?php
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

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
                    if ($id_certificado_curso !== '0') {
                        $rqdcca1 = query("SELECT texto_qr,cont_tres FROM cursos_certificados WHERE id='$id_certificado_curso' ORDER BY id DESC LIMIT 1 ");
                        $rqdcca2 = mysql_fetch_array($rqdcca1);
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
                        $rqdcca2 = mysql_fetch_array($rqdcca1);
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
                        $rqdcca2 = mysql_fetch_array($rqdcca1);
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
                        $rqdcca2 = mysql_fetch_array($rqdcca1);
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
                <div class="panel-heading">EMISION / IMPRESION MULTIPLE DE FACTURAS</div>
                <div class="panel-body">

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
                    /* certificados adicionales */
                    $rqmc1 = query("SELECT * FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC ");
                    if (mysql_num_rows($rqmc1) > 0) {
                        ?>
                        <hr/>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CERTIFICADOS ADICIONALES</h4>
                        <table class="table table-bordered">
                            <?php
                            $cnt = 0;
                            while ($rqmc2 = mysql_fetch_array($rqmc1)) {
                                $rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id='" . $rqmc2['id_certificado'] . "' LIMIT 1 ");
                                $rqdcrt2 = mysql_fetch_array($rqdcrt1);
                                ?>
                                <tr>
                                    <td class="visible-lg">
                                        <?php
                                        echo "<b>" . $rqdcrt2['codigo'] . "</b>";
                                        echo "<br/>";
                                        echo $rqdcrt2['texto_qr'];
                                        echo "<br/>";
                                        echo $rqdcrt2['fecha_qr'];
                                        ?> 
                                    </td>
                                    <td class="visible-lg" style="width:120px;">
                                        <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('<?php echo $rqdcrt2['id']; ?>');"> <i class="fa fa-print"></i> Imprimir</a> 
                                        <br/>
                                        <br/>
                                        <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emite_certificados_multiple('<?php echo $rqdcrt2['id']; ?>', '<?php echo $id_curso; ?>', '0');" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                    }
                    ?>

                    <?php
                    $rqecd1 = query("SELECT id FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' ");
                    if (mysql_num_rows($rqecd1) > 0) {
                        ?>
                        <hr/>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>CUPONES INFOSICOES</h4>
                        <b>ADMINISTRACI&Oacute;N DE CUPONES</b>
                        <br/>
                        <i>Generado de cupones Infosicoes para participantes de este curso.</i>
                        <br/>
                        <br/>
                        <a data-toggle="modal" data-target="#MODAL-emite-cupones-descuento" onclick="emision_cupones_infosicoes();" class="btn btn-info"> CUPONES INFOSICOES</a>
                        <?php
                    }
                    ?>

                    <?php
                    /* MATERIAL DIGITAL */
                    if ($id_material_curso !== '0') {
                        $rqmd1 = query("SELECT nombre_material FROM cursos_material WHERE id='$id_material_curso' ");
                        $rqmd2 = mysql_fetch_array($rqmd1);
                        ?>
                        <br/>
                        <hr/>
                        <h4 style='background:#257990;color:#FFF;padding:5px 7px;text-align:center;border-radius: 7px;'>MATERIAL DIGITAL</h4>
                        <br/>
                        <b><?php echo $rqmd2['nombre_material']; ?></b>
                        <br>
                        <i>Se muestra el listado de archivos del material asignado.</i>
                        <br/>
                        <?php
                        $rqda1 = query("SELECT * FROM cursos_material_archivos WHERE id_material='$id_material_curso' ");
                        if (mysql_num_rows($rqda1) == 0) {
                            ?>
                            <div class="alert alert-info">
                                <strong>AVISO</strong> no se encontraron registros.
                            </div>
                            <?php
                        } else {
                            ?>
                            <table class="table table-striped table-bordered">
                                <?php
                                $cnt = 1;
                                while ($rqda2 = mysql_fetch_array($rqda1)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $cnt++; ?>
                                        </td>
                                        <td>
                                            <?php echo $rqda2['nombre_digital']; ?>
                                        </td>
                                        <td>
                                            <a href="contenido/archivos/material/<?php echo $rqda2['nombre_fisico']; ?>" target="_blank">VISUALIZAR</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <br/>
                            <span id="AJAXCONTENT-enviar_material_digital">
                                <a onclick="enviar_material_digital();" class="btn btn-primary"> ENVIAR POR CORREO</a>
                            </span>
                            <?php
                        }
                    }
                    ?>


                    <?php
                    /*
                      $rqdpg1 = query("SELECT nombre FROM cursos_pdf_generados WHERE id_curso='$id_curso' ");
                      if (mysql_num_rows($rqdpg1) > 0) {

                      $rqecda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
                      $rqecda2 = mysql_fetch_array($rqecda1);
                      if ($rqecda2['nivel'] == '1') {
                      ?>
                      <br/>
                      <hr/>
                      <i>PDF's generados para este curso:</i>
                      <br/>
                      <?php
                      while ($rqdpg2 = mysql_fetch_array($rqdpg1)) {
                      ?>
                      <br/>
                      <a href="contenido/archivos/pdfcursos/<?php echo $rqdpg2['nombre']; ?>" target="_blank"><?php echo $rqdpg2['nombre']; ?></a>
                      <br/>
                      <?php
                      }
                      }
                      }
                     */
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>



<script>

    var array_check_participante = new Object();

    var aux_idsalmacenador = "<?php echo $aux_idsalmacenador; ?>";

</script>

<script>
    function reporte_cierre_p1() {
        $("#AJAXBOX-reporte_cierre").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.reporte_cierre_p1.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.reporte_cierre_p2.php?dat=' + dat,
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
                url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.enviar_material_digital.php',
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


<script>
    function cvirtual_send_mailto_accesos(id_participante) {     
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.cvirtual_send_mailto_accesos.php',
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
    function cvirtual_send_whatsapp_accesos(id_participante) {     
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.cvirtual_send_whatsapp_accesos.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                window.open("https://api.whatsapp.com/send?"+data, '_blank');
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