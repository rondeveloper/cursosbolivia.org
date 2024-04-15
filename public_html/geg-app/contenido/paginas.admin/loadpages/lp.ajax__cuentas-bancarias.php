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
/* verif acceso */
if (!acceso_cod('adm-bancos')) {
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
    $qr_busqueda = " AND (banco LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-banco */
if (isset_post('agregar-banco')) {
    $banco = post('banco');
    $numero_cuenta = post('numero_cuenta');
    $titular = post('titular');
    $estado = post('estado');

    $rv1 = query("SELECT id FROM cuentas_de_banco WHERE numero_cuenta='$numero_cuenta' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rv1) == 0) {

        query("INSERT INTO cuentas_de_banco ("
                . "banco,"
                . "numero_cuenta,"
                . "titular,"
                . "estado"
                . ") VALUES("
                . "'$banco',"
                . "'$numero_cuenta',"
                . "'$titular',"
                . "'$estado'"
                . " ) ");
        $id_banco = mysql_insert_id();
        logcursos('Agregado de banco', 'banco-creacion', 'banco', $id_banco);
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> la cuenta : ' . $banco . ' ya existe en la base de datos.
</div>';
    }
}


/* editar-banco */
if (isset_post('editar-banco')) {
    $id_banco = post('id_banco');
    $banco = post('banco');
    $numero_cuenta = post('numero_cuenta');
    $titular = post('titular');
    $estado = post('estado');

    query("UPDATE cuentas_de_banco SET "
            . "banco='$banco',"
            . "numero_cuenta='$numero_cuenta',"
            . "titular='$titular',"
            . "estado='$estado'"
            . " WHERE id='$id_banco' LIMIT 1 ");
    logcursos('Edicion de banco', 'banco-edicion', 'banco', $id_banco);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-banco */
if (isset_post('eliminar-banco')) {
    $id_banco = post('id_banco');
    query("UPDATE cuentas_de_banco SET "
            . "estado='0' "
            . " WHERE id='$id_banco' LIMIT 1 ");
    logcursos('Eliminacion de banco', 'banco-eliminacion', 'banco', $id_banco);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM cuentas_de_banco WHERE estado IN ('1','2') $qr_busqueda ORDER BY id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cuentas_de_banco WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="whatsapp-bancos.adm">Whatsapp bancos</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-banco">
                <i class="fa fa-plus"></i> 
                AGREGAR CUENTA BANCARIA
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> CUENTAS BANCARIAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de bancos.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de bancos
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>BANCO</th>
                                <th>N&Uacute;MERO DE CUENTA</th>
                                <th>TITULAR</th>
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
                                    <td><?php echo $producto['banco']; ?></td>
                                    <td><?php echo $producto['numero_cuenta']; ?></td>
                                    <td><?php echo $producto['titular']; ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-banco-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <!--
                                        <a data-toggle="modal" data-target="#MODAL-cursos_asignados" onclick="cursos_asignados('<?php echo $producto['id']; ?>','0');">
                                            <button type="button" class="btn btn-warning active"><i class="fa fa-list"></i></button>
                                        </a>
                                        -->
                                        &nbsp;|&nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-banco-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-banco-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE BANCO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">BANCO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control"  name="banco" value="<?php echo $producto['banco']; ?>" required placeholder="Numero whatsapp..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">N&Uacute;MERO DE CUENTA</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="numero_cuenta" value="<?php echo $producto['numero_cuenta']; ?>" required placeholder="N&uacute;mero de cuenta..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">TITULAR</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="titular" value="<?php echo $producto['titular']; ?>" required placeholder="Titular de cuenta..." autocomplete="off"/>
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
                                                                    <input type="hidden" name="id_banco" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-banco" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-banco-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE BANCO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar la banco ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['banco']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_banco" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-banco" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR BANCO"/>
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

                    <li><a href="whatsapp-bancos/1.adm">Primero</a></li>                           
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
                                echo '<li class="active"><a href="whatsapp-bancos/' . $i . '.adm">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="whatsapp-bancos/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="whatsapp-bancos/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-banco" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVA CUENTA BANCARIA</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos de la nueva cuenta.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">BANCO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="banco" value="" required placeholder="Banco..."  autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">N&Uacute;MERO DE CUENTA</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="numero_cuenta" value="" required placeholder="N&uacute;mero de cuenta..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">TITULAR</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="titular" value="" required placeholder="Titular de cuenta..." autocomplete="off"/>
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
                            <input type="submit" name="agregar-banco" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR BANCO"/>
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
                <h4 class="modal-title"><i class="fa fa-edit"></i> CURSOS DEL BANCO</h4>
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
    function cursos_asignados(id_banco, id_curso) {
        $("#AJAXCONTENT-cursos_asignados").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.whatsapp-bancos.cursos_asignados.php',
            data: {id_banco: id_banco, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_asignados").html(data);
            }
        });
    }
</script>
