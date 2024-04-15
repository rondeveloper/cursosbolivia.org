<?php
/* mensaje */
$mensaje = '';

/* ip */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = mysql_real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
}

/* $certificado_id */
$certificado_id = '';
if (isset_post('id_certificado')) {
    $certificado_id = post('id_certificado');
}

$sw_certificado_valido = false;

/* validacion */
if (isset_post('valida-certificado')) {
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
        );
    }
    if (($response != null && $response->success)) {

        $certificado_id = post('id_certificado');
        $nombre_institucion = post('nombre_institucion');
        $nombre_solicitante = post('nombre_solicitante');
        $cargo_solicitante = post('cargo_solicitante');
        $correo_solicitante = post('correo_solicitante');
        $fecha = date("Y-m-d H:i");

        /* verificacion */
        /*
        $rqdv1 = query("SELECT 
                            *,
                            ec.id_participante,
                            (concat(p.prefijo,' ',p.nombres,' ',p.apellidos))receptor_certificado, 
                            (concat(p.apellidos,' ',p.nombres))participante, 
                            (c.fecha)fecha_curso, 
                            (ec.id_certificado)id_de_certificado, 
                            (c.titulo)nombre_curso  
                            FROM cursos_emisiones_certificados ec 
                            INNER JOIN cursos_participantes p 
                            ON ec.id_participante=p.id 
                            INNER JOIN cursos c 
                            ON ec.id_curso=c.id 
                            WHERE ec.certificado_id='$certificado_id' ORDER BY ec.id DESC limit 1 ");
        */
        $rqdv1 = query("SELECT 
                            * 
                            FROM emisiones_certificados ec 
                            WHERE ec.certificado_id='$certificado_id' ORDER BY ec.id DESC limit 1 ");
        if (mysql_num_rows($rqdv1) > 0) {

            query("INSERT INTO cursos_validacion_certificados(
                     certificado_id, 
                     apellido, 
                     nombre_institucion, 
                     nombre_solicitante, 
                     cargo_solicitante, 
                     correo_solicitante, 
                     fecha, 
                     ip
                     ) VALUES (
                     '$certificado_id',
                     '$apellido',
                     '$nombre_institucion',
                     '$nombre_solicitante',
                     '$cargo_solicitante',
                     '$correo_solicitante',
                     '$fecha',
                     '$ip_coneccion'
                     )");
            $id_validacion = mysql_insert_id();

            $sw_certificado_valido = true;
            $certificado = mysql_fetch_array($rqdv1);

            //logcursos('Solicitud de validacion de certificado [' . $id_validacion . ':' . $nombre_solicitante . ']', 'certificado-validacion', 'participante', $certificado['id_participante']);
            //logcursos('Solicitud de validacion de certificado [' . $certificado['receptor_de_certificado'] . '][' . $id_validacion . ':' . $nombre_solicitante . ']', 'certificado-validacion', 'curso', $certificado['id_curso']);

            $mensaje = '<div class="alert alert-success">
  <strong>CERTIFICADO VALIDO</strong> los datos se verificaron correctamente en nuestros registros y el certificado con ID ' . $certificado_id . ' corresponde a los datos mostrados a continuaci&oacute;n.
</div>';
        } else {
            $mensaje = '<div class="alert alert-danger">
  <strong>REGISTRO NO ENCONTRADO</strong> verifique que el ID de certificado y el primer apellido sean correctos e intente nuevamente.
</div>';
        }
    } else {
        echo "<script>alert('Verifica que no eres un robot');location.href='validacion-de-certificado.html';</script>";
    }
}
?>
<style>
    .TituloArea h3:after {
        width: 355px !important;
    }
</style>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>VALIDADOR DE CERTIFICADOS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Mediante el sistema de validaci&oacute;n de certificados, se podr&aacute; verificar la autenticidad del certificado correspondiente emitido para cada uno de los cursos realizados, estos pueden ser solicitados por la instituci&oacute;n o persona que lo requiera.
                        <br/>
                        Para ello llene los datos del formulario siguiente e ingrese el <b>'ID de certificado'</b> ubicado en la parte inferior del c&oacute;digo QR del certificado emitido.
                        <br/>
                        <b>IMPORTANTE: La informaci&oacute;n v&aacute;lida es la generada en la pantalla.</b>
                    </p>
                </div>

                <br/>

                <?php echo $mensaje; ?>

                <div class="boxForm ajusta_form_contacto" style="background: #f7f7f7;border: 1px solid #5bc0de;box-shadow: 1px 1px 3px #d0d0d0;">
                    <?php
                    if ($sw_certificado_valido) {

                        /* cert */
                        $rqddcc1 = query("SELECT * FROM certificados WHERE id='" . $certificado['id_certificado'] . "' ORDER BY id DESC limit 1 ");
                        $rqddcc2 = mysql_fetch_array($rqddcc1);

                        /* datos de receptor de certificado */
                        $rqdpc1 = query("SELECT nombres,apellidos,ci,ci_expedido,prefijo FROM cursos_participantes WHERE id='" . $certificado['id_participante'] . "' ORDER BY id DESC limit 1 ");
                        $rqdpc2 = mysql_fetch_array($rqdpc1);
                        $receptor_de_certificado = utf8_decode(trim(trim($rqdpc2['prefijo']) . ' ' . trim($rqdpc2['nombres']) . ' ' . trim($rqdpc2['apellidos'])));
                        $ci_participante = $rqdpc2['ci'];
                        $ci_expedido_participante = $rqdpc2['ci_expedido'];

                        /* generad de QR */
                        include_once 'contenido/librerias/phpqrcode/qrlib.php';
                        $file = 'qrcod-vcert-' . time() . '-' . $certificado_id . '.png';
                        $file_qr_certificado = 'contenido/imagenes/qrcode/' . $file;
                        if (!is_file($file_qr_certificado)) {
                            copy('contenido/imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
                        }
                        $data = $certificado['certificado_id'] . '|' . utf8_decode($rqddcc2['texto_qr']) . '|' . $certificado['receptor_de_certificado'] . '|' . $rqddcc2['fecha_qr'];
                        QRcode::png($data, $file_qr_certificado);
                        ?>
                        <table class="table table-striped table-hover table-bordered" style="background: #FFF;">
                            <tr>
                                <td><b>RECEPTOR DE CERTIFICADO:</b></td>
                                <td><?php echo $certificado['receptor_de_certificado']; ?></td>
                            </tr>
                            <tr>
                                <td><b>ID DE CERTIFICADO:</b></td>
                                <td><?php echo $certificado['certificado_id']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo utf8_encode($rqddcc2['cont_uno']); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo utf8_encode($certificado['receptor_certificado']); ?></td>
                            </tr>
                                <tr>
                                    <td colspan="2" class="text-center">Habilidades digitales para el Siglo XXI</td>
                                </tr>
<!--                            <tr>
                                <td colspan="2" class="text-center"><?php echo $rqddcc2['cont_uno']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $rqddcc2['cont_dos']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $rqddcc2['cont_tres']; ?></td>
                            </tr>-->
                            <tr>
                                <td colspan="2" class="text-center">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <img src='<?php echo $file_qr_certificado; ?>' style="width: 200px;"/>
                                </td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        ?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>ID de certificado:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="id_certificado" value="<?php echo $certificado_id; ?>" class="form-control" placeholder="Ingrese el ID de certificado..." aria-describedby="basic-addon1" required=""/>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>Nombre de la Instituci&oacute;n solicitante:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="nombre_institucion" value="" class="form-control" placeholder="..." aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>Nombre del solicitante:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="nombre_solicitante" value="" class="form-control" placeholder="..." aria-describedby="basic-addon1" required=""/>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>Cargo en la instituci&oacute;n del solicitante:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="cargo_solicitante" value="" class="form-control" placeholder="..." aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>Correo del solicitante:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="email" name="correo_solicitante" value="" class="form-control" placeholder="..." aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                                    <br/>
                                    <div style="width:300px;margin:auto;">
                                        <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                        <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                    </div> 
                                    <br/>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <input type="submit" class="btn btn-info btn-block" value="VALIDAR CERTIFICADO" name="valida-certificado"/>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </div>



                <br/>
                <hr/>
                <br/>

            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>

            </div>
        </div>

    </section>
</div>                     



<?php

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}
