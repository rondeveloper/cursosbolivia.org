<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

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
if($postdata!==''){
    $_POST = json_decode(base64_decode($postdata),true);
}
?>

<!-- CONTENIDO DE PAGINA -->

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
    $qr_busqueda = " AND ( e.descripcion LIKE '%$buscar%' OR e.enlace LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-enlace */
if (isset_post('agregar-enlace')) {
    $descripcion = post('descripcion');
    $enlace = limpiar_enlace(post('enlace'));
    $estado = post('estado');

    $vrf1 = query("SELECT id FROM enlaces_cursos WHERE enlace='$enlace' AND estado IN (1,2) ORDER BY id DESC limit 1 ");
    if(num_rows($vrf1)==0){
        $result = query("INSERT INTO enlaces_cursos("
                . "descripcion,"
                . "enlace,"
                . "estado"
                . ") VALUES("
                . "'$descripcion',"
                . "'$enlace',"
                . "'$estado'"
                . " ) ");
        $id_enlace = insert_id();

        logcursos('Creacion de enlace', 'enlace-creacion', 'enlace', $id_enlace);

        $mensaje .= '<div class="alert alert-success">
    <strong>Exito!</strong> registro agregado exitosamente.
    </div>';
    }else{
        $mensaje .= '<div class="alert alert-danger">
    <strong>ERROR</strong> ya existe un enlace ['.$enlace.'].
    </div>';
    }
}


/* editar-enlace */
if (isset_post('editar-enlace')) {
    $id_enlace = post('id_enlace');
    $descripcion = post('descripcion');
    $enlace = limpiar_enlace(post('enlace'));
    $estado = post('estado');

    $vrf1 = query("SELECT id FROM enlaces_cursos WHERE enlace='$enlace' AND estado IN (1,2) ORDER BY id DESC limit 1 ");
    if(num_rows($vrf1)==0){    
        query("UPDATE enlaces_cursos SET "
                . "descripcion='$descripcion',"
                . "enlace='$enlace',"
                . "estado='$estado'"
                . " WHERE id='$id_enlace' LIMIT 1 ");

        logcursos('Edicion de enlace', 'enlace-ediccion', 'enlace', $id_enlace);

        $mensaje .= '<div class="alert alert-success">
    <strong>Exito!</strong> registro editado correctamente.
    </div>';
    }else{
        $mensaje .= '<div class="alert alert-danger">
    <strong>ERROR</strong> ya existe un enlace ['.$enlace.'].
    </div>';
    }
}

/* eliminar-enlace */
if (isset_post('eliminar-enlace')) {
    $id_enlace = post('id_enlace');
    query("UPDATE enlaces_cursos SET "
            . "estado='0' "
            . " WHERE id='$id_enlace' LIMIT 1 ");
    query("DELETE FROM rel_cursoenlace WHERE id_enlace='$id_enlace' ");
    logcursos('Edicion de enlace', 'enlace-ediccion', 'enlace', $id_enlace);
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT e.* FROM enlaces_cursos e WHERE e.estado IN (1,2) $qr_busqueda ORDER BY e.id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM enlaces_cursos e WHERE estado IN (1,2) $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
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
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-enlace">
                <i class="fa fa-plus"></i> 
                AGREGAR ENLACE
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> ENLACES A CURSOS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de enlaces
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de enlace..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_tags();"/>
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
                                <th>DESCRIPCI&Oacute;N</th>
                                <th>ENLACE</th>
                                <th>CURSO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = '<b style="color:red;">DESACTIVADO</b>';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<b style="color:green;">ACTIVADO</b>';
                                }
                                $id_enlace = $producto['id'];
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td>
                                        <b style="font-size: 15pt;color:#3b69a2;"><?php echo $producto['descripcion']; ?></b>
                                        <br>
                                        <?php
                                        $rqdcc1 = query("SELECT COUNT(*) AS total FROM rel_cursoenlace WHERE id_enlace='$id_enlace' ");
                                        $rqdcc2 = fetch($rqdcc1);
                                        echo 'Cursos asignados: '.$rqdcc2['total'].'';
                                        ?>
                                    </td>
                                    <td>
                                        <b style="font-size: 14pt;color:#73b123;"><?php echo $producto['enlace']; ?></b>
                                        <br>
                                        <a href="<?php echo $dominio.$producto['enlace'].'/'; ?>" target="_blank"><?php echo $dominio.$producto['enlace'].'/'; ?></a>
                                    </td>
                                    <td class="text-center" style="line-height: 2;">
                                        <?php
                                        $rqdcce1 = query("SELECT id_curso FROM rel_cursoenlace WHERE id_enlace='$id_enlace' AND estado=1 ORDER BY id DESC limit 1 ");
                                        $rqdcce2 = fetch($rqdcce1);
                                        echo '<b style="border: 2px solid #f52c2c;padding: 2px 10px;background: #fff;color: #f52c2c;font-size: 12pt;border-radius: 4px;">'.$rqdcce2['id_curso'].'</b>';
                                        echo '<br>';
                                        echo '<a '.loadpage('cursos-listar/1/no-search/ID'.$rqdcce2['id_curso']).' class="btn btn-xs btn-warning">Panel</a>';
                                        ?>
                                    </td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-enlace-<?php echo $producto['id']; ?>" title="EDITAR">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-enlace-<?php echo $producto['id']; ?>" title="ELIMINAR">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-enlace-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE ENLACE</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post" enctype="multipart/form-data">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="descripcion" value="<?php echo $producto['descripcion']; ?>" required placeholder="Descripci&oacute;n del tag..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">ENLACE</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="text" class="form-control form-cascade-control" name="enlace" value="<?php echo $producto['enlace']; ?>" required placeholder="Descripci&oacute;n del tag..." autocomplete="off"/>
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
                                                                    <input type="hidden" name="id_enlace" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-enlace" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR ENLACE"/>
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
                                        <div id="MODAL-eliminar-enlace-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE ENLACE</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al enlace ?
                                                                        </label>
                                                                        <br/>                                                                    
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">ENLACE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['enlace']; ?>" required placeholder="Apellidos y tags..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_enlace" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-enlace" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR ENLACE"/>
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
<div id="MODAL-agregar-enlace" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO ENLACE</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo enlace.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="descripcion" value="" required placeholder="Descripci&oacute;n del enlace..." autocomplete="off" required/>
                                        <br/>
                                    </div>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">ENLACE</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="enlace" value="" required placeholder="Enlace..." autocomplete="off" required/>
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
                            <input type="submit" name="agregar-enlace" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR ENLACE"/>
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
