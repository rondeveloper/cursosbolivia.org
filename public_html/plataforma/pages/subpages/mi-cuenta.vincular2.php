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

/* datos */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];

/* datos gplus */
$gplus_id = $rqdu2['gplus_id'];
$gplus_url = $rqdu2['gplus_url'];
$gplus_email = $rqdu2['gplus_email'];
$gplus_name = $rqdu2['gplus_name'];

/* datos facebook */
$fb_id = '';
$fb_url = '';
$fb_email = '';
$fb_name = '';

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #e8e8e8;">
            <div class="col-md-2">
                <?php
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">
                <div class="TituloArea">
                    <h3>VINCULAR CUENTAS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Accede a tu area de usuario de <?php echo $___nombre_del_sitio; ?> con tu cuenta de Google o Facebook.
                    </p>
                </div>

                <?php echo $mensaje; ?>

                <table class='table table-striped table-bordered'>
                    <tr>
                        <th>
                            Proveedor
                        </th>
                        <th>
                            Cuenta
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Perfil
                        </th>
                        <th>
                            Acci&oacute;n
                        </th>
                    </tr>
                    <tr>
                        <?php
                        require_once('../contenido/librerias/gplus-login/settings.php');
                        $enalce_login_gplus = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
                        ?>
                        <td>
                            <a href="<?php echo $enalce_login_gplus; ?>">
                                Google
                            </a>
                        </td>
                        <?php
                        if ($gplus_id !== '') {
                            ?>
                            <td>
                                <?php echo $gplus_name; ?>
                            </td>
                            <td>
                                <?php echo $email_usuario; ?>
                            </td>
                            <td>
                                <a href="<?php echo $gplus_url; ?>" target="_blank"><?php echo $gplus_url; ?></a>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-info">
                                    DESVINCULAR
                                </a>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td colspan="2">
                                Sin cuenta vinculada
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <a href="<?php echo $enalce_login_gplus; ?>" class="btn btn-xs btn-success">
                                    VINCULAR CUENTA
                                </a>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <td>
                            Facebook
                        </td>
                        <?php
                        if ($fb_id !== '') {
                            ?>
                            <td>
                                <?php echo $fb_name; ?>
                                <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{your-app-id}',
      cookie     : true,
      xfbml      : true,
      version    : '{api-version}'
    });
      
    FB.AppEvents.logPageView();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
                            </td>
                            <td>
                                <?php echo $fb_usuario; ?>
                            </td>
                            <td>
                                <a href="<?php echo $fb_url; ?>" target="_blank"><?php echo $fb_url; ?></a>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-info">
                                    DESVINCULAR
                                </a>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td colspan="2">
                                Sin cuenta vinculada
                            </td>
                            <td>
                                -
                            </td>
                            <td>
                                <a href="<?php //echo $enalce_login_gplus; ?>" class="btn btn-xs btn-success">
                                    VINCULAR CUENTA
                                </a>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>

                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>

                <hr/>




            </div>

        </div>

    </section>
</div>                     



<?php

function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
?>