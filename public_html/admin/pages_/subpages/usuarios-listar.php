<?php
/* acceso */
if (!acceso_cod('adm-usuarios')) {
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
    $qr_busqueda = " AND ( nombres LIKE '%$buscar%' OR apellidos LIKE '%$buscar%' OR email LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-usuario */
if (isset_post('agregar-usuario')) {
    $descripcion = post('descripcion');
    $estado = post('estado');
    $nom_img = 'imagen';

    if (!isset_archivo($nom_img)) {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> no se selecciono ninguna imagen.
</div>';
    } else {

        $nombre_imagen = time() . archivoName($nom_img);
        move_uploaded_file(archivo($nom_img), $___path_raiz."contenido/imagenes/usuarios/$nombre_imagen");
        $result = query("INSERT INTO cursos_usuarios("
                . "imagen,"
                . "descripcion,"
                . "estado"
                . ") VALUES("
                . "'$nombre_imagen',"
                . "'$descripcion',"
                . "'$estado'"
                . " ) ");

        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    }
}


/* editar-usuario */
if (isset_post('editar-usuario')) {
    $id_usuario = post('id_usuario');
    $nombres = post('nombres');
    $apellidos = post('apellidos');
    $email = post('email');
    $celular = post('celular');
    $estado = post('estado');
    $nom_img = 'imagen';
    if (isset_archivo($nom_img)) {
        $nombre_imagen = time() . archivoName($nom_img);
        move_uploaded_file(archivo($nom_img), $___path_raiz."contenido/imagenes/usuarios/$nombre_imagen");
    }
    query("UPDATE cursos_usuarios SET "
            . "nombres='$nombres',"
            . "apellidos='$apellidos',"
            . "email='$email',"
            . "celular='$celular',"
            . "estado='$estado'"
            . " WHERE id='$id_usuario' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-usuario */
if (isset_post('eliminar-usuario')) {
    $id_usuario = post('id_usuario');

    query("UPDATE cursos_usuarios SET "
            . "estado='0' "
            . " WHERE id='$id_usuario' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT *,(select count(*) from cursos_proceso_registro where id_usuario=u.id)cnt_cursos FROM cursos_usuarios u WHERE estado IN ('1','2') $qr_busqueda ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_usuarios WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="usuarios-listar.adm">Usuarios</a></li>
            <li class="active">Listado de usuarios</li>
        </ul>

        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-usuario">
                <i class="fa fa-plus"></i> 
                AGREGAR USUARIO
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> USUARIOS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de usuarios registrados.
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de usuarios
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de usuario..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_usuarios();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_usuarios();"><i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NOMBRES</th>
                                <th>APELLIDOS</th>
                                <th>EMAIL</th>
                                <th>CELULAR</th>
                                <th>CNT. CURSOS</th>
                                <th>INTERESES</th>
                                <th>ESTADO</th>
                                <th>FECHA REGISTRO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = 'NO AUTENTIFICADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = 'AUTENTIFICADO';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo $producto['nombres']; ?></td>
                                    <td><?php echo $producto['apellidos']; ?></td>
                                    <td><?php echo $producto['email']; ?></td>
                                    <td><?php echo $producto['celular']; ?></td>
                                    <td><?php echo $producto['cnt_cursos']; ?></td>
                                    <td>
                                        <?php
                                        $rqci1 = query("SELECT titulo FROM cursos_categorias WHERE id IN (select id_categoria from cursos_rel_usu_cat where id_usuario='".$producto['id']."') ");
                                        while($rqci2 = fetch($rqci1)){
                                            echo $rqci2['titulo'].'<br/>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td><?php echo $producto['fecha_registro']; ?></td>
                                    <td>
                                        <a data-toggle="modal" data-target="#MODAL-editar-usuario-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-usuario-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-usuario-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE USUARIO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRES</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['nombres']; ?>" required placeholder="Nombres del usuario..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">APELLIDOS</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="apellidos" value="<?php echo $producto['apellidos']; ?>" required placeholder="Apellidos del usuario..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">EMAIL</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="email" value="<?php echo $producto['email']; ?>" required placeholder="Email del usuario..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">CELULAR</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="celular" value="<?php echo $producto['celular']; ?>" required placeholder="Celular..." autocomplete="off"/>
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
                                                                                    <option value="1" <?php echo $text_select; ?> >AUTENTIFICADO</option>
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['estado'] == '2') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="2" <?php echo $text_select; ?> >NO AUTENTIFICADO</option>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_usuario" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-usuario" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-usuario-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE USUARIO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al usuario ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['nombres'].' '.$producto['apellidos']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">EMAIL</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nit_ci" value="<?php echo $producto['email']; ?>" required placeholder="..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_usuario" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-usuario" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR USUARIO"/>
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

                    <li><a href="usuarios-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="usuarios-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="usuarios-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-usuario" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO USUARIO</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo usuario.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">REGISTRO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <p>Puedes hacer el registro de nuevo usuario desde la pagina principal con el siguiente enlace:</p>
                                        <a href="registro-de-usuarios.html" class="btn btn-info" target="_blank">REGISTRAR NUEVO USUARIO</a>
                                        <br/>
                                    </div>
                                </div>
                            </div>
<!--                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">IMAGEN</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="file" class="form-control form-cascade-control" name="imagen" value="" required placeholder="Imagen del usuario..."/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="descripcion" value="" required placeholder="Descripci&oacute;n de la usuario..." autocomplete="off"/>
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
                            </div>-->
                        </div>
                        <div class="panel-footer">
                            <input type="submit" name="agregar-usuario" disabled="disabled" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR USUARIO"/>
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
