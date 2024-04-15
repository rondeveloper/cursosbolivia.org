<?php
$mensaje = '';

$nombres = '';
$apellidos = '';
$email = '';
$celular = '';


if (isset($get[2]) && $get[2] == 'cuenta-google-no-encontrada') {
    $mensaje .= '<div class="alert alert-danger">
  <strong>Aviso</strong> no se encontro cuenta de usuario vinculada a la cuenta Google ingresada.
</div>';
}

/* recuperar-cuenta */
if (isset_post('recuperar-cuenta')) {
    $email = post('email');
    $rqvc1 = query("SELECT * FROM cursos_usuarios WHERE email='$email' ORDER BY id DESC limit 1 ");
    if (num_rows($rqvc1) > 0) {
        $rqvc2 = fetch($rqvc1);
        $email_usuario = $rqvc2['email'];
        $password_usuario = $rqvc2['password'];
        $nombre_usuario = $rqvc2['nombres'] . ' ' . $rqvc2['apellidos'];



        $contenido_correo = "<div style='background:#c8c8c8;width:100%;padding:0px;margin:0px;padding-top:30px;padding-bottom:30px;'>
    <div style='background:#FFF;width:80%;margin:auto;padding:30px;border:1px solid #56829c;border-radius:5px;color:#333;'><h2 style='text-align:center;background:#31a516;color:#FFF;border-radius:5px;padding:5px;'>Datos de Ingreso</h2>
        <center><a href='".$dominio."'><img width='230px' src='".$dominio."contenido/alt/logotipo-v3.png' style='background: #35ab19;padding: 5px 15px;border-radius: 5px;'/></a></center>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Saludos " . $nombre_usuario . "
            <br/>
            Te informamos que para un mejor uso de los servicios que ofrece ".$___nombre_del_sitio." es necesario que configures tu cuenta correctamente, para ello 
            debes ingresar a la siguiente URL cuando gustes y completar tus datos de usuario.
        </p>
        <div style='text-align:center;'><a style='background:green;color:#FFF;padding:10px;border-radius:5px;' href='".$dominio."ingreso-de-usuarios.html'>Ingresar a mi cuenta en ".$___nombre_del_sitio."</a></div>        
        <br/>
        <br/>
        <table>
        <tr>
        <td style='padding:5px;'><b>Nick de usuario:</b></td>
        <td style='padding:5px;'>" . $email_usuario . "</td>
        </tr>
        <tr>
        <td style='padding:5px;'><b>Contrase&ntilde;a:</b></td>
        <td style='padding:5px;'>" . $password_usuario . "</td>
        </tr>
        </table>
        <br/>
        <br/>
        Esperamos que nuestra aplicaci&oacute;n te sea de utilidad.<br/><br/>Gracias por tu atenci&oacute;n.<br/></p>
        <hr/>
        <br/>
        <div style='margin:auto;width:80%;font-size:9.5pt;color:#333;line-height:1;'>
            <b>Comun&iacute;quese con nosotros:</b>
            <br/>
            info@nemabol.com
            <br/>
            Av camacho Edif. Saenz N° 1377 Piso 3 Of. 301 esq. Loayza
            <br/>
            69713008 L&iacute;nea ".$___nombre_del_sitio."
        </div>
    </div>
</div>";

        $asunto = "Envio de datos de ingreso a ".$___nombre_del_sitio;
        SISTsendEmail($email_usuario, $asunto, $contenido_correo);
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito</strong> la contrase&ntilde;a de tu cuenta fue enviada a tu correo electr&oacute;nico.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error</strong> no se encontro algun registro con ese email.
</div>';
    }
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
                    <h3>RECUPERAR CONTRASE&Ntilde;A</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        En esta secci&oacute;n puedes recuperar la contrase&ntilde;a con la que te registraste.
                    </p>
                </div>

                <?php echo $mensaje; ?>

                <div class="boxForm ajusta_form_contacto">
                    <h5>INGRESA TU CORREO</h5>
                    <hr/>
                    <form action="" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form-control required string" type="text" name="email" placeholder="Correo electr&oacute;nico / Nick de usuario..." required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <input type="submit" name="recuperar-cuenta" class="btn btn-success" value="RECUPERAR CONTRASE&Ntilde;A"/>
                            </div>
                        </div>
                        <br/>
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


