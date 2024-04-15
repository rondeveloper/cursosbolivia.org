<?php
/* datos de configuracion */
$email_principal = $__CONFIG_MANAGER->getData('email_principal');
$facebook_page = $__CONFIG_MANAGER->getData('facebook_page');
$src_google_maps = $__CONFIG_MANAGER->getData('src_google_maps');

/* envio de correo de contacto */
if (isset_post('enviar-correo-de-contacto')) {
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }
    if ($response != null && $response->success) {
        /* carga composer autoload */
        require_once $___path_raiz . '../vendor/autoload.php';

        $pagina_confirmacion = "gracias-por-su-mensaje.html";

        $nombre = post('nombre');
        $email = post('correo_electronico');
        $telefono = post('telefono');
        $mensaje = post('mensaje');

        $contenido_correo = "<b>Nombre:</b> $nombre<br>";
        $contenido_correo .= "<b>Direccion Email:</b> $email<br>";
        $contenido_correo .= "<b>Telefono:</b> $telefono<br>";
        $contenido_correo .= "<br><br>" . str_replace('\r\n', '<br/>', nl2br($mensaje)) . "<br>";
        $contenido_correo .= "<br><br><p>Servicio de mensajeria $___nombre_del_sitio " . date("Y") . "</p>";

        $contenido_correo = platillaEmailUno($contenido_correo, $___nombre_del_sitio . " - MENSAJE DE CONTACTO", $email_principal, urlUnsubscribe($email_principal), 'Usuario');
        $asunto = "Mensaje dirigido a " . $___nombre_del_sitio . " | " . $nombre;

        SISTsendEmail($email_principal, $asunto, $contenido_correo);
        movimiento('Envio de mensaje [contacto] | ' . $nombre, 'envio-mensaje', 'mainpage', '1');
        echo "<script>location.replace('" . $pagina_confirmacion . "')</script>";
    } else {
        echo "<script>alert('Verifica que no eres un Robot');history.back();</script>";
    }
    exit;
}

?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container" style="min-height: 570px;">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-6">
                <div class="">
                    <div style="margin-top: 10px;margin-bottom: 20px;">
                        <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/banner_contactenos.jpg" style="width:100%;margin:auto;" />
                    </div>
                    <?php
                    $rqd1 = query("SELECT s.*,(d.nombre)dr_departamento FROM sucursales s INNER JOIN departamentos d ON s.id_departamento=d.id WHERE s.estado=1 ");
                    while ($rqd2 = fetch($rqd1)) {
                    ?>
                        <div style="border: 1px solid #d2d2d2;margin-bottom: 20px;padding: 20px;border-radius: 5px;font-size: 11pt;line-height: 2;">
                            <h3 style="margin-top: 0px;"><?php echo $rqd2['dr_departamento']; ?></h3>
                            <i style="color: #717171;text-decoration: underline;font-weight: bold;">Direcci&oacute;n:</i> &nbsp; <?php echo $rqd2['direccion']; ?>
                            <br>
                            <i style="color: #717171;text-decoration: underline;font-weight: bold;">Horarios:</i> &nbsp; <?php echo $rqd2['horarios_atencion']; ?>
                            <br>
                            <i style="color: #717171;text-decoration: underline;font-weight: bold;">N&uacute;meros:</i> &nbsp; <?php echo $rqd2['num_celular'] . ' ' . $rqd2['num_telefono']; ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div style="text-align: center;border: 1px solid #d2d2d2;border-radius: 5px;margin: 5px 0px;padding: 10px 2px;font-size: 15pt;line-height: 1.5;color: #464646;">
                    <b>EMAIL</b>
                    <br>
                    <?php echo $email_principal; ?>
                </div>
                <div style="text-align: center;border: 1px solid #d2d2d2;border-radius: 5px;margin: 5px 0px;padding: 10px 2px;font-size: 15pt;line-height: 1.5;color: #464646;margin-top: 19px;">
                    <b>FACEBOOK</b>
                    <br>
                    <a href="<?php echo $facebook_page; ?>" target="_blank" style="color: #587bcc;font-weight: 500;text-decoration: underline;"><?php echo $facebook_page; ?></a>
                </div>
            </div>
            <div class="col-md-6">
                <form method="post" action="">
                    <div style="border: 1px solid #d2d2d2;margin: 10px;padding: 10px 27px;padding-bottom: 25px;border-radius: 5px;">
                        <h4 style="text-align: center;margin-bottom: 20px;color: #8e8e8e;">Envianos tu mensaje</h4>
                        <table class="table">
                            <tr>
                                <td>
                                    <p>
                                        <label for="asunto">Asunto:</label>
                                    </p>
                                </td>
                                <td>
                                    <select name="asunto" class="form-control">
                                        <option value="contacto">Contacto</option>
                                        <option value="atencion-al-cliente">Atenci&oacute;n al cliente</option>
                                        <option value="soporte-tecnico">Soporte t&eacute;cnico</option>
                                        <option value="reporte-de-deposito">Reporte de dep&oacute;sito y/o pagos</option>
                                        <option value="gerencia">Gerencia</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <label for="nombre">Nombre Completo:</label>
                                    </p>
                                </td>
                                <td>
                                    <input class="form-control" name="nombre" type="text" maxlength="50" required="">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <label for="correo_electronico">Correo electr&oacute;nico: </label>
                                    </p>
                                </td>
                                <td>
                                    <input class="form-control" name="correo_electronico" type="email" maxlength="30" required="">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <label for="telefono">Tel&eacute;fono / Celular</label>
                                    </p>
                                </td>
                                <td>
                                    <input class="form-control" name="telefono" type="number" maxlength="25" required="">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p>
                                        <label for="mensaje">Ingrese su mensaje</label>
                                    </p>
                                    <textarea class="form-control" name="mensaje" maxlength="5000" style="width:100%;height:170px;" required=""></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center">
                                    <div align="center">
                                        <div style="width:300px;margin:auto;">
                                            <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                            <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center">
                                    <input name="enviar-correo-de-contacto" type="submit" value="Enviar Mensaje de Contacto" class="btn btn-success" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>

        <br>

        <div>
            <iframe src="<?php echo $src_google_maps; ?>" frameborder="0" style="border:0;width:100%;height:450px;" allowfullscreen></iframe>
        </div>
    </section>
</div>