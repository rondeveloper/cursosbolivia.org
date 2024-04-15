<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');
$nro_certificado = post('nro_certificado');

/* mensaje */
$mensaje = '';

/* datos de participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($resultado1);
$id_usuario = $participante['id_usuario'];

/* adminsitrador */
$id_administrador = administrador('id');

/* datos de curso */
$id_curso = $participante['id_curso'];
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,id_certificado_3,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,id_modalidad FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$id_certificado_3_curso = $rqc2['id_certificado_3'];
$url_imagen_del_curso = $dominio_www . "paginas/" . $rqc2['imagen'] . ".size=4.img";

$costo_curso = $rqc2['costo'];
$id_modalidad_curso = $rqc2['id_modalidad'];

/* codigo de certificado */
if ((int) $nro_certificado == 0) {
    $codigo_de_certificado_del_curso = 'CERTIFICADOS GENERALES';
    $txt_verif_id_emision_certificado = -1;
} elseif ((int) $nro_certificado > 0) {
    $id_certificado_curso = $nro_certificado;
    /* cert adicional */
    $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_curso' ");
    $rqc_c2_2 = fetch($rqc_c2_1);
    $codigo_de_certificado_del_curso = $rqc_c2_2['codigo'];
    $txt_verif_id_emision_certificado = 0;
}

/* curso */
$rqdcrr1 = query("SELECT id_certificado,id_certificado_2,id_certificado_3 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdcrr1);
$ids_certs_iniciales = (int) $curso['id_certificado'] . ',' . (int) $curso['id_certificado_2'] . ',' . (int) $curso['id_certificado_3'];

/* sw certificados adiconales */
$sw_certificados_adicionales = false;
$rqvca1 = query("SELECT id FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' LIMIT 1 ");
if (num_rows($rqvca1) > 0 && ((int) $nro_certificado == 0)) {
    $sw_certificados_adicionales = true;
}

/* $sw_certificado_culminacion */
$sw_certificado_culminacion = false;
$rqvcce1 = query("SELECT * FROM certificados_culminacion WHERE id_curso='$id_curso' AND estado='1' LIMIT 1 ");
if (num_rows($rqvcce1) > 0) {
    $sw_certificado_culminacion = true;
    $rqvcce2 = fetch($rqvcce1);
    $id_certificado_culminacion = $rqvcce2['id'];
}

/* emite_cert_culminacion */
if (isset_post('emite_cert_culminacion') && $sw_certificado_culminacion) {
    $nota = 97;
    $rqvrfe1 = query("SELECT id FROM certificados_culminacion_emisiones WHERE id_certificado_culminacion='$id_certificado_culminacion' AND id_participante='$id_participante' ");
    if (num_rows($rqvrfe1) == 0) {
        query("INSERT INTO certificados_culminacion_emisiones(id_certificado_culminacion,id_participante,nota,fecha_emision,estado) VALUES ('$id_certificado_culminacion','$id_participante','$nota',NOW(),'1')");
        logcursos('Emision de certificado de culminacion', 'participante-edicion', 'participante', $id_participante);
        $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong><br>El certificado se emitio correctamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>ERROR</strong><br>El participante ya tiene emitido el certificado.
</div>';
    }
}

/* sw_registra_compromiso_culminacion */
if (isset_post('sw_registra_compromiso_culminacion')) {
    $rqvrfe1 = query("SELECT id FROM compromisos_finalizacion WHERE id_participante='$id_participante' AND id_curso='$id_curso' ");
    if (num_rows($rqvrfe1) == 0) {
        query("INSERT INTO compromisos_finalizacion(id_participante, id_usuario, id_curso, id_administrador, archivo, fecha_registro, estado) VALUES ('$id_participante','$id_usuario','$id_curso','$id_administrador','',NOW(),'1')");
        logcursos('Registro de compromiso de finalizacion', 'participante-edicion', 'participante', $id_participante);
        $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong><br>El compromiso se registro correctamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>ERROR</strong><br>El participante ya tiene el compromiso registrado.
</div>';
    }
}
?>
<style>
    .mysubtitle {
        background: #23b7a2;
        color: #FFF;
        text-align: center;
        padding: 20px 0px;
        margin-bottom: 0px;
        font-weight: bold;
    }
    .mycontbody{
        border: 1px solid #23b7a2;padding: 10px;margin-bottom: 20px;border-radius: 0px 0px 15px 15px;
    }
</style>
<div class="row">
    <div class="col-md-6 text-left">
        <b>CERTIFICADOS</b>
        <br />
        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
        <br />
        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
        <br />
        <b>CERTIFICADO:</b> <?php echo $codigo_de_certificado_del_curso; ?>
        <br />
        <b>PARTICIPANTE:</b> <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
    </div>
    <div class="col-md-6 text-right">
        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
    </div>
</div>
<hr />
<?php echo $mensaje; ?>
<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.8;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;" onclick="nom_to_busc_aux_s2();" id="nom-nom_to_busc_aux_s2">
            <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
        <br />
        <br />
        <b style="font-size: 12pt;">
            <?php echo trim($participante['correo'] . ' - ' . $participante['celular']); ?>
        </b>
    </div>
</div>
<hr />

<!-- just in case DIV CONTENT AJAX :: EMITE CERTIFICADO P1 -->
<div id="ajaxloading-emite_certificado_p1"></div>
<div id="ajaxbox-emite_certificado_p1"></div>

<?php if ($sw_certificado_culminacion) { ?>
    <h4 class="mysubtitle">CERTIFICADO DE CULMINACI&Oacute;N</h4>
    <div class="mycontbody">
        <br>
        <?php
        $rqecdc1 = query("SELECT * FROM certificados_culminacion_emisiones WHERE id_certificado_culminacion='$id_certificado_culminacion' AND id_participante='$id_participante' ");
        if (num_rows($rqecdc1) == 0) {
        ?>
            <div class="alert alert-info">
                <strong>AVISO</strong><br>Este participante no tiene emitido el certificado culminaci&oacute;n.
            </div>
            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_cert_culminacion();" class="btn btn-md btn-default">EMITIR CERT. DE CULMINACI&Oacute;N</a>
        <?php
        } else {
            $rqecdc2 = fetch($rqecdc1);
            $id_emision = $rqecdc2['id'];
            $hash = md5(md5($id_emision . 'cce5616'));
        ?>
            <table class="table table-striped table-bordered">
                <tr>
                    <td><b>CERTIFICADO DIGITAL</b> - (CON FONDO)</td>
                    <td>
                        <b onclick="imprimir_cert_culminacion('<?php echo $id_emision; ?>','<?php echo $hash; ?>','digital');" class="btn btn-md btn-success">VISUALIZAR</b>
                    </td>
                </tr>
                <tr>
                    <td><b>ENVIAR CERTIFICADO</b> - (por correo)</td>
                    <td id="box-enviar_cert_culminacion_digital-<?php echo $id_emision; ?>">
                        <b onclick="enviar_certificado_culminacion('<?php echo $id_emision; ?>');" class="btn btn-md btn-primary">ENVIAR</b>
                    </td>
                </tr>
            </table>
        <?php
        }
        ?>
    </div>
    <hr>
<?php } ?>


<h4 class="mysubtitle">CERTIFICADOS GENERALES</h4>
<div class="mycontbody">
    <br>
    <?php
    if ($sw_certificados_adicionales || ($ids_certs_iniciales != '0,0,0' && $txt_verif_id_emision_certificado == -1)) {
    ?>
        <div>
            <div id="AJAXCONTENT-emitir_certificados_adicionales">
                <?php
                $ids_emisiones_adicionales = '';
                $rqvpca1 = query("SELECT id_emision_certificado FROM cursos_rel_partcertadicional WHERE id_participante='$id_participante' AND id_certificado IN (SELECT id_certificado FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso') ");
                $sw_certs_adicionales_emitidos = false;
                while ($rqvpca2 = fetch($rqvpca1)) {
                    $sw_certs_adicionales_emitidos = true;
                    $ids_emisiones_adicionales .= ',' . $rqvpca2['id_emision_certificado'];
                }
                if ($sw_certs_adicionales_emitidos) {
                ?>
                    <div class="text-center">
                        <b class="btn btn-warning" onclick="imprimir_certificados_digitales('<?php echo trim($ids_emisiones_adicionales, ','); ?>');"><i class="fa fa-print"></i> IMPRIMIR TODOS DIGITALES</b>
                        <br>
                        <br>
                        <b class="btn btn-default" onclick="imprimir_certificados_adicionales('<?php echo trim($ids_emisiones_adicionales, ','); ?>');"><i class="fa fa-print"></i> IMPRIMIR TODOS</b>
                        <br>
                        <br>
                        <b class="btn btn-success" onclick="enviar_emitidos_por_correo(0);"><i class="fa fa-send"></i> ENVIAR PDFs POR CORREO</b>
                        <br>
                        <br>
                        <b class="btn btn-success" onclick="enviar_emitidos_por_correo(1);"><i class="fa fa-send"></i> ENVIAR LINKs POR CORREO</b>
                        <div id="AJAXCONTENT-enviar_emitidos_por_correo"></div>
                        <br>
                        <br>
                        Enlaces de descarga: <b class="btn btn-default" onclick="copyToClipboard('cont-enlaces-descarga-cert');"><i class="fa fa-copy"></i></b> &nbsp;&nbsp;&nbsp; <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 35px;border-radius: 20%;cursor: pointer;border: 1px solid #d4d4d4;" onclick="enviar_enlaces_por_wap();">
                    </div>
                <?php
                } else {
                ?>
                    <b>Emitir todos simultaneamente:</b>
                    <br>
                    <b class="btn btn-block btn-success" onclick="emitir_certificados_adicionales();">EMITIR TODOS LOS CERTIFICADOS</b>
                <?php
                }
                ?>
                <br>
                <hr />
                <br>
                <?php
                $cont_enlaces_descarga = '*DESCARGA DE CERTIFICADOS: '.trim($participante['nombres'] . ' ' . $participante['apellidos']).'*<br><br>';
                $rqmc1 = query("SELECT * FROM cursos_certificados WHERE id IN ($ids_certs_iniciales) OR id IN (SELECT id_certificado FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC ) ");
                while ($rqdcrt2 = fetch($rqmc1)) {
                    $id_certificado_adicional = $rqdcrt2['id'];
                ?>
                    <b class="btn btn-xs btn-block btn-info active">CERTIFICADO <?php echo $rqdcrt2['codigo']; ?></b>
                    <table class="table table-bordered">
                        <tr>
                            <td>CERTIFICADO (Texto QR)</td>
                            <td><?php echo $rqdcrt2['texto_qr']; ?></td>
                        </tr>
                        <tr>
                            <td>FECHAS (QR)</td>
                            <td><?php echo $rqdcrt2['fecha_qr'] . ($rqdcrt2['fecha2_qr'] == '0000-00-00' ? '' : ' al ' . $rqdcrt2['fecha2_qr']); ?></td>
                        </tr>
                        <?php
                        $rqvpca1 = query("SELECT id_emision_certificado FROM cursos_rel_partcertadicional WHERE id_participante='$id_participante' AND id_certificado='$id_certificado_adicional' LIMIT 1 ");
                        if (num_rows($rqvpca1) > 0) {
                            $rqvpca2 = fetch($rqvpca1);
                            $id_emision_certificado = $rqvpca2['id_emision_certificado'];
                            $rqdc1 = query("SELECT certificado_id,texto_qr FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' LIMIT 1 ");
                            $rqdc2 = fetch($rqdc1);
                            $certificado_id = $rqdc2['certificado_id'];
                            $texto_qr = $rqdc2['texto_qr'];
                            $rqvec1 = query("SELECT sw_enviado FROM cursos_envio_certificados WHERE id_emision_certificado='$id_certificado_adicional' ");
                            $txt_estado_envio = "SIN ENVIO";
                            if (num_rows($rqvec1) > 0) {
                                $rqvec2 = fetch($rqvec1);
                                if ($rqvec2['sw_enviado'] == 1) {
                                    $txt_estado_envio = "<b style='color:#00cc33;'>ENVIADO</b>";
                                } else {
                                    $txt_estado_envio = "<b style='color:#006f93;'>A ENVIAR</b>";
                                }
                            }

                            $cont_enlaces_descarga .= '===========================<br>*'.$certificado_id.':* '.$dominio.'C/'.$certificado_id.'/<br>'.$texto_qr.'<br><br>';
                        ?>
                            <tr>
                                <td>ID de certificado</td>
                                <td><?php echo $certificado_id; ?></td>
                            </tr>
                            <tr>
                                <td>Certificado impreso</td>
                                <td><button class="btn btn-default btn-sm btn-block" onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado; ?>');"><i class="fa fa-eye"></i> CERTIFICADO IMPRESO</button></td>
                            </tr>
                            <tr>
                                <td>Certificado digital</td>
                                <td><button class="btn btn-default btn-sm btn-block" onclick="visualizar_certificado_digital('<?php echo $certificado_id; ?>');"><i class="fa fa-exchange"></i> CERTIFICADO DIGITAL</button></td>
                            </tr>
                            <?php

                            echo '<tr style="display:none;">';
                            echo '<td>Envio fisico de certificado</td>';
                            echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $id_emision_certificado . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                            echo '</tr>';

                            ?>
                            <tr>
                                <td>
                                    Envia el <b>certificado digital</b> en forma de PDF.
                                </td>
                                <td id="box-enviar_cert_digital-<?php echo $id_emision_certificado; ?>">
                                    <b class="btn btn-default btn-sm btn-block" onclick="enviar_cert_digital('<?php echo $id_emision_certificado; ?>');">
                                        <i class="fa fa-send"></i> &nbsp; ENVIAR POR CORREO
                                    </b>
                                </td>
                            </tr>
                        <?php

                            echo '<tr>';
                            echo '<td rowspan="2">Copia legalizada cert. principal</td>';
                            echo '<td><button class="btn btn-default btn-sm btn-block" onclick="imprimir_copia_legalizada(\'' . $id_emision_certificado . '\',\'reverso\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA (reverso)</button></td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td><button class="btn btn-default btn-sm btn-block" onclick="imprimir_copia_legalizada(\'' . $id_emision_certificado . '\',\'anverso\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA (anverso)</button></td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>Edici&oacute;n individual</td>';
                            echo '<td><button class="btn btn-default btn-sm btn-block" onclick="edita_certificado_individual(\'' . $id_emision_certificado . '\');"><i class="fa fa-edit"></i> EDITAR CERTIFICADO</button></td>';
                            echo '</tr>';
                        } else {
                        ?>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <a onclick="emite_certificado_p1(<?php echo $id_participante; ?>, <?php echo $id_certificado_adicional; ?>);" class="btn btn-sm btn-warning">EMITIR CERTIFICADO</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <br>
                    <br>
                <?php
                }
                ?>
                <div id="cont-enlaces-descarga-cert" style="display: none;"><?php echo $cont_enlaces_descarga; ?></div>
                
                <b class="btn btn-xs btn-block btn-warning active">LOG DE IMPRESIONES/VISUALIZACIONES</b>
                <table class="table table-bordered">
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Modo</th>
                        <th>N&uacute;mero</th>
                        <th>Costo</th>
                        <th>Administrador</th>
                        <th>Fecha</th>
                    </tr>
                    <?php
                    $rqliv1 = query("SELECT e.certificado_id,a.nombre,l.modo,l.costo,l.num_cert_fisico,l.fecha FROM certsgenimp_log l INNER JOIN cursos_emisiones_certificados e ON l.id_emision_certificado=e.id LEFT JOIN administradores a ON l.id_administrador=a.id WHERE e.id_participante='$id_participante' ORDER BY l.id DESC ");
                    $cnt_impvg = num_rows($rqliv1);
                    while($rqliv2 = fetch($rqliv1)){
                    ?>
                    <tr>
                        <td><?php echo $cnt_impvg--; ?></td>
                        <td><?php echo $rqliv2['certificado_id']; ?></td>
                        <td><?php echo $rqliv2['modo']; ?></td>
                        <td><?php echo $rqliv2['num_cert_fisico']==''?'<span style="color:#DDD;font-size:8pt;">Sin n&uacute;mero</span>':$rqliv2['num_cert_fisico']; ?></td>
                        <td><?php echo $rqliv2['costo']; ?> Bs</td>
                        <td><?php echo $rqliv2['nombre']==''?'<span style="color:#DDD;font-size:8pt;">Sin administrador</span>':$rqliv2['nombre']; ?></td>
                        <td><?php echo date("d/m/Y H:i:s",strtotime($rqliv2['fecha'])); ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    <?php
    } elseif ($txt_verif_id_emision_certificado == 0) {
    ?>

        <input type="hidden" id="id_certificado-<?php echo $participante['id']; ?>" value="<?php echo $id_certificado_curso; ?>" />
        <input type="hidden" id="id_curso-<?php echo $participante['id']; ?>" value="<?php echo $id_curso; ?>" />
        <input type="hidden" id="id_participante-<?php echo $participante['id']; ?>" value="<?php echo $participante['id']; ?>" />

        <!-- DIV CONTENT AJAX :: EMITE CERTIFICADO P2 -->
        <div id="ajaxloading-emite_certificado_p2"></div>
        <div class="text-center" id='ajaxbox-emite_certificado_p2'>
            <h5 class="text-center">
                Emision de certificado para
            </h5>
            <div class="row">
                <div class="col-md-12 text-left">
                    <input type="text" class="form-control text-center" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>" readonly="" />
                    <input type="hidden" id="receptor_de_certificado-<?php echo $participante['id']; ?>" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>" />
                </div>
            </div>
            <?php
            echo "<input type='hidden' id='cont_tres' value=''/>";
            echo "<input type='hidden' id='fecha_qr' value=''/>";
            echo "<input type='hidden' id='cont_dos' value=''/>";
            echo "<input type='hidden' id='texto_qr' value=''/>";
            ?>
            <br />
            <br />

            <button class="btn btn-success" onclick="emite_certificado_p2('<?php echo $participante['id']; ?>',<?php echo (int) $nro_certificado; ?>);">EMITIR CERTIFICADO</button>
            &nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
        </div>
        <hr />

    <?php
    } else {
        echo '<div class="alert alert-info">
    <strong>AVISO</strong> este curso no tiene certificados asociados.
    </div>';
    }
    ?>
</div>

<h4 class="mysubtitle">COMPROMISO DE CULMINACI&Oacute;N</h4>
<div class="mycontbody">
    <br>
    <?php
    if ($participante['id_usuario'] != '0') {
        $rqdcc1 = query("SELECT * FROM compromisos_finalizacion WHERE id_participante='$id_participante' ");
        if (num_rows($rqdcc1) == 0) {
    ?>
            <div class="alert alert-warning">
                No se tiene registro de compromiso de culminaci&oacute;n
            </div>
            <b class="btn btn-xs btn-default" onclick="compromiso_culminacion();"><i class="fa fa-plus"></i> REGISTRO DE COMPROMISO</b>
        <?php
        } else {
            $rqdcc2 = fetch($rqdcc1);
            $id_compromiso = $rqdcc2['id'];
            $documento_firmado = $rqdcc2['archivo'];
        ?>
            <table class="table table-striped table-bordered">
                <tr>
                    <td><b>PDF compromiso:</b></td>
                    <td><a href='<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/compromiso-finalizacion.php?id_compromiso=<?php echo $id_compromiso; ?>&hash=<?php echo md5(md5($id_compromiso."0012151")); ?>' target="_blank">visualizar archivo</a></td>
                </tr>
                <tr>
                    <td><b>PDF firmado:</b></td>
                    <td>
                        <?php
                        if($documento_firmado==''){
                            /* usuario */
                            $rqddus1 = query("SELECT u.email,u.password FROM cursos_usuarios u WHERE u.id='".$participante['id_usuario']."' ORDER BY id DESC limit 1 ");
                            $rqddus2 = fetch($rqddus1);
                            $nick_usuario = $rqddus2['email'];
                            $password_usuario = $rqddus2['password'];
                            $texto_wap = 'Hola '.$participante['nombres'].' '.$participante['apellidos'].'__Acabamos de generar un *documento compromiso* para terminar el curso:__ __*'.$nombre_del_curso.'*__ __El cual debes descargarlo, imprimirlo, firmarlo y luego subir una foto/escaneo del documento a nuestra plataforma en la seccion DOCUMENTOS.__ __*Url de la plataforma:* '.$dominio_plataforma.'__*Usuario:* '.$nick_usuario.'__*Contraseña:* '.$password_usuario;
                            ?>
                            El participante a&uacute;n no subio el documento firmado
                            <br>
                            <br>
                            Mensaje de notificaci&oacute;n de compromiso: 
                            <a href="https://api.whatsapp.com/send?phone=591<?php echo $participante['id_usuario']; ?>&text=<?php echo str_replace('__','%0A',str_replace(' ','%20',$texto_wap)); ?>" target="_blank">
                                <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 40px;border-radius: 20%;curor:pointer;">
                            </a>
                            <?php
                        }else{
                            echo "<a href='".$dominio."contenido/archivos/documentos/".$documento_firmado."' target='_blank'>visualizar archivo</a>";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        <?php
        }
    } else {
        ?>
        <div class="alert alert-danger">
            El participante no tiene ningun curso activado y no tiene cuenta de usuario.
        </div>
    <?php
    }
    ?>
</div>

<!--enviar_cert_digital-->
<script>
    function enviar_cert_digital(id_emision_certificado) {
        if (confirm('Desea enviar el certificado por correo ?')) {
            $("#box-enviar_cert_digital-" + id_emision_certificado).html('Procesando...');
            $.ajax({
                url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_cert_digital.php',
                data: {
                    id_emision_certificado: id_emision_certificado
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#box-enviar_cert_digital-" + id_emision_certificado).html(data);
                }
            });
        }
    }
</script>

<!--enviar_certificado_culminacion-->
<script>
    function enviar_certificado_culminacion(id_emision_certificado) {
        if (confirm('Desea enviar el certificado por correo ?')) {
            $("#box-enviar_cert_culminacion_digital-" + id_emision_certificado).html('Procesando...');
            $.ajax({
                url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_cert_digital.php',
                data: {
                    id_emision_certificado: id_emision_certificado,
                    keyaccess: '5rw4t6gd1',
                    id_administrador: '<?php echo administrador('id'); ?>'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#box-enviar_cert_culminacion_digital-" + id_emision_certificado).html(data);
                }
            });

        }
    }
</script>


<script>
    function emitir_certificados_adicionales() {
        if (confirm('Desea emitir todos los certificados adicionales ?')) {
            $("#AJAXCONTENT-emitir_certificados_adicionales").html('Procesando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.emitir_certificados_adicionales.php',
                data: {
                    id_participante: '<?php echo $id_participante; ?>'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-emitir_certificados_adicionales").html(data);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });
        }
    }
</script>

<!-- enviar_emitidos_por_correo -->
<script>
    function enviar_emitidos_por_correo(solo_links) {
        if (confirm('Desea enviar todos los certificados emitidos por correo ? (COMO CERTIFICADOS DIGITALES)')) {
            $("#AJAXCONTENT-enviar_emitidos_por_correo").html('Procesando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.enviar_emitidos_por_correo.php',
                data: {
                    id_participante: '<?php echo $id_participante; ?>',
                    solo_links: solo_links
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-enviar_emitidos_por_correo").html(data);
                    lista_participantes(VAR_id_curso, 0);
                }
            });
        }
    }
</script>


<!-- ajax imprimir_certificados_adicionales -->
<script>
    function imprimir_certificados_adicionales(ids_emisiones) {
        if(confirm('DESEA VISUALIZAR LOS CERTIFICADOS ?')){
            $("#AJAXCONTENT-modgeneral").html('Procesando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
                data: {
                    ids_emisiones: ids_emisiones,
                    modimp: 'todos-imp-fisico'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>

<!-- ajax imprimir_certificados_digitales -->
<script>
    function imprimir_certificados_digitales(ids_emisiones) {
        if(confirm('DESEA VISUALIZAR LOS CERTIFICADOS ?')){
            window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-digital-3-masivo.php?ids_emisiones='+ids_emisiones+'&id_administrador=<?php echo $id_administrador; ?>&hash=<?php echo md5($id_administrador . 'hash'); ?>', 'popup', 'width=700,height=500');
        }
    }
</script>

<!-- ajax imprimir_certificado_individual -->
<script>
    function imprimir_certificado_individual(dat) {
        if(confirm('DESEA VISUALIZAR EL CERTIFICADO ?')){
            $("#AJAXCONTENT-modgeneral").html('Procesando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
                data: {
                    ids_emisiones: dat,
                    modimp: 'imp-fisico'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>

<!-- ajax visualizar_certificado_digital -->
<script>
    function visualizar_certificado_digital(dat) {
        if(confirm('DESEA VISUALIZAR EL CERTIFICADO ?')){
            window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado='+dat+'&id_administrador=<?php echo $id_administrador; ?>&hash=<?php echo md5($id_administrador . 'hash'); ?>', 'popup', 'width=700,height=500');
        }
    }
</script>


<!-- emite_cert_culminacion -->
<script>
    function emite_cert_culminacion() {
        $("#AJAXCONTENT-enviar_emitidos_por_correo").html('Procesando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.enviar_emitidos_por_correo.php',
            data: {
                id_participante: '<?php echo $id_participante; ?>'
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-enviar_emitidos_por_correo").html(data);
            }
        });
    }

    function emite_cert_culminacion() {
        $("#ajaxloading-emite_certificado_p1").html('Cargando...');
        $("#ajaxbox-emite_certificado_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
            data: {
                id_participante: '<?php echo $id_participante; ?>',
                nro_certificado: 0,
                emite_cert_culminacion: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_certificado_p1").html("");
                $("#ajaxbox-emite_certificado_p1").html(data);
            }
        });
    }
</script>

<!-- ajax imprimir_cert_culminacion -->
<script>
    function imprimir_cert_culminacion(id_emision, hash, mod) {
        var file_impresion;
        if (mod === 'digital') {
            file_impresion = 'certificado-culminacion-ipelc-digital.php';
        } else {
            file_impresion = 'certificado-culminacion-ipelc.php';
        }
        window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/' + file_impresion + '?id_emision=' + id_emision + '&hash=' + hash, 'popup', 'width=700,height=500');
    }
</script>

<!-- edita_certificado_individual -->
<script>
    function edita_certificado_individual(id_emision_certificado) {
        $("#TITLE-modgeneral").html('EDICI&Oacute;N DE CERTIFICADO INDIVIDUAL');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.edita_certificado_individual.php',
            data: {
                id_emision_certificado: id_emision_certificado
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<script>
    function compromiso_culminacion() {
        if (confirm('DESEA ASIGNAR UN COMPROMISO DE FINALIZACION A ESTE PARTICIPANTE ?')) {
            $("#AJAXCONTENT-modgeneral").html("Cargando...");
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
                data: {
                    id_participante: '<?php echo $id_participante; ?>',
                    nro_certificado: 0,
                    sw_registra_compromiso_culminacion: 1
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>


<script>
function enviar_enlaces_por_wap(){
    var cont = $("#cont-enlaces-descarga-cert").html();
    window.open('https://api.whatsapp.com/send?phone=591<?php echo $participante['celular']; ?>&text='+cont.replace(/<br>/g, '%0A').replace(/ /g, '%20'),'blank');
}
</script>


<script>
function nom_to_busc_aux_s2(){
    var cont_a = $("#nom-nom_to_busc_aux_s2").html();
    $("#input-busca-participante").val(cont_a.trim());
}
</script>


