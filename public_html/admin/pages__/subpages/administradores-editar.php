<?php
/* verif acceso */
if (!acceso_cod('adm-administradores')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

/* mensaje */
$mensaje = '';

/* data */
$id_administrador = $get[2];

/* editar-datos */
if (isset_post('editar-datos')) {
    $nombre = post('nombre');
    $nick = post('nick');
    $ocupacion = post('ocupacion');
    $email = post('email');
    query("UPDATE administradores SET "
        . "nombre='$nombre',"
        . "nick='$nick',"
        . "ocupacion='$ocupacion',"
        . "email='$email' "
        . " WHERE id='$id_administrador' LIMIT 1 ");
    movimiento('Edicion datos de administrador - [' . $id_administrador . ']', 'edicion-administrador', 'administrador', $id_administrador);
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado exitosamente.
</div>';
}

/* editar-password */
if (isset_post('editar-password')) {
    if ((post('password1') !== '') && (post('password1') == post('password2'))) {
        $password = hash_password(post('password1'));
        query("UPDATE administradores SET "
            . "password='$password' "
            . " WHERE id='$id_administrador' AND estado='1' LIMIT 1 ");
        movimiento('Edicion password de administrador - [' . $id_administrador . ']', 'edicion-administrador', 'administrador', $id_administrador);
        $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado exitosamente.
</div>';
    } else {
        $mensaje = '<div class="alert alert-warning">
  <strong>Error!</strong> las contrase&ntilde;as no coinciden.
</div>';
    }
}


/* cierreapertura-caja */
if (isset_post('cierreapertura-caja')) {
    $sw_cierreapertura_caja = post('sw_cierreapertura_caja');
    query("UPDATE administradores SET "
        . "sw_cierreapertura_caja='$sw_cierreapertura_caja' "
        . " WHERE id='$id_administrador' AND estado='1' LIMIT 1 ");
    movimiento('Edicion CAJA apertura/cierre de administrador - [' . $id_administrador . ']', 'edicion-administrador', 'administrador', $id_administrador);
    $mensaje = '<div class="alert alert-success">
<strong>Exito!</strong> registro modificado exitosamente.
</div>';
}

/* registro */
$r1 = query("SELECT * FROM administradores WHERE id='$id_administrador' ");
$r2 = fetch($r1);
?>



<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="administradores-listar.adm">Administradores</a></li>
            <li class="active">Administradores</li>
        </ul>

        <h3 class="page-header">
            <i class="fa fa-indent"></i> Edici&oacute;n de administrador <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    DATOS DE ADMINISTRADOR
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <form action="" method="post" onsubmit="alert('DESHABILITADO');return false;">
                <div class="panel-body">
                    <div class="form-group has-success">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label> *
                                            <input name="nombre" class="form-control" id="email" placeholder="Introduce el Nombre Completo" value="<?php echo $r2['nombre']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nick">Nick</label> *
                                            <input name="nick" class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['nick']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Direcci&oacute;n de E-mail</label>
                                            <input name='email' class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['email']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ocupacion">Cargo</label>
                                            <input name="ocupacion" class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['ocupacion']; ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit" name="editar-datos" class="btn btn-success btn-sm btn-animate-demo btn-block" value="ACTUALIZAR DATOS" />
                </div>
            </form>
        </div>



        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    CONTRASE&Ntilde;A DE ADMINISTRADOR
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="form-group has-success">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password1">Nueva contrase&ntilde;a</label> *
                                            <input name="password1" type="password" class="form-control" placeholder="Introduce la nueva contrase&ntilde;a" value="" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password2">Repita la contrase&ntilde;a</label> *
                                            <input name="password2" type="password" class="form-control" placeholder="Repite la nueva contrase&ntilde;a" value="" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit" name="editar-password" class="btn btn-success btn-sm btn-animate-demo btn-block" value="ACTUALIZAR CONTRASE&Ntilde;A" />
                </div>
            </form>
        </div>


        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    CAJA : APERTURA/CIERRE
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="form-group has-success">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <?php
                                    $checked_a = '';
                                    $checked_b = ' checked="checked" ';
                                    if ($r2['sw_cierreapertura_caja'] == '1') {
                                        $checked_a = ' checked="checked" ';
                                        $checked_b = '';
                                    }
                                    ?>
                                    <div class="col-md-6">
                                        <div class="form-group text-center">
                                            <label>
                                                <input name="sw_cierreapertura_caja" type="radio" value="1" <?php echo $checked_a; ?>/> &nbsp; <b>HABILITADO</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-center">
                                            <label>
                                                <input name="sw_cierreapertura_caja" type="radio" value="0" <?php echo $checked_b; ?>/> &nbsp; <b>NO HABILITADO</b>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit" name="cierreapertura-caja" class="btn btn-success btn-sm btn-animate-demo btn-block" value="ACTUALIZAR" />
                </div>
            </form>
        </div>


    </div>

</div>


<hr />
<br />