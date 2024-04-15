<?php
/* mensaje */
$mensaje = "";

if (isset_post('editar')) {

    $id = administrador('id');

    $nombre = post('nombre');
    $nick = post('nick');
    $password = post('password');

    if ($nombre == '' || $nick == '' || $password == '') {
        echo "<script>alert('Debes llenar los campos correctamente!');history.back();</script>";
        exit;
    }

    $rs1 = query("SELECT nick FROM administradores WHERE nick='$nick' AND id<>'$id' ");

    if (num_rows($rs1) > 0) {
        echo "<script>alert('Nick ya existente!');history.back();</script>";
        exit;
    }

    query(" UPDATE administradores SET "
            . "nick='$nick',"
            . "password='$password'"
            . " WHERE id='$id' LIMIT 1 ");

    administradorSet('nick', $nick);
    administradorSet('empresa', $nombre);
    administradorSet('nombre', $nombre);

        movimiento('Edicion de datos de administrador', 'edicion-administrador', 'administrador', $id);

        $mensaje .= "<h3>Tus datos fueron actualizados Exitosamente!</h3>";
        $mensaje .= "<hr/>";
        
        $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> Tus datos fueron actualizados correctamente.
</div>';
}

$r1 = query("SELECT * FROM administradores WHERE id='" . administrador('id') . "'");
$r2 = fetch($r1);
?>



<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a >Configuraci&oacute;n</a></li>
            <li class="active">Mi Cuenta</li>
        </ul>
        <h3 class="page-header">
            <i class="fa fa-indent"></i> Edici&oacute;n de Datos <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<?php //editorTinyMCE('editor');   ?>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-cascade">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Cuenta de <?php echo administrador('empresa'); ?>
                    <span class="pull-right">
                        <a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div style="clear: both;"></div>
                    <div class="form-group has-success">
                        <label class="col-lg-2 col-md-3 control-label  text-success">Nickname</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" class="form-control form-cascade-control" name="nick" value="<?php echo $r2['nick']; ?>" required=""/>
                            <br/>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="form-group has-success">
                        <label class="col-lg-2 col-md-3 control-label  text-success">Contrase&ntilde;a</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="password" class="form-control form-cascade-control" name="password" value="<?php echo $r2['password']; ?>" required=""/>
                            <br/>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="form-group">
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="submit" name="editar" class="btn btn-success btn-lg btn-animate-demo" value="Actualizar Datos"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

