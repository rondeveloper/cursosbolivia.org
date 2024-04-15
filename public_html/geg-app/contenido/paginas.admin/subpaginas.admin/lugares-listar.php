<?php
/* acceso */
if (!acceso_cod('adm-lugares')) {
    echo "DENEGADO";
    exit;
}

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
if (isset_post('buscador') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } elseif (isset($get[5])) {
        $buscar = $get[5];
    }
    if (isset_post('id_ciudad') && post('id_ciudad') !== '0') {
        $id_ciudad = post('id_ciudad');
        $qr_busqueda = " AND l.id_ciudad='$id_ciudad' AND ( l.nombre LIKE '%$buscar%') ";
    } elseif (isset_post('id_departamento') && post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_busqueda = " AND l.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') AND ( l.nombre LIKE '%$buscar%') ";
    } else {
        $qr_busqueda = " AND ( l.nombre LIKE '%$buscar%') ";
    }

    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-lugar */
if (isset_post('agregar-lugar')) {
    $nombre = post('nombre');
    $id_ciudad = post('id_ciudad');
    $salon = post('salon');
    $direccion = post('direccion');
    $google_maps = post_html('google_maps');
    $estado = post('estado');

    $result = query("INSERT INTO cursos_lugares("
            . "id_ciudad,"
            . "nombre,"
            . "salon,"
            . "direccion,"
            . "google_maps,"
            . "estado"
            . ") VALUES("
            . "'$id_ciudad',"
            . "'$nombre',"
            . "'$salon',"
            . "'$direccion',"
            . "'$google_maps',"
            . "'$estado'"
            . " ) ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
}


/* editar-lugar */
if (isset_post('editar-lugar')) {
    $id_lugar = post('id_lugar');
    $nombre = post('nombre');
    $id_ciudad = post('id_ciudad');
    $salon = post('salon');
    $direccion = post('direccion');
    $google_maps = post_html('google_maps');
    $estado = post('estado');

    query("UPDATE cursos_lugares SET "
            . "nombre='$nombre',"
            . "id_ciudad='$id_ciudad',"
            . "salon='$salon',"
            . "direccion='$direccion',"
            . "google_maps='$google_maps',"
            . "estado='$estado'"
            . " WHERE id='$id_lugar' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-banner */
if (isset_post('eliminar-banner')) {
    $id_lugar = post('id_lugar');

    query("UPDATE cursos_lugares SET "
            . "estado='0' "
            . " WHERE id='$id_lugar' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT l.id,l.nombre,l.estado,l.salon,l.direccion,l.google_maps,d.nombre as departamento,c.nombre as ciudad FROM cursos_lugares l INNER JOIN ciudades c ON l.id_ciudad=c.id INNER JOIN departamentos d ON c.id_departamento=d.id WHERE l.estado IN ('1','2') $qr_busqueda ORDER BY d.orden ASC,c.nombre ASC,l.nombre ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_lugares l WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Lugares</li>
        </ul>

        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-lugar">
                <i class="fa fa-plus"></i> 
                AGREGAR LUGAR
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> LUGARES <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de lugares registrados.
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="col-md-6">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                    <input type="text" name="buscar" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Ingrese criterio de busqueda de lugar..."/>
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();">
                    <?php
                    echo "<option value='0'>Todos los departamentos...</option>";
                    $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                    while ($rqd2 = mysql_fetch_array($rqd1)) {
                        $text_check = '';
                        if ($id_departamento == $rqd2['id']) {
                            $text_check = ' selected="selected" ';
                        }
                        echo "<option value='" . $rqd2['id'] . "' $text_check>" . $rqd2['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_ciudad" id="select_ciudad">
                    <?php
                    echo "<option value='0'>Todos las ciudades...</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" value="BUSCAR" name="buscador" class="btn btn-warning btn-block active"/>
            </div>
        </form>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <br/>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de lugares
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DEPARTAMENTO</th>
                                <th>CIUDAD</th>
                                <th>NOMBRE</th>
                                <th>SAL&Oacute;N</th>
                                <th>DIRECCI&Oacute;N</th>
                                <th>GOOGLE MAPS</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = mysql_fetch_array($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<b style="color:green;">ACTIVADO</b>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo $producto['departamento']; ?></td>
                                    <td><?php echo $producto['ciudad']; ?></td>
                                    <td><?php echo $producto['nombre']; ?></td>
                                    <td><?php echo $producto['salon']; ?></td>
                                    <td><?php echo $producto['direccion']; ?></td>
                                    <td><?php echo substr(str_replace('<', '< ', $producto['google_maps']), 0, 70); ?>...</td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-lugar-<?php echo $producto['id']; ?>" title="EDITAR">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-banner-<?php echo $producto['id']; ?>" title="ELIMINAR">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-lugar-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE LUGAR</h4>
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
                                                                                    <select class="form-control form-cascade-control" name="id_departamento" id="select_departamento_r<?php echo $producto['id']; ?>" onchange="actualiza_ciudades_r('<?php echo $producto['id']; ?>');">
                                                                                        <?php
                                                                                        //$rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ");
                                                                                        $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                                                                        $aux_last_id_departamento = '0';
                                                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                                                            $selected = '';
                                                                                            if ($producto['departamento'] == $rqd2['nombre']) {
                                                                                                $selected = ' selected="selected" ';
                                                                                                $aux_last_id_departamento = $rqd2['id'];
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['nombre']; ?></option>
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
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">CIUDAD</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <select class="form-control form-cascade-control" name="id_ciudad" id="select_ciudad_r<?php echo $producto['id']; ?>">
                                                                                        <?php
                                                                                        $rqd1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$aux_last_id_departamento' AND estado='1' ORDER BY nombre ASC ");
                                                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                                                            $selected = '';
                                                                                            if ($producto['ciudad'] == $rqd2['nombre']) {
                                                                                                $selected = ' selected="selected" ';
                                                                                            }
                                                                                            echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="nombre" value="<?php echo $producto['nombre']; ?>" required placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">SAL&Oacute;N</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="salon" value="<?php echo $producto['salon']; ?>" placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">DIRCCI&Oacute;N</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <textarea class="form-control form-cascade-control" name="direccion" rows="3"><?php echo $producto['direccion']; ?></textarea>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">GOOGLE MAPS</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="google_maps" value='<?php echo $producto['google_maps']; ?>' required placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
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
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_lugar" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-lugar" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-banner-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE LUGAR</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al lugar ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['nombre']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">SAL&Oacute;N</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nit_ci" value="<?php echo $producto['salon']; ?>" required placeholder="N&uacute;mero de NIT o CI..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_lugar" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-banner" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR LUGAR"/>
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

                    <li><a href="lugares-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="lugares-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="lugares-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-lugar" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO LUGAR</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo lugar.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                        <div class="col-lg-9 col-md-9">
                                            <select class="form-control form-cascade-control" name="id_departamento" id="select_departamento_t" onchange="actualiza_ciudades_t();">
                                                <?php
                                                $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                                $aux_cnt = 0;
                                                while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                    if ($aux_cnt==0) {
                                                        $aux_last_id_departamento = $rqd2['id'];
                                                        $aux_cnt++;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $rqd2['id']; ?>"><?php echo $rqd2['nombre']; ?></option>
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
                                        <label class="col-lg-3 col-md-3 control-label text-primary">CIUDAD</label>
                                        <div class="col-lg-9 col-md-9">
                                            <select class="form-control form-cascade-control" name="id_ciudad" id="select_ciudad_t">
                                                <?php
                                                $rqd1 = query("SELECT id,nombre FROM ciudades WHERE id_departamento='$aux_last_id_departamento' AND estado='1' ORDER BY nombre ASC ");
                                                while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                    $selected = '';
                                                    if ($curso['ciudad'] == $rqd2['nombre']) {
                                                        $selected = ' selected="selected" ';
                                                    }
                                                    echo '<option value="' . $rqd2['id'] . '" ' . $selected . ' >' . $rqd2['nombre'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <br/>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                        <div class="col-lg-9 col-md-9">
                                            <input type="text" class="form-control form-cascade-control" name="nombre" value="" required placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
                                            <br/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 control-label text-primary">SAL&Oacute;N</label>
                                        <div class="col-lg-9 col-md-9">
                                            <input type="text" class="form-control form-cascade-control" name="salon" value="" placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
                                            <br/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 control-label text-primary">DIRECCI&Oacute;N</label>
                                        <div class="col-lg-9 col-md-9">
                                            <input type="text" class="form-control form-cascade-control" name="direccion" value="" placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 control-label text-primary">GOOGLE MAPS</label>
                                        <div class="col-lg-9 col-md-9">
                                            <input type="text" class="form-control form-cascade-control" name="google_maps" value="" required placeholder="Descripci&oacute;n del lugar..." autocomplete="off"/>
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
                                <input type="submit" name="agregar-lugar" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR LUGAR"/>
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

<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0', sw_option_todos: '1'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>
<script>
    function actualiza_ciudades_r(dat) {
        $("#select_ciudad_r" + dat).html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento_r" + dat).val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad_r" + dat).html(data);
            }
        });
    }
</script>
<script>
    function actualiza_ciudades_t() {
        $("#select_ciudad_t").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento_t").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad_t").html(data);
            }
        });
    }
</script>
<script>
    actualiza_ciudades();
</script>


