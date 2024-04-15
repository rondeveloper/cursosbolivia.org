<?php
/* verif acceso */
if (!acceso_cod('adm-bancos')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 100;
$start = ($vista - 1) * $registros_a_mostrar;

$buscar = "";
$qr_busqueda = "";
if (isset_post('buscar') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
    $qr_busqueda = " AND (banco LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-banco */
if (isset_post('agregar-banco')) {
    $id_banco = post('id_banco');
    $numero_cuenta = post('numero_cuenta');
    $titular = post('titular');
    $descripcion = post('descripcion');
    $estado = post('estado');

    $rv1 = query("SELECT id FROM cuentas_de_banco WHERE numero_cuenta='$numero_cuenta' ORDER BY id DESC limit 1 ");
    if (num_rows($rv1) == 0) {

        query("INSERT INTO cuentas_de_banco ("
                . "id_banco,"
                . "numero_cuenta,"
                . "titular,"
                . "descripcion,"
                . "estado"
                . ") VALUES("
                . "'$id_banco',"
                . "'$numero_cuenta',"
                . "'$titular',"
                . "'$descripcion',"
                . "'$estado'"
                . " ) ");
        $id_banco = insert_id();
        logcursos('Agregado de banco', 'c-banco-creacion', 'c-banco', $id_banco);
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> la cuenta : ' . $banco . ' ya existe en la base de datos.
</div>';
    }
}


/* editar-cuenta */
if (isset_post('editar-cuenta')) {
    $id_cuenta = post('id_cuenta');
    $id_banco = post('id_banco');
    $numero_cuenta = post('numero_cuenta');
    $titular = post('titular');
    $descripcion = post('descripcion');
    $estado = post('estado');

    query("UPDATE cuentas_de_banco SET "
            . "id_banco='$id_banco',"
            . "numero_cuenta='$numero_cuenta',"
            . "titular='$titular',"
            . "descripcion='$descripcion',"
            . "estado='$estado'"
            . " WHERE id='$id_cuenta' LIMIT 1 ");
    logcursos('Edicion de cuenta de banco', 'c-banco-edicion', 'c-banco', $id_cuenta);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-cuenta */
if (isset_post('eliminar-cuenta')) {
    $id_banco = post('id_banco');
    query("UPDATE cuentas_de_banco SET "
            . "estado='0' "
            . " WHERE id='$id_banco' LIMIT 1 ");
    logcursos('Eliminacion de banco', 'banco-eliminacion', 'banco', $id_banco);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}


/* editar-banco */
if (isset_post('editar-banco')) {
    $id_banco = post('id_banco');
    $nombre = post('nombre');
    $imagen = post('previous_image');
    $estado = post('estado');

    if (isset_archivo('imagen')) {
        $imagen = rand(999, 999999) . archivoName('imagen');
        move_uploaded_file(archivo('imagen'), $___path_raiz."contenido/imagenes/bancos/" . $imagen);
    }

    query("UPDATE bancos SET "
            . "nombre='$nombre',"
            . "imagen='$imagen',"
            . "estado='$estado'"
            . " WHERE id='$id_banco' LIMIT 1 ");
    logcursos('Edicion de banco', 'banco-edicion', 'banco', $id_banco);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro editado correctamente.
</div>';
}

$resultado1 = query("SELECT c.*,(b.nombre)nombre_banco FROM cuentas_de_banco c LEFT JOIN bancos b ON c.id_banco=b.id WHERE c.estado IN ('1','2') ORDER BY c.id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cuentas_de_banco WHERE estado IN ('1','2') ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="whatsapp-bancos.adm">Whatsapp bancos</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-banco">
                <i class="fa fa-plus"></i> 
                AGREGAR CUENTA BANCARIA
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> CUENTAS BANCARIAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de bancos.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de cuentas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DESCRIPCI&Oacute;N</th>
                                <th>BANCO</th>
                                <th>N&Uacute;MERO DE CUENTA</th>
                                <th>TITULAR</th>
                                <th>ASIG. CURSO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = '<i class="label label-default">DESACTIVADO</i>';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<i class="label label-success">ACTIVADO</i>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo $producto['descripcion']; ?></td>
                                    <td><?php echo $producto['nombre_banco']; ?></td>
                                    <td><?php echo $producto['numero_cuenta']; ?></td>
                                    <td><?php echo $producto['titular']; ?></td>
                                    <td><?php echo $producto['sw_asignar'] == '1' ? '<i class="label label-success">Habilitado</i>' : '<i class="label label-default">No habilitado</i>'; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-cuenta-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-cuenta-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-cuenta-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE BANCO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control"  name="descripcion" value="<?php echo $producto['descripcion']; ?>" required placeholder="Descripci&oacute;n..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">BANCO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="id_banco">
                                                                                    <?php
                                                                                    $rqdbi1 = query("SELECT id,nombre FROM bancos WHERE estado='1' ");
                                                                                    while ($rqdbi2 = fetch($rqdbi1)) {
                                                                                        $text_select = '';
                                                                                        if ($rqdbi2['id'] == $producto['id_banco']) {
                                                                                            $text_select = ' selected="selected" ';
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $rqdbi2['id']; ?>" <?php echo $text_select; ?>><?php echo $rqdbi2['nombre']; ?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">N&Uacute;MERO DE CUENTA</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="numero_cuenta" value="<?php echo $producto['numero_cuenta']; ?>" required placeholder="N&uacute;mero de cuenta..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">TITULAR</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="titular" value="<?php echo $producto['titular']; ?>" required placeholder="Titular de cuenta..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">ESTADO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="estado">
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['estado'] == '1') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="1" <?php echo $text_select; ?> >ACTIVADO</option>
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['estado'] == '2') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="2" <?php echo $text_select; ?> >DESACTIVADO</option>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_cuenta" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-cuenta" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div id="MODAL-eliminar-cuenta-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE CUENTA DE BANCO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar la cuenta ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">DESCRIPCION</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['descripcion']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_banco" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-cuenta" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR CUENTA"/>
                                                                    &nbsp; 
                                                                    <button type="button" class="btn btn-default btn-sm btn-animate-demo" data-dismiss="modal">CANCELAR</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>


        <hr>

        <?php
        $resultadob1 = query("SELECT * FROM bancos WHERE estado IN ('1','2') ");
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de bancos
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>IMAGEN</th>
                                <th>BANCO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($producto = fetch($resultadob1)) {
                                $txt_estado = '<i class="label label-default">DESACTIVADO</i>';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<i class="label label-success">ACTIVADO</i>';
                                }
                                $url_imagen = $dominio_www.'contenido/imagenes/images/imagen-no-disponible.png';
                                if ($producto['imagen'] != '') {
                                    $url_imagen = $dominio_www.'contenido/imagenes/bancos/' . $producto['imagen'];
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td class="text-center" style="width: 80px;"><img src="<?php echo $url_imagen; ?>" style="width: 70px;height: 70px;"/></td>
                                    <td><?php echo $producto['nombre']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>
                                        <a data-toggle="modal" data-target="#MODAL-editar-banco-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-banco-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-banco-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE BANCO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post" enctype="multipart/form-data">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">BANCO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control"  name="nombre" value="<?php echo $producto['nombre']; ?>" required placeholder="Nombre..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NUEVA IMAGEN</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="file" class="form-control form-cascade-control" name="imagen" value="" placeholder="imagen..."/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">ESTADO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="estado">
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['estado'] == '1') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="1" <?php echo $text_select; ?> >ACTIVADO</option>
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['estado'] == '2') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="2" <?php echo $text_select; ?> >DESACTIVADO</option>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_banco" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="hidden" name="previous_image" value="<?php echo $producto['imagen']; ?>"/>
                                                                    <input type="submit" name="editar-banco" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR BANCO"/>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div id="MODAL-eliminar-banco-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE BANCO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar el banco ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['nombre']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_banco" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-banco" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR BANCO"/>
                                                                    &nbsp; 
                                                                    <button type="button" class="btn btn-default btn-sm btn-animate-demo" data-dismiss="modal">CANCELAR</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-banco" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA CUENTA BANCARIA</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva cuenta.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control"  name="descripcion" value="" required placeholder="Descripci&oacute;n..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">BANCO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="id_banco">
                                            <?php
                                            $rqdbi1 = query("SELECT id,nombre FROM bancos WHERE estado='1' ");
                                            while ($rqdbi2 = fetch($rqdbi1)) {
                                                ?>
                                                <option value="<?php echo $rqdbi2['id']; ?>"><?php echo $rqdbi2['nombre']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">N&Uacute;MERO DE CUENTA</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="numero_cuenta" value="" required placeholder="N&uacute;mero de cuenta..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">TITULAR</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="titular" value="" required placeholder="Titular de cuenta..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">ESTADO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="estado">
                                            <option value="1">ACTIVADO</option>
                                            <option value="2">DESACTIVADO</option>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" name="agregar-banco" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR BANCO"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>


<!-- Modal cursos_asignados -->
<div id="MODAL-cursos_asignados" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog" style="width: 850px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> CURSOS DEL BANCO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-cursos_asignados"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    function cursos_asignados(id_banco, id_curso) {
        $("#AJAXCONTENT-cursos_asignados").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.whatsapp-bancos.cursos_asignados.php',
            data: {id_banco: id_banco, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_asignados").html(data);
            }
        });
    }
</script>
