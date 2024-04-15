<?php
/* verif acceso */
if (!acceso_cod('adm-administradores')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

/* mensaje */
$mensaje = "";

/* agregar-administrador */
if (isset_post('agregar-administrador')) {

    $rverif1 = query("SELECT id FROM administradores WHERE nick='" . post('nick') . "' ");
    $cnt_verif1 = mysql_num_rows($rverif1);

    if (post('nick') == '' || post('password1') == '' || post('nombre') == '') {
        $mensaje = '<div class="alert alert-warning">
  <strong>Error!</strong> debes llenar los campos necesarios.
</div>';
    } elseif ($cnt_verif1 > 0) {
        $mensaje = '<div class="alert alert-warning">
  <strong>Error!</strong> elija otro nick de usuario.
</div>';
    } elseif (post('password1') !== post('password2')) {
        $mensaje = '<div class="alert alert-warning">
  <strong>Error!</strong> las contrase&ntilde;as no coinciden.
</div>';
    } else {
        query("INSERT INTO administradores ("
                . "nick,"
                . "password,"
                . "nombre,"
                . "ocupacion,"
                . "email,"
                . "nivel,"
                . "nombre_registro,"
                . "fecha_registro,"
                . "sw_infosicoes,"
                . "estado"
                . ") VALUES("
                . "'" . post('nick') . "',"
                . "'" . hash_password(post('password1')) . "',"
                . "'" . post('nombre') . "',"
                . "'" . post('ocupacion') . "',"
                . "'" . post('email') . "',"
                . "'" . "0" . "',"
                . "'" . post('nombre') . "',"
                . "'" . date("Y-m-d h:i:s") . "',"
                . "'1',"
                . "'1'"
                . ")");
        $id_nuevo_administrador = mysql_insert_id();
        movimiento('Creacion de administrador ['.$id_nuevo_administrador.']['.post('nombre').']', 'creacion-administrador', 'administrador', $id_nuevo_administrador);

        $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado correctamente.
</div>';
    }
}
?>


<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="administradores-listar.adm">Administradores</a></li>
            <li class="active">Crear nuevo</li>
        </ul>       
        <h3 class="page-header">
            <i class="fa fa-indent"></i> Crear nuevo administrador <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    DATOS DE NUEVO ADMINISTRADOR
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <form action="" method="post">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label> *
                                        <input name="nombre" type="text" class="form-control" id="email" placeholder="Introduce el nombre completo" required=""/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nick">Nick</label> *
                                        <input name="nick" type="text" class="form-control" id="email" placeholder="Introduce el nick de usuario" required=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Direcci&oacute;n de E-mail</label>
                                        <input name='email' class="form-control" id="email" placeholder="Introduce tu E-mail" value=""/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ocupacion">Cargo</label>
                                        <input name="ocupacion" class="form-control" id="email" placeholder="Introduce el cargo asignado" value=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password1">Nueva contrase&ntilde;a</label> *
                                        <input name="password1" type="password" class="form-control" placeholder="Introduce la nueva contrase&ntilde;a" value="" required/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password2">Repita la contrase&ntilde;a</label> *
                                        <input name="password2" type="password" class="form-control" placeholder="Repite la nueva contrase&ntilde;a" value="" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <input type="submit" name="agregar-administrador" class="btn btn-success btn-sm btn-block" value="AGREGAR ADMINISTRADOR"/>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>





