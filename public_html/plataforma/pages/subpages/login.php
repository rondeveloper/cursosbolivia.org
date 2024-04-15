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
            <div class="col-md-3"></div>
            <div class="col-md-6" style="margin-bottom:150px;">
                <div class="">
                <h3 class="text-center" style="background: #2897c7;color: #FFF;margin-bottom: 0px;padding: 20px;box-shadow: inset 1px 0px 8px 5px #10b2e4;border: 1px solid #37a9da;border-radius: 5px;">PLATAFORMA VIRTUAL</h3>
                </div>
                <div class="text-center" style="margin:30px 0px 70px 0px;">
                    Bienvenid@ a nuestra plataforma virtual de aprendizaje, donde podr&aacute;s acceder a los materiales, videos, evaluaci&oacute;nes, certificados y otros contenidos de los cursos a los cuales te registraste en <?php echo $___nombre_del_sitio; ?>, esperamos que adquieras mucho conocimiento.
                </div>

                <?php echo $mensaje; ?>

                    <div class="boxForm ajusta_form_contacto" style="background: #e8e8e8;box-shadow: 0px 1px 10px 6px #d6d8f1;border: 1px solid white;">
                        <h5>INGRESA A TU CUENTA</h5>
                        <hr/>
                        <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
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
                                <a href_="recuperar-contresena.html" style="text-decoration: underline;">&iquest; olvido su contrase&ntilde;a ?</a>
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
            </div>
        </div>
    </section>
</div>                     

