<?php
require_once "contenido/librerias/classes/class.codigo-control-v7.php";

use clases\CodigoControlV7;

/* mensaje */

$mensaje = '';

/* data */
$id_participante = $get[2];
$hash = $get[3];

/* verificacion */
if (md5(md5('autfact1015121' . $id_participante)) != $hash) {
    echo "<script>alert('DENEGADO');location.href='$dominio';</script>";
    exit;
}

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($rqdp1);
$nombre_participante = $participante['nombres'] . ' ' . $participante['apellidos'];
$correo_participante = $participante['correo'];
$ci_participante = $participante['ci'];
$id_proceso_registro = $participante['id_proceso_registro'];
$id_departamento = $participante['id_departamento'];
$id_curso = $participante['id_curso'];


/* proceso registro */
$rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$procreg = fetch($rqdpr1);
$monto_a_facturar = $procreg['monto_deposito'];
$id_emision_factura = (int)$procreg['id_emision_factura'];

/* generar-factura */
if (isset_post('generar-factura')) {

    $ci = post('ci');
    $nit_a_facturar = post('nit');
    $nombre_a_facturar = strtoupper(post('nombre_fact'));
    $id_administrador = 99;
    $id_actividad = '3';

    /* verificacion de CI */
    if ($ci_participante != $ci) {
        $mensaje .= '<br><br><div class="alert alert-danger">
  <strong>ERROR</strong> el C.I. no corresponde al participante.
</div>';
    }else{


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
            echo "<b>Error!</b> no se encontro ID de curso";
            exit;
        }
        $rqauxc2 = fetch($rqauxc1);
        $titulo_curso = strtoupper($rqauxc2['titulo']);
        $participante_curso = strtoupper($participante['nombres'] . ' ' . $participante['apellidos']);

        $concepto = $titulo_curso . ' - PARTICIPANTE: ' . $participante_curso . '.';
        $fecha_emision = date("Y-m-d");
        $fecha_registro = date("Y-m-d H:i");

        /* numero de factura */
        $rqfea1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id_dosificacion='$id_dosificacion' AND estado IN (1,2) ORDER BY nro_factura DESC limit 1 ");
        $rqfea2 = fetch($rqfea1);
        $nro_factura = (int) ($rqfea2['nro_factura'] + 1);

        /* generacion de codigo de control */
        $codigo_de_control = CodigoControlV7::generar($nro_autorizacion, $nro_factura, $nit_a_facturar, str_replace('-', '', $fecha_emision), Util::redondeoMontoCodigoControl($monto_a_facturar), $llave_dosificacion);

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

        query("UPDATE cursos_proceso_registro SET id_emision_factura='$id_emision_factura' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

        logcursos('Emision de AUTO-FACTURA [F:' . $nro_factura . ']', 'partipante-certificados', 'participante', $id_participante);

        /* update en contabilidad */
        $rqdctb1 = query("SELECT id FROM contabilidad_rel_data WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
        if (num_rows($rqdctb1) > 0) {
            $rqdctb2 = fetch($rqdctb1);
            $id_contabilidad = $rqdctb2['id'];
            query("UPDATE contabilidad_rel_data SET id_factura='$id_emision_factura' WHERE id='$id_contabilidad' ORDER BY id DESC limit 1 ");
        }

        $mensaje .= '<br><div class="alert alert-success">
        <strong>EXITO</strong> factura emitida exitosamente.
    </div>';
    }
}

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <?php echo $mensaje; ?>
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="TituloArea">
                    <h3>GENERAR FACTURA</h3>
                </div>

                <div class="Titulo_texto1">
                    <?php if ($id_emision_factura==0) { ?>
                        <div style="background: #f3fbff;padding: 20px;font-size: 12pt;text-align: justify;border: 1px solid #e0e0e0;">
                            Hola <?php echo $nombre_participante; ?>
                            <br>
                            <br>
                            Desde aqu&iacute; puedes generar tu factura, para ello llena cuidadosamente los datos del siguiente formulario:
                            <br>
                            <br>

                            <div style="text-align:center;padding: 20px 0px;">
                                <form action="" method="post">
                                    <table class="table table-bordered" style="background: #FFF;">
                                        <tr>
                                            <td style="padding: 15px;"><b>Participante:</b></td>
                                            <td style="padding: 15px;"><?php echo $nombre_participante; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>C.I.:</b></td>
                                            <td><input type="text" value="" class="form-control" name="ci" placeholder="Ingresa el CI con el que te registraste..." required="" autocomplete="off"/></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>Monto a facturar:</b></td>
                                            <td style="padding: 15px;"><?php echo $monto_a_facturar; ?> BS</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>NIT a facturar:</b></td>
                                            <td><input type="number" value="" class="form-control" name="nit" placeholder="Ingresa el NIT a facturar..." required="" autocomplete="off"/></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>Facturar a nombre de:</b></td>
                                            <td><input type="text" value="" class="form-control" name="nombre_fact" placeholder="Ingresa el nombre a facturar..." required="" autocomplete="off"/></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <input type="submit" name="generar-factura" value="GENERAR FACTURA" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                                </form>
                            </div>
                        </div>
                    <?php 
                } else { 
                    /* factura */
                    $rqdf1 = query("SELECT * FROM facturas_emisiones WHERE id='$id_emision_factura' LIMIT 1 ");
                    $rqdf2 = fetch($rqdf1);
                    $nro_factura = $rqdf2['nro_factura'];
                    $nombre_a_facturar = $rqdf2['nombre_receptor'];
                    $nit_a_facturar = $rqdf2['nit_receptor'];
                    $monto_a_facturar = $rqdf2['total'];
                    $fecha_emision = $rqdf2['fecha_emision'];
                    $codigo_de_control = $rqdf2['codigo_de_control'];
                    $nro_autorizacion = $rqdf2['nro_autorizacion'];
                    ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                            Factura emitida
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
                                    <br />
                                    <br />
                                    <b>Descarga de la factura: -> </b> <a href="https://cursos.bo/F/<?php echo $id_emision_factura; ?>/" class="btn btn-default btn-lg">DESCARGAR PDF FACTURA</a>
                                    <br />
                                </td>
                            </tr>
                        </table>
                    <?php } ?>
                </div>
                <hr />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
    </section>
</div>