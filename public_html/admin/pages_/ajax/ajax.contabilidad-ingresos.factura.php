<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

require_once "../../../contenido/librerias/classes/class.codigo-control-v7.php";

use clases\CodigoControlV7;

if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

/* data */
$id_contabilidad = post('id_contabilidad');

/* rel data factura */
$id_factura = 0;
$rqrdt1 = query("SELECT id_factura FROM contabilidad_rel_data WHERE id_contabilidad='$id_contabilidad' ORDER BY id DESC limit 1 ");
if(num_rows($rqrdt1)>0){
    $rqrdt2 = fetch($rqrdt1);
    $id_factura = $rqrdt2['id_factura'];
}

/* registro */
$rqc1 = query("SELECT * FROM contabilidad WHERE id='$id_contabilidad' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$concepto = $rqc2['detalle'];
$total = $rqc2['monto'];
$id_actividad = '0';
if ($rqc2['id_referencia'] == '1' || true) {
    $id_actividad = '3';
}

if($id_factura>0){
/* factura */
$rqfea1 = query("SELECT * FROM facturas_emisiones WHERE id='$id_factura' ORDER BY id DESC limit 1 ");
$rqfea2 = fetch($rqfea1);
?>
<table class="table table-striped table-bordered">
    <tr>
        <td><b>Nro. de Factura: </b></td>
        <td><?php echo $rqfea2['nro_factura']; ?></td>
    </tr>
    <tr>
        <td><b>Detalle: </b></td>
        <td><?php echo $rqfea2['concepto']; ?></td>
    </tr>
    <tr>
        <td><b>Facturado a nombre de: </b></td>
        <td><?php echo $rqfea2['nombre_receptor']; ?></td>
    </tr>
    <tr>
        <td><b>NIT: </b></td>
        <td><?php echo $rqfea2['nit_receptor']; ?></td>
    </tr>
    <tr>
        <td><b>Monto facturado: </b></td>
        <td><?php echo $rqfea2['total']; ?></td>
    </tr>
    <tr>
        <td><b>Fecha de emision: </b></td>
        <td><?php echo $rqfea2['fecha_emision']; ?></td>
    </tr>
    <tr>
        <td><b>Codigo de control: </b></td>
        <td><?php echo $rqfea2['codigo_de_control']; ?></td>
    </tr>
    <tr>
        <td><b>Nro. de autorizaci&oacute;n: </b></td>
        <td><?php echo $rqfea2['nro_autorizacion']; ?></td>
    </tr>
    <tr>
        <td><b>Estado: </b></td>
        <td><?php echo $rqfea2['estado']=='1'?'<b class="label label-success">Emitido</b>':'<b class="label label-danger">Anulado</b>'; ?></td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <br>
            <br>
            <button class="btn btn-warning" onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1.php?id_factura=<?php echo $id_factura; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">IMPRIMIR FACTURA</button>
        </td>
    </tr>
</table>
<?php
} elseif (isset_post('emitir-factura')) {
    /* crear registro */
    $id_actividad = post('id_actividad');
    $concepto = post('concepto');
    $total = post('total');
    $nombre_receptor = post('nombre_receptor');
    $nit_receptor = post('nit_receptor');

    $nombre_a_facturar = $nombre_receptor;
    $nit_a_facturar = $nit_receptor;
    $monto_a_facturar = $total;

    $id_administrador = administrador('id');

    /* datos para emision de factura */
    $rqdf1 = query("SELECT * FROM facturas_dosificaciones WHERE estado='1' AND id_actividad='$id_actividad' ORDER BY id DESC limit 1 ");
    $rqdf2 = fetch($rqdf1);

    $id_dosificacion = $rqdf2['id'];
    $nro_autorizacion = $rqdf2['nro_autorizacion'];
    $nit_emisor = $rqdf2['nit_emisor'];
    $fecha_limite_emision = $rqdf2['fecha_limite_emision'];
    $llave_dosificacion = $rqdf2['llave_dosificacion'];

    $fecha_emision = date("Y-m-d");
    $fecha_registro = date("Y-m-d H:i");

    /* numero de factura */
    $rqfea1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id_dosificacion='$id_dosificacion' AND estado IN (1,2) ORDER BY nro_factura DESC limit 1 ");
    $rqfea2 = fetch($rqfea1);
    $nro_factura =  (int)$rqfea2['nro_factura'] + 1;

    /* generacion de codigo de control */
    $codigo_de_control = CodigoControlV7::generar($nro_autorizacion, $nro_factura, $nit_a_facturar, str_replace('-', '', $fecha_emision), $monto_a_facturar, $llave_dosificacion);

    /* estado */
    $estado = '1';

    query("INSERT INTO facturas_emisiones(
           id_dosificacion, 
           id_administrador, 
           id_actividad, 
           nro_factura, 
           nro_autorizacion, 
           nit_emisor, 
           fecha_limite_emision, 
           codigo_de_control, 
           nombre_receptor, 
           nit_receptor, 
           total, 
           concepto, 
           fecha_emision, 
           ciudad_emision, 
           fecha_registro, 
           estado
           ) VALUES (
            '$id_dosificacion',
           '$id_administrador',
           '$id_actividad',
           '$nro_factura',
           '$nro_autorizacion',
           '$nit_emisor',
           '$fecha_limite_emision',
           '$codigo_de_control',
           '$nombre_a_facturar',
           '$nit_a_facturar',
           '$monto_a_facturar',
           '$concepto',
           '$fecha_emision',
           'LA PAZ',
           '$fecha_registro',
           '$estado'
           )");

    /* id de emision de factura */
    $id_emision_factura = insert_id();

    /* log */
    logcursos('Emision de factura [id:' . $id_emision_factura . '][cont:'.$id_contabilidad.']', 'edicion-contabilidad', 'contabilidad', $id_contabilidad);

    /* rel data */
    query("INSERT INTO contabilidad_rel_data (id_contabilidad,id_factura) VALUES ('$id_contabilidad','$id_emision_factura') ");
?>
    <div class="alert alert-success">
        <strong>EXITO</strong> Factura emitida exitosamente.
    </div>

    <table class="table table-striped table-bordered">
        <tr>
            <td><b>Nro. de Factura:</b></td>
            <td><?php echo $nro_factura; ?></td>
        </tr>
        <tr>
            <td><b>Detalle: </b></td>
            <td><?php echo $concepto; ?></td>
        </tr>
        <tr>
            <td><b>Facturado a nombre de: </b></td>
            <td><?php echo $nombre_a_facturar; ?></td>
        </tr>
        <tr>
            <td><b>NIT: </b></td>
            <td><?php echo $nit_a_facturar; ?></td>
        </tr>
        <tr>
            <td><b>Monto facturado: </b></td>
            <td><?php echo $monto_a_facturar; ?></td>
        </tr>
        <tr>
            <td><b>Fecha de emision: </b></td>
            <td><?php echo $fecha_emision; ?></td>
        </tr>
        <tr>
            <td><b>Codigo de control: </b></td>
            <td><?php echo $codigo_de_control; ?></td>
        </tr>
        <tr>
            <td><b>Nro. de autorizaci&oacute;n: </b></td>
            <td><?php echo $nro_autorizacion; ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <br>
                <br>
                <button class="btn btn-warning" onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1.php?id_factura=<?php echo $id_emision_factura; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">IMPRIMIR FACTURA</button>
            </td>
        </tr>
    </table>
<?php
} else {
?>
    <div class="panel panel-primary">
        <div class="panel-body">
            <form action="" method="post" id="FORM-factura">
                <table style="width:100%;" class="table table-striped">
                    <tr>
                        <td>
                            <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; ACTIVIDAD: </span>
                        </td>
                        <td>
                            <select name="id_actividad" class="form-control">
                                <?php
                                $rqac1 = query("SELECT * FROM facturas_actividades WHERE estado='1' ");
                                while ($rqac2 = fetch($rqac1)) {
                                ?>
                                    <option value="<?php echo $rqac2['id']; ?>" <?php echo $rqac2['id'] == $id_actividad ? ' selected="selected" ' : ''; ?>><?php echo $rqac2['actividad']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; CONCEPTO: </span>
                        </td>
                        <td>
                            <input type="text" name="concepto" value="<?php echo $concepto; ?>" class="form-control" id="date" required="" autocomplete="off">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; TOTAL (BS): </span>
                        </td>
                        <td>
                            <input type="number" name="total" value="<?php echo $total; ?>" class="form-control" id="date" required="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; A NOMBRE DE: </span>
                        </td>
                        <td>
                            <input type="text" name="nombre_receptor" value='<?php echo $curso['cont_uno']; ?>' class="form-control" id="date" required="" autocomplete="off">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; N&Uacute;MERO NIT: </span>
                        </td>
                        <td>
                            <input type="number" name="nit_receptor" value='<?php echo $curso['cont_dos']; ?>' class="form-control" id="date" required="" autocomplete="off">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div style="text-align: center;padding:20px;">
                                <input type="hidden" name="id_contabilidad" value="<?php echo $id_contabilidad; ?>" />
                                <input type="hidden" name="emitir-factura" value="1" />
                                <input type="submit" name="" value="EMITIR FACTURA" class="btn btn-success btn-block" />
                            </div>
                        </td>
                    </tr>
                </table>
            </form>

        </div>
    </div>


<?php
}
?>

<script>
    $('#FORM-factura').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.contabilidad-ingresos.factura.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
                $("#td-idcont-<?php echo $id_contabilidad; ?>").html('<b class="btn btn-xs btn-success btn-block" onclick="factura(<?php echo $id_contabilidad; ?>);">Factura emitida</b>');
            }
        });
    });
</script>