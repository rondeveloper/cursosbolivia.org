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


/* agregar-celular_numero */
if (isset_post('agregar-celular_numero')) {
    $numero = post('numero');
    $nombre = post('nombre');
    $interes = post('interes');
    $id_departamento = post('id_departamento');
    //$estado = post('estado');
    $estado = '1';

    $rv1 = query("SELECT id FROM celulares_numeros WHERE numero='$numero' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rv1) == 0) {

        query("INSERT INTO celulares_numeros("
                . "numero,"
                . "nombre,"
                . "interes,"
                . "id_departamento,"
                . "estado"
                . ") VALUES("
                . "'$numero',"
                . "'$nombre',"
                . "'$interes',"
                . "'$id_departamento',"
                . "'$estado'"
                . " ) ");
        $id_celular_numero = mysql_insert_id();
        logcursos('Agregado de celular_numero', 'celular_numero-creacion', 'celular_numero', $id_celular_numero);
        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> la celular_numero con numero: ' . $numero . ' ya existe en la base de datos.
</div>';
    }
}


/* editar-celular_numero */
if (isset_post('editar-celular_numero')) {
    $id_celular_numero = post('id_celular_numero');
    $numero = post('numero');
    $nombre = post('nombre');
    $interes = post('interes');
    $id_departamento = post('id_departamento');
    $estado = '1';
    query("UPDATE celulares_numeros SET "
            . "numero='$numero',"
            . "nombre='$nombre',"
            . "interes='$interes',"
            . "id_departamento='$id_departamento',"
            . "estado='$estado'"
            . " WHERE id='$id_celular_numero' LIMIT 1 ");
    logcursos('Edicion de celular_numero', 'celular_numero-edicion', 'celular_numero', $id_celular_numero);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-celular_numero */
if (isset_post('eliminar-celular_numero')) {
    $id_celular_numero = post('id_celular_numero');
    query("UPDATE celulares_numeros SET "
            . "estado='0' "
            . " WHERE id='$id_celular_numero' LIMIT 1 ");
    logcursos('Eliminacion de celular_numero', 'celular_numero-eliminacion', 'celular_numero', $id_celular_numero);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT *,(c.nombre)propietario,(d.nombre)departamento FROM celulares_numeros c INNER JOIN departamentos d ON d.id=c.id_departamento WHERE c.estado IN ('1','2') $qr_busqueda ORDER BY c.id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM celulares_numeros c INNER JOIN departamentos d ON d.id=c.id_departamento WHERE c.estado IN ('1','2') $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="whatsapp-numeros.adm">Whatsapp numeros</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group pull-right">
            <button class="btn btn-success active" data-toggle="modal" data-target="#MODAL-agregar-celular_numero">
                <i class="fa fa-plus"></i> 
                AGREGAR NUMERO CELULAR
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> BASE DE NUMEROS DE CELULAR <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de Numeros de celular.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>
        
        <div class="panel panel-info">
            <div class="panel-heading">
                CATEGORIAS DE INTERES
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de numeros de celular
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>N&Uacute;MERO CELULAR</th>
                                <th>PROPIETARIO</th>
                                <th>INTER&Eacute;S</th>
                                <th>DEPARTAMENTO</th>
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
                                    <td><?php echo $producto['numero']; ?></td>
                                    <td><?php echo $producto['propietario']; ?></td>
                                    <td><?php echo strtoupper($producto['interes']); ?></td>
                                    <td><?php echo $producto['departamento']; ?></td>
                                    <td>                                   
                                        <a data-toggle="modal" data-target="#MODAL-editar-celular_numero-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-celular_numero-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-celular_numero-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE NUMERO CELULAR</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NUMERO CELULAR</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" maxlength="8" name="numero" value="<?php echo $producto['numero']; ?>" required placeholder="Numero whatsapp..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">PROPIETARIO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="nombre" value="<?php echo $producto['propietario']; ?>" required placeholder="Responsable del numero celular..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">ESTADO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="interes">
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['interes'] == 'cursos') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="cursos" <?php echo $text_select; ?>>CURSOS</option>
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['interes'] == 'infosicoes') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="infosicoes" <?php echo $text_select; ?>>INFOSICOES</option>
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['interes'] == 'hosting') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="hosting" <?php echo $text_select; ?>>HOSTING</option>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                                                        <div class="col-lg-9 col-md-9">
                                                                            <select class="form-control form-cascade-control" name="id_departamento">
                                                                                <?php
                                                                                $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                                                                while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                                                    $selected = '';
                                                                                    if ($producto['id_departamento'] == $rqd2['id']) {
                                                                                        $selected = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="<?php echo $rqd2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqd2['nombre']; ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_celular_numero" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-celular_numero" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-celular_numero-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE NUMERO CELULAR</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar el numero de celular ?
                                                                        </label>
                                                                        <br/>                                                                    
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NUMERO</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['numero']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_celular_numero" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-celular_numero" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR NUMERO CELULAR"/>
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
<div id="MODAL-agregar-celular_numero" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO NUMERO CELULAR</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo numero.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">N&Uacute;MERO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="number" class="form-control form-cascade-control" name="numero" value="" required placeholder="Numero de celular..." maxlength="8" autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">PROPIETARIO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="nombre" value="" required placeholder="Responsable del numero celular..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">INTERES</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="interes">
                                            <option value="cursos">CURSOS</option>
                                            <option value="infosicoes">INFOSICOES</option>
                                            <option value="hosting">HOSTING</option>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="id_departamento">
                                            <?php
                                            $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                            while ($rqd2 = mysql_fetch_array($rqd1)) {
                                                ?>
                                                <option value="<?php echo $rqd2['id']; ?>"><?php echo $rqd2['nombre']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <!--                            <div class="row">
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
                            <input type="submit" name="agregar-celular_numero" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR NUMERO CELULAR"/>
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
                <h4 class="modal-title"><i class="fa fa-edit"></i> CURSOS DEL NUMERO CELULAR</h4>
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
    function cursos_asignados(id_numero, id_curso) {
        $("#AJAXCONTENT-cursos_asignados").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.whatsapp-numeros.cursos_asignados.php',
            data: {id_numero: id_numero, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_asignados").html(data);
            }
        });
    }
</script>
