<?php
/* acceso */
if (!acceso_cod('adm-categorias')) {
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
    $qr_busqueda = " AND (titulo LIKE '%$buscar%' OR descripcion LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-categoria */
if (isset_post('agregar-categoria')) {
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $descripcion = post('descripcion');
    $estado = post('estado');

    $rv1 = query("SELECT id FROM cursos_categorias WHERE titulo='$titulo' ORDER BY id DESC limit 1 ");
    if (num_rows($rv1) == 0) {

        $result = query("INSERT INTO cursos_categorias("
                . "titulo,"
                . "titulo_identificador,"
                . "descripcion,"
                . "estado"
                . ") VALUES("
                . "'$titulo',"
                . "'$titulo_identificador',"
                . "'$descripcion',"
                . "'$estado'"
                . " ) ");

        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> el categoria titulo: ' . $titulo . ' ya existe en la base de datos.
</div>';
    }
}


/* editar-categoria */
if (isset_post('editar-categoria')) {
    $id_categoria = post('id_categoria');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $descripcion = post('descripcion');
    $estado = post('estado');

    query("UPDATE cursos_categorias SET "
            . "titulo='$titulo',"
            . "titulo_identificador='$titulo_identificador',"
            . "descripcion='$descripcion',"
            . "estado='$estado'"
            . " WHERE id='$id_categoria' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-categoria */
if (isset_post('eliminar-categoria')) {
    $id_categoria = post('id_categoria');

    query("UPDATE cursos_categorias SET "
            . "estado='0' "
            . " WHERE id='$id_categoria' LIMIT 1 ");
    
    query("UPDATE cursos SET id_categoria='0' WHERE id_categoria='$id_categoria' ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

/* actualizador de contador */
if(false){
    $rqc1 = query("SELECT id FROM cursos_categorias ");
    while($rqc2 = fetch($rqc1)){
        $rqtc1 = query("SELECT count(*) AS total FROM cursos WHERE id_categoria='".$rqc2['id']."' ");
        $rqtc2 = fetch($rqtc1);
        $aux_cursos_total = $rqtc2['total'];
        $rqtc1 = query("SELECT count(*) AS total FROM cursos WHERE id_categoria='".$rqc2['id']."' AND estado='1' ");
        $rqtc2 = fetch($rqtc1);
        $aux_cursos_activos = $rqtc2['total'];
        query("UPDATE cursos_categorias SET cnt_cursos_total='$aux_cursos_total',cnt_cursos_activos='$aux_cursos_activos' WHERE id='".$rqc2['id']."' LIMIT 1 ");
    }
}

$resultado1 = query("SELECT * FROM cursos_categorias WHERE estado IN ('1','2') $qr_busqueda ORDER BY id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_categorias WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="categorias-listar.adm">Clientes</a></li>
            <li class="active">Listado de categorias</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-categoria">
                <i class="fa fa-plus"></i> 
                AGREGAR CATEGORIA
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> CATEGORIAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de categorias registradas.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de categorias
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de categoria..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_categorias();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_categorias();"><i class="fa fa-search"></i>
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
                                <th>NOMBRE DE CATEGORIA</th>
                                <th>DESCRIPCI&Oacute;N</th>
                                <th>TOTAL CURSOS</th>
                                <th>CURSOS ACTIVOS</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = 'ACTIVADO';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo $producto['titulo']; ?></td>
                                    <td><?php echo $producto['descripcion']; ?></td>
                                    <td><?php echo $producto['cnt_cursos_total']; ?></td>
                                    <td><?php echo $producto['cnt_cursos_activos']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-categoria-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-categoria-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-categoria-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE CATEGORIA</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE DE LA CATEGORIA</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['titulo']; ?>" required placeholder="Nombre de la categoria..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="descripcion" value="<?php echo $producto['descripcion']; ?>" required placeholder="Descripci&oacute;n de la categoria..." autocomplete="off"/>
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
                                                                    <input type="hidden" name="id_categoria" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-categoria" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-categoria-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE CATEGORIA</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar la categoria ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['titulo']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nit_ci" value="<?php echo $producto['descripcion']; ?>" required placeholder="N&uacute;mero de NIT o CI..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_categoria" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-categoria" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR CATEGORIA"/>
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

                    <li><a href="categorias-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="categorias-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="categorias-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-categoria" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA CATEGORIA</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva categoria.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE DE LA CATEGORIA</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="titulo" value="" required placeholder="Nombre de la categoria..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="descripcion" value="" required placeholder="Descripci&oacute;n de la categoria..." autocomplete="off"/>
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
                            <input type="submit" name="agregar-categoria" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR CATEGORIA"/>
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
