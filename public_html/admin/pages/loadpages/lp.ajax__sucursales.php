<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php

/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 30;
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
    $qr_busqueda = " AND ( c.nombre LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-sucursal */
if (isset_post('agregar-sucursal')) {
    $nombre = post('nombre');
    $id_departamento = post('id_departamento');
    $direccion = post('direccion');
    $horarios_atencion = post('horarios_atencion');
    $num_celular = post('num_celular');
    $num_telefono = post('num_telefono');
    $estado = post('estado');
    
    $result = query("INSERT INTO sucursales("
        . "id_departamento,"
        . "nombre,"
        . "direccion,"
        . "horarios_atencion,"
        . "num_celular,"
        . "num_telefono,"
        . "estado"
        . ") VALUES("
        . "'$id_departamento',"
        . "'$nombre',"
        . "'$direccion',"
        . "'$horarios_atencion',"
        . "'$num_celular',"
        . "'$num_telefono',"
        . "'$estado'"
        . " ) ");
    $id_sucursal = insert_id();
    logcursos('Creacion de sucursal [' . $nombre . ']', 'sucursal-creacion', 'sucursal', $id_sucursal);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
}


/* editar-sucursal */
if (isset_post('editar-sucursal')) {
    $id_sucursal = post('id_sucursal');
    $nombre = post('nombre');
    $id_departamento = post('id_departamento');
    $direccion = post('direccion');
    $horarios_atencion = post('horarios_atencion');
    $num_celular = post('num_celular');
    $num_telefono = post('num_telefono');
    $estado = post('estado');

    query("UPDATE sucursales SET "
        . "nombre='$nombre',"
        . "id_departamento='$id_departamento',"
        . "direccion='$direccion',"
        . "horarios_atencion='$horarios_atencion',"
        . "num_celular='$num_celular',"
        . "num_telefono='$num_telefono',"
        . "estado='$estado'"
        . " WHERE id='$id_sucursal' LIMIT 1 ");
    logcursos('Edicion de ciudad', 'sucursal-edicion', 'sucursal', $id_sucursal);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro editado correctamente.
</div>';
}

/* eliminar-sucursal */
if (isset_post('eliminar-sucursal')) {
    $id_sucursal = post('id_sucursal');
    query("UPDATE sucursales SET "
        . "estado='0' "
        . " WHERE id='$id_sucursal' LIMIT 1 ");
    logcursos('Eliminacion de sucursal', 'sucursal-eliminacion', 'sucursal', $id_sucursal);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT s.*,(d.nombre)dr_departamento FROM sucursales s INNER JOIN departamentos d ON s.id_departamento=d.id WHERE s.estado IN ('1','2') $qr_busqueda ORDER BY d.orden ASC,s.nombre ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM sucursales c WHERE estado IN ('1','2') AND id<>'24' $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - (($vista - 1) * $registros_a_mostrar);
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-sucursal">
                <i class="fa fa-plus"></i>
                AGREGAR SUCURSAL
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> SUCURSALES <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de sucursales
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!--
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de ciudad..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_sucursales();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_sucursales();"><i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <br/>
                 -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOMBRE</th>
                                <th>DATOS</th>
                                <th>CONTACTO</th>
                                <th>ADMINISTRADORES</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<b style="color:green;">ACTIVADO</b>';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><b style="font-size: 14pt;"><?php echo $producto['nombre']; ?></b></td>
                                    <td>
                                        <b>Departamento:</b>
                                        <br>
                                        <?php echo $producto['dr_departamento']; ?>
                                        <br>
                                        <br>
                                        <b>Direcci&oacute;n:</b>
                                        <br>
                                        <?php echo $producto['direccion']; ?>
                                        <br>
                                        <br>
                                        <b>Horarios:</b>
                                        <br>
                                        <?php echo $producto['horarios_atencion']; ?>
                                    </td>
                                    <td>
                                        <b>Celular:</b> <?php echo $producto['num_celular']; ?>
                                        <br>
                                        <b>Tel&eacute;fono:</b> <?php echo $producto['num_telefono']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $rqdas1 = query("SELECT a.nombre FROM rel_admsuc r INNER JOIN administradores a ON a.id=r.id_administrador WHERE r.id_sucursal='" . $producto['id'] . "' ");
                                        if (num_rows($rqdas1) == 0) {
                                        ?>
                                            Sin administradores
                                        <?php
                                        }
                                        while ($rqdas2 = fetch($rqdas1)) {
                                        ?>
                                            <b class="text-info"><?php echo $rqdas2['nombre']; ?></b>
                                            <br>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>
                                        <a data-toggle="modal" data-target="#MODAL-editar-sucursal-<?php echo $producto['id']; ?>">
                                            <button type="button" class="btn btn-xs btn-info btn-block"><i class="fa fa-edit"></i> Editar</button>
                                        </a>
                                        <br>
                                        <button type="button" class="btn btn-xs btn-warning btn-block" onclick="administradores('<?php echo $producto['id']; ?>');">
                                            <i class="fa fa-group"></i> Administradores
                                        </button>
                                        <br>
                                        <a href="inventario-sucursal/<?php echo $producto['id']; ?>.adm" class="btn btn-xs btn-default btn-block">
                                            <i class="fa fa-list"></i> Inventario
                                        </a>
                                        <br>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-sucursal-<?php echo $producto['id']; ?>" class="btn btn-xs btn-danger btn-block">
                                            <i class="fa fa-times"></i> Eliminar
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-sucursal-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE SUCURSAL</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post" enctype="multipart/form-data">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <select class="form-control form-cascade-control" name="id_departamento">
                                                                                        <?php
                                                                                        $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                                                                        while ($rqd2 = fetch($rqd1)) {
                                                                                            $selected = '';
                                                                                            if ($producto['id_departamento'] == $rqd2['id']) {
                                                                                                $selected = ' selected="selected" ';
                                                                                            }
                                                                                        ?>
                                                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?>><?php echo $rqd2['nombre']; ?></option>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="nombre" value="<?php echo $producto['nombre']; ?>" required placeholder="Descripci&oacute;n del ciudad..." autocomplete="off" />
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">DIRECCI&Oacute;N</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="direccion" value="<?php echo $producto['direccion']; ?>" required placeholder="Direccion..." autocomplete="off" />
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">HORARIO DE ATENCI&Oacute;N</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="horarios_atencion" value="<?php echo $producto['horarios_atencion']; ?>" required placeholder="Horario..." autocomplete="off" />
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">CELULAR</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="num_celular" value="<?php echo $producto['num_celular']; ?>" placeholder="Celular..." autocomplete="off" />
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">TEL&Eacute;FONO</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="num_telefono" value="<?php echo $producto['num_telefono']; ?>" placeholder="Telefono..." autocomplete="off" />
                                                                                    <br />
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
                                                                                        <option value="1" <?php echo $text_select; ?>>ACTIVADO</option>
                                                                                        <?php
                                                                                        $text_select = '';
                                                                                        if ($producto['estado'] == '2') {
                                                                                            $text_select = ' selected="selected" ';
                                                                                        }
                                                                                        ?>
                                                                                        <option value="2" <?php echo $text_select; ?>>DESACTIVADO</option>
                                                                                    </select>
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_sucursal" value="<?php echo $producto['id']; ?>" />
                                                                    <input type="submit" name="editar-sucursal" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS" />
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
                                        <div id="MODAL-eliminar-sucursal-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE SUCURSAL</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar la sucursal ?
                                                                        </label>
                                                                        <br />
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['nombre']; ?>" required placeholder="Apellidos y nombres..." disabled="" />
                                                                            <br />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_sucursal" value="<?php echo $producto['id']; ?>" />
                                                                    <input type="submit" name="eliminar-sucursal" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR SUCURSAL" />
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



        <div class="row">
            <div class="col-md-12">
                <ul class="pagination">
                    <?php
                    $urlget3 = '';
                    if (isset($get[3])) {
                        $urlget3 = '/' . $get[3];
                    }
                    $urlget4 = '';
                    if (isset($get[4])) {
                        $urlget4 = '/' . $get[4];
                    }
                    $urlget5 = '';
                    if (isset($buscar)) {
                        if ($urlget3 == '') {
                            $urlget3 = '/--';
                        }
                        if ($urlget4 == '') {
                            $urlget4 = '/--';
                        }
                        $urlget5 = '/' . $buscar;
                    }
                    ?>

                    <li><a href="sucursales/1.adm">Primero</a></li>
                    <?php
                    $inicio_paginador = 1;
                    $fin_paginador = 15;
                    $total_cursos = ceil($total_registros / $registros_a_mostrar);

                    if ($vista > 10) {
                        $inicio_paginador = $vista - 5;
                        $fin_paginador = $vista + 10;
                    }
                    if ($fin_paginador > $total_cursos) {
                        $fin_paginador = $total_cursos;
                    }

                    if ($total_cursos > 1) {
                        for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                            if ($vista == $i) {
                                echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="sucursales/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li><a href="sucursales/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->
        </div>

    </div>
</div>



<script>
    function administradores(id_sucursal) {
        $("#TITLE-modgeneral").html('ADMINISTRADORES DE SUCURSAL');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.sucursales.administradores.php',
            data: {
                id_sucursal: id_sucursal
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- Modal -->
<div id="MODAL-agregar-sucursal" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA sucursal</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva sucursal.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="id_departamento">
                                            <?php
                                            $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                            while ($rqd2 = fetch($rqd1)) {
                                            ?>
                                                <option value="<?php echo $rqd2['id']; ?>"><?php echo $rqd2['nombre']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="nombre" value="" required placeholder="Nombre de la sucursal..." autocomplete="off" />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DIRECCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="direccion" value="" required placeholder="Direccion..." autocomplete="off" />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">HORARIO DE ATENCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="horarios_atencion" value="" required placeholder="Horario..." autocomplete="off" />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">CELULAR</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="num_celular" value="" placeholder="Celular..." autocomplete="off" />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">TEL&Eacute;FONO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="num_telefono" value="" placeholder="Telefono..." autocomplete="off" />
                                        <br />
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
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" name="agregar-sucursal" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR CIUDAD" />
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