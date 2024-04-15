<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

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
/* datos de control de consulta */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}
$user_agent = real_escape_string($_SERVER['HTTP_USER_AGENT']);
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

/* tipo de movimiento */
$id_tipo_movimiento = 2;

/* id administrador */
$id_administrador = administrador('id');

/* post search */
$postjson = json_encode($_POST);
if ($postjson == '[]') {
    $post_search_data = 'no-search';
} else {
    $post_search_data = base64_encode($postjson);
}

/* get search */
if (isset($get[3]) && $get[3] !== 'no-search') {
    $_POST = json_decode(base64_decode($get[3]), true);
}

/* busqueda */
$busqueda = "";
$id_departamento = "0";
$fecha_busqueda = "";
$fecha_final_busqueda = "";
$txt_estado = "";
$htm_titlepage = 'EGRESOS';
$qr_busqueda = "";
$qr_fecha = "";
$qr_estado = " AND c.estado IN (0,1,2) AND c.sw_suspendido='0'  ";
$qr_departamento = "";
$qr_docente = "";
$qr_lugar = "";
$qr_modalidad = " AND id_modalidad IN (1) ";


/* agregar-referencia */
if (isset_post('agregar-referencia')) {
    $referencia = post('referencia');
    query("INSERT INTO contabilidad_referencias (id_tipo_movimiento,titulo,estado) VALUES ('$id_tipo_movimiento','$referencia','1') ");
    $id_referencia = insert_id();
    logcursos('Creacion de referencia I [' . $referencia . ']', 'creacion-referencia', 'referencia', $id_referencia);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro fue agregado correctamente.
</div>';
}

/* agregar-ingreso */
if (isset_post('agregar-ingreso')) {
    $monto = post('monto');
    $tipo = $id_tipo_movimiento;
    $referencia = post('referencia');
    $fecha = date("Y-m-d H:i");
    $detalle = post('detalle');
    $id_administracion = administrador('id');
    query("INSERT INTO contabilidad (id_tipo_movimiento, id_referencia, monto, fecha, detalle, id_administrador, fecha_registro, estado) VALUES ('$tipo','$referencia','$monto','$fecha','$detalle','$id_administracion',NOW(),'1') ");
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
}


/* editar-referencia */
if (isset_post('editar-referencia')) {
    $id_referencia = post('id_referencia');
    $referencia = post('referencia');
    $estado = post('estado');
    query("UPDATE contabilidad_referencias SET titulo='$referencia',estado='$estado' WHERE id='$id_referencia' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de referencia I/E [a:' . $referencia . ']', 'edicion-referencia', 'referencia', $id_referencia);
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro fue modificado correctamente.
</div>';
}


/* registros_pagina */
$registros_a_mostrar = 20;
if (isset_post('registros_pagina')) {
    $registros_a_mostrar = (int) post('registros_pagina');
}
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = fetch($rqda1);
$nivel_administrador = $rqda2['nivel'];


/* eliminacion de curso */
if (isset_post('delete-course')) {
    if ($nivel_administrador == '1' || isset_organizador()) {
        $id_curso_delete = post('id_curso');
        /* fecha nula */
        $rqdcd1 = query("SELECT fecha,numero FROM blog WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        $rqdcd2 = fetch($rqdcd1);
        if ($rqdcd2['numero'] == '0') {
            if ($rqdcd2['fecha'] == (date("Y") . '-12-31')) {
                query("UPDATE blog SET fecha='" . date("Y") . "-01-01' WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
            }
            /* proceso */
            query("UPDATE blog SET estado='3' WHERE id='$id_curso_delete' ORDER BY id DESC ");
            logcursos('Eliminacion de curso', 'curso-eliminacion', 'curso', $id_curso_delete);
            $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO!</strong> curso eliminado correctamente.
</div>';
        } else {
            $mensaje = '<br/><div class="alert alert-danger">
  <strong>ERROR!</strong> el curso ya tiene numeraci&oacute;n y no puede ser eliminado.
</div>';
        }
    }
}

/* registros */
$data_required = "
ie.*,(rf.titulo)dr_referencia
";
$resultado1 = query("SELECT $data_required FROM contabilidad ie INNER JOIN contabilidad_referencias rf ON ie.id_referencia=rf.id WHERE ie.estado='1' AND ie.id_tipo_movimiento='$id_tipo_movimiento' AND fecha=CURDATE() AND id_administrador='$id_administrador' ORDER BY ie.fecha DESC,ie.id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM contabilidad ie WHERE ie.estado='1' ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - (($vista - 1) * $registros_a_mostrar);
?>
<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb" style="margin: 0px;">
            <?php
            include '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="row" style="padding: 10px 0px;">
            <div class="col-md-5">
                <b style="font-size: 15pt;color: #3283ca;">
                    EGRESOS <i class="fa fa-info-circle animated bounceInDown show-info"></i>
                </b>
            </div>
            <div class="col-md-7 text-right">
            <?php include '../items/item.enlaces_contabilidad.php';?>
            </div>
        </div>
    </div>
</div>


<?php echo $mensaje; ?>



<!-- Estilos -->
<style>
    .tr_curso_suspendido td {
        background: #ebefdd !important;
    }

    .tr_curso_cerrado td {
        background: #eaedf1 !important;
        border-color: #FFF !important;
    }

    .tr_curso_cerrado:hover td {
        background: #FFF !important;
        border-color: #eaedf1 !important;
    }

    .tr_curso_eliminado td {
        background: #f3e3e3 !important;
    }
</style>

<div class="panel panel-primary">
    <div class="panel-heading">MODULO DE INGRESOS</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="" method="post">
                    <div style="    background: #c3f1cf;
                         padding: 15px;
                         border-radius: 10px;
                         margin-bottom: 15px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon">Monto:</span>
                                    <input type="number" name="monto" class="form-control" required="" autocomplete="off" placeholder="0" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon">Detalle:</span>
                                    <input type="text" name="detalle" autocomplete="off" class="form-control" placeholder="Detalle..." />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon">Referencia:</span>
                                    <select class="form-control" name="referencia">
                                        <?php
                                        $rqrff1 = query("SELECT * FROM contabilidad_referencias WHERE estado='1' AND id_tipo_movimiento='$id_tipo_movimiento' ");
                                        while ($rqrff2 = fetch($rqrff1)) {
                                        ?>
                                            <option value="<?php echo $rqrff2['id']; ?>"><?php echo $rqrff2['titulo']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <input type="submit" name="agregar-ingreso" value="REGISTRAR" class="btn btn-success btn-block" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table users-table table-condensed table-hover table-striped table-bordered">
                <thead>
                <tr>
                        <th class="" style="font-size:10pt;">#</th>
                        <th class="" style="font-size:10pt;">Detalle</th>
                        <th class="" style="font-size:10pt;">Monto</th>
                        <th class="" style="font-size:10pt;">Referencia</th>
                        <th class="" style="font-size:10pt;">Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(num_rows($resultado1)==0){
                        echo '<div class="alert alert-info">
                        <strong>SIN REGISTROS</strong><br>No se registraron egresos con su cuenta el d&iacute;a de hoy.
                      </div>
                      ';
                    }
                    while ($row = fetch($resultado1)) {
                    ?>
                        <tr>
                            <td class="">
                                <?php echo $cnt--; ?>
                            </td>
                            <td class="">
                                <?php echo $row['detalle']; ?>
                            </td>
                            <td class="">
                                <?php echo $row['monto']; ?> BS
                            </td>
                            <td class="">
                                <i class="label label-default" style="background: #eaeff9;padding: 2px 5px;border-radius: 0px;color: #27709a;border: 1px solid #c1d5e0;"><?php echo $row['dr_referencia']; ?></i>
                            </td>
                            <td class="">
                                <?php echo date("H:i", strtotime($row['fecha_registro'])); ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
