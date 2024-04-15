<?php
/* mensaje */
$mensaje = '';

$nombres = '';
$apellidos = '';
$email = '';
$celular = '';
$sw_registro = false;
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <?php echo $mensaje; ?>

                <br/>

                <div class="boxForm ajusta_form_contacto" style="background: #f7f7f7;border: 1px solid #5bc0de;box-shadow: 1px 1px 3px #d0d0d0;">

                    <div class="row">
                        <div class="cont-regg col-md-12">
                            <div class="corp">
                                <div class="text-center" style="padding-top:40px;padding-bottom:20px;">
                                    <a href="<?php echo $dominio; ?>" style="font-size: 27pt;color: red;font-weight: bold;">
                                        <?php echo $___nombre_del_sitio; ?>
                                    </a>
                                </div>
                                <p style="font-size: 1.8rem;line-height: 1.5;text-align: center;padding: 0px 30px;color: #555;">
                                    El registro en <?php echo $___nombre_del_sitio; ?> le permite recibir notificaciones personalizadas por correo, acerca de los distintos cursos que ofrecemos.
                                </p>
                            </div>
                            <div class="modal-body">
                                <div class="modal-inner">
                                    <div class="credentials-box">
                                        <?php if(false){ ?>
                                        <div class="facebook-credentials js-facebook-credentials" style="width: 90%;margin: auto;">
                                            <form action="" accept-charset="UTF-8" method="get"><input name="utf8" type="hidden" value="?">
                                                <a href="<?php echo $dominio; ?>contenido/librerias/facebook-login/fbconfig.php"  name="button" type="submit" class="btn btn-facebook btn-block btn-lg"><i class="fa fa-facebook"></i> Reg&iacute;strate con Facebook</a>
                                            </form>
                                            <form action="" accept-charset="UTF-8" method="get">
                                                <?php
                                                require_once('contenido/librerias/gplus-login/settings.php');
                                                $enalce_login_gplus = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
                                                ?>
                                                <a href="<?php echo $enalce_login_gplus; ?>" name="button" type="submit" class="btn btn-google btn-block btn-lg"><i class="fa fa-google"></i> Reg&iacute;strate con Google</a>
                                            </form>
                                        </div>
                                        <?php } ?>
                                        <div class="simple-credentials js-simple-credentials" style="margin-top: 20px;padding-top: 20px;
                                             padding-bottom: 30px;
                                             background-color: #F7F7F7;">
                                            <div style="width: 90%;margin: auto;">
                                                <div class="credentials-or">
                                                    <span>Reg&iacute;strate con tu email</span>
                                                </div>
                                                <form class="simple_form" novalidate="novalidate" id="new_user" action="registro-de-usuarios.html" accept-charset="UTF-8" method="post">
                                                    <fieldset>
                                                        <div class="form-group email optional user_email"><input class="form-control string email optional input-lg" autocomplete="off" placeholder="Correo electr&oacute;nico..." type="email" value="" name="email" id="user_email"></div>
                                                        <div class="form-group number optional user_password"><input class="form-control number optional input-lg" autocomplete="off" placeholder="N&uacute;mero de celular..." type="number" name="celular" id="user_celular"></div>
                                                        <div class="form-group password optional user_password"><input class="form-control password optional input-lg" autocomplete="off" placeholder="Contrase&ntilde;a para <?php echo $___nombre_del_sitio; ?>..." type="password" name="password" id="user_password"></div>
                                                    </fieldset>
                                                    <div class="form-actions t-signup-button">
                                                        <input name="fast-user-register" type="submit" class="btn btn-success btn-lg btn-block-xs-down" value="Crear cuenta"/>
                                                    </div>
                                                </form>
                                                <div class="info info--sm">
                                                    <span class="ab-visible">
                                                        Al hacer clic en "Crear cuenta" acepto las condiciones de uso y recibir novedades y promociones.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    &iquest;Ya tienes cuenta?
                                    <a class="link-primary" href="ingreso-de-usuarios.html">Entrar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="">
                    <hr/>
                    <b>REGISTRO COMO ORGANIZADOR:</b> universidad, instituto, centro de capacitaci&oacute;n, profesional o cualquier insituci&oacute;n que desea brindar sus cursos mediante nuestra plataforma. Registrate como organizador en el siguiente enlace: <a href="registro-de-organizador.html" style="text-decoration: underline;">registro de organizador</a>.
                    <hr/>
                </div>

                <br />
                <br />

            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>
                <div class="">
                    
                </div>
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

