<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

require_once "../../../contenido/librerias/classes/class.codigo-control-v7.php";

use clases\CodigoControlV7;

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$nombre_a_facturar = strtoupper(post('nombre_a_facturar'));
$nit_a_facturar = post('nit_a_facturar');
$monto_a_facturar = post('monto_a_facturar');
$id_certificado = post('id_certificado');
$id_curso = post('id_curso');
$id_participante = post('id_participante');
$id_administrador = administrador('id');
$id_actividad = '3';

/* verificacion de monto */
if ((int) $monto_a_facturar <= 0) {
    echo "<b>Error!</b> no se ingreso monto para la facturaci&oacute;n.";
    exit;
}

/* datos para emision de factura */
$rqdf1 = query("SELECT * FROM facturas_dosificaciones WHERE estado='1' AND id_actividad='$id_actividad' ORDER BY id DESC limit 1 ");
$rqdf2 = fetch($rqdf1);

$id_dosificacion = $rqdf2['id'];
$nro_autorizacion = $rqdf2['nro_autorizacion'];
$nit_emisor = $rqdf2['nit_emisor'];
$fecha_limite_emision = $rqdf2['fecha_limite_emision'];
$llave_dosificacion = $rqdf2['llave_dosificacion'];

/* datos curso */
$rqauxc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' LIMIT 1 ");
if (num_rows($rqauxc1) == 0) {
    echo "<b>Error!</b> no se encontro ID de curso, actualize la pagina y vuelva a intentar.";
    exit;
}
$rqauxc2 = fetch($rqauxc1);
$titulo_curso = $rqauxc2['titulo'];

/* datos de certificado */
$qrdcc1 = query("SELECT cont_tres FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$qrdcc2 = fetch($qrdcc1);
$cont_tres_certificado = $qrdcc2['cont_tres'];

/* datos participante */
$rqauxcc1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' LIMIT 1 ");
if (num_rows($rqauxcc1) == 0) {
    echo "<b>Error!</b> no se encontro ID de participante, actualize la pagina y vuelva a intentar.";
    exit;
}
$rqauxcc2 = fetch($rqauxcc1);
$participante_curso = $rqauxcc2['nombres'] . ' ' . $rqauxcc2['apellidos'];

//*$concepto = $titulo_curso . ' - Participante: ' . $participante_curso.'. '.$cont_tres_certificado;
$concepto = strtoupper($titulo_curso . ' - PARTICIPANTE: ' . $participante_curso . '.');
$fecha_emision = date("Y-m-d");
$fecha_registro = date("Y-m-d H:i");

/* numero de factura */
$rqfea1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id_dosificacion='$id_dosificacion' AND estado IN (1,2) ORDER BY nro_factura DESC limit 1 ");
$rqfea2 = fetch($rqfea1);
$nro_factura = (int) ($rqfea2['nro_factura'] + 1);

/* generacion de codigo de control */
$codigo_de_control = CodigoControlV7::generar($nro_autorizacion, $nro_factura, $nit_a_facturar, str_replace('-', '', $fecha_emision), $monto_a_facturar, $llave_dosificacion);

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
           '1'
           )");
$id_emision_factura = insert_id();

/* id de proceso de registro */
$rqpr1 = query("SELECT id_proceso_registro,correo FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqpr2 = fetch($rqpr1);
$id_proceso_registro = $rqpr2['id_proceso_registro'];
$correo = $rqpr2['correo'];

query("UPDATE cursos_proceso_registro SET id_emision_factura='$id_emision_factura' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

logcursos('Emision de factura [F:' . $nro_factura . ']', 'partipante-certificados', 'participante', $id_participante);

/* sw cierre */
query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

/* datos curso */
$rqdcf1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdcf2 = fetch($rqdcf1);
$fecha_curso = $rqdcf2['fecha'];
if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
    logcursos('Emision de factura [fuera de fecha][F:' . $nro_factura . ']', 'curso-edicion', 'curso', $id_curso);
}

/* update en contabilidad */
$rqdctb1 = query("SELECT id FROM contabilidad_rel_data WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
if(num_rows($rqdctb1)>0){
    $rqdctb2 = fetch($rqdctb1);
    $id_contabilidad = $rqdctb2['id'];
    query("UPDATE contabilidad_rel_data SET id_factura='$id_emision_factura' WHERE id='$id_contabilidad' ORDER BY id DESC limit 1 ");
}
?>
<div class="alert alert-success">
    <strong>Exito!</strong> Factura emitida exitosamente.
</div>

<table class="table table-striped">
    <tr>
        <td>Nro. de Factura: </td>
        <td><?php echo $nro_factura; ?></td>
    </tr>
    <tr>
        <td>Factura a nombre de: </td>
        <td><?php echo $nombre_a_facturar; ?></td>
    </tr>
    <tr>
        <td>NIT: </td>
        <td><?php echo $nit_a_facturar; ?></td>
    </tr>
    <tr>
        <td>Monto facturado: </td>
        <td><?php echo $monto_a_facturar; ?></td>
    </tr>
    <tr>
        <td>Fecha de emision: </td>
        <td><?php echo $fecha_emision; ?></td>
    </tr>
    <tr>
        <td>Codigo de control: </td>
        <td><?php echo $codigo_de_control; ?></td>
    </tr>
    <tr>
        <td>Nro. de autorizaci&oacute;n: </td>
        <td><?php echo $nro_autorizacion; ?></td>
    </tr>
    <tr>
        <td colspan='2'>
            <br/>
            <br/>
            <b>Visualizaci&oacute;n -> </b> <button onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1.php?id_factura=<?php echo $id_emision_factura; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">IMPRIMIR FACTURA</button>
            <br/>
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <br/>
            <hr/>
            <h4 class="text-center">ENVIO DE FACTURA DIGITAL</h4>
            <div class="text-center" id="box-modal_envia-factura-1017">
                <h5 class="text-center">
                    Ingrese el correo al cual se hara el envio de la factura
                </h5>
                <div class="row">
                    <div class="col-md-8 text-right">
                        <input type="text" id="correo-de-envio-<?php echo $id_emision_factura; ?>" class="form-control text-center" value="<?php echo $correo; ?>">
                    </div>
                    <div class="col-md-3 text-left" id="box-modal_envia-factura-<?php echo $id_emision_factura; ?>">
                        <button class="btn btn-success" onclick="enviar_factura('<?php echo $id_emision_factura; ?>');"><i class="fa fa-send"></i> ENVIAR</button>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </td>
    </tr>
</table>
