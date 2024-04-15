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
/* verif acceso */
if (!acceso_cod('adm-whatsapp')) {
    echo "Denegado!";
    exit;
}
/* END verif acceso */

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
    $qr_busqueda = " AND (numero LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-whatsapp_numero */
if (isset_post('agregar-whatsapp_numero')) {
    $numero = post('numero');
    $responsable = post('responsable');
    $estado = post('estado');
    $administrador = post('administrador');

    $rv1 = query("SELECT id FROM whatsapp_numeros WHERE numero='$numero' ORDER BY id DESC limit 1 ");
    if (num_rows($rv1) == 0) {

        query("INSERT INTO whatsapp_numeros("
                . "id_administrador,"
                . "numero,"
                . "responsable,"
                . "estado"
                . ") VALUES("
                . "'$administrador',"
                . "'$numero',"
                . "'$responsable',"
                . "'$estado'"
                . " ) ");
        $id_whatsapp_numero = insert_id();
        logcursos('Agregado de whatsapp_numero', 'whatsapp_numero-creacion', 'whatsapp_numero', $id_whatsapp_numero);
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> la whatsapp_numero con numero: ' . $numero . ' ya existe en la base de datos.
</div>';
    }
}


/* editar-whatsapp_numero */
if (isset_post('editar-whatsapp_numero')) {
    $id_whatsapp_numero = post('id_whatsapp_numero');
    $numero = post('numero');
    $responsable = post('responsable');
    $estado = post('estado');
    $administrador = post('administrador');
    query("UPDATE whatsapp_numeros SET "
            . "id_administrador = '$administrador'," 
            . "numero='$numero',"
            . "responsable='$responsable',"
            . "estado='$estado'"
            . " WHERE id='$id_whatsapp_numero' LIMIT 1 ");
    logcursos('Edicion de whatsapp_numero', 'whatsapp_numero-edicion', 'whatsapp_numero', $id_whatsapp_numero);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-whatsapp_numero */
if (isset_post('eliminar-whatsapp_numero')) {
    $id_whatsapp_numero = post('id_whatsapp_numero');
    query("UPDATE whatsapp_numeros SET "
            . "estado='0' "
            . " WHERE id='$id_whatsapp_numero' LIMIT 1 ");
    logcursos('Eliminacion de whatsapp_numero', 'whatsapp_numero-eliminacion', 'whatsapp_numero', $id_whatsapp_numero);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT wn.*, (wn.id)dr_id_whatsapp_numero, a.nombre, a.email, a.celular, a.id FROM whatsapp_numeros AS wn LEFT JOIN administradores AS a ON  wn.id_administrador = a.id WHERE wn.estado IN ('1','2') $qr_busqueda ORDER BY wn.id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM whatsapp_numeros WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<style>
    .whatsapp-administrador-listar{
        font-size: 14px;
    }
</style>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="whatsapp-numeros.adm">Whatsapp numeros</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-whatsapp_numero">
                <i class="fa fa-plus"></i> 
                AGREGAR NUMERO WHATSAPP
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> NUMEROS DE WHATSAPP <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de whatsapp numeros.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de numeros de whatsapp
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NUMERO WHATSAPP</th>
                                <th>RESPONSABLE</th>
                                <th>ADMIN</th>
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
                                    <td><?php echo $producto['numero']; ?></td>
                                    <td><?php echo $producto['responsable']; ?></td>
                                    <td>
                                        <div class="whatsapp-administrador-listar">
                                                <b>Nombre:</b>
                                                <div><?= $producto['nombre'] ? $producto['nombre'] : 'no hay dato' ?></div>
                                                <b>Correo:</b>
                                                <div><?= $producto['email'] ? $producto['email'] : 'no hay dato'?></div>
                                        </div>
                                    </td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-whatsapp_numero-<?php echo $producto['dr_id_whatsapp_numero']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-cursos_asignados" onclick="cursos_asignados('<?php echo $producto['dr_id_whatsapp_numero']; ?>','0');">
                                            <button type="button" class="btn btn-warning active"><i class="fa fa-list"></i></button>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-whatsapp_numero-<?php echo $producto['dr_id_whatsapp_numero']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-whatsapp_numero-<?php echo $producto['dr_id_whatsapp_numero']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE NUMERO WHATSAPP</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NUMERO WHATSAPP</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" maxlength="8" name="numero" value="<?php echo $producto['numero']; ?>" required placeholder="Numero whatsapp..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">RESPONSABLE</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="responsable" value="<?php echo $producto['responsable']; ?>" required placeholder="Responsable del numero whatsapp..." autocomplete="off"/>
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
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">ADMINISTRADOR</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="administrador">
                                                                                   <option value="<?= $producto['id_administrador'] ?>" selected="selected">
                                                                                        <?= $producto['nombre'] ? $producto['nombre'] : 'no hay dato' ?>
                                                                                    </option>
                                                                                   <?php
                                                                                      $resultado3 = query("SELECT * FROM administradores WHERE estado=1 ORDER BY id DESC ");
                                                                                     while ($producto2 = fetch($resultado3)) {
                                                                                       if($producto['id_administrador'] !=$producto2['id']){?>
                                                                                          <option value="<?= $producto2['id'] ?>">
                                                                                          <?= $producto2['nombre'] ? $producto2['nombre']  : 'no hay dato' ?>
                                                                                        </option>
                                                                                    <?php } 
                                                                                      }?>

                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                  
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_whatsapp_numero" value="<?php echo $producto['dr_id_whatsapp_numero']; ?>"/>
                                                                    <input type="submit" name="editar-whatsapp_numero" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-whatsapp_numero-<?php echo $producto['dr_id_whatsapp_numero']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE NUMERO WHATSAPP</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar la whatsapp_numero ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['numero']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_whatsapp_numero" value="<?php echo $producto['dr_id_whatsapp_numero']; ?>"/>
                                                                    <input type="submit" name="eliminar-whatsapp_numero" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR NUMERO WHATSAPP"/>
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

                    <li><a href="whatsapp-numeros/1.adm">Primero</a></li>                           
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
                                echo '<li class="active"><a href="whatsapp-numeros/' . $i . '.adm">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="whatsapp-numeros/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="whatsapp-numeros/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-whatsapp_numero" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA NUMERO WHATSAPP</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva whatsapp_numero.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NUMERO WHATSAPP</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="number" class="form-control form-cascade-control" name="numero" value="" required placeholder="Numero de whatsapp..." maxlength="8" autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">RESPONSABLE</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="responsable" value="" required placeholder="Responsable del numero whatsapp..." autocomplete="off"/>
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
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">ADMINISTRADOR</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="administrador">
                                            <option value="0">Sin administrador</option>
                                            <?php
                                              $resultado1 = query("SELECT * FROM administradores WHERE estado=1 ORDER BY id DESC ");
                                             while ($producto = fetch($resultado1)) {?>
                                                <option value="<?= $producto['id'] ?>"><?= $producto['nombre'] ? $producto['nombre'] : 'no hay dato' ?></option>
                                            <?php }?>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" name="agregar-whatsapp_numero" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR NUMERO WHATSAPP"/>
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


<!-- Modal cursos_asignados -->
<div id="MODAL-cursos_asignados" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog" style="width: 850px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> CURSOS DEL NUMERO WHATSAPP</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-cursos_asignados"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    function cursos_asignados(id_numero,id_curso) {
        $("#AJAXCONTENT-cursos_asignados").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.whatsapp-numeros.cursos_asignados.php',
            data: {id_numero: id_numero, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-cursos_asignados").html(data);
            }
        });
    }
</script>
