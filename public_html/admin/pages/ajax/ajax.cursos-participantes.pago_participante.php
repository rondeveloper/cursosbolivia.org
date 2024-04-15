<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');
$id_administrador = administrador('id');

/* proceso de edicion */
if (isset_post('update-proceso-2')) {
    /* datos recibidos */
    $monto_pago = post('monto_pago');
    $id_curso = post('id_curso');
    $id_modo_pago = post('id_modo_pago');
    $id_banco = post('id_banco');
    $observaciones = post('observaciones');

    /* monto en gratuito */
    if ($id_modo_pago == '10') {
        $monto_pago = '0';
    }

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = fetch($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    $sw_pago_enviado = '1';
    if ($id_modo_pago == '0') {
        $sw_pago_enviado = '0';
    }

    /* imagen deposito */
    $imagen_deposito = post('actual_imagen_deposito');
    if (is_uploaded_file(archivo('imagen_deposito'))) {
        if ($imagen_deposito !== '') {
            logcursos('Sube imagen respaldo de pago[prev:' . $imagen_deposito . ']', 'partipante-edicion', 'participante', $id_participante);
        }
        $imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_deposito')), (strlen(archivoName('imagen_deposito')) - 7));
        move_uploaded_file(archivo('imagen_deposito'), $___path_raiz . 'contenido/imagenes/depositos/' . $imagen_deposito);
    }
    /* edicion de datos de participante */
    query("UPDATE cursos_participantes SET 
            id_modo_pago='$id_modo_pago', sw_pago='$sw_pago_enviado'  
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    query("UPDATE cursos_proceso_registro SET 
            id_modo_pago='$id_modo_pago',
            monto_deposito='$monto_pago',
            imagen_deposito='$imagen_deposito',
            sw_pago_enviado='$sw_pago_enviado',
            id_banco='$id_banco',
            observaciones='$observaciones',
            paydata_id_administrador='$id_administrador',
            paydata_fecha=NOW() 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    logcursos('Edicion datos de PAGO', 'partipante-edicion', 'participante', $id_participante);
    /* aux curso */
    $rqddcraux1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqddcraux2 = fetch($rqddcraux1);
    $fecha_curso = $rqddcraux2['fecha'];
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Edicion datos de PAGO [fuera de fecha][part:' . $id_participante . ']', 'curso-edicion', 'curso', $id_curso);
    }
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* registro de ingreso */
    if ($monto_pago > 0) {
        $rqdppi1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
        $rqdppi2 = fetch($rqdppi1);
        $ringreso_monto = $monto_pago;
        $ringreso_id_tipo_movimiento = 1;
        $ringreso_id_modo_pago = $id_modo_pago;
        $ringreso_id_referencia = 1;
        $ringreso_detalle = 'Registro a curso [' . $id_curso . '] de participante: ' . $rqdppi2['nombres'] . ' ' . $rqdppi2['apellidos'] . ' [' . $id_participante . ']';
        $ringreso_id_administrador = administrador('id');
        /* id sucursal */
        $rqdds1 = query("SELECT id_sucursal FROM administradores WHERE id='$ringreso_id_administrador' LIMIT 1 ");
        $rqdds2 = fetch($rqdds1);
        $id_sucursal = $rqdds2['id_sucursal'];
        query("INSERT INTO contabilidad (
            id_tipo_movimiento, 
            id_modo_pago, 
            id_referencia, 
            id_sucursal, 
            monto, 
            fecha, 
            detalle, 
            id_administrador, 
            fecha_registro, 
            estado
            ) VALUES (
                '$ringreso_id_tipo_movimiento',
                '$ringreso_id_modo_pago',
                '$ringreso_id_referencia',
                '$id_sucursal',
                '$ringreso_monto',
                CURDATE(),
                '$ringreso_detalle',
                '$ringreso_id_administrador',
                NOW(),
                '1'
                ) ");
        $id_contabilidad = insert_id();
        query("INSERT INTO contabilidad_rel_data (
            id_contabilidad,
            id_participante
            ) VALUES (
                '$id_contabilidad',
                '$id_participante'
                )");
?>
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>$ INGRESO AGREGADO</strong> registro de ingreso agregado correctamente.
        </div>
    <?php
    }
    ?>
    <div class="alert alert-success">
        <strong>EXITO</strong> participante editado correctamente.
    </div>
<?php
}

/* sw_proceso_anulacion */
if (isset_post('sw_proceso_anulacion')) {
    $id_administrador = administrador('id');
    query("UPDATE cursos_participantes SET 
    id_modo_pago='0', sw_pago='0' 
    WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = fetch($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];
    query("UPDATE cursos_proceso_registro SET 
            id_modo_pago='0',
            monto_deposito='0',
            imagen_deposito='',
            sw_pago_enviado='0',
            id_banco='0',
            paydata_id_administrador='$id_administrador',
            paydata_fecha=NOW() 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    logcursos('Anulacion de pago', 'partipante-edicion', 'participante', $id_participante);

    /* registro de salida por anulacion */
    /*
    $rqdctb1 = query("SELECT id FROM contabilidad_rel_data WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
    if(num_rows($rqdctb1)>0){
        $rqdctb2 = fetch($rqdctb1);
        $id_contabilidad = $rqdctb2['id'];
        query("UPDATE contabilidad_rel_data SET id_factura='$id_emision_factura' WHERE id='$id_contabilidad' ORDER BY id DESC limit 1 ");
    }
    */
?>
    <div class="alert alert-success">
        <strong>EXITO</strong> el pago se anulo correctamente.
    </div>
<?php
}

/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = fetch($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT * FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];
$id_banco_pago = $data_registro['id_banco'];
if ($imagen_de_deposito == '') {
    $url_imagen = "https://www.eternacadencia.com.ar/components/com_virtuemart/assets/images/vmgeneral/no-image.jpg";
} else {
    $url_imagen = $dominio_www . "contenido/imagenes/depositos/" . $imagen_de_deposito;
}

$sw_pago_enviado = $data_registro['sw_pago_enviado'];
$paydata_id_administrador = $data_registro['paydata_id_administrador'];
$paydata_fecha = $data_registro['paydata_fecha'];
$observaciones_registro = $data_registro['observaciones'];

$id_cobro_khipu = $data_registro['id_cobro_khipu'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* curso */
$rqddcr1 = query("SELECT estado,costo,costo2,costo3,costo_e,costo_e2,sw_fecha2,fecha2,sw_fecha3,fecha3 FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqddcr2 = fetch($rqddcr1);

$costo_curso = $rqddcr2['costo'];
$costo2_curso = $rqddcr2['costo2'];
$costo3_curso = $rqddcr2['costo3'];
$costoe_curso = $rqddcr2['costo_e'];
$costoe2_curso = $rqddcr2['costo_e2'];

/* costo base */
$costo_base = $rqddcr2['costo'];
if ($rqddcr2['sw_fecha2'] == '1' && (date("Y-m-d") <= $rqddcr2['fecha2'])) {
    $costo_base = $rqddcr2['costo2'];
}
if ($rqddcr2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rqddcr2['fecha3'])) {
    $costo_base = $rqddcr2['costo3'];
}

/* habilitacion de edicion de pago */
$sw_procesos = false;
$htm_disabled = ' disabled="" ';
if (($rqddcr2['estado'] == '1' || $rqddcr2['estado'] == '2') && $sw_pago_enviado == '0') {
    $sw_procesos = true;
    $htm_disabled = '';
}

/* sw solo info */
$sw_solo_info = false;
if(isset_post('sw_solo_info')){
    $sw_solo_info = true;
}
?>

<div class="row">
    <div class="col-md-12 text-left">
        <b style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;">
            <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </b>
        <?php
        if ($sw_pago_enviado == '1') {
        ?>
            <b class="pull-right btn btn-success active">PAGO DEFINIDO</b>
        <?php
        } else {
        ?>
            <b class="pull-right btn btn-danger active">PAGO NO DEFINIDO</b>
        <?php
        }
        ?>
        <br />
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr />
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
    <form id="form-pagoparticipante">
        <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td><b>Forma de pago:</b></td>
                            <td colspan="2">
                                <select name="id_modo_pago" class="form-control" id="F-data_fpago" required="" onchange="selec_modo_pago(this.value);" <?php echo $htm_disabled; ?>>
                                    <?php
                                    $rqdmp1 = query("SELECT id,titulo,titulo_identificador FROM modos_de_pago WHERE estado='1' ");
                                    while ($rqdmp2 = fetch($rqdmp1)) {
                                        $selected_aux1 = '';
                                        if ($participante['id_modo_pago'] == $rqdmp2['id']) {
                                            $selected_aux1 = ' selected="selected" ';
                                        }
                                    ?>
                                        <option value="<?php echo $rqdmp2['id']; ?>" <?php echo $selected_aux1; ?>><?php echo $rqdmp2['titulo']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                        $aux_show_monto = 'table-row';
                        if ($participante['id_modo_pago'] == '10') {
                            $aux_show_monto = 'none';
                        }
                        ?>
                        <tr id="tr-montopago" style="display: <?php echo $aux_show_monto; ?>;">
                            <td><b>Monto de pago:</b></td>
                            <td colspan="2">
                                <!-- <select name="monto_pago" class="form-control" id="F-data_mpago" required="" <?php echo $htm_disabled; ?>>
                                    <?php
                                    if ($monto_de_pago > 0) {
                                    ?>
                                        <option value="<?php echo $monto_de_pago; ?>"><?php echo $monto_de_pago; ?></option>
                                    <?php
                                    }
                                    ?>
                                    <option value="<?php echo $costo_curso; ?>"><?php echo $costo_curso; ?></option>
                                    <?php
                                    if ($costo2_curso > 0) {
                                    ?>
                                        <option value="<?php echo $costo2_curso; ?>"><?php echo $costo2_curso; ?></option>
                                    <?php
                                    }
                                    if ($costo3_curso > 0) {
                                    ?>
                                        <option value="<?php echo $costo3_curso; ?>"><?php echo $costo3_curso; ?></option>
                                    <?php
                                    }
                                    if ($costoe_curso > 0) {
                                    ?>
                                        <option value="<?php echo $costoe_curso; ?>"><?php echo $costoe_curso; ?></option>
                                    <?php
                                    }
                                    if ($costoe2_curso > 0) {
                                    ?>
                                        <option value="<?php echo $costoe2_curso; ?>"><?php echo $costoe2_curso; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select> -->
                                <input type="number" name="monto_pago" id="F-data_mpago" required="" value="<?php echo $monto_de_pago; ?>" class="form-control" <?php echo $htm_disabled; ?> />
                            </td>
                        </tr>
                        <tr id="tr-comprobantepago" style="display: none;">
                            <td><b>Comprobante de pago:</b></td>
                            <td colspan="2"><input type="file" name="imagen_deposito" id="imagen_deposito" class="form-control" <?php echo $htm_disabled; ?> /></td>
                        </tr>
                        <?php
                        $aux_show_banco = 'none';
                        if ($id_banco_pago != 0) {
                            $aux_show_banco = 'table-row';
                        }
                        ?>
                        <tr id="tr-banco" style="display: <?php echo $aux_show_banco; ?>;">
                            <td><b>Banco:</b></td>
                            <td colspan="2">
                                <select name="id_banco" class="form-control" id="id-banco" <?php echo $htm_disabled; ?>>
                                    <option value="0">NO APLICA</option>
                                    <?php
                                    $rqdmdp1 = query("SELECT c.*,(b.nombre)nombre_banco FROM cuentas_de_banco c LEFT JOIN bancos b ON c.id_banco=b.id WHERE c.estado='1' ORDER BY b.nombre DESC ");
                                    while ($rqdmdp2 = fetch($rqdmdp1)) {
                                        $selected_aux1 = '';
                                        if ($id_banco_pago == $rqdmdp2['id']) {
                                            $selected_aux1 = ' selected="selected" ';
                                        }
                                    ?>
                                        <option value="<?php echo $rqdmdp2['id']; ?>" <?php echo $selected_aux1; ?>>
                                            <?php echo $rqdmdp2['nombre_banco'] . ' &nbsp; | &nbsp; ' . $rqdmdp2['numero_cuenta']; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td><b>Observaciones:</b></td>
                            <td colspan="2"><textarea class="form-control text-center" name="observaciones" placeholder="Observaciones..." id="id-observaciones" <?php echo $htm_disabled; ?> style="height: 75px;text-align: left;"><?php echo $observaciones_registro; ?></textarea></td>
                        </tr>
                        <?php
                        if ($sw_procesos && (!$sw_solo_info)) {
                        ?>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3" id="RESPONSE-cont">
                                    <button type="submit" class="btn btn-sm btn-success btn-block"><i class="fa fa-plus"></i> <?php echo ((int)$costo_curso>0 ? 'REGISTRAR PAGO': 'ACTIVAR CURSO') ?></b>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                    <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>" />
                    <input type="hidden" name="actual_imagen_deposito" value="<?php echo $imagen_de_deposito; ?>" />
                    <input type="hidden" name="update-proceso-2" value="1" />
                </div>
                <div class="col-md-6">
                    <img src="<?php echo $url_imagen; ?>" style="width:100%;" />
                    <br />
                    <?php
                    if ($imagen_de_deposito == '') {
                        echo "Sin imagen";
                    } else {
                        echo '<a href="' . $url_imagen . '" target="_blank" style="color: #4CAF50;text-decoration: underline;">' . $imagen_de_deposito . '</a>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        if ($id_cobro_khipu != '0') {
            $rqdckv1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ");
            $rqdckv2 = fetch($rqdckv1);
        ?>
            <br />

            <div style="border: 1px solid #8cc2e6;">
                <table class="table table-bordered table-dtriped">
                    <tr>
                        <td colspan="3">PAGO CON TARJETA ( KHIPU )</td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo strtoupper($rqdckv2['payment_id']); ?>
                        </td>
                        <td>
                            <?php
                            if ($rqdckv2['estado'] == '1') {
                                echo '<b class="btn btn-xs btn-success active small">PAGO REALIZADO</b>';
                            } else {
                                echo '<b class="btn btn-xs btn-danger active small">PAGO NO EFECTUADO</b>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo "<a href='https://khipu.com/payment/info/" . $rqdckv2['payment_id'] . "' target='_blank'>https://khipu.com/payment/info/" . $rqdckv2['payment_id'] . "</a>"; ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php
        }
        ?>


        <?php
        /* codigo de descuento */
        $rqvcd1 = query("SELECT cdu.id,cdu.fecha,cd.descuento,cd.codigo,cd.id_administrador FROM codigos_descuento_usos cdu INNER JOIN codigos_descuento cd ON cd.id=cdu.id_codigo_descuento WHERE cdu.id_participante='$id_participante' ");
        if (num_rows($rqvcd1) > 0) {
            $rqvcd2 = fetch($rqvcd1);
        ?>
            <br />
            <div style="border: 1px solid #8cc2e6;">
                <table class="table table-bordered table-dtriped">
                    <tr>
                        <td colspan="3" style="padding: 20px 0px;">
                            <span style="background: orange;padding: 10px 25px;border-radius: 10px;color: #FFF;font-weight: bold;font-size: 15pt;">
                                C&Oacute;DIGO DE DESCUENTO
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            DESCUENTO:
                            <b class="label label-success" style="font-size: 11pt;padding: 5px 10px;"><?php echo $rqvcd2['descuento']; ?> BS</b>
                        </td>
                        <td>
                            C&Oacute;DIGO:
                            <b class="label label-success" style="font-size: 11pt;padding: 5px 10px;"><?php echo $rqvcd2['codigo']; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            FECHA REGISTRO: <?php echo $rqvcd2['fecha']; ?>
                        </td>
                        <td>
                            EMISOR:
                            <?php
                            $rqda1 = query("SELECT nombre FROM administradores WHERE id='" . $rqvcd2['id_administrador'] . "' LIMIT 1 ");
                            $rqda2 = fetch($rqda1);
                            echo $rqda2['nombre'];
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php
        }
        ?>

        <?php
        /* edicion */
        if((int)$id_administrador == 10 || (int)$id_administrador == 11){
        ?>
            <div class="text-center">
                <br>
                <b class="btn btn-info btn-md" onclick="editar_pago();"><i class="fa fa-edit"></i> &nbsp; EDITAR PAGO</b>

                <?php
                /* sw pago verificado */
                $rqvp1 = query("SELECT id FROM rel_pagosverificados WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                if(num_rows($rqvp1)==0 && (!$sw_solo_info) || true){
                ?>
                    &nbsp; <b class="btn btn-danger btn-md" onclick="anular_pago();"><i class="fa fa-trash-o"></i> &nbsp; ANULAR PAGO</b>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>

        <br />
        <div style="border: 1px solid #8cc2e6;">
            <table class="table table-bordered table-dtriped">
                <tr>
                    <td colspan="4">DATOS DE REGISTRO/ACTUALIZACI&Oacute;N DE PAGO</td>
                </tr>
                <tr>
                    <td><b>Administrador:</b></td>
                    <td>
                        <?php
                        if ($paydata_id_administrador == '1') {
                            echo "SISTEMA";
                        } elseif ($paydata_id_administrador == '0') {
                            echo "Sin datos registrados";
                        } else {
                            $rqda1 = query("SELECT nombre FROM administradores WHERE id='$paydata_id_administrador' LIMIT 1 ");
                            $rqda2 = fetch($rqda1);
                            echo $rqda2['nombre'];
                        }
                        ?>
                    </td>
                    <td><b>Fecha:</b></td>
                    <td>
                        <?php
                        if ($paydata_id_administrador == '0') {
                            echo "Sin datos registrados";
                        } else {
                            echo date("d / m / Y H:i", strtotime($paydata_fecha));
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;<br>DATOS ADICIONALES DE PAGO</td>
                </tr>
                <tr>
                    <td><b>Departamento:</b></td>
                    <td>
                        <?php
                        if ($data_registro['pago_id_departamento'] == '0') {
                            echo "Sin datos";
                        } else {
                            $rqdadd1 = query("SELECT nombre FROM departamentos WHERE id='" . $data_registro['pago_id_departamento'] . "' LIMIT 1 ");
                            $rqdadd2 = fetch($rqdadd1);
                            echo $rqdadd2['nombre'];
                        }
                        ?>
                    </td>
                    <td><b>ID de transacci&oacute;n:</b></td>
                    <td>
                        <?php
                        if ($data_registro['transaccion_id'] == '') {
                            echo "Sin datos";
                        } else {
                            echo $data_registro['transaccion_id'];
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Fecha de pago:</b></td>
                    <td>
                        <?php
                        if ($data_registro['pago_fechahora'] == '0000-00-00 00:00:00') {
                            echo "Sin datos";
                        } else {
                            echo date("d / m / Y", strtotime($data_registro['pago_fechahora']));
                        }
                        ?>
                    </td>
                    <td><b>Hora de pago:</b></td>
                    <td>
                        <?php
                        if ($data_registro['pago_fechahora'] == '0000-00-00 00:00:00') {
                            echo "Sin datos";
                        } else {
                            echo date("H:i", strtotime($data_registro['pago_fechahora']));
                        }
                        ?>
                    </td>
                </tr>
            
            </table>
        </div>


    </form>
</div>

<?php
if((!$sw_solo_info)){
?>
<br>
<hr>
<br>
<b>ACCESOS RAPIDOS:</b>
&nbsp;
<b class="btn btn-xs btn-default" onclick="acceso_cursos_virtuales_FastAccess('<?php echo $id_participante; ?>');">
    C-VIRTUALES
</b>
<?php
}
?>

<script>
    function acceso_cursos_virtuales_FastAccess(id_participante) {
        $("#ajaxbox-pago_participante").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.acceso_cursos_virtuales.php',
            data: {
                id_participante: id_participante
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxbox-pago_participante").html(data);
            }
        });
    }
</script>

<script>
    $('#form-pagoparticipante').on('submit', function(e) {
        e.preventDefault();
        if(validacion_costo_base()){
            $("#ajaxloading-pago_participante").html(text__loading_dos);
            var formData = new FormData(this);
            formData.append('_token', $('input[name=_token]').val());
            $.ajax({
                type: 'POST',
                url: 'pages/ajax/ajax.cursos-participantes.pago_participante.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#ajaxloading-pago_participante").html("");
                    $("#ajaxbox-pago_participante").html(data);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });
        }
    });
</script>

<script>
    function anular_pago() {
        if (confirm('ESTA SEGURO QUE DESEA ANULAR ESTE PAGO ?')) {
            $("#ajaxbox-pago_participante").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.pago_participante.php',
                data: {
                    id_participante: '<?php echo $id_participante; ?>',
                    sw_proceso_anulacion: 1
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxbox-pago_participante").html(data);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });
        }
    }
</script>

<script>
    function editar_pago() {
        $("#ajaxbox-pago_participante").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.pago_participante.editar.php',
            data: {
                id_participante: '<?php echo $id_participante; ?>',
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxbox-pago_participante").html(data);
                //*lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<script>
    function selec_modo_pago(id_modo_pago) {
        if (id_modo_pago == 1) {
            $("#tr-montopago").css('display', 'table-row');
            $("#tr-comprobantepago").css('display', 'none');
            $("#tr-banco").css('display', 'none');
            document.getElementById("imagen_deposito").required = false;
            document.getElementById("id-observaciones").required = false;
            $("#id-banco").val(0);
        } else if (id_modo_pago == 10) {
            $("#tr-montopago").css('display', 'none');
            $("#tr-comprobantepago").css('display', 'none');
            $("#tr-banco").css('display', 'none');
            document.getElementById("imagen_deposito").required = false;
            document.getElementById("F-data_mpago").required = false;
            document.getElementById("id-observaciones").required = true;
            $("#id-banco").val(0);
        } else if (id_modo_pago == 5 || id_modo_pago == 11) {
            $("#tr-comprobantepago").css('display', 'table-row');
            $("#tr-montopago").css('display', 'table-row');
            $("#tr-banco").css('display', 'none');
            document.getElementById("imagen_deposito").required = true;
            document.getElementById("id-observaciones").required = false;
            $("#id-banco").val(0);
        } else {
            $("#tr-comprobantepago").css('display', 'table-row');
            $("#tr-montopago").css('display', 'table-row');
            $("#tr-banco").css('display', 'table-row');
            document.getElementById("imagen_deposito").required = true;
            document.getElementById("id-observaciones").required = false;
        }
    }
</script>

<script>
    let sw_aux_notif_costobase = false;
    function validacion_costo_base(){
        const costo_base = parseInt('<?php echo $costo_base; ?>');
        const costo_ingresado = parseInt(document.getElementById('F-data_mpago').value);
        if((costo_ingresado<costo_base) && (!sw_aux_notif_costobase)){
            document.getElementById('id-observaciones').required = true;
            sw_aux_notif_costobase = true;
            alert('Esta ingresando un monto inferior al costo del curso,\ndebe escribir el motivo en Observaciones.');
            return false;
        }else{
            return true;
        }
    }
</script>
