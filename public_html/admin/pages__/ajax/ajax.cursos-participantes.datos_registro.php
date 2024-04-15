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

/* datos recibidos */
$id_participante = post('id_participante');

/* datos de participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($resultado1);
$id_usuario = $participante['id_usuario'];

/* datos de registro */
$rqrp1 = query("SELECT id,codigo,fecha_registro,sw_pago_enviado,celular_contacto,correo_contacto,id_modo_pago,id_emision_factura,monto_deposito,transaccion_id,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);
$id_proceso_de_registro = $data_registro['id'];
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_emision_factura = $data_registro['id_emision_factura'];
$sw_pago_enviado = $data_registro['sw_pago_enviado'];

$id_modo_pago = $data_registro['id_modo_pago'];
$monto_de_pago = $data_registro['monto_deposito'];
$transaccion_id = $data_registro['transaccion_id'];
$imagen_de_deposito = $data_registro['imagen_deposito'];

$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];

$id_administrador_registro = (int) $data_registro['id_administrador'];


/* datos de curso */
$id_curso = $participante['id_curso'];
$rqc1 = query("SELECT estado,sw_ipelc,titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,id_modalidad FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$estado_curso = $rqc2['estado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$sw_ipelc = $rqc2['sw_ipelc'];
$url_imagen_del_curso = $dominio_www.'paginas/' . $rqc2['imagen'] . '.size=4.img';

$costo_curso = $rqc2['costo'];
$id_modalidad_curso = $rqc2['id_modalidad'];
?>

<div class="row">
    <div class="col-md-6 text-left">
        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
        <br/>
        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
        <br/>
        <b>REGISTRO:</b> &nbsp; <?php echo $codigo_de_registro; ?>
        <br/>
        <b>PARTICIPANTE:</b> &nbsp; <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        <br/>
        <b>C.I.:</b> &nbsp; <?php echo $participante['ci']; ?>
        <br/>
        <b>EMAIL:</b> &nbsp; <?php echo $participante['correo']; ?>
        <br/>
        <b>CELULAR:</b> &nbsp; <?php echo $participante['celular']; ?>
        <br/>
        <b>OBSERVACI&Oacute;N:</b> &nbsp; <?php echo $participante['observacion']; ?>
        <hr/>
    </div>
    <div class="col-md-6 text-right">
        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
    </div>
</div>
<div class="row">
    <div class="col-md-4 text-center">
        <b class="btn btn-info btn-block active" onclick="window.open('<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_de_registro . ''); ?>.impresion', 'popup', 'width=700,height=500');">
            <i class="fa fa-file"></i> &nbsp; FICHA (Imp.)
        </b>
    </div>
    <div class="col-md-4 text-center">
        <a class="btn btn-info btn-block active" href="<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_de_registro . '/pdf'); ?>.impresion">
            <i class="fa fa-file"></i> &nbsp; FICHA (PDF)
        </a>
    </div>
    <div class="col-md-4 text-center">
        <b class="btn btn-warning btn-block active" onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/recibo-participante-1.php?id_participante=<?php echo $id_participante; ?>', 'popup', 'width=700,height=500');">
            <i class="fa fa-file"></i> &nbsp; RECIBO
        </b>
    </div>
</div>
<hr/>
<div class="text-center">
    <?php 
    $url_descarga = 'https://cursos.bo/FR/' . $id_proceso_de_registro . '/';
    $text_wap = 'Hola '.trim($participante['nombres'] . ' ' . $participante['apellidos']).'__Puedes descargar tu ficha de registro con el siguiente enlace:__'.$url_descarga;
    ?>
    <a href="https://api.whatsapp.com/send?phone=591<?php echo $participante['celular']; ?>&text=<?php echo str_replace('__','%0A',str_replace(' ','%20',$text_wap)); ?>" target="_blank"><img src="https://www.cursos.bo/contenido/imagenes/wapicons/wap-init-0.jpg" style="    height: 45px;border: 1px solid #76aede;border-radius: 20%;cursor: pointer;"></a>
    &nbsp;
    <a href="<?php echo $url_descarga; ?>" target="_blank" style="font-size: 17pt;text-decoration: underline;"><?php echo $url_descarga; ?></a>
</div>
<hr/>
<?php
if ($estado_curso == '1' || $estado_curso == '2') {
    ?>
    <div class="row">
        <div class="col-md-8 text-left">
            <input type="email" id="sendficha_correo" value="<?php echo $participante['correo']; ?>" class="form-control" placeholder="Correo a enviar..."/>
            <input type="hidden" id="sendficha_id_proceso_registro" value="<?php echo $id_proceso_de_registro; ?>"/>
        </div>
        <div class="col-md-4 text-right" id="box-sendficha">
            <b class="btn btn-default btn-block" onclick="enviar_ficharegistro();">
                <i class="fa fa-send"></i> &nbsp; ENVIAR FICHA
            </b>
        </div>
    </div>
    <?php
}
?>
<?php
if ((int) $monto_de_pago == 0 || true) {
    ?>
    <div class="row">
        <div class="col-md-8 text-left">
            <input type="email" id="solicitar_pago_correo" value="<?php echo $participante['correo']; ?>" class="form-control" placeholder="Correo a enviar..."/>
            <input type="hidden" id="solicitar_pago_id_proceso_registro" value="<?php echo $id_proceso_de_registro; ?>"/>
        </div>
        <div class="col-md-4 text-right" id="box-solicitar_pago">
            <b class="btn btn-default btn-block" onclick="solicitar_pago();">
                <i class="fa fa-money"></i> &nbsp; SOLICITAR PAGO
            </b>
        </div>
    </div>
    <?php
}
?>
<hr/>
<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;">
            <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-12 text-left">
        <b>Fecha de registro:</b> &nbsp; <?php echo $fecha_de_registro; ?>
        <br/>
        <!--                                                <b>CELULAR CONTACTO:</b> &nbsp; <?php echo $celular_de_registro; ?>
                                                        <br/>
                                                        <b>CORREO CONTACTO:</b> &nbsp; <?php echo $correo_de_registro; ?>
                                                        <br/>-->
        <b>Registro:</b> &nbsp; <?php echo $codigo_de_registro; ?>
        <br/>
        <?php
        if ($id_administrador_registro <> 0) {
            $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador_registro' ");
            $rqda2 = fetch($rqda1);
            ?>
            <b>Administrador:</b> &nbsp; <?php echo $rqda2['nombre']; ?>
            <br/>
            <?php
        }
        ?>
        <b>Nro. de participantes:</b> &nbsp; <?php echo $nro_participantes_de_registro; ?>
        <br/>
        <?php
        if ($id_modo_pago == '0') {
            ?>
            <b>Metodo de pago:</b> &nbsp; Sin Pago
            <br/>
            <b>Monto pagado:</b> &nbsp; <?php echo (int) $monto_de_pago; ?>
            <br/>
            <?php
        } elseif ($id_modo_pago == '3') {
            ?>
            <b>Metodo de pago:</b> &nbsp; DEPOSITO BANCARIO
            <br/>
            <?php
            if ($sw_pago_enviado == '0') {
                echo "PAGO NO ENVIADO";
                echo "<br/>";
            } else {
                ?>
                <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                <br/>
                <b>ID transacci&oacute;n:</b> &nbsp; <?php echo $transaccion_id; ?>
                <?php
            }
        } elseif ($id_modo_pago == '6') {
            $id_cobro_khipu = $data_registro['id_cobro_khipu'];
            $rqck1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
            $rqck2 = fetch($rqck1);
            $enlace_khipu = "https://khipu.com/payment/info/" . $rqck2['payment_id'];
            ?>
            <b>Metodo de pago:</b> &nbsp; KHIPU
            <br/>
            <b>Monto ingresado:</b> &nbsp; <?php echo $monto_de_pago; ?>
            <br/>
            <b>Enlace de transferencia:</b> &nbsp; <a href='<?php echo $enlace_khipu; ?>' target='_blank'><?php echo $enlace_khipu; ?></a>
            <br/>
            <?php
            if ($rqck2['estado'] == '0') {
                ?>
                <b>Estado del pago:</b> &nbsp; <b style='color:red;'>TRANSFERENCIA NO REALIZADA</b>
                <?php
            } elseif ($rqck2['estado'] == '1') {
                ?>
                <b>Estado del pago:</b> &nbsp; <b style='color:green;'>TRANSFERENCIA EXITOSA</b>
                <?php
            } else {
                ?>
                <b>Estado del pago:</b> &nbsp; <b style='color:red;'>DATOS NO ENCONTRADOS</b>
                <?php
            }
            ?>
            <?php
        } else {
            ?>
            <b>Metodo de pago:</b> &nbsp; PAGO EN OFICINA
            <br/>
            <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
            <?php
        }
        ?>
        <hr/>
        <?php
        if($sw_ipelc=='1'){
            ?>
            <b class="btn btn-xs btn-block btn-primary active">ENV&Iacute;O CERTIFICACI&Oacute;N IPELC</b>
            <br>
            <?php
            $codigo_doc = 'pago-envio';
            $vrs1 = query("SELECT id,nombre FROM documentos_usuario WHERE id_usuario='$id_usuario' AND codigo='$codigo_doc' ORDER BY id DESC limit 1 ");
                if (num_rows($vrs1)>0) {
                    echo '<div class="alert alert-success">
  <strong>SOLICITUD DE ENVIO REALIZADA</strong>
  <br>El participante envi&oacute; el comprobante de pago.
</div>
';
                    $vrs2 = fetch($vrs1);
                    $url_imagen = $dominio_wwww.'contenido/imagenes/doc-usuarios/'.$vrs2['nombre'];
                    $vrscd1 = query("SELECT d.nombre FROM cursos_envio_certificados e INNER JOIN departamentos d ON e.id_departamento=d.id WHERE e.id_usuario='$id_usuario' ORDER BY e.id DESC limit 1 ");
                    $vrscd2 = fetch($vrscd1);
                    $nombre_ciudad = $vrscd2['nombre'];
                    echo "<table class='table table-bordered table-striped'>";
                    echo "<tr>";
                    echo "<td><b>Ciudad:</b></td>";
                    echo "<td>" . $nombre_ciudad . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td><b>Comprobante:</b></td>";
                    echo "<td><a href='$url_imagen' target='_blank' style='text-decoration:underline;color:blue'>VER IMAGEN ENVIADA</a></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td colspan='2'><img src='$url_imagen' style='width:100%;'/></td>";
                    echo "</tr>";
                    echo "</table>";
                }else{
                    echo '<div class="alert alert-info">
  <strong>AVISO</strong>
  <br>El participante no realiz&oacute; la solicitud de env&iacute;o.
</div>
';
                }
            
        }
        ?>

        <hr/>
        <div class="text-center">
            <b class="btn btn-xs btn-block btn-primary active">IMAGEN RESPALDO DE PAGO</b>
            <?php
            if ($imagen_de_deposito == '') {
                echo "<br/>SIN IMAGEN RESPALDO REGISTRADO";
            } else {
                $dir_img_deposito = $___path_raiz.'contenido/imagenes/depositos/' . $imagen_de_deposito;
                $aux_url_domain = '';
                if (!file_exists($dir_img_deposito)) {
                    $aux_url_domain = 'https://www.infosiscon.com/';
                    echo "<i>Registro efectuado desde:</i> <b style='color:#1b6596;'>INFOSICOES</b>";
                    echo "<br/>";
                }
                ?>
                <br/>
                <a href="<?php echo $dominio_www; ?>depositos/<?php echo $imagen_de_deposito; ?>.img" target="_blank"><?php echo $imagen_de_deposito; ?></a>
                <br/>
                <br/>
                <img src="<?php echo $dominio_www; ?>depositos/<?php echo $imagen_de_deposito; ?>.size=4.img" style="width:80%;border:1px solid #DDD;padding:1px;">
                <?php
            }
            ?>
        </div>
        <hr/>
        
        <?php if(false){ ?>
        <div>
            <b class="btn btn-xs btn-block btn-primary active">CERTIFICADOS</b>
            <table class="table table-striped table-bordered">
                <tr>
                    <th colspan="2">
                        <b>PRIMER CERTIFICADO</b>
                    </th>
                </tr>
                <?php
                if ($participante['id_emision_certificado'] !== '0') {
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado'] . "' ");
                    $rqdc2 = fetch($rqdc1);

                    $rqvec1 = query("SELECT sw_enviado FROM cursos_envio_certificados WHERE id_emision_certificado='" . $participante['id_emision_certificado'] . "' ");
                    $txt_estado_envio = "SIN ENVIO";
                    if (num_rows($rqvec1) > 0) {
                        $rqvec2 = fetch($rqvec1);
                        if ($rqvec2['sw_enviado'] == 1) {
                            $txt_estado_envio = "<b style='color:#00cc33;'>ENVIADO</b>";
                        } else {
                            $txt_estado_envio = "<b style='color:#006f93;'>A ENVIAR</b>";
                        }
                    }

                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado'] . '\');">MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Edici&oacute;n</td>';
                        echo '<td><button class="btn btn-warning btn-xs btn-block" onclick="edita_certificado_individual(\'' . $participante['id_emision_certificado'] . '\');" data-toggle="modal" data-target="#MODAL-edita_certificado_individual">EDITAR CERTIFICADO</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado'] . '\');">COPIA LEGALIZADA</button></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Certificado digital</td>';
                    echo '<td><button class="btn btn-info btn-xs btn-block" onclick="visualizar_certificado_digital(\'' . $participante['id_emision_certificado'] . '\');">CERTIFICADO DIGITAL</button></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Envio fisico de certificado</td>';
                    echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $participante['id_emision_certificado'] . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                    echo '</tr>';
                    ?>
                    <tr>
                        <td>
                            <input type="email" id="enviar_cert_digital_correo" value="<?php echo $participante['correo']; ?>" class="form-control" placeholder="Correo a enviar..."/>
                            <input type="hidden" id="enviar_cert_digital_id_emision_certificado" value="<?php echo $participante['id_emision_certificado']; ?>"/>
                        </td>
                        <td id="box-enviar_cert_digital">
                            <b class="btn btn-default btn-xs btn-block" onclick="enviar_cert_digital();">
                                <i class="fa fa-send"></i> &nbsp; ENVIAR CERT. DIGITAL
                            </b>
                        </td>
                    </tr>
                    <?php
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene primer certificado</td>';
                    echo '</tr>';
                }
                ?>



            </table>
            <table class="table table-striped table-bordered">
                <tr>
                    <th colspan="2">
                        <b>SEGUNDO CERTIFICADO</b>
                    </th>
                </tr>
                <?php
                if ($participante['id_emision_certificado_2'] !== '0') {
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_2'] . "' ");
                    $rqdc2 = fetch($rqdc1);

                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado_2'] . '\');">MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado_2'] . '\');">COPIA LEGALIZADA</button></td>';
                    echo '</tr>';
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene segundo certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <hr/>
        <?php } ?>
        
        <div>
            <b class="btn btn-xs btn-block btn-primary active">REPROGRAMACI&Oacute;N</b>
            <table class="table table-striped table-bordered">
                <tr>
                    <th colspan="2">
                        <b>REPROGRAMACI&Oacute;N DE CURSO</b>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>El proceso de reprogramaci&oacute;n asigna un compromiso de futura asistencia al curso.</p>
                    </td>
                </tr>
                <?php
                $rqvrc1 = query("SELECT codigo FROM cursos_reprogramacion_participantes WHERE id_participante='$id_participante' LIMIT 1 ");
                if (num_rows($rqvrc1) == 0) {
                    if ($estado_curso == '1' || $estado_curso == '2') {
                        ?>
                        <tr id="TR-AJAXBOX-reprogramacion_de_curso">
                            <td>Reprogramaci&oacute;n</td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm active" onclick="reprogramacion_de_curso('<?php echo $participante['id']; ?>');">INICIAR PROCESO</button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    $rqvrc2 = fetch($rqvrc1);
                    ?>
                    <tr>
                        <td>Reprogramaci&oacute;n</td>
                        <td class="text-center">
                            <b><?php echo $rqvrc2['codigo']; ?></b>
                        </td>
                    </tr>
                    <?php
                }
                ?>

            </table>
        </div>
        <hr/>

        
        
        
        


    </div>
</div>

<hr/>


<!-- envio de ficha de registro -->
<script>
    function enviar_ficharegistro() {
        var sendficha_correo = $("#sendficha_correo").val();
        var sendficha_id_proceso_registro = $("#sendficha_id_proceso_registro").val();
        $("#box-sendficha").html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_ficharegistro.php',
            data: {sendficha_correo: sendficha_correo, sendficha_id_proceso_registro: sendficha_id_proceso_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-sendficha").html(data);
            }
        });
    }
</script>

<!-- solicitar_pago -->
<script>
    function solicitar_pago() {
        var correo = $("#solicitar_pago_correo").val();
        var id_proceso_registro = $("#solicitar_pago_id_proceso_registro").val();
        $("#box-solicitar_pago").html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.solicitar_pago.php',
            data: {correo: correo, id_proceso_registro: id_proceso_registro},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-solicitar_pago").html(data);
            }
        });
    }
</script>

<!-- enviar_cert_digital -->
<script>
    function enviar_cert_digital() {
        var correo = $("#enviar_cert_digital_correo").val();
        var id_emision_certificado = $("#enviar_cert_digital_id_emision_certificado").val();
        $("#box-enviar_cert_digital").html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_cert_digital.php',
            data: {correo: correo, id_emision_certificado: id_emision_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-enviar_cert_digital").html(data);
            }
        });
    }
</script>



