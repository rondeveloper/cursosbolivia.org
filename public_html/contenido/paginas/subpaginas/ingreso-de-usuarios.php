<?php

$nombres = '';
$apellidos = '';
$email = '';
$celular = '';


if (isset($get[2]) && $get[2] == 'cuenta-google-no-encontrada') {
    $mensaje .= '<div class="alert alert-danger">
  <strong>Aviso</strong> no se encontro cuenta de usuario vinculada a la cuenta Google ingresada.
</div>';
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>Ingreso de Usuario</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Ingresa tus datos de usuario en el siguiente formulario para poder hacer uso de nuestra plataforma.
                    </p>
                </div>

                <?php echo $mensaje; ?>

                <?php
                if (!$sw_ingreso) {
                    ?>
                    <div class="boxForm ajusta_form_contacto">
                        <h5>INGRESA A TU CUENTA</h5>
                        <hr/>
                        <form action="ingreso-de-usuarios.html" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="text" name="email" placeholder="Correo electr&oacute;nico / Nick de usuario..." required="">
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar-a-cuenta" class="btn btn-success" value="INGRESAR A MI CUENTA"/>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="recuperar-contresena.html" style="text-decoration: underline;">&iquest; olvido su contrase&ntilde;a ?</a>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <?php
                                require_once('contenido/librerias/gplus-login/settings.php');
                                $enalce_login_gplus = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
                                ?>
                                <div class="col-md-12 text-center">
                                    <b>O inicia sesion con:</b>
                                    <br/>
                                    <a href="<?php echo $enalce_login_gplus; ?>" class="btn btn-default" style="width: 130px;">Google</a>
                                    &nbsp;
                                    &nbsp;
                                    <a  class="btn btn-default" style="width: 130px;">Facebook</a>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group text-center">
                                <span><b style="font-weight:bold;">&iquest; No tienes una cuenta ?</b> registrate con el siguiente enlace:</span>
                                <br/>
                                <br/>
                                <div class="col-md-12 text-center">
                                    <a href="registro-de-usuarios.html" type="submit" class="btn btn-primary">CREAR UNA CUENTA</a>
                                </div>
                            </div>
                        </form>

                    </div>
                    <?php
                } else {
                    ?>
                    Bienvenido a la plataforma <?php echo $___nombre_del_sitio; ?>
                    <hr/>
                    <a href="<?php echo $dominio; ?>" class="btn btn-warning">CONTINUAR</a>
                    <?php
                    /* ingreso desde curso */
                    if (isset($get[3]) && ($get[2] == 'curso')) {
                        $id_curso = $get[3];
                        $rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
                        $rqdc2 = fetch($rqdc1);
                        $titulo_identificador_curso = $rqdc2['titulo_identificador'];
                        echo "<script>location.href='" . $dominio . "registro-curso/$titulo_identificador_curso.html';</script>";
                    }
                    /* ingreso desde foro */
                    if (isset($get[3]) && ($get[2] == 'foro')) {
                        $id_foro = $get[3];
                        $rqdc1 = query("SELECT tema FROM cursos_foros WHERE id='$id_foro' ORDER BY id DESC limit 1 ");
                        $rqdc2 = fetch($rqdc1);
                        $tema_foro = $rqdc2['tema'];
                        echo "<script>location.href='" . $dominio . "foro/" . limpiar_enlace($tema_foro) . "/$id_foro.html';</script>";
                    }
                }
                ?>


                <br />
                <br />


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


