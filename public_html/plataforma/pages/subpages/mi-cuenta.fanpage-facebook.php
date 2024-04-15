<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* data usuario */
$rqdu1 = query("SELECT id_departamento,nombres,apellidos,celular,email FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'];
$celular_usuario = $rqdu2['celular'];
$email_usuario = $rqdu2['email'];
if ($email_usuario == 'no-email-data') {
    $email_usuario = '';
}
$id_departamento_usuario = $rqdu2['id_departamento'];
?>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v4.0&appId=203513730154620&autoLogAppEvents=1"></script>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <?php
                if ($id_departamento_usuario !== '0') {
                    $rqddw1 = query("SELECT nombre,url_facebook FROM departamentos WHERE id='$id_departamento_usuario' LIMIT 1 ");
                    $rqddw2 = fetch($rqddw1);
                    $nombre_departamento = $rqddw2['nombre'];
                    $url_facebook = $rqddw2['url_facebook'];
                    if ($url_facebook !== '') {
                        ?>
                        <div class="TituloArea">
                            <h3>P&Aacute;GINA DE FACEBOOK</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <p>
                                Enterate de los cursos de tu inter&eacute;s mediante la fanpage de 'Facebook'. Presiona el boton 'Me gusta' para seguir a la p&aacute;gina de difusion de cursos de capacitaci&oacute;n en el departamento de '<?php echo $nombre_departamento; ?>'.
                            </p>
                        </div>
                        <div style="text-align:center;">
                            <div class="fb-page" data-href="<?php echo $url_facebook; ?>" data-tabs="timeline" data-width="700" data-height="900" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                                <blockquote cite="<?php echo $url_facebook; ?>" class="fb-xfbml-parse-ignore">
                                    <a href="<?php echo $url_facebook; ?>">Cursos <?php echo $nombre_departamento; ?></a>
                                </blockquote>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="TituloArea">
                            <h3>P&Aacute;GINA DE FACEBOOK</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <p>
                                Enterate de los cursos de tu inter&eacute;s mediante la fanpage de 'Facebook'. Presiona el boton 'Me gusta' para seguir a la p&aacute;gina de difusion de cursos de capacitaci&oacute;n en el departamento de '<?php echo $nombre_departamento; ?>'.
                            </p>
                        </div>
                        <div style="text-align:center;">
                            <div class="fb-page" data-href="https://www.facebook.com/cursoswebbolivia/" data-tabs="timeline" data-width="700" data-height="900" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                                <blockquote cite="https://www.facebook.com/cursoswebbolivia/" class="fb-xfbml-parse-ignore">
                                    <a href="https://www.facebook.com/cursoswebbolivia/">Cursos Bolivia</a>
                                </blockquote>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="TituloArea">
                        <h3>P&Aacute;GINA DE FACEBOOK</h3>
                    </div>
                    <div class="Titulo_texto1">
                        <p>
                            Enterate de los cursos de tu inter&eacute;s mediante la fanpage de 'Facebook'. Presiona el boton 'Me gusta' para seguir a la p&aacute;gina de difusion de cursos de capacitaci&oacute;n a nivel nacional.
                        </p>
                    </div>
                    <div style="text-align:center;">
                        <div class="fb-page" data-href="https://www.facebook.com/cursoswebbolivia/" data-tabs="timeline" data-width="700" data-height="900" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                            <blockquote cite="https://www.facebook.com/cursoswebbolivia/" class="fb-xfbml-parse-ignore">
                                <a href="https://www.facebook.com/cursoswebbolivia/">Cursos Bolivia</a>
                            </blockquote>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <hr/>
                <p>Para recibir la notificaci&oacute;n del curso que te interesa inmediatamente cuando se publique puedes activar la opci&oacute;n 'Ver primero' en la p&aacute;gina de facebook como se muestra a continuaci&oacute;n.</p>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <b>Ingresando por celular:</b>
                        <br/>
                        <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/ver-primero-en-facebook-1.png" style=""/>
                    </div>
                    <div class="col-md-6 text-center">
                        <b>Ingresando v&iacute;a web:</b>
                        <br/>
                        <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/ver-primero-en-facebook-2.gif" style=""/>
                    </div>
                </div>
                <hr/>
            </div>
        </div>
    </section>
</div>                     

