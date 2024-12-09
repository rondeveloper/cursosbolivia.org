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
if (false) {
    $rqc1 = query("SELECT id FROM cursos_categorias ");
    while ($rqc2 = fetch($rqc1)) {
        $rqtc1 = query("SELECT count(*) AS total FROM cursos WHERE id_categoria='" . $rqc2['id'] . "' ");
        $rqtc2 = fetch($rqtc1);
        $aux_cursos_total = $rqtc2['total'];
        $rqtc1 = query("SELECT count(*) AS total FROM cursos WHERE id_categoria='" . $rqc2['id'] . "' AND estado='1' ");
        $rqtc2 = fetch($rqtc1);
        $aux_cursos_activos = $rqtc2['total'];
        query("UPDATE cursos_categorias SET cnt_cursos_total='$aux_cursos_total',cnt_cursos_activos='$aux_cursos_activos' WHERE id='" . $rqc2['id'] . "' LIMIT 1 ");
    }
}

$resultado1 = query("SELECT * FROM quejas_reclamos q INNER JOIN cursos_usuarios u ON q.id_usuario=u.id WHERE q.estado IN (1) $qr_busqueda ORDER BY q.id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM quejas_reclamos q INNER JOIN cursos_usuarios u ON q.id_usuario=u.id WHERE q.estado IN (1) $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - (($vista - 1) * $registros_a_mostrar);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group pull-right">

        </div>
        <h3 class="page-header"> SUGERENCIAS, QUEJAS Y RECLAMOS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<?php
/*
$ress = query("SELECT id,imagen,imagen2,imagen3,imagen4 FROM cursos WHERE estado='0' ORDER BY id DESC limit 500,500 ");
$cntt = 1;
while ($producto = fetch($ress)) {
    $imagen = $producto['imagen'];
    $imagen2 = $producto['imagen2'];
    $imagen3 = $producto['imagen3'];
    $imagen4 = $producto['imagen4'];
    if ($imagen !== '') redimenImage($imagen);
    if ($imagen2 !== '') redimenImage($imagen2);
    if ($imagen3 !== '') redimenImage($imagen3);
    if ($imagen4 !== '') redimenImage($imagen4);
    echo "<hr>".$cntt++."__ $imagen , $imagen2 , $imagen3  $imagen4 <br>";
}
function redimenImage($img_name) {
    global $___path_raiz;
    $sizes = [
        'small' => 90,
        'medium' => 380,
        'large' => 730
    ];
    $img_path = $___path_raiz."contenido/imagenes/paginas/" . $img_name;
    foreach ($sizes as $sizeName => $newWidth) {
        $ruta_destino =  $___path_raiz."contenido/imagenes/paginas/" . $sizeName . "-" . $img_name;
        UploadImageUtil::resizeImage($img_path, $ruta_destino, $newWidth);
    }
    return $img_name;
}
*/
?>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>USUARIO</th>
                                <th>TIPO</th>
                                <th>DETALLE</th>
                                <th>ARCHIVO</th>
                                <th>FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = $total_registros - (($vista-1)*$registros_a_mostrar);
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = 'ACTIVADO';
                                }
                            ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo $producto['nombres'] . ' ' . $producto['apellidos']; ?></td>
                                    <td><?php echo $producto['tipo']; ?></td>
                                    <td><?php echo $producto['detalle']; ?></td>
                                    <td>
                                        <?php
                                        if ($producto['archivo'] == '') {
                                            echo 'Sin archivo';
                                        }else{
                                            echo '<a class="btn btn-info" href="'.$dominio.'contenido/imagenes/doc-usuarios/'.$producto['archivo'].'" target="_blank">VER ARCHIVO</a>';

                                            /*
                                            $url_from = $___path_raiz.'contenido/imagenes/doc-usuarios/'.$producto['archivo'];
                                            $url_dest = $___path_raiz.'folderauxiliar/'.str_replace(' ','-',$producto['nombres'] . ' ' . $producto['apellidos']).'__'.substr($producto['archivo'],strlen($producto['archivo'])-5);
                                            copy($url_from,$url_dest);
                                            echo '<br>OK';
                                            */

                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date("d/m/Y H:i",strtotime($producto['fecha'])); ?></td>
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

                    <li><a href="quejas-reclamos/1.adm">Primero</a></li>
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
                                echo '<li><a href="quejas-reclamos/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>
                    <li><a href="quejas-reclamos/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
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
                                        <input type="text" class="form-control form-cascade-control" name="titulo" value="" required placeholder="Nombre de la categoria..." autocomplete="off" />
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="descripcion" value="" required placeholder="Descripci&oacute;n de la categoria..." autocomplete="off" />
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
                            <input type="submit" name="agregar-categoria" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR CATEGORIA" />
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