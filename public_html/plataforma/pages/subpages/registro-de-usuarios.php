<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../contenido/librerias/phpmailer/vendor/autoload.php';

/* mensaje */
$mensaje = '';

$nombres = '';
$apellidos = '';
$email = '';
$email_2 = '';
$celular = '';
$password = '';
$sw_registro = false;

if(isset_post('fast-user-register')){
    $email = post('email');
    $password = post('password');
    $celular = post('celular');
}

/* creacion de cuenta */
if (isset_post('crear-cuenta')) {
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
        );
    }

    if (($response != null && $response->success) || $sw_cookie) {


        $nombres = post('nombres');
        $apellidos = post('apellidos');
        $ci = post('ci');
        $email = post('email');
        $email_2 = post('email_2');
        $celular = post('celular');
        $password = post('password');
        $password2 = post('password2');
        $fecha_registro = date("Y-m-d H:i");
        $estado = '2';
        $hash_usuario = md5('Rsur5-'.rand(99,9999));

        $sw_inscribir = true;

        /* validacion de correo */
        if ($email !== $email_2) {
            $sw_inscribir = false;
            $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> los correos no coinciden.
</div>';
        }

        /* validacion de password */
        if ($password !== $password2) {
            $sw_inscribir = false;
            $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> las contrase&ntilde;as no coinciden.
</div>';
        }

        /* validacion de email repetido */
        $rqer1 = query("SELECT id FROM cursos_usuarios WHERE email='$email' LIMIT 1");
        if (num_rows($rqer1) > 0) {
            $sw_inscribir = false;
            $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> el correo ' . $email . ' ya se registro como usuario en la plataforma.
</div>';
        }
        
        /* validacion 2 areas */
        $cnt_checks = 0;
        $rqc1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
        while ($rqc2 = fetch($rqc1)) {
            $id_categoria = $rqc2['id'];
            if (isset_post('categoria-' . $id_categoria)) {
                $cnt_checks++;
            }
        }
        if($cnt_checks<2){
            $sw_inscribir = false;
            $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> debe seleccionar al menos 2 areas de inter&eacute;s.
</div>';
        }


        if ($sw_inscribir) {

            /* creacion de usuario */
            query("INSERT INTO cursos_usuarios(
            nombres,
            apellidos,
            email,
            celular,
            password,
            hash_usuario,
            fecha_registro,
            estado
            ) VALUES (
            '$nombres',
            '$apellidos',
            '$email',
            '$celular',
            '$password',
            '$hash_usuario',
            '$fecha_registro',
            '$estado'
            )");

            $id_usuario = insert_id();
            
            setcookie("id_usuario",$id_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
            setcookie("hash_usuario",$hash_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
                    
            /* creacion de categorias de interes */
            $rqc1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
            while ($rqc2 = fetch($rqc1)) {
                $id_categoria = $rqc2['id'];
                if (isset_post('categoria-' . $id_categoria)) {
                    query("INSERT INTO cursos_rel_usu_cat (id_usuario,id_categoria) VALUES('$id_usuario','$id_categoria') ");
                }
            }

            $sw_registro = true;

            $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> el registro fue creado exitosamente.
</div>';

            /* envio de correo de autentificacion */
            $subject = ('Bienvenido a '.$___nombre_del_sitio.' - Verificación de cuenta: ' . $nombres . ' ' . $apellidos);
            $url_autentificacion = $dominio_plataforma.'verificacion-cuenta/' . $id_usuario . '/' . md5(rand(99, 9999)) . '.html';
            $body = '
        <div>
        <div style="background:#31b312;padding:4px 0px;text-align:center;border-radius: 5px;">
        <img src="'.$dominio_www.'contenido/alt/logotipo-v3.png" style="130px;"/>
        </div>
        <div style="background:#fff;padding:10px">
            <div style="border: 1px solid #dadada;padding:  20px;border-radius: 5px;line-height: 2;font-family: candara;font-size: 12pt;">
             Saludos ' . $nombres . ' ' . $apellidos . ' 
             <br/>
             Te damos la bienvenida a nuestra plataforma, para poder hacer uso de tu cuenta es necesario que ingreses al siguiente enlace de autentificaci&oacute;n:
             <br/>
             <br/>
             <a href="' . $url_autentificacion . '">VERIFICACI&Oacute;N DE CUENTA</a>
             <br/>
             <br/>
             Gracias por unirte a '.$___nombre_del_sitio.'
             <br/>
            </div>
        </div>
        <div style="background:#31b312;padding:4px 0px;color:#FFF;text-align:center;border-radius: 5px;">
        Cursos y capacitaciones Bolivia
        </div>
        </div>';
            SISTsendEmail($email, $subject, $body);
        }
    } else {
        echo "<script>alert('Verifica que no eres un Robot');history.back();</script>";
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
                    <h3>Registro de Usuario</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Llena el siguiente formulario para poder crear una cuenta en nuestra plataforma.
                    </p>
                </div>

                <?php echo $mensaje; ?>

                <?php
                if (!$sw_registro) {
                    ?>
                    <div class="boxForm ajusta_form_contacto">
                        <form class="form-horizontal validable" id="contactform" action="" method="post">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <b>DATOS DE USUARIO</b>
                                    <br/>
                                    <input class="form-control required string" type="text" name="nombres" placeholder="Nombre(s)" value="<?php echo $nombres; ?>" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="text" name="apellidos" placeholder="Apellidos" value="<?php echo $apellidos; ?>" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="number" name="ci" placeholder="C.I. (numero de carnet)" value="<?php echo $ci; ?>" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <hr style="margin: 5px;"/>
                                    <b>DATOS DE CONTACTO</b>
                                    <br/>
                                    <input class="form-control string" type="email" name="email" placeholder="Correo electr&oacute;nico" value="<?php echo $email; ?>" required=""/>
                                    <input class="form-control string" type="email" name="email_2" placeholder="Vuelve a ingresar tu Correo electr&oacute;nico" value="<?php echo $email_2; ?>" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control string" type="number" name="celular" placeholder="Celular" value="<?php echo $celular; ?>" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <hr style="margin: 5px;"/>
                                    <b>CONTRASE&Ntilde;A DE INGRESO</b>
                                    <br/>
                                    <input class="form-control string" type="password" name="password" placeholder="Contrase&ntilde;a" value="<?php echo $password; ?>" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control string" type="password" name="password2" placeholder="Repite la contrase&ntilde;a" required=""/>
                                </div>
                                <div class="col-sm-12">
                                    <hr style="margin: 5px;"/>
                                    <b>AREAS DE INTER&Eacute;S</b>
                                    <br/>
                                    <?php
                                    $rqc1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                    $cnt_areas_interes = num_rows($rqc1);
                                    $cnt_aux = 1;
                                    while ($rqc2 = fetch($rqc1)) {
                                        ?>
                                        <div class="col-md-4 col-xs-6">
                                            <div style="padding-bottom: 2px;">
                                                <label style="background: #FFF;width:100%;cursor:pointer;text-align:center;border-radius:5px;box-shadow:1px 1px 1px solid gray;">
                                                    <input type='checkbox' name='categoria-<?php echo $rqc2['id']; ?>' value='true' id="checkbox-<?php echo $cnt_aux; ?>"/> 
                                                    <?php echo $rqc2['titulo']; ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-12">
                                    <hr style="margin: 5px;"/>
                                    <div style="width:300px;margin:auto;">
                                        <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                        <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <input type="submit" name="crear-cuenta" class="btn btn-success" value="CREAR CUENTA">
                                </div>
                            </div>
                        </form>

                    </div>
                    <?php
                } else {
                    ?>
                    <p>
                        Tu cuenta ha sido creada exitosamente, para poder hacer uso de ella es necesario que ingreses al enlace de autentificaci&oacute;n que enviamos a tu correo: <?php echo $email; ?>
                    </p>
                    <hr/>
                    <p>
                        Gracias por unirte a <?php echo $___nombre_del_sitio; ?>
                    </p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <?php
                }
                ?>


                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>

            </div>
        </div>

    </section>
</div>                     
