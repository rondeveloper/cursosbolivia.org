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

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado,fecha,id_modalidad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}
$sw_habilitacion_procesos = false;

$fecha_curso = $rqvhc2['fecha'];
$id_modalidad_curso = (int) $rqvhc2['id_modalidad'];



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
$rqc2 = fetch($rqc1);
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
            $qr_pago = " AND id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='1' AND id_curso='$id_curso' ) ";
            break;
        case 'sinpago':
            $qr_pago = " AND id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='0' AND id_curso='$id_curso' ) ";
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
//$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno $qr_pago ORDER BY id DESC ");
$resultado1 = query("SELECT p.*,(count(*))cnt_cursos_virtuales FROM cursos_participantes p INNER JOIN cursos_onlinecourse_acceso a ON a.id_usuario=p.id_usuario WHERE p.id_usuario<>'0' AND p.estado='1' AND (p.correo LIKE '%hotmail%' OR p.correo LIKE '%outlook%' OR p.correo LIKE '%msn%' OR p.correo LIKE '%live%') GROUP BY p.id ORDER BY a.id DESC LIMIT 100 ");

/* contador */
$cnt = num_rows($resultado1);

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
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Curso</th>
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
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Estado C-VIRTUAL</th>
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
            if (num_rows($rqvca1) > 0) {
                $sw_certificados_adicionales = true;
            }

            if (num_rows($resultado1) == 0) {
                echo "<tr><td colspan='11'>No existen participantes registrados.</td></tr>";
            }

            while ($participante = fetch($resultado1)) {

                /* datos de registro */
                $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,sw_pago_enviado,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                $data_registro = fetch($rqrp1);
                $id_proceso_de_registro = $data_registro['id'];
                $codigo_de_registro = $data_registro['codigo'];
                $fecha_de_registro = $data_registro['fecha_registro'];
                $celular_de_registro = $data_registro['celular_contacto'];
                $correo_de_registro = $data_registro['correo_contacto'];
                $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                $id_emision_factura = $data_registro['id_emision_factura'];

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
                
                
                /* datos curso */
                $id_curso_part = $participante['id_curso'];
                $rqdcc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso_part' ORDER BY id DESC limit 1 ");
                $rqdcc2 = fetch($rqdcc1);
 
                /* sw_curso_virtual */
                $sw_curso_virtual = false;
                $qrcoe1 = query("SELECT count(*) AS total FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso_part' and estado='1' ORDER BY id DESC limit 1 ");
                $qrcoe2 = fetch($qrcoe1);
                $cnt_cursos_cirtuales_asociados = (int) $qrcoe2['total'];
                if ($cnt_cursos_cirtuales_asociados > 0 && ($id_modalidad_curso == 2 || $id_modalidad_curso == 3)) {
                    $sw_curso_virtual = true;
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
                        echo trim($rqdcc2['titulo']);
                        echo "<br><b>Cursos activados ".$participante['cnt_cursos_virtuales']."</b>";
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



                        echo $razon_social_de_registro;
                        echo "<br/>";
                        echo $nit_de_registro;
                        ?>
                    </td>
                    <td class="simple-td">
                        <?php
                        $sw_pago_definido = false;
                        if ($monto_de_pago !== '' && $participante['id_modo_pago'] != '0') {
                            $sw_pago_definido = true;
                            echo $monto_de_pago;
                            echo "<br/>";
                            echo "<span style='color:gray;font-size:8pt;'>" . $participante['id_modo_pago'] . "</span>";
                            ?>
                            <br/>
<!--                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-default">
                                <i class="fa fa-info"></i> INFO PAGO
                            </a>-->
                            <?php
                        } elseif ($sw_habilitacion_procesos) {
                            $enlace_pago = $dominio.'registro-curso-p5c/'.md5('idr-' . $participante['id_proceso_registro']).'/'.$participante['id_proceso_registro'].'.html';
                            $txt_whatsapp = 'Hola ' . ($participante['nombres']) . '__te hacemos el envío del enlace donde debe reportar el pago para el curso__*'.($nombre_curso).'*:__ __Enlace de pago:__'.$enlace_pago;
                            $txt_whatsapp = (str_replace('__', '%0A', str_replace(' ', '%20', $txt_whatsapp)));
                            ?>
                            <b style='color:#e74c3c;'>PAGO NO DEFINIDO</b>
                            <br/>
<!--                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-danger">
                                <i class="fa fa-info"></i> INFO PAGO
                            </a>-->
                            <?php if(strlen(trim($participante['celular']))==8){ ?>
                            <br/>
                            <a href="https://api.whatsapp.com/send?phone=591<?php echo trim($participante['celular']); ?>&text=<?php echo $txt_whatsapp; ?>" target="_blank">
                                <img src="https://cdn.iconscout.com/icon/free/png-256/whatsapp-circle-1868968-1583132.png" style="height: 30px;border: 1px solid #ff6868;border-radius: 50%;"/>
                            </a>
                            <?php } ?>
                            <?php
                        }
                        if ($imagen_de_deposito != '') {
                            echo "<br/><span class='btn btn-xs btn-success active small' onclick='window.open(\"".$dominio."depositos/$imagen_de_deposito.size=6.img\" , \"ventana1\" , \"width=800,height=800,scrollbars=NO\");'>Imagen respaldo</span>";
                        }
                        if ($id_cobro_khipu != '0') {
                            $rqdckv1 = query("SELECT payment_id FROM khipu_cobros WHERE id='$id_cobro_khipu' AND estado='1' ");
                            if(num_rows($rqdckv1)>0){
                                $rqdckv2 = fetch($rqdckv1);
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
                        echo "<b style='color:#444;'>ADMIN</b>";
                        echo "<br/>";
                        if ($data_registro['id_administrador'] == '0') {
                            echo "<span style='color:gray;'>Sistema</span>";
                        } else {
                            $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                            $rqadr2 = fetch($rqadr1);
                            echo "<span style='color:gray;font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
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
                            $rqdcntcva1 = query("SELECT count(*) AS total FROM cursos_onlinecourse_acceso WHERE id_usuario='".$participante['id_usuario']."' AND id_onlinecourse IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso_part' and estado='1' ) ");
                            $rqdcntcva2 = fetch($rqdcntcva1);
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
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>


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
    function cvirtual_send_whatsapp_accesos(id_participante) {     
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cvirtual_send_whatsapp_accesos.php',
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