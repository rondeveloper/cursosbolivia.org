<?php
/* mensaje */
$mensaje = '';

/* id organizador */
$id_organizador = organizador('id');

/* editar-organizador */
if (isset_post('editar-organizador')) {
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
        move_uploaded_file(archivo($nom_img), "contenido/imagenes/organizadores/$nombre_imagen");
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

$resultado1 = query("SELECT * FROM cursos_organizadores WHERE id='$id_organizador' ");
$producto = mysql_fetch_array($resultado1);

$total_registros = 1;
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
            <li class="active">Participantes</li>
        </ul>

        <div class="form-group pull-right">
            <a href="organizador/<?php echo $producto['titulo_identificador']; ?>.html" class="btn btn-warning active" target="_blank">
                <i class="fa fa-plus"></i> 
                VER PERFIL P&Uacute;BLICO
            </a> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> CUENTA DE ORGANIZADOR <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
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
                Datos de cuenta
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <br/>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>LOGO</th>
                                <th>NOMBRE</th>
                                <th>NOMBRE EXTENDIDO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $txt_estado = 'DESACTIVADO';
                            if ($producto['estado'] == '1') {
                                $txt_estado = '<b style="color:green;">ACTIVADO</b>';
                            }
                            ?>
                            <tr>
                                <td><img src="contenido/imagenes/organizadores/<?php echo $producto['imagen']; ?>" style="height:50px;"/></td>
                                <td><?php echo $producto['titulo']; ?></td>
                                <td><?php echo $producto['nombre_extendido']; ?></td>
                                <td><?php echo $txt_estado; ?></td>
                                <td>                                   
                                    <a data-toggle="modal" data-target="#MODAL-editar-organizador-<?php echo $producto['id']; ?>" title="Editar">
                                        <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
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
                                                                                while ($rqd2 = mysql_fetch_array($rqd1)) {
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

                                </td>
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

