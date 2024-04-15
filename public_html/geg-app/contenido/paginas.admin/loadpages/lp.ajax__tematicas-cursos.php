<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);
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
    $qr_busqueda = " AND (titulo LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-tematica */
if (isset_post('agregar-tematica')) {
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $estado = post('estado');

    $rv1 = query("SELECT id FROM cursos_tematicas WHERE titulo='$titulo' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rv1) == 0) {

        query("INSERT INTO cursos_tematicas("
                . "titulo,"
                . "titulo_identificador,"
                . "estado"
                . ") VALUES("
                . "'$titulo',"
                . "'$titulo_identificador',"
                . "'$estado'"
                . " ) ");
        $id_tematica = mysql_insert_id();
        logcursos('Agregado de tematica', 'tematica-creacion', 'tematica', $id_tematica);
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> la tematica con titulo: ' . $titulo . ' ya existe en la base de datos.
</div>';
    }
}


/* editar-tematica */
if (isset_post('editar-tematica')) {
    $id_tematica = post('id_tematica');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $estado = post('estado');

    query("UPDATE cursos_tematicas SET "
            . "titulo='$titulo',"
            . "titulo_identificador='$titulo_identificador',"
            . "estado='$estado'"
            . " WHERE id='$id_tematica' LIMIT 1 ");
    logcursos('Edicion de tematica', 'tematica-edicion', 'tematica', $id_tematica);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-tematica */
if (isset_post('eliminar-tematica')) {
    $id_tematica = post('id_tematica');
    query("UPDATE cursos_tematicas SET "
            . "estado='0' "
            . " WHERE id='$id_tematica' LIMIT 1 ");
    logcursos('Eliminacion de tematica', 'tematica-eliminacion', 'tematica', $id_tematica);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM cursos_tematicas WHERE estado IN ('1','2') $qr_busqueda ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_tematicas WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="tematicas-cursos.adm">Tematicas</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-tematica">
                <i class="fa fa-plus"></i> 
                AGREGAR TEM&Aacute;TICA
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> TEM&Aacute;TICAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de tematicas.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de tem&aacute;ticas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de tematica..." value="<?php echo $buscar; ?>" autocomplete="off"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button"><i class="fa fa-search"></i>
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
                                <th>TEM&Aacute;TICA</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($producto = mysql_fetch_array($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = 'ACTIVADO';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo $producto['titulo']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-tematica-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-tematica-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-tematica-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE TEM&Aacute;TICA</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE DE LA TEM&Aacute;TICA</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['titulo']; ?>" required placeholder="Nombre de la tematica..." autocomplete="off"/>
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
                                                                    <input type="hidden" name="id_tematica" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-tematica" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-tematica-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE TEM&Aacute;TICA</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar la tematica ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['titulo']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_tematica" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-tematica" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR TEM&Aacute;TICA"/>
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

                    <li><a href="tematicas-cursos/1.adm">Primero</a></li>                           
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
                                echo '<li class="active"><a href="tematicas-cursos/' . $i . '.adm">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="tematicas-cursos/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="tematicas-cursos/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-tematica" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA TEM&Aacute;TICA</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva tematica.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE DE LA TEM&Aacute;TICA</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="titulo" value="" required placeholder="Nombre de la tematica..." autocomplete="off"/>
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
                            <input type="submit" name="agregar-tematica" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR TEM&Aacute;TICA"/>
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
