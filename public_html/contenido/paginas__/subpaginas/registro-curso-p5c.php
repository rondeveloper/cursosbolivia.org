<?php
/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* envio de correo de inscripcion */
$sw_envio_correo_inscripcion = false;

/* update reporte de pago */
$sw_update_reportepago = false;
if (isset($get[4]) && ($get[4] == 'update')) {
    $sw_update_reportepago = true;
}

$mensaje = '';

if (isset_post('id_curso')) {

    /* datos del curso */
    $id_curso = post('id_curso');
    $cnt_participantes = post('nro_participantes');
    $id_turno = post('id_turno');
    $correo_proceso_registro = post('correo_proceso_registro');
    $celular_proceso_registro = post('celular_proceso_registro');
    $nombre_institucion = post('nombre_institucion');
    $telefono_institucion = post('telefono_institucion');
    $razon_social = post('razon_social');
    $nit = post('nit');

    $id_departamento_envio = post('id_departamento_envio');
    $direccion_envio = post('direccion_envio');
    $destinatario_envio = post('destinatario_envio');
    $celular_envio = post('celular_envio');
    $id_cur_free = (int)post('id_cur_free');

    $rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY estado DESC,id DESC limit 1 ");
    $curso = fetch($rq1);


    /* INSCRIPCION */
    $fecha_registro = date("Y-m-d H:i:s");

    $titulo_curso = $curso['titulo'];
    $url_curso = $curso['titulo_identificador'];
    $fecha_curso = $curso['fecha'];
    $ciudad_curso = $curso['ciudad'];
    $lugar_curso = $curso['lugar'];
    $horario_curso = $curso['horarios'];
    $cod_reg = $id_curso . '-' . date("ymdh");
    $sw_pago_enviado = '0';

    $nombres = post('nombres_p1');
    $apellidos = post('apellidos_p1');
    $prefijo = post('prefijo_p1');
    $correo = post('correo_p1');
    $ci = post('ci_p1');
    $ci_expedido = post('ci_expedido_p1');
    $tel_cel = post('tel_cel_p1');
    $id_dep = post('id_dep_p1');

    /* proceso registro */
    $hashcod_registro = substr(md5(rand(0, 99999)), 4, 7);
    $rqveprr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ");
    if (num_rows($rqveprr1) == 0) {
        query("INSERT INTO cursos_proceso_registro(id_curso, fecha_registro, id_modo_pago, cod_reg, razon_social, nit, cnt_participantes, correo_contacto, celular_contacto,imagen_deposito,monto_deposito,id_turno,estado,sw_pago_enviado,hash_cod) VALUES ('$id_curso','$fecha_registro','0','$cod_reg','$razon_social','$nit','$cnt_participantes','$correo_proceso_registro','$celular_proceso_registro','','$monto_deposito','$id_turno','1','$sw_pago_enviado','$hashcod_registro')");
        $id_proceso_registro = insert_id();
        /* envio de correo de inscripcion */
        $sw_envio_correo_inscripcion = true;
    } else {
        $rqveprr2 = fetch($rqveprr1);
        $id_proceso_registro = $rqveprr2['id_proceso_registro'];
    }

    $codigo_de_registro = "R00$id_proceso_registro";
    query("UPDATE cursos_proceso_registro SET codigo='$codigo_de_registro' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    /* direcccion envio */
    if (strlen($direccion_envio) > 5) {
        query("INSERT INTO cursos_proceso_registro_direnvio(id_proceso_registro, id_departamento, direccion, destinatario, celular) VALUES ('$id_proceso_registro','$id_departamento_envio','$direccion_envio','$destinatario_envio','$celular_envio')");
    }

    /* aux ids_participantes_curso_gratuito */
    $ids_participantes_curso_gratuito = '0';

    $rqvp1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$id_curso' AND nombres LIKE '$nombres' AND apellidos LIKE '$apellidos' AND id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    if (num_rows($rqvp1) == 0) {

        /* numeracion */
        $rqln1 = query("SELECT numeracion FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ORDER BY numeracion DESC limit 1 ");
        $rqln2 = fetch($rqln1);
        $numeracion = ((int) $rqln2['numeracion']) + 1;

        query("INSERT INTO cursos_participantes (
                   id_curso,
                   id_proceso_registro,
                   id_departamento,
                   id_turno,
                   numeracion,
                   sw_pago,
                   nombres,
                   apellidos,
                   prefijo,
                   correo,
                   ci,
                   ci_expedido,
                   celular,
                   institucion,
                   tel_institucion
                   ) VALUES (
                   '$id_curso',
                   '$id_proceso_registro',
                   '$id_dep',
                   '$id_turno',
                   '$numeracion',
                   '$sw_pago_enviado',
                   '$nombres',
                   '$apellidos',
                   '$prefijo',
                   '$correo',
                   '$ci',
                   '$ci_expedido',
                   '$tel_cel',
                   '$nombre_institucion',
                   '$telefono_institucion'
                   ) ");
        $id_participante = insert_id();
        logcursos('Registro de participante [' . $codigo_de_registro . '][SIST-REG]', 'participante-registro', 'participante', $id_participante);
        $ids_participantes_curso_gratuito = ',' . $id_participante;

        /* codigo de descuento */
        if (isset_post('cod_descuento')) {
            $cod_descuento = post('cod_descuento');
            $rqddcv1 = query("SELECT id,limite_usos,id_curso FROM codigos_descuento WHERE codigo='$cod_descuento' AND fecha_expiracion>=CURDATE() ORDER BY id DESC limit 1 ");
            if (num_rows($rqddcv1)>0) {
                $rqddcv2 = fetch($rqddcv1);
                $id_codigo_descuento = $rqddcv2['id'];
                $limite_usos = $rqddcv2['limite_usos'];
                $c_desc_id_curso = $rqddcv2['id_curso'];
                if($c_desc_id_curso=='0' || $c_desc_id_curso==$id_curso){
                    $rqddcv1 = query("SELECT COUNT(*) AS total FROM codigos_descuento_usos WHERE id_codigo_descuento='$id_codigo_descuento' ");
                    $rqddcv2 = fetch($rqddcv1);
                    if ((int)$rqddcv2['total']>=(int)$limite_usos) {
                        $mensaje .= '<div class="alert alert-waring">
      <strong>AVISO</strong> el c&oacute;digo de descuento '.$cod_descuento.' ya alcanzo el limite de usos.
    </div>';
                    }else{
                        query("INSERT INTO codigos_descuento_usos
                        (id_codigo_descuento, id_participante, fecha, estado) 
                        VALUES 
                        ('$id_codigo_descuento','$id_participante',NOW(),'1')");
                        $mensaje .= '<div class="alert alert-success">
                        <strong>DESCUENTO REGISTRADO</strong> el c&oacute;digo de descuento '.$cod_descuento.' fue validado correctamente.
                      </div>';
                    }
                }else{
                    $mensaje .= '<div class="alert alert-waring">
                    <strong>AVISO</strong> el c&oacute;digo de descuento '.$cod_descuento.' no es valido para este curso.
                  </div>';
                }
            }else{
                $mensaje .= '<div class="alert alert-waring">
  <strong>AVISO</strong> el c&oacute;digo de descuento '.$cod_descuento.' no es valido.
</div>';
            }
        }

        /* id_cur_free */ 
        if ($id_cur_free>0) {
            query("INSERT INTO cursos_rel_usuariocurfreecur(id_usuario,id_curso,id_participante,id_curso_free,estado) VALUES ('0','$id_curso','$id_participante','" . $id_cur_free . "','0')");
        }

        /* datos-adicionales ipelc */
        if (isset_post('profesion')) {
            $profesion = post('profesion');
            $fecha_nacimiento = post('fecha_nacimiento');
            $direccion = post('direccion');
            $genero = post('genero');
            $qrv1 = query("SELECT id FROM ipelc_data_adicional WHERE id_participante='$id_participante' LIMIT 1 ");
            if(num_rows($qrv1)==0){
                query("INSERT INTO ipelc_data_adicional (id_participante, profesion, fecha_nacimiento, direccion, genero) VALUES ('$id_participante','$profesion','$fecha_nacimiento','$direccion','$genero')");
            }else{
                $qrv2 = fetch($qrv1);
                $id_rda = $qrv2['id'];
                query("UPDATE ipelc_data_adicional SET id_participante='$id_participante',profesion='$profesion',fecha_nacimiento='$fecha_nacimiento',direccion='$direccion',genero='$genero' WHERE id='$id_rda' ORDER BY id DESC LIMIT 1 ");
            }
        }

        /* registro de conversion API facebook */
        $event_data['email'] = $correo;
        $event_data['value_monto'] = $curso['costo'];
        $event_data['urlpage'] = $dominio.$curso['titulo_identificador'].'.html';
        $event_data['idproducto'] = "curso ".$curso['id'];
        $res = json_decode(sendConvertionAPI($event_data));
        if($res->events_received!='1'){
            $mensaje .= '<div class="alert alert-danger">
            <strong>ERROR</strong>
          No se registro la conversion.
          </div>';
        }

        /* solicitar_envio_cert_fisico */
        if (isset_post('solicitar_envio_cert_fisico')) {
            $direccion_envio_cert_fisico = post('direccion_envio_cert_fisico');
            query("INSERT INTO direnvio_certs_datapart (id_participante, direccion) VALUES ('$id_participante','$direccion_envio_cert_fisico')");
        }
        
        $mensaje .= '<div class="alert alert-success">
  <strong>REGISTRO EXITOSO</strong>
se ha registrado sus datos como participante del curso.
</div>';
    }
    /* envio de correo de inscripcion */
    if ($sw_envio_correo_inscripcion) {
        enviar_correo_registro($id_proceso_registro);
    }
} elseif (isset($get[3])) {
    $id_proceso_registro = $get[3];
    $hash = $get[2];
    if ($hash == md5('idr-' . $id_proceso_registro)) {
        $rqdpr1 = query("SELECT id_curso,codigo,sw_pago_enviado FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
        $rqdpr2 = fetch($rqdpr1);
        $id_curso = $rqdpr2['id_curso'];
        $codigo_de_registro = $rqdpr2['codigo'];
        $sw_pago_enviado = $rqdpr2['sw_pago_enviado'];

        /* datos del curso */
        $rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY estado DESC,id DESC limit 1 ");
        $curso = fetch($rq1);
    } else {
        echo "<script>location.href='$dominio';</script>";
        exit;
    }
} elseif (isset($get[2])) {
    $key_participante_registrado = $get[2];
    if (isset($_SESSION[$key_participante_registrado])) {
        $id_proceso_registro = $_SESSION[$key_participante_registrado];

        $rqdpr1 = query("SELECT id_curso,codigo,sw_pago_enviado FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
        $rqdpr2 = fetch($rqdpr1);
        $id_curso = $rqdpr2['id_curso'];
        $codigo_de_registro = $rqdpr2['codigo'];
        $sw_pago_enviado = $rqdpr2['sw_pago_enviado'];

        /* datos del curso */
        $rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY estado DESC,id DESC limit 1 ");
        $curso = fetch($rq1);
    }
}
?>
<style>
    .myinput{
        background: #d9faff;
        padding: 10px 20px;
        height: auto;
        border-radius: 10px;
    }
    .btn-infopago{
        background: #fdfdca;
        color: #de9000;
        border: 1px solid orange;
        padding: 2px 10px;
        cursor: pointer;
        border-radius: 3px;
    }
</style>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">

                    <div class="areaRegistro1 ftb-registro-5">
                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>
                        <br>
                        <?php echo $mensaje; ?>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <?php
                                $rqdcl1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                $rqdcl2 = fetch($rqdcl1);
                                $nombre_cliente = $rqdcl2['nombres'] . ' ' . $rqdcl2['apellidos'];
                                ?>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td style='padding:5px;'>C&oacute;digo de registro:</td>
                                        <td style='padding:5px;'><?php echo $codigo_de_registro; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Participante:</td>
                                        <td style='padding:5px;'><?php echo $nombre_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Curso:</td>
                                        <td style='padding:5px;'><?php echo $curso['titulo']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php
                        if ((int) $curso['costo'] > 0) {

                            if ($sw_pago_enviado == '1' && (!$sw_update_reportepago)) {
                                ?>
                                <br>
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        FICHA DE INSCRIPCI&Oacute;N
                                    </div>
                                    <div class="panel-body text-center">
                                        <br/>
                                        <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                        return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                        <br/>
                                        <br/>
                                        <a href="<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger"><i class="fa fa-print"></i> DESCARGAR EN PDF</a>
                                        <br/>
                                        &nbsp;
                                    </div>
                                    <hr>
                                    <?php
                                    if ((int) $curso['sw_askfactura'] == 1) {
                                        ?>
                                        <p>
                                            En caso de existir alg&uacute;n error en el reporte de pago y la imagen no este clara o sea erronea puedes volver a subir la imagen/foto del reporte de dep&oacute;sito o transferencia desde el siguiente enlace: <a href="registro-curso-p5c/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>/update.html" style="color:#004eff;text-decoration: underline;">modificar reporte de pago</a>, puede llenar los datos de facturaci&oacute;n en el siguiente enlace: <a href="registro-curso-facturacion/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>.html" style="color:#004eff;text-decoration: underline;">datos de facturaci&oacute;n</a>.
                                        </p>
                                        <?php
                                    } else {
                                        ?>
                                        <p>
                                            En caso de existir alg&uacute;n error en el reporte de pago y la imagen no este clara o sea erronea puedes volver a subir la imagen/foto del reporte de dep&oacute;sito o transferencia desde el siguiente enlace: <a href="registro-curso-p5c/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>/update.html" style="color:#004eff;text-decoration: underline;">modificar reporte de pago</a>.
                                        </p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            } else {
                                
                                /* cuenta bancaria */
                                $rqdcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                                $rqdcb2 = fetch($rqdcb1);
                                $banco = strtoupper($rqdcb2['nombre_banco']);
                                $numero_cuenta = $rqdcb2['numero_cuenta'];
                                $titular = strtoupper($rqdcb2['titular']);
                                ?>
                                <br>
                                <div class="alert alert-danger">
                                    <strong>AVISO IMPORTANTE</strong><br>Para poder ser habilitado y recibir los accesos al curso es necesario subir el comprobante de pago.
                                </div>
                                <p>Puede visualizar los datos de pago presionando el siguiente bot&oacute;n: &nbsp; <b class="btn-infopago" onclick="ver_metodos_pago()"><i class="fa fa-info"></i> VER METODOS DE PAGO</b></p>
                                <div>
                                    <h3 style="background: #DDD;color: #444;margin-top: 20px;padding: 7px 0px;text-align: center;border-radius: 7px;border: 1px solid #bfbfbf;">SUBIR COMPROBANTE DE PAGO</h3>
                                    <br>
                                    <div class="row">
                                        <form action="registro-curso-p6.html" method="post" enctype="multipart/form-data">
                                            <div class="panel panel-default" style="border: 1px solid #9ecc93;">
                                                <div class="panel-body">
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-10">
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <b>Metodo de pago:</b>
                                                                        <br/>
                                                                        <select class="form-control myinput" name='id_modo_pago' onchange="modPago(this.value);">
                                                                            <?php
                                                                            $rqccmtp1 = query("SELECT id,titulo,titulo_identificador FROM modos_de_pago WHERE sw_formreg='1' AND estado='1' ORDER BY FIELD(id,4,3,5,11) ");
                                                                            while ($rqccmtp2 = fetch($rqccmtp1)) {
                                                                                ?>
                                                                                <option value="<?php echo $rqccmtp2['id']; ?>"><?php echo $rqccmtp2['titulo'] . ($rqccmtp2['id'] == 5 ? ' &nbsp; 69714008' : '') . ($rqccmtp2['id'] == 11 ? ' &nbsp; info@nemabol.com' : ''); ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr id="TR-banco" style="display: none;">
                                                                    <td>
                                                                        <b>Banco:</b>
                                                                        <br/>
                                                                        <select class="form-control myinput" name="id_banco" id="id_banco" onchange="modCB();">
                                                                            <?php
                                                                            $rqbn1 = query("SELECT id,nombre FROM bancos WHERE estado=1 ORDER BY id ASC ");
                                                                            while ($rqbn2 = fetch($rqbn1)) {
                                                                                ?>
                                                                                <option value="<?php echo $rqbn2['id']; ?>"><?php echo $rqbn2['nombre']; ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <b>Cuenta:</b>
                                                                        <br/>
                                                                        <select class="form-control myinput" name="id_cuenta_banco" id="id_cuenta_banco">
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr id="TR-paypal" style="display: none;">
                                                                    <td colspan="2" class="text-center" style="padding: 25px 15px 5px 15px;">
                                                                        <p>Presione el siguiente botón para hacer el pago con paypal: <b><?php echo round($costo/7,2); ?> USD</b></p>
                                                                        <a href="https://www.paypal.com/paypalme/nemabol/<?php echo round($costo/7,2); ?>USD" target="_blank" class="btn btn-info">PAGAR CON PAYPAL</a>
                                                                        <br>
                                                                        <br>
                                                                        <p style="color:#00a4ca;">(Luego debe subir el comprobante de pago en este formulario)</p>
                                                                    </td>
                                                                </tr>
                                                                <tr id="TR-idtransaccion">
                                                                    <td colspan="2">
                                                                        <b>ID de transacci&oacute;n:</b> <span style="color:gray;font-size:9pt;">(TIGO MONEY)</span>
                                                                        <br/>
                                                                        <input class="form-control myinput" type='text' name='transaccion_id' placeholder="ID de transacci&oacute;n..."/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Monto en Bolivianos del pago:</b>
                                                                        <br/>
                                                                        <input class="form-control myinput" type='number' name='monto_deposito' required="required" value="<?php echo $costo; ?>"/>
                                                                    </td>
                                                                    <td>
                                                                        <b>Imagen/foto/screenshot del comprobante:</b>
                                                                        <br/>
                                                                        <input class="form-control myinput" type='file' accept="image/*" name='imagen_deposito' required="required"/>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <b>Ciudad donde se hizo el pago:</b>
                                                                        <br/>
                                                                        <select class="form-control myinput" name="ciudad">
                                                                            <option value="3">La Paz</option>
                                                                            <option value="1">Cochabamba</option>
                                                                            <option value="4">Santa Cruz</option>
                                                                            <option value="6">Chuquisaca</option>
                                                                            <option value="2">Potosi</option>
                                                                            <option value="8">Oruro</option>
                                                                            <option value="7">Pando</option>
                                                                            <option value="9">Beni</option>
                                                                            <option value="5">Tarija</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <b>Fecha cuando se hizo el pago:</b>
                                                                        <br/>
                                                                        <input class="form-control myinput" type='date' name='fecha' required="required" value="<?php echo date("Y-m-d"); ?>"/>
                                                                    </td>
                                                                    <td>
                                                                        <b>Hora cuando se hizo el pago:</b>
                                                                        <br/>
                                                                        <input class="form-control myinput" type='time' name='hora' required="required" value="<?php echo date("H:i"); ?>"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br/>
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12 text-center" style="padding: 20px;">
                                                            <input type="submit" class="btn btn-lg btn-success" value='SUBIR COMPROBANTE' style="border-radius: 7px;" name='subir-comprobante'/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id_proceso_registro" value="<?php echo $id_proceso_registro; ?>"/>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            query("UPDATE cursos_proceso_registro SET monto_deposito='0',id_modo_pago='10' WHERE id='$id_proceso_registro' LIMIT 1 ");
                            ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    FICHA DE INSCRIPCI&Oacute;N
                                </div>
                                <div class="panel-body text-center">
                                    <br/>
                                    <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                    <br/>
                                    <br/>
                                    <a href="<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger"><i class="fa fa-print"></i> DESCARGAR EN PDF</a>
                                    <br/>
                                    &nbsp;
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <br/>
                        <br/>
                        
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- ver metodos de pago -->
<script>
    function ver_metodos_pago() {
        $("#title-MODAL-general").html('METODOS DE PAGO');
        $("#body-MODAL-general").html('Cargando...');
        $("#MODAL-general").modal('show');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso-p5c.ver_metodos_pago.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#body-MODAL-general").html(data);
            }
        });
    }
</script>

<!-- modPago -->
<script>
    function modPago(cod) {
        switch (cod) {
            case '5':
                $('#TR-idtransaccion').css('display', 'table-row');
                $('#TR-banco').css('display', 'none');
                $('#TR-paypal').css('display', 'none');
                break;
            case '11':
                $('#TR-paypal').css('display', 'table-row');
                $('#TR-banco').css('display', 'none');
                $('#TR-idtransaccion').css('display', 'none');
                break;
            default:
                $('#TR-banco').css('display', 'table-row');
                $('#TR-idtransaccion').css('display', 'none');
                $('#TR-paypal').css('display', 'none');
        }
    }
</script>

<!-- modCB -->
<script>
    function modCB() {
        var cod = $("#id_banco").val();
        $("#id_cuenta_banco").html("<option>Cargando...</option>");
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso-p5c.modCB.php',
            data: {cod: cod},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#id_cuenta_banco").html(data);
            }
        });
    }
    modCB();
</script>


<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}

function enviar_correo_registro($id_proceso_registro) {
    global $dominio;
    /* datos de registro */
    $rqdr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    $registro_curso = fetch($rqdr1);
    $id_curso = $registro_curso['id_curso'];
    $id_turno = $registro_curso['id_turno'];
    $codigo_de_registro = $registro_curso['codigo'];

    /* datos del curso */
    $rq1 = query("SELECT *,(select titulo from departamentos where id=c.id_ciudad)ciudad,(select nombre from cursos_lugares where id=c.id_lugar limit 1)lugar_curso FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $curso = fetch($rq1);

    $titulo_curso = str_replace('?','',($curso['titulo']));
    $id_modalidad_curso = $curso['id_modalidad'];
    $fecha_curso = date("d/m/Y", strtotime($curso['fecha']));

    /* participantes */
    $datos_formulario_de_inscripcion = "<table style='width:100%;'>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Codigo de registro:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$codigo_de_registro</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$titulo_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    if ($id_modalidad_curso != '2') {
        $datos_formulario_de_inscripcion .= "<tr>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Fecha del curso:</td>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$fecha_curso</td>";
        $datos_formulario_de_inscripcion .= "</tr>";
    }

    if ($id_modalidad_curso != '1') {
        $texto_requisito_ingreso = 'Para poder ser habilitado en el curso virtual es necesario completar el pago y generar la ficha de inscripci&oacute;n correspondiente, una vez sea verificado por uno de nuestros administradores se le hara el env&iacute;o de los datos de acceso al curso.';
    } else {
        $texto_requisito_ingreso = 'Para poder hacer el ingreso el d&iacute;a del curso es necesario llevar la ficha de inscripci&oacute;n previamente impresa junto con el comprobante del pago realizado.';
    }

    /* PARTICIPANTES DEL CURSO */
    $rqpic1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ");
    $rqpc2 = fetch($rqpic1);
    $nombre_participante = $rqpc2['nombres'] . ' ' . $rqpc2['apellidos'];
    $correo_participante = $rqpc2['correo'];
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Participante:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$nombre_participante</td>";
    $datos_formulario_de_inscripcion .= "</tr>";

    $datos_formulario_de_inscripcion .= "</table>";

    if ($id_turno == '0') {
        $tr_turno = "";
    } else {
        $rqdt1 = query("SELECT titulo,descripcion FROM cursos_turnos WHERE id='$id_turno' LIMIT 1 ");
        $rqdt2 = fetch($rqdt1);
        $tr_turno = "<tr><td style='padding:5px;'>Turno:</td><td style='padding:5px;'>" . $rqdt2['titulo'] . " | " . $rqdt2['descripcion'] . "</td></tr>";
    }

    $url_proceso_registro = $dominio.'registro-curso-p5c/' . md5('idr-' . $id_proceso_registro) . '/' . $id_proceso_registro . '.html';

    /* CONTENIDO DEL CORREO */

    /* [INFO-PAGO-CUENTAS-BANCARIAS] */
    $data_info_pago_cuentas_bancarias = '<div style="font-size: 10.5pt;line-height: 2;font-weight: bold;color: #000;">';
    $data_info_pago_cuentas_bancarias .= '<p style="color: #ff0000;font-size: 10pt;text-align: center;background: #f9f9f9;padding: 10px 0px;">PAGO MEDIANTE TRANSFERENCIA BANCARIA, GIRO TIGOMONEY o DEPOSITO BANCARIO</p><b style="color: #ff0000;">CUENTA BANCARIAS:</b><br>';
    $rqcdbe1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='".$curso['id']."' AND r.sw_transbancunion=0 AND r.estado=1 ORDER BY c.id ASC ");
    while($rqcdbe2 = fetch($rqcdbe1)){
        $data_info_pago_cuentas_bancarias .= $rqcdbe2['nombre_banco'].' <span style="color: #ff0000;">A nombre de :</span> '.$rqcdbe2['titular'].'   <span style="color: #ff0000;">cuenta</span> '.$rqcdbe2['numero_cuenta'].'<br>';
    }
    $rqcdbdbu1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='".$curso['id']."' AND r.sw_transbancunion=1 AND r.estado=1 ORDER BY c.id ASC ");
    if(num_rows($rqcdbdbu1)>0){
        $data_info_pago_cuentas_bancarias .= '<br><b style="color: #ff0000;">TRANSFERENCIA DESTE CAJERO BANCO UNION:</b><br>';
        while($rqcdbdbu2 = fetch($rqcdbdbu1)){
            $data_info_pago_cuentas_bancarias .= '<span style="font-weight: normal;">Datos cuenta <b>'.$rqcdbdbu2['nombre_banco'].'</b> <b style="color: #0000ff;">'.$rqcdbdbu2['numero_cuenta'].'</b> '.$rqcdbdbu2['tipo_cuenta'].' <b>'.strtoupper($rqcdbdbu2['titular']).'</b></span><br>';
        }
    }
    $rqcddt1 = query("SELECT c.* FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id WHERE r.id_curso='".$curso['id']."' AND r.estado=1 GROUP BY c.datos_adicionales ORDER BY c.id ASC ");
    if(num_rows($rqcddt1)>0){
        $data_info_pago_cuentas_bancarias .= '<br><b style="color: #ff0000;">DATOS PARA TRANSFERENCIA:</b><br>';
        while($rqcddt2 = fetch($rqcddt1)){
            $data_info_pago_cuentas_bancarias .= '<span style="font-weight: normal;">Cuenta '.$rqcddt2['tipo_cuenta'].' <b>'.strtoupper($rqcddt2['titular']).'</b> ('.$rqcddt2['datos_adicionales'].')</span><br>';
        }
    }
    $rqcntm1 = query("SELECT t.* FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros t ON r.id_numtigomoney=t.id WHERE r.id_curso='".$curso['id']."' AND r.estado=1 ORDER BY t.id ASC ");
    if(num_rows($rqcntm1)>0){
        $data_info_pago_cuentas_bancarias .= '<br><b style="color: #ff0000;">PAGOS POR TIGO MONEY:</b><br>';
        while($rqcntm2 = fetch($rqcntm1)){
            $data_info_pago_cuentas_bancarias .= '<span style="font-weight: normal;">A la linea <b>'.$rqcntm2['numero'].'</b> el costo sin recargo (<b>Titular '.$rqcntm2['titular'].'</b>)<br>';
        }
    }
    $data_info_pago_cuentas_bancarias .= '</div>';
    $data_info_pago_cuentas_bancarias .= '<p>Ver todas las formas de pago : <a href="'.$dominio.'formas-de-pago.html">'.$dominio.'formas-de-pago.html</a></p>';




    $bodycont = "<div style='line-height:2;'>
                                                    <p style='text-align: left;'><span style='font-size: 12pt; color: #ff0000;'><strong>$titulo_curso<br></strong></span></p>
                                                    <p style='text-align: left;'>
                                                        <span style='font-size: 11pt; color: #000000;'>
                                                            " . $texto_requisito_ingreso . "
                                                        </span>
                                                    </p>
                                                    <p style='text-align: left;'>
                                                        <span style='font-size: 11pt; color: #000000;'>
                                                            Puede subir el comprobante de pago y descargar la ficha de inscripci&oacute;n en el siguiente enlace: 
                                                        </span>
                                                    </p>
                                                    <p style='text-align:center;'>
                                                        <br/>
                                                        <a href='" . $url_proceso_registro . "' style='text-decoration: none;'><b style='background: #049c04;color: white;padding: 10px 30px;border-radius: 7px;'>SUBIR COMPROBANTE DE PAGO</b></a>
                                                        <br/>
                                                        <br/>
                                                        <a href='" . $url_proceso_registro . "' style='text-decoration: underline;color: #0189ea;'>" . $url_proceso_registro . "</a>
                                                    </p>
                                                    <br>
                                                    <b>DATOS DE REGISTRO</b>";
    $bodycont .= $datos_formulario_de_inscripcion;
    $bodycont .= $data_info_pago_cuentas_bancarias;
    $bodycont .= "  <br/>
                    <p>Esperamos que el curso el sea de mucha utilidad.<br/><br/>Muchas gracias por realizar tu inscripci&oacute;n.<br/></p>
                    <br/>
                    </div>
            ";

    $titulo_mensaje = 'PROCESO DE INSCRIPCI&Oacute;N A CURSO';
    $url_unsubscribe = $dominio.'unsubscribe/' . $correo_participante . '/0001/' . md5($correo_participante . 'dardebaja') . '.html';
    $contenido_correo = platillaEmailUno($bodycont, $titulo_mensaje, $correo_participante, $url_unsubscribe, $nombre_participante);

    $asunto = ("INSCRIPCION $codigo_de_registro - ") . utf8_encode($titulo_curso);
    SISTsendEmail($correo_participante, $asunto, $contenido_correo);
}
