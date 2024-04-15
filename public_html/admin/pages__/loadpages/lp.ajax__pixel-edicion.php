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
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
$mensaje = '';

$vista = 1;
$registros_a_mostrar = 30;
$start = ($vista - 1) * $registros_a_mostrar;

/* id_pixel_actual */
if (isset_post('id_pixel_actual')) {
    $id_pixel_actual = post('id_pixel_actual');
    query("UPDATE facebook_pixels SET sw_current=0 WHERE 1 ");
    query("UPDATE facebook_pixels SET sw_current=1 WHERE id='$id_pixel_actual' ");
    logcursos('Cambio de pixel a la web', 'pixel-set', 'pixel', $id_pixel_actual);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> procesp realizado exitosamente.
</div>';
}

/* agregar-pixel */
if (isset_post('agregar-pixel')) {
    $pixel = str_replace('"', "'", post_html('pixel'));
    $descripcion = post('descripcion');

    $result = query("INSERT INTO facebook_pixels("
            . "pixel,"
            . "descripcion,"
            . "estado"
            . ") VALUES("
            . ' "' . $pixel . '",'
            . "'$descripcion',"
            . "'1'"
            . " ) ");
    $id_pixel = insert_id();
    logcursos('Creacion de pixel', 'pixel-creacion', 'pixel', $id_pixel);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
}


/* editar-pixel */
if (isset_post('editar-pixel')) {
    $id_pixel = post('id_pixel');
    $pixel = str_replace('"', "'", post_html('pixel'));
    $descripcion = post('descripcion');
    query("UPDATE facebook_pixels SET "
            . ' pixel="' . $pixel . '"  ,'
            . "descripcion='$descripcion' "
            . " WHERE id='$id_pixel' LIMIT 1 ");
    logcursos('Edicion de pixel', 'pixel-edicion', 'pixel', $id_pixel);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-pixel */
if (isset_post('eliminar-pixel')) {
    $id_pixel = post('id_pixel');

    query("UPDATE facebook_pixels SET "
            . "estado='0' "
            . " WHERE id='$id_pixel' LIMIT 1 ");
    logcursos('Eliminacion de pixel', 'pixel-eliminacion', 'pixel', $id_pixel);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM facebook_pixels WHERE estado IN (1) ");
$total_registros = num_rows($resultado1);
$cnt = 1;
?>
<style>
    .modal-dialog{
        width: 800px !important;
    }
    .panel-primary>.panel-heading {
        border-color: #428bca!important;
    }
</style>

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
            <li><a <?php echo loadpage('inicio'); ?>>Panel Principal</a></li>
            <li><a <?php echo loadpage('cursos-listar'); ?>>Cursos</a></li>
            <li class="active">Creaci&oacute;n</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-pixel">
                <i class="fa fa-plus"></i> 
                AGREGAR PIXEL
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> PIXELS DE FACEBOOK <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Registros de pixel facebook
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de pixels
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ PIXEL ACTUAL:</span>
                                <select class="form-control" name="id_pixel_actual">
                                    <?php
                                    $rqpxd1 = query("SELECT id,descripcion FROM facebook_pixels WHERE estado='1' ORDER BY sw_current DESC ");
                                    while ($rqpxd2 = fetch($rqpxd1)) {
                                        ?>
                                        <option value="<?php echo $rqpxd2['id']; ?>"><?php echo $rqpxd2['descripcion']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info" type="button"><i class="fa fa-search"></i> ACTUALIZAR</button>
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
                                <th>DESCRIPCI&Oacute;N PIXEL</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = 'DESACTIVADO';
                                if ($producto['sw_current'] == '1') {
                                    $txt_estado = '<b style="color:green;">ACTIVADO</b>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo $producto['descripcion']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-pixel-<?php echo $producto['id']; ?>" title="EDITAR">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-pixel-<?php echo $producto['id']; ?>" title="ELIMINAR">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-pixel-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE CIUDAD</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">


                                                            <form enctype="multipart/form-data" action="pixel-edicion.adm" method="post">
                                                                <div class="panel-body">

                                                                    <div class="tab-content">
                                                                        <div id="home" class="tab-pane fade in active">
                                                                            <table style="width:100%;" class="table table-striped">
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><i class="fa fa-list"></i> &nbsp; Descripci&oacute;n PIXEL: </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control" name="descripcion" value="<?php echo $producto['descripcion']; ?>" required=""/>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; C&oacute;digo: </span>
                                                                                        <br/>
                                                                                        <br/>
                                                                                        <input type="reset" value="Resetear pixel" onClick="$('#contpixel<?php echo $producto['id']; ?>').html('');"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <textarea class="form-control" name="pixel" rows="15" style="resize: none;" id="contpixel<?php echo $producto['id']; ?>"><?php echo $producto['pixel']; ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <div style="text-align: center;padding:20px;">
                                                                        <input type="hidden" name="id_pixel" value="<?php echo $producto['id']; ?>"/>
                                                                        <input type="submit" value="ACTUALIZAR" name="editar-pixel" class="btn btn-success btn-block active"/>
                                                                    </div>
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
                                        <div id="MODAL-eliminar-pixel-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE PIXEL</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al pixel ?
                                                                        </label>
                                                                        <br/>                                                                    
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="titulo" value="<?php echo $producto['descripcion']; ?>" disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_pixel" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-pixel" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR PIXEL"/>
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

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-pixel" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO PIXEL</h4>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="pixel-edicion.adm" method="post">
                    <div class="panel-body">

                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <table style="width:100%;" class="table table-striped">
                                    <tr>
                                        <td>
                                            <span class="input-group-addon"><i class="fa fa-list"></i> &nbsp; Descripci&oacute;n PIXEL: </span>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="descripcion" value="" required=""/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; C&oacute;digo: </span>
                                            <br/>
                                            <br/>
                                            <input type="reset" value="Resetear pixel" onClick="$('#contpixel_').html('');"/>
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="pixel" rows="15" style="resize: none;" id="contpixel_"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div style="text-align: center;padding:20px;">
                            <input type="submit" value="AGREGAR PIXEL" name="agregar-pixel" class="btn btn-success btn-block active"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>
