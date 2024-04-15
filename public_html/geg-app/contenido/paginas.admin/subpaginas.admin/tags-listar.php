<?php
/* acceso */
if (!acceso_cod('adm-tags')) {
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

$registros_a_mostrar = 50;
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
    $qr_busqueda = " AND ( t.tag LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-tag */
if (isset_post('agregar-tag')) {
    $tag = post('tag');
    $id_categoria = post('id_categoria');
    $estado = post('estado');

    $result = query("INSERT INTO cursos_tags("
            . "id_categoria,"
            . "tag,"
            . "estado"
            . ") VALUES("
            . "'$id_categoria',"
            . "'$tag',"
            . "'$estado'"
            . " ) ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
}


/* editar-tag */
if (isset_post('editar-tag')) {
    $id_tag = post('id_tag');
    $tag = post('tag');
    $id_categoria = post('id_categoria');
    $estado = post('estado');

    query("UPDATE cursos_tags SET "
            . "tag='$tag',"
            . "id_categoria='$id_categoria',"
            . "estado='$estado'"
            . " WHERE id='$id_tag' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-banner */
if (isset_post('eliminar-banner')) {
    $id_tag = post('id_tag');

    query("UPDATE cursos_tags SET "
            . "estado='0' "
            . " WHERE id='$id_tag' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT t.id,t.tag,(c.titulo)categoria,t.estado FROM cursos_tags t INNER JOIN cursos_categorias c ON t.id_categoria=c.id WHERE t.estado IN ('1','2') $qr_busqueda ORDER BY t.tag ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_tags t WHERE estado IN ('1','2') $qr_busqueda ");
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
            <li><a href="cursos-listar.adm">Tags</a></li>
            <li class="active">Listado</li>
        </ul>

        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-tag">
                <i class="fa fa-plus"></i> 
                AGREGAR ETIQUETA
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> ETIQUETAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de tags registrados.
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de tags
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de tag..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_tags();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_tags();"><i class="fa fa-search"></i>
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
                                <th>CATEGORIA</th>
                                <th>ETIQUETA</th>
                                <th>CURSOS</th>
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
                                    <td><?php echo $producto['categoria']; ?></td>
                                    <td><?php echo $producto['tag']; ?></td>
                                    <td>
                                        <?php
                                        /*
                                        $id_tag = $producto['id'];
                                        $text_tag = $producto['tag'];
                                        $text_tag_tit = limpiar_enlace($producto['tag']);
                                        $rqdcc1 = query("SELECT COUNT(*) AS total FROM cursos WHERE titulo LIKE '%$text_tag%' or titulo_identificador LIKE '%$text_tag_tit%' ");
                                        $rqdcc2 = mysql_fetch_array($rqdcc1);
                                        echo $rqdcc2['total'];
                                        echo "|";
                                        $rqdcct1 = query("SELECT id FROM cursos WHERE titulo LIKE '%$text_tag%' or titulo_identificador LIKE '%$text_tag_tit%' ");
                                        $cnt = 0;
                                        while ($rqdcct2 = mysql_fetch_array($rqdcct1)) {
                                            $id_curso = $rqdcct2['id'];
                                            $rqverif1 = query("SELECT COUNT(1) AS total FROM cursos_rel_cursostags WHERE id_curso='$id_curso' AND id_tag='$id_tag' ");
                                            $rqverif2 = mysql_fetch_array($rqverif1);
                                            if ($rqverif2['total'] == 0) {
                                                query("INSERT INTO cursos_rel_cursostags (id_curso,id_tag) VALUES ('$id_curso','$id_tag') ");
                                                $cnt++;
                                            }
                                        }
                                        echo $cnt;
                                        */
                                        $id_tag = $producto['id'];
                                        $rqdcc1 = query("SELECT COUNT(*) AS total FROM cursos_rel_cursostags WHERE id_tag='$id_tag' ");
                                        $rqdcc2 = mysql_fetch_array($rqdcc1);
                                        echo $rqdcc2['total'];
                                        ?>
                                    </td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-tag-<?php echo $producto['id']; ?>" title="EDITAR">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-banner-<?php echo $producto['id']; ?>" title="ELIMINAR">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-tag-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE TAG</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post" enctype="multipart/form-data">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">CATEGORIA</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <select class="form-control form-cascade-control" name="id_categoria">
                                                                                        <?php
                                                                                        //$rqd1 = query("SELECT * FROM cursos_categorias WHERE tipo='1' ");
                                                                                        $rqd1 = query("SELECT * FROM cursos_categorias ");
                                                                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                                                            $selected = '';
                                                                                            if ($producto['id_categoria'] == $rqd2['id']) {
                                                                                                $selected = ' selected="selected" ';
                                                                                            }
                                                                                            ?>
                                                                                            <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['titulo']; ?></option>
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
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="tag" value="<?php echo $producto['tag']; ?>" required placeholder="Descripci&oacute;n del tag..." autocomplete="off"/>
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
                                                                    <input type="hidden" name="id_tag" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-tag" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE TAG</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al tag ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['tag']; ?>" required placeholder="Apellidos y tags..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_tag" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-banner" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR TAG"/>
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

                    <li><a href="tags-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="tags-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="tags-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-tag" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO TAG</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo tag.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">CATEGORIA</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="id_categoria">
                                            <?php
                                            //$rqd1 = query("SELECT * FROM cursos_categorias WHERE tipo='1' ");
                                            $rqd1 = query("SELECT * FROM cursos_categorias  ");
                                            while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                ?>
                                                <option value="<?php echo $rqd2['id']; ?>"><?php echo $rqd2['titulo']; ?></option>
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
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="tag" value="" required placeholder="Descripci&oacute;n del tag..." autocomplete="off"/>
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
                            <input type="submit" name="agregar-tag" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR TAG"/>
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
