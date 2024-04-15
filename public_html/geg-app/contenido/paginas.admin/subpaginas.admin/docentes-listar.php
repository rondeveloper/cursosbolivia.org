<?php
/* acceso */
if (!acceso_cod('adm-docentes')) {
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
    $qr_busqueda = " AND ( nombres LIKE '%$buscar%' ) ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-docente */
if (isset_post('agregar-docente')) {
    $nombres = post('nombres');
    $prefijo = post('prefijo');
    $fecha_registro = date("Y-m-d");
    $curriculum = post_html('curriculum');
    $id_modalidad_pago = post('id_modalidad_pago');
    $estado = post('estado');
    query("INSERT INTO cursos_docentes("
            . "nombres,"
            . "prefijo,"
            . "curriculum,"
            . "id_modalidad_pago,"
            . "fecha_registro,"
            . "estado"
            . ") VALUES("
            . "'$nombres',"
            . "'$prefijo',"
            . "'$curriculum',"
            . "'$id_modalidad_pago',"
            . "'$fecha_registro',"
            . "'$estado'"
            . " ) ");
    $id_docente = mysql_insert_id();
    movimiento('Creacion de docente[' . $nombres . ']', 'creacion-docente', 'docente', $id_docente);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
}


/* editar-docente */
if (isset_post('editar-docente')) {
    $id_docente = post('id_docente');
    $nombres = post('nombres');
    $prefijo = post('prefijo');
    $estado = post('estado');
    $curriculum = post_html('curriculum');
    $id_modalidad_pago = post('id_modalidad_pago');
    $pago_hora = post('pago_hora');
    $pago2_hora = post('pago2_hora');
    $paydata_limite = post('paydata_limite');
    $nom_img = 'imagen';
    query("UPDATE cursos_docentes SET "
            . "nombres='$nombres',"
            . "prefijo='$prefijo',"
            . "curriculum='$curriculum',"
            . "id_modalidad_pago='$id_modalidad_pago',"
            . "pago_hora='$pago_hora',"
            . "pago2_hora='$pago2_hora',"
            . "paydata_limite='$paydata_limite',"
            . "estado='$estado'"
            . " WHERE id='$id_docente' LIMIT 1 ");
    movimiento('Edicion de docente', 'edicion-docente', 'docente', $id_docente);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-docente */
if (isset_post('eliminar-docente')) {
    $id_docente = post('id_docente');
    query("UPDATE cursos_docentes SET "
            . "estado='0' "
            . " WHERE id='$id_docente' LIMIT 1 ");
    movimiento('Eliminacion de docente', 'eliminacion-docente', 'docente', $id_docente);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM cursos_docentes WHERE estado IN ('1','2') $qr_busqueda ORDER BY estado ASC, id ASC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_docentes WHERE estado IN ('1','2') $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="docentes-listar.adm">Docentes</a></li>
            <li class="active">Listado de docentes</li>
        </ul>

        <div class="form-group pull-right">
            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-docente">
                <i class="fa fa-plus"></i> 
                AGREGAR DOCENTE
            </button> &nbsp;&nbsp;
        </div>
        <h3 class="page-header"> DOCENTES <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de docentes registrados.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de docentes
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de docente..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_docentes();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_docentes();"><i class="fa fa-search"></i>
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
                                <th>PREF.</th>
                                <th>NOMBRE</th>
                                <th>CURRICULUM</th>
                                <th>MODALIDAD PAGO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $idstext = 'editor00';
                            while ($producto = mysql_fetch_array($resultado1)) {
                                $idstext .= ',editor' . $producto['id'];
                                $txt_estado = '<span style="color:red;">DESACTIVADO</span>';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<span style="color:green;">ACTIVADO</span>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo $producto['prefijo']; ?></td>
                                    <td><?php echo trim($producto['nombres'] . ' ' . $producto['apellidos']); ?></td>
                                    <td><?php echo substr(strip_tags($producto['curriculum']), 0, 150); ?>...</td>
                                    <td>
                                        <?php
                                        if ($producto['id_modalidad_pago'] == '0') {
                                            echo 'Sin modalidad de pago';
                                        } else {
                                            $rqmdp1 = query("SELECT * FROM cursos_docentes_modalidades_pago WHERE id='" . $producto['id_modalidad_pago'] . "' ");
                                            $rqmdp2 = mysql_fetch_array($rqmdp1);
                                            echo '<b>' . $rqmdp2['titulo'] . '</b>';
                                            echo '<br/>';
                                            echo 'Pago por hora: ' . $producto['pago_hora'] . ' BS';
                                            if($producto['id_modalidad_pago']=='2'){
                                                echo '<br/>';
                                                echo 'Limite minimo: ' . $producto['paydata_limite'] . ' part.';
                                            }
                                            if($producto['id_modalidad_pago']=='3'){
                                                echo '<br/>';
                                                echo 'Pago por hora(interior): ' . $producto['pago2_hora'] . ' BS';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>
                                        <a data-toggle="modal" data-target="#MODAL-editar-docente-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info active"><i class="fa fa-edit"></i></button>
                                        </a>
                                        <a data-toggle="modal" data-target="#MODAL-eliminar-docente-<?php echo $producto['id']; ?>" title="">
                                            <button type="button" class="btn btn-danger active"><i class="fa fa-times"></i></button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-docente-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE DOCENTE</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">PREF.</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="prefijo" value="<?php echo $producto['prefijo']; ?>" placeholder="Prefijo..." autocomplete="off"/>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['nombres']; ?>" required placeholder="Nombres del docente..." autocomplete="off"/>
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
                                                                                    <option value="1" <?php echo $text_select; ?> >CUENTA ACTIVADA</option>
                                                                                    <?php
                                                                                    $text_select = '';
                                                                                    if ($producto['estado'] == '2') {
                                                                                        $text_select = ' selected="selected" ';
                                                                                    }
                                                                                    ?>
                                                                                    <option value="2" <?php echo $text_select; ?> >CUENTA DESACTIVADA</option>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-3 col-md-3 control-label text-primary">MODALIDAD DE PAGO</label>
                                                                            <div class="col-lg-9 col-md-9">
                                                                                <select class="form-control form-cascade-control" name="id_modalidad_pago">
                                                                                    <option value="0">Sin modalidad de pago</option>
                                                                                    <?php
                                                                                    $rqmdp1 = query("SELECT * FROM cursos_docentes_modalidades_pago WHERE estado='1' ");
                                                                                    while ($rqmdp2 = mysql_fetch_array($rqmdp1)) {
                                                                                        $text_select = '';
                                                                                        if ($producto['id_modalidad_pago'] == $rqmdp2['id']) {
                                                                                            $text_select = ' selected="selected" ';
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $rqmdp2['id']; ?>" <?php echo $text_select; ?>>
                                                                                            <?php echo $rqmdp2['titulo']; ?>
                                                                                        </option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if ($producto['id_modalidad_pago'] == '1') {
                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">PAGO POR HORA</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="number" class="form-control form-cascade-control" name="pago_hora" value="<?php echo $producto['pago_hora']; ?>" required placeholder="Pago por hora..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        echo '<input type="hidden" name="paydata_limite" value="' . $producto['paydata_limite'] . '"/>';
                                                                        echo '<input type="hidden" name="pago2_hora" value="' . $producto['pago2_hora'] . '"/>';
                                                                    } elseif ($producto['id_modalidad_pago'] == '2') {
                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">PAGO POR HORA</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="number" class="form-control form-cascade-control" name="pago_hora" value="<?php echo $producto['pago_hora']; ?>" required placeholder="Pago por hora..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">LIMITE MINIMO</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="number" class="form-control form-cascade-control" name="paydata_limite" value="<?php echo $producto['paydata_limite']; ?>" required placeholder="Limite minimo de participantes..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        echo '<input type="hidden" name="pago2_hora" value="' . $producto['pago2_hora'] . '"/>';
                                                                    } elseif ($producto['id_modalidad_pago'] == '3') {
                                                                        ?>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">PAGO POR HORA</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="number" class="form-control form-cascade-control" name="pago_hora" value="<?php echo $producto['pago_hora']; ?>" required placeholder="Pago por hora..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-lg-3 col-md-3 control-label text-primary">PAGO POR HORA (Interior)</label>
                                                                                <div class="col-lg-9 col-md-9">
                                                                                    <input type="number" class="form-control form-cascade-control" name="pago2_hora" value="<?php echo $producto['pago2_hora']; ?>" required placeholder="Limite minimo de participantes..." autocomplete="off"/>
                                                                                    <br/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        echo '<input type="hidden" name="paydata_limite" value="' . $producto['paydata_limite'] . '"/>';
                                                                    } else {
                                                                        echo '<input type="hidden" name="pago_hora" value="' . $producto['pago_hora'] . '"/>';
                                                                        echo '<input type="hidden" name="paydata_limite" value="' . $producto['paydata_limite'] . '"/>';
                                                                        echo '<input type="hidden" name="pago2_hora" value="' . $producto['pago2_hora'] . '"/>';
                                                                    }
                                                                    ?>
                                                                    <div class="row">
                                                                        <div class="form-group">
                                                                            <label class="col-lg-12 col-md-12 control-label text-primary">CURRICULUM</label>
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <textarea name="curriculum" id="editor<?php echo $producto['id']; ?>" style="width:100% !important;margin:auto;height:500px;" rows="25"><?php echo $producto['curriculum']; ?></textarea>
                                                                                <br/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_docente" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="editar-docente" class="btn btn-success btn-sm btn-animate-demo" value="ACTUALIZAR DATOS"/>
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
                                        <div id="MODAL-eliminar-docente-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-trash"></i> ELIMINACI&Oacute;N DE DOCENTE</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <form action="" method="post">
                                                                <div class="panel-body">

                                                                    <div class="form-group">
                                                                        <label class="col-md-12 control-label text-danger">
                                                                            &iquest; Desea eliminar al docente ?
                                                                        </label>
                                                                        <br/>                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-2 col-md-3 control-label text-primary">NOMBRE</label>
                                                                        <div class="col-lg-10 col-md-9">
                                                                            <input type="text" class="form-control form-cascade-control" name="nombres" value="<?php echo $producto['nombres'] . ' ' . $producto['apellidos']; ?>" required placeholder="Apellidos y nombres..." disabled=""/>
                                                                            <br/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="panel-footer">
                                                                    <input type="hidden" name="id_docente" value="<?php echo $producto['id']; ?>"/>
                                                                    <input type="submit" name="eliminar-docente" class="btn btn-danger btn-sm btn-animate-demo" value="ELIMINAR DOCENTE"/>
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

                    <li><a href="docentes-listar/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="docentes-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="docentes-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<!-- Modal -->
<div id="MODAL-agregar-docente" class="modal modal-info fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> AGREGAR NUEVO DOCENTE</h4>
            </div>
            <div class="modal-body">
                <p>
                    A continuaci&oacute;n ingresa los datos del nuevo docente.
                </p>

                <div class="panel panel-default">
                    <form action="" method="post">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">PREF.</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="prefijo" value="" placeholder="Prefijo ejemplo: Ing. Lic. Dr. etc..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                    <div class="col-lg-9 col-md-9">
                                        <input type="text" class="form-control form-cascade-control" name="nombres" value="" required placeholder="Nombre del docente..." autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">ESTADO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="estado">
                                            <option value="1" <?php echo $text_select; ?> >CUENTA ACTIVADA</option>
                                            <option value="2" <?php echo $text_select; ?> >CUENTA DESACTIVADA</option>
                                        </select>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-3 control-label text-primary">MODALIDAD DE PAGO</label>
                                    <div class="col-lg-9 col-md-9">
                                        <select class="form-control form-cascade-control" name="id_modalidad_pago">
                                            <option value="0">Sin modalidad de pago</option>
                                            <?php
                                            $rqmdp1 = query("SELECT * FROM cursos_docentes_modalidades_pago WHERE estado='1' ");
                                            while ($rqmdp2 = mysql_fetch_array($rqmdp1)) {
                                                ?>
                                                <option value="<?php echo $rqmdp2['id']; ?>">
                                                    <?php echo $rqmdp2['titulo']; ?>
                                                </option>
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
                                    <label class="col-lg-12 col-md-12 control-label text-primary">CURRICULUM</label>
                                    <div class="col-lg-12 col-md-12">
                                        <textarea name="curriculum" id="editor00" style="width:100% !important;margin:auto;height:500px;" rows="25"></textarea>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="submit" name="agregar-docente" class="btn btn-success btn-sm btn-animate-demo" value="AGREGAR DOCENTE"/>
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

<?php
editorTinyMCE($idstext);
?>
