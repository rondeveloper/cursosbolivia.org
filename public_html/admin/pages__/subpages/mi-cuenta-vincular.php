<?php
/* mensaje */
$mensaje = '';

$id_administrador = administrador('id');


/* desvicular-google */
if (isset($get[2]) && $get[2] == 'desvicular-google') {
    query("UPDATE administradores SET gplus_id='',gplus_url='',gplus_email='',gplus_name='' WHERE id='$id_administrador' ");
}

/* desvicular-facebook */
if (isset($get[2]) && $get[2] == 'desvicular-facebook') {
    query("UPDATE administradores SET fb_id='',fb_url='',fb_email='',fb_name='' WHERE id='$id_administrador' ");
}

/* ask vincular cuenta */
$_SESSION['ask_vincular'] = 'true';

/* datos */
$rqdu1 = query("SELECT * FROM administradores WHERE id='$id_administrador' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);

/* datos gplus */
$gplus_id = $rqdu2['gplus_id'];
$gplus_url = $rqdu2['gplus_url'];
$gplus_email = $rqdu2['gplus_email'];
$gplus_name = $rqdu2['gplus_name'];

/* datos facebook */
$fb_id = $rqdu2['fb_id'];
$fb_url = $rqdu2['fb_url'];
$fb_email = $rqdu2['fb_email'];
$fb_name = $rqdu2['fb_name'];
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="mi-cuenta.adm">Mi cuenta</a></li>
            <li class="active">Vincular cuenta</li>
        </ul>

        <div class="form-group pull-right">
            <!--            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-banner">
                            <i class="fa fa-plus"></i> 
                            AGREGAR BANNER
                        </button> &nbsp;&nbsp;-->
        </div>
        <h3 class="page-header"> VINCULAR CUENTA <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de banners registradas.
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Vinculaci&oacute;n de cuentas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>
                                    Proveedor
                                </th>
                                <th>
                                    VINCULACI&Oacute;N
                                </th>
                                <th>
                                    Cuenta
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Acci&oacute;n
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                require_once($___path_raiz.'contenido/librerias/gplus-login/settings.php');
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
                                        <b style="color:green;">SI</b>
                                    </td>
                                    <td>
                                        <?php echo $gplus_name; ?>
                                    </td>
                                    <td>
                                        <?php echo $gplus_email; ?>
                                    </td>
                                    <td>
                                        <a href="mi-cuenta-vincular/desvicular-google.adm" class="btn btn-xs btn-default">
                                            <i class="fa fa-trash-o"></i> DESVINCULAR
                                        </a>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td>
                                        <b style="color:gray;">NO</b>
                                    </td>
                                    <td colspan="2">
                                        -
                                    </td>
                                    <td>
                                        <a href="<?php echo $enalce_login_gplus; ?>" class="btn btn-xs btn-warning active">
                                            VINCULAR CUENTA
                                        </a>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>
                                    <a href="<?php echo $dominio; ?>contenido/librerias/facebook-login/fbconfig.php">
                                        Facebook
                                    </a>
                                </td>
                                <?php
                                if ($fb_id !== '') {
                                    ?>
                                    <td>
                                        <b style="color:green;">SI</b>
                                    </td>
                                    <td>
                                        <?php echo $fb_name; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($fb_usuario == '') {
                                            echo "Email no proporcionado";
                                        } else {
                                            echo $fb_usuario;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="mi-cuenta-vincular/desvicular-facebook.adm" class="btn btn-xs btn-default">
                                            <i class="fa fa-trash-o"></i> DESVINCULAR
                                        </a>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td>
                                        <b style="color:gray;">NO</b>
                                    </td>
                                    <td colspan="2" id="testLOGIN">
                                        Sin cuenta vinculada
                                    </td>
                                    <td>
                                        <a href="<?php echo $dominio; ?>contenido/librerias/facebook-login/fbconfig.php" class="btn btn-xs btn-warning active">
                                            VINCULAR CUENTA
                                        </a>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>




    </div>
</div>
