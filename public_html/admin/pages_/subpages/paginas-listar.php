<?php
/* acceso */
if (!acceso_cod('adm-paginas')) {
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

$resultado1 = query("SELECT * FROM  cursos_paginas WHERE estado='1' $qr_busqueda ORDER BY id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_paginas WHERE estado='1' $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="paginas-listar.adm">Paginas</a></li>
            <li class="active">Listado de paginas</li>
        </ul>

        <div class="form-group pull-right">
<!--            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-usuario">
                <i class="fa fa-plus"></i> 
                AGREGAR USUARIO
            </button> &nbsp;&nbsp;-->
        </div>
        <h3 class="page-header"> PAGINAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de paginas.
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                ETIQUETAS
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                [PAGE-NAME] = Nombre oficial de la pagina, actualmente-> '<?php echo $___nombre_del_sitio; ?>'
            </div>
            <!-- /.panel-body -->
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de p&aacute;ginas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SECCI&Oacute;N</th>
                                <th>T&Iacute;TULO</th>
                                <th>CONTENIDO</th>
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
                                    <td><b><?php echo $producto['nombre']; ?></b></td>
                                    <td><?php echo $producto['titulo']; ?></td>
                                    <td><?php echo substr(strip_tags($producto['contenido']),0,70); ?>...</td>
                                    <td>
                                        <a href="paginas-editar/<?php echo $producto['id']; ?>.adm"title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
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
