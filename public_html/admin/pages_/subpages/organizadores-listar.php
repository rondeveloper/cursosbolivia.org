<?php
/* acceso */
if (!acceso_cod('adm-organizadores')) {
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
if (isset_post('buscar') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
    $qr_busqueda = " AND ( nombre_extendido LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-organizador */
if (isset_post('agregar-organizador')) {
    $nombre_extendido = post('nombre_extendido');
    $nom_img = 'imagen';
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $codigo = post('codigo');
    $id_departamento = post('id_departamento');
    $nit = post('nit');
    $direccion = post('direccion');
    $telefono = post('telefono');
    $correo = post('correo');
    $descripcion = post('descripcion');
    $google_maps = post_html('google_maps');
    $estado = post('estado');

    if (!isset_archivo($nom_img)) {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> no se selecciono ninguna imagen.
</div>';
    } else {

        $nombre_imagen = time() . archivoName($nom_img);
        move_uploaded_file(archivo($nom_img), $___path_raiz."contenido/imagenes/organizadores/$nombre_imagen");
        $result = query("INSERT INTO cursos_organizadores("
                . "imagen,"
                . "nombre_extendido,"
                . "titulo,"
                . "titulo_identificador,"
                . "codigo,"
                . "id_departamento,"
                . "nit,"
                . "direccion,"
                . "telefono,"
                . "correo,"
                . "descripcion,"
                . "google_maps,"
                . "estado"
                . ") VALUES("
                . "'$nombre_imagen',"
                . "'$nombre_extendido',"
                . "'$titulo',"
                . "'$titulo_identificador',"
                . "'$codigo',"
                . "'$id_departamento',"
                . "'$nit',"
                . "'$direccion',"
                . "'$telefono',"
                . "'$correo',"
                . "'$descripcion',"
                . "'$google_maps',"
                . "'$estado'"
                . " ) ");

        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    }
}


/* editar-organizador */
if (isset_post('editar-organizador')) {
    $id_organizador = post('id_organizador');
    $nombre_imagen = post('nombre_imagen');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $nombre_extendido = post('nombre_extendido');
    $codigo = post('codigo');
    $id_departamento = post('id_departamento');
    $nit = post('nit');
    $direccion = post('direccion');
    $telefono = post('telefono');
    $correo = post('correo');
    $descripcion = post('descripcion');
    $google_maps = post_html('google_maps');
    $estado = post('estado');
    $nom_img = 'imagen';
    if (isset_archivo($nom_img)) {
        $nombre_imagen = time() . archivoName($nom_img);
        move_uploaded_file(archivo($nom_img), $___path_raiz."contenido/imagenes/organizadores/$nombre_imagen");
    }
    query("UPDATE cursos_organizadores SET "
            . "imagen='$nombre_imagen',"
            . "nombre_extendido='$nombre_extendido',"
            . "titulo='$titulo',"
            . "titulo_identificador='$titulo_identificador',"
            . "codigo='$codigo',"
            . "id_departamento='$id_departamento',"
            . "nit='$nit',"
            . "direccion='$direccion',"
            . "telefono='$telefono',"
            . "correo='$correo',"
            . "descripcion='$descripcion',"
            . "google_maps='$google_maps',"
            . "estado='$estado'"
            . " WHERE id='$id_organizador' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-banner */
if (isset_post('eliminar-banner')) {
    $id_organizador = post('id_organizador');

    query("UPDATE cursos_organizadores SET "
            . "estado='0' "
            . " WHERE id='$id_organizador' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM cursos_organizadores WHERE estado IN ('1','2') $qr_busqueda ORDER BY id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_organizadores WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Participantes</li>
        </ul>

        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-organizador">
                <i class="fa fa-plus"></i> 
                AGREGAR ORGANIZADOR
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> ORGANIZADORES <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de organizadores registrados.
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de organizadores
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de organizador..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_organizadores();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_organizadores();"><i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <br/>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>LOGO</th>
                                <th>NOMBRE</th>
                                <th>NOMBRE EXTENDIDO</th>
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
                                    <td><img src="<?php echo $dominio_www; ?>contenido/imagenes/organizadores/<?php echo $producto['imagen']; ?>" style="height:50px;"/></td>
                                    <td><?php echo $producto['titulo']; ?></td>
                                    <td><?php echo $producto['nombre_extendido']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-organizador-<?php echo $producto['id']; ?>" title="EDITAR">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a href="organizadores-palabras-reservadas/<?php echo $producto['id']; ?>.adm" title="PALABRAS RESERVADAS">
                                            <button type="button" class="btn btn-warning active"><i class="fa fa-list"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-banner-<?php echo $producto['id']; ?>" title="ELIMINAR">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-organizador-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE ORGANIZADOR</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post" enctype="multipart/form-data">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NUEVO LOGO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="file" class="form-control form-cascade-control" name="imagen" value="" placeholder="Imagen del banner..."/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE EXTENDIDO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="nombre_extendido" value="<?php echo $producto['nombre_extendido']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['titulo']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">CODIGO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="codigo" value="<?php echo $producto['codigo']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="id_departamento">
                                                                                    <?php
                                                                                    $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ");
                                                                                    while ($rqd2 = fetch($rqd1)) {
                                                                                        $selected = '';
                                                                                        if ($producto['id_departamento'] == $rqd2['id']) {
                                                                                            $selected = ' selected="selected" ';
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
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NIT</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="nit" value="<?php echo $producto['nit']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">DIRECCI&Oacute;N</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="direccion" value="<?php echo $producto['direccion']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">TEL&Eacute;FONO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="telefono" value="<?php echo $producto['telefono']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">CORREO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="correo" value="<?php echo $producto['correo']; ?>" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <textarea class="form-control form-cascade-control" name="descripcion" rows="3" required><?php echo $producto['descripcion']; ?></textarea>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">GOOGLE MAPS</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="google_maps" value='<?php echo $producto['google_maps']; ?>' required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
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
                                                                    <input type="hidden" name="id_organizador" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="hidden" name="nombre_imagen" value="<?php echo $producto['imagen']; ?>"/>
                                                                    <input type="submit" name="editar-organizador" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE ORGANIZADOR</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al organizador ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NONBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['titulo']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE EXTENDIDO</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nit_ci" value="<?php echo $producto['nombre_extendido']; ?>" required placeholder="N&uacute;mero de NIT o CI..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_organizador" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-banner" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR ORGANIZADOR"/>
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

                    <li><a href="organizadores-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="organizadores-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="organizadores-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-organizador" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO ORGANIZADOR</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo organizador.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">LOGO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="file" class="form-control form-cascade-control" name="imagen" value="" placeholder="Imagen del organizador..." required=""/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE EXTENDIDO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="nombre_extendido" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="titulo" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">CODIGO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="codigo" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="id_departamento">
                                            <?php
                                            $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ");
                                            while ($rqd2 = fetch($rqd1)) {
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
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NIT</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="nit" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DIRECCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="direccion" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">TEL&Eacute;FONO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="telefono" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">CORREO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="correo" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <textarea class="form-control form-cascade-control" name="descripcion" rows="3" required></textarea>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">GOOGLE MAPS</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="google_maps" value="" required placeholder="Descripci&oacute;n del organizador..." autocomplete="off"/>
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
                            <input type="submit" name="agregar-organizador" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR ORGANIZADOR"/>
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
