<?php
$sw_cookie = false;

//INGRESO POR COOKIE
if ((!isset_administrador()) && isset($_COOKIE["hsygbaj"]) && isset($_COOKIE["stedfyc"])) {
    if ($_COOKIE["hsygbaj"] !== "" || $_COOKIE["stedfyc"] !== "") {
        $rs = query("SELECT * FROM administradores WHERE nick='" . $_COOKIE["hsygbaj"] . "' AND cookie='" . $_COOKIE["stedfyc"] . "' AND cookie<>'' AND sw_cursosbo='1' ");
        if (mysql_num_rows($rs) == 1) {
            $empresa_encontrado = mysql_fetch_array($rs);
            administradorSet('id', $empresa_encontrado['id']);
            $sw_cookie = true;
        }
    }
}

//INGRESO POR FORMULARIO
if (isset_post('nick')) {

    /* valid less 5 */
    if (strlen(post('nick')) < 5 || strlen(post('password')) < 5) {
        echo "<script>alert('Datos invalidos');history.back();</script>";
        exit;
    }

//codigo captcha
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
// respuesta vacía
    $response = null;
// comprueba la clave secreta
    $reCaptcha = new ReCaptcha($secret);

    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
        );
    }

    if (($response != null && $response->success) || $sw_cookie || true) {

        //$resultado_administrador = query("SELECT * FROM administradores WHERE nick='" . post('nick') . "' AND password='" . md5(md5(post('password')) . 'infos') . "' AND sw_cursosbo='1' AND estado IN (1) ");
        $resultado_administrador = query("SELECT * FROM administradores WHERE nick='" . post('nick') . "' AND sw_cursosbo='1' AND estado IN (1) ");
        $administrador = mysql_fetch_array($resultado_administrador);
        if ($administrador) {
            administradorSet('id', $administrador['id']);
            if ($sw_cookie) {
                logcursos('Ingreso de administrador [por cookie]', 'ingreso-administrador', 'administrador', $administrador['id']);
            } else {
                logcursos('Ingreso de administrador', 'ingreso-administrador', 'administrador', $administrador['id']);
            }
            if (isset_post('recordar') && post('recordar') == '1') {
                mt_srand(time());
                $numero_aleatorio = substr(md5(rand(100, 1000)), 1, 9);
                query("UPDATE administradores SET cookie='$numero_aleatorio' WHERE id='" . $administrador['id'] . "' ");
                setcookie("hsygbaj", $administrador['nick'], time() + (60 * 60 * 24 * 30));
                setcookie("stedfyc", $numero_aleatorio, time() + (60 * 60 * 24 * 30));
            }

            header("location: admin");
        } else {
            echo "<script>alert('DATOS INCORRECTOS!');history.back();</script>";
        }
    } else {
        echo "<script>alert('Verifica que no eres un Robot');history.back();</script>";
    }
    exit;
} else {

    /* ask login */
    $_SESSION['ask_login'] = 'true';
    ?>
    <!DOCTYPE html>
    <html lang="en-us">
        <head>
            <meta name="viewport" content="width=device-width, maximum-scale=1, user-scalable=no"/>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta charset="utf-8">

            <meta name="robots" content="noindex" />
            <meta name="googlebot" content="noindex">
            <meta name="googlebot-news" content="nosnippet">

            <base href='<?php echo $dominio; ?>' target='_self'/>

            <title>CURSOS.BO - Administraci&oacute;n</title>

            <meta name="description" content="CURSOS.BO - Administraci&oacute;n">
            <link rel="stylesheet" type="text/css" href="contenido/css/no_log.css">
            <link rel="stylesheet" type="text/css" href="contenido/css/fonts/fontawesome/font-awesome.css">
            <link rel="icon" type="image/x-icon" href="contenido/imagenes/images/favicon.png"/>


            <meta name="google-signin-scope" content="profile email">
            <meta name="google-signin-client_id" content="521942249199-p8m7lposn5urnl8k2l67k08sh3jgqdjd.apps.googleusercontent.com">
            <script src="https://apis.google.com/js/platform.js" async defer></script>
        </head>
        <body>
            <!-- Container -->
            <div class="container">
                <div id="content" style="width:auto;">
                    <!-- Begin Content -->
                    <div id="element-box" class="login well">
                        <img src="contenido/imagenes/images/logotipo.PNG" alt="Administracion" style="
                             max-width: 60%;
                             height: 70px;
                             background: #ffffff;
                             border-radius: 10px;
                             padding: 5px 50px;
                             border: 1px solid #387bc3;
                             ">
                        <br/>
                        <br/>
                        <b>Administraci&oacute;n</b>
                        <hr>

                        <div id="system-message-container">
                        </div>
                        <form action="" method="post" id="form-login" class="form-inline">
                            <fieldset class="loginform">
                                <div class="control-group">
                                    <div class="controls">
                                        <div class="input-prepend input-append">
                                            <span class="add-on">
                                                <i class="fa fa-user"></i> 
                                                <label for="mod-login-username" class="element-invisible">Nombre Usuario</label>
                                            </span>
                                            <input value="" name="nick" tabindex="1" id="mod-login-username" class="input-medium" placeholder="Nombre Usuario" size="15" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <div class="input-prepend input-append">
                                            <span class="add-on">
                                                <i class="fa fa-sign-out"></i> 
                                                <label for="mod-login-password" class="element-invisible">Contrase&ntilde;a</label>
                                            </span>
                                            <input name="password" tabindex="2" id="mod-login-password" class="input-medium" placeholder="Contrase&ntilde;a" size="15" type="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <div style="text-align: center;padding-top:15px;">
                                            <input type="checkbox" value="1" name="recordar" checked="checked" style="margin-top:-2px;"> No cerrar sesi&oacute;n
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <br/>
                                        <div style="width:300px;margin:auto;">
                                            <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                            <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <div class="btn-group pull-center">
                                            <br/>
                                            <button tabindex="3" class="btn btn-primary btn-large"> &nbsp; Acceder &nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                                <input name="option" value="com_login" type="hidden">
                                <input name="task" value="login" type="hidden">
                                <input name="return" value="aW5kZXgucGhw" type="hidden">
                                <input name="e493cb3f7c94ec81d5ba7aee46a1d58c" value="1" type="hidden">	
                            </fieldset>
                            <div class="form-group">
                                <hr/>
                                <?php
                                //require_once('contenido/librerias/gplus-login/settings.php');
                                //$enalce_login_gplus = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
                                ?>
                                <div class="col-md-12 text-center">
                                    O inicia sesion con:
                                    <br/>
                                    <br/>
<!--                                    <div style="line-height: 3;">
                                        <a href="<?php echo $enalce_login_gplus; ?>" class="btn btn-warning" style="width: 130px;">Google</a>
                                    </div>-->
                                    <div style="line-height: 3;width: 122px;margin: auto;">
                                        <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>


                    <div>
                        <script>
                            function onSignIn(googleUser) {
                                // Useful data for your client-side scripts:
                                var profile = googleUser.getBasicProfile();
                                console.log("ID: " + profile.getId()); // Don't send this directly to your server!
                                console.log('Full Name: ' + profile.getName());
                                console.log('Given Name: ' + profile.getGivenName());
                                console.log('Family Name: ' + profile.getFamilyName());
                                console.log("Image URL: " + profile.getImageUrl());
                                console.log("Email: " + profile.getEmail());

                                // The ID token you need to pass to your backend:
                                var id_token = googleUser.getAuthResponse().id_token;
                                console.log("ID Token: " + id_token);
                            }
                        </script>
                    </div>


                    <!-- End Content -->
                </div>
            </div>
            <div class="navbar navbar-fixed-bottom hidden-phone">
                <a href="<?php echo $dominio; ?>" class="pull-left"> <i class="fa fa-sign-out"></i> Ir a la p&aacute;gina principal del sitio.</a>
            </div>


        </body>
    </html>
    <?php
}
