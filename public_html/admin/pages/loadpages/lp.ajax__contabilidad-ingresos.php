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

/* tipo de movimiento */
$id_tipo_movimiento = 1;

/* id administrador */
$id_administrador = administrador('id');

/* agregar-ingreso */
if (isset_post('agregar-ingreso')) {
    $monto = post('monto');
    $tipo = $id_tipo_movimiento;
    $referencia = post('referencia');
    $fecha = date("Y-m-d");
    $detalle = post('detalle');
    query("INSERT INTO contabilidad (id_tipo_movimiento, id_referencia, monto, fecha, detalle, id_administrador, fecha_registro, estado) VALUES ('$tipo','$referencia','$monto','$fecha','$detalle','$id_administrador',NOW(),'1') ");
    $mensaje = '<br/><div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
}

/* registros */
$data_required = "
ct.*,(rf.titulo)dr_referencia,(rd.id_factura)dr_id_factura
";
$resultado1 = query("SELECT $data_required 
FROM contabilidad ct 
INNER JOIN contabilidad_referencias rf ON ct.id_referencia=rf.id 
LEFT JOIN contabilidad_rel_data rd ON rd.id_contabilidad=ct.id 
WHERE 
ct.estado='1' AND 
ct.id_tipo_movimiento='$id_tipo_movimiento' AND 
fecha=CURDATE() AND 
id_administrador='$id_administrador' 
ORDER BY ct.fecha DESC,ct.id DESC ");
$total_registros = num_rows($resultado1);
$cnt = $total_registros;
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
                    INGRESOS <i class="fa fa-info-circle animated bounceInDown show-info"></i>
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
    <div class="panel-heading">MODULO DE INGRESOS DEL D&Iacute;A</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form action="" method="post">
                    <div style="background: #c3e2f1;padding: 15px;border-radius: 10px;margin-bottom: 15px;">
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
                                <input type="submit" name="agregar-ingreso" value="REGISTRAR INGRESO" class="btn btn-success btn-block" />
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
                        <th class="" style="font-size:10pt;">ID</th>
                        <th class="" style="font-size:10pt;">Detalle</th>
                        <th class="" style="font-size:10pt;">Monto</th>
                        <th class="" style="font-size:10pt;">Referencia</th>
                        <th class="" style="font-size:10pt;">Hora</th>
                        <th class="" style="font-size:10pt;">Factura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (num_rows($resultado1) == 0) {
                        echo '<div class="alert alert-info">
                        <strong>SIN REGISTROS</strong><br>No se registraron ingresos con su cuenta el d&iacute;a de hoy.
                      </div>
                      ';
                    }
                    while ($row = fetch($resultado1)) {
                        $dr_id_factura = (int)$row['dr_id_factura'];
                    ?>
                        <tr>
                            <td>
                                <?php echo $cnt--; ?>
                            </td>
                            <td>
                                <span class="label label-default" style="background: #eaeff9;padding: 2px 5px;border-radius: 0px;color: #27709a;border: 1px solid #c1d5e0;"><?php echo $row['id']; ?></span>
                            </td>
                            <td>
                                <?php echo $row['detalle']; ?>
                            </td>
                            <td>
                                <?php echo $row['monto']; ?> BS
                            </td>
                            <td>
                                <span class="label label-default" style="background: #eaeff9;padding: 2px 5px;border-radius: 0px;color: #27709a;border: 1px solid #c1d5e0;"><?php echo $row['dr_referencia']; ?></span>
                            </td>
                            <td>
                                <?php echo date("H:i", strtotime($row['fecha_registro'])); ?>
                            </td>
                            <td id="td-idcont-<?php echo $row['id']; ?>">
                                <?php
                                if ($dr_id_factura==0) {
                                ?>
                                    <b class="btn btn-xs btn-default btn-block" onclick="factura('<?php echo $row['id']; ?>');">Emitir factura</b>
                                <?php
                                } else {
                                ?>
                                    <b class="btn btn-xs btn-success btn-block" onclick="factura('<?php echo $row['id']; ?>');">Factura emitida</b>
                                <?php
                                }
                                ?>
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


<!-- factura -->
<script>
    function factura(id_contabilidad) {
        $("#TITLE-modgeneral").html('FACTURA');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.contabilidad-ingresos.factura.php',
            data: {
                id_contabilidad: id_contabilidad
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>