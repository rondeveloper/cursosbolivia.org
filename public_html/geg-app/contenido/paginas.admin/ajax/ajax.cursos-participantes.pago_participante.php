<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* proceso de edicion */
if (isset_post('update-proceso-2')) {
    /* datos recibidos */
    $monto_pago = post('monto_pago');
    $id_curso = post('id_curso');
    $modo_pago = post('modo_pago');
    $id_administrador = administrador('id');

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = mysql_fetch_array($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    $sw_pago_enviado = '1';
    if ($modo_pago == '') {
        $sw_pago_enviado = '0';
    }

    /* imagen deposito */
    $imagen_deposito = post('actual_imagen_deposito');
    if (is_uploaded_file(archivo('imagen_deposito'))) {
        if ($imagen_deposito !== '') {
            logcursos('Sube imagen respaldo de pago[prev:' . $imagen_deposito . ']', 'partipante-edicion', 'participante', $id_participante);
        }
        $imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_deposito')), (strlen(archivoName('imagen_deposito')) - 7));
        move_uploaded_file(archivo('imagen_deposito'), '../../imagenes/depositos/' . $imagen_deposito);
    }
    /* edicion de datos de participante */
    query("UPDATE cursos_participantes SET 
            modo_pago='$modo_pago' 
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    query("UPDATE cursos_proceso_registro SET 
            metodo_de_pago='$modo_pago',
            monto_deposito='$monto_pago',
            imagen_deposito='$imagen_deposito',
            sw_pago_enviado='$sw_pago_enviado',
            paydata_id_administrador='$id_administrador',
            paydata_fecha=NOW() 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    logcursos('Edicion datos de PAGO', 'partipante-edicion', 'participante', $id_participante);
    /* aux curso */
    $rqddcraux1 = query("SELECT fecha FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqddcraux2 = mysql_fetch_array($rqddcraux1);
    $fecha_curso = $rqddcraux2['fecha'];
    if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
        logcursos('Edicion datos de PAGO [fuera de fecha][part:'.$id_participante.']', 'curso-edicion', 'curso', $id_curso);
    }
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    ?>
    <div class="alert alert-success">
        <strong>Exito!</strong> Participante editado correctamente.
    </div>
    <?php
}

/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = mysql_fetch_array($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT * FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = mysql_fetch_array($rqrp1);

$metodo_de_pago = $data_registro['metodo_de_pago'];
$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];
if ($imagen_de_deposito == '') {
    $url_imagen = "https://www.eternacadencia.com.ar/components/com_virtuemart/assets/images/vmgeneral/no-image.jpg";
} else {
    $url_imagen = "contenido/imagenes/depositos/" . $imagen_de_deposito;
}

$sw_pago_enviado = $data_registro['sw_pago_enviado'];
$paydata_id_administrador = $data_registro['paydata_id_administrador'];
$paydata_fecha = $data_registro['paydata_fecha'];

$id_cobro_khipu = $data_registro['id_cobro_khipu'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* curso */
$rqddcr1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqddcr2 = mysql_fetch_array($rqddcr1);
$sw_procesos = false;
$htm_disabled = ' disabled="" ';
if ($rqddcr2['estado'] == '1' || $rqddcr2['estado'] == '2') {
    $sw_procesos = true;
    $htm_disabled = '';
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
        <br/>
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
    <form id="form-pagoparticipante">
        <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-center">
                        Monto de pago
                    </h5>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Monto de pago (BS):</span>
                        <input type="text" class="form-control text-center" name="monto_pago" value="<?php echo $monto_de_pago; ?>" placeholder="Monto..." <?php echo $htm_disabled; ?>/>
                    </div>
                    <br/>
                    <h5 class="text-center">
                        Modo de pago
                    </h5>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Modalidad de pago:</span>
                        <select name="modo_pago" class="form-control" <?php echo $htm_disabled; ?>>
                            <option value="">Sin pago</option>
                            <?php
                            $rqdmdp1 = query("SELECT * FROM modos_de_pago WHERE estado='1' ");
                            while ($rqdmdp2 = mysql_fetch_array($rqdmdp1)) {
                                $selected_aux1 = '';
                                if ($participante['modo_pago'] == $rqdmdp2['titulo_identificador']) {
                                    $selected_aux1 = ' selected="selected" ';
                                }
                                ?>
                                <option value="<?php echo $rqdmdp2['titulo_identificador']; ?>" <?php echo $selected_aux1; ?> >
                                    <?php echo $rqdmdp2['titulo']; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <br/>
                    <h5 class="text-center">
                        Comprobante: 
                        <?php
                        if ($imagen_de_deposito == '') {
                            echo "Sin imagen";
                        } else {
                            echo '<a href="' . $url_imagen . '" target="_blank" style="color: #4CAF50;text-decoration: underline;">' . $imagen_de_deposito . '</a>';
                        }
                        ?>
                    </h5>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Comprobante:</span>
                        <input type="file" class="form-control text-center" name="imagen_deposito" id="imagen_deposito" <?php echo $htm_disabled; ?>/>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="<?php echo $url_imagen; ?>" style="width:100%;"/>
                    <br/>
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
            $rqdckv2 = mysql_fetch_array($rqdckv1);
            ?>
            <br/>

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

        <br/>

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
                            $rqda2 = mysql_fetch_array($rqda1);
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
            </table>
        </div>

        <br/>

        <?php
        if ($sw_procesos) {
            ?>
            <div class="panel-footer">
                <br/>
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>" />
                <input type="hidden" name="actual_imagen_deposito" value="<?php echo $imagen_de_deposito; ?>"/>
                <input type="hidden" name="update-proceso-2" value="1" />
                <input type="submit" class="btn btn-success" value="ACTUALIZAR DATOS"/>
                &nbsp;&nbsp;&nbsp;
                <b class="btn btn-danger" data-dismiss="modal">CANCELAR</b>
                <br/>
                <br/>
            </div>
            <?php
        }
        ?>
    </form>
</div>

<br>
<hr>
<br>
<b>ACCESOS RAPIDOS:</b>
&nbsp;
<b class="btn btn-xs btn-default" onclick="acceso_cursos_virtuales('<?php echo $id_participante; ?>');" data-toggle="modal" data-target="#MODAL-acceso_cursos_virtuales">
    C-VIRTUALES
</b>


<script>
    $('#form-pagoparticipante').on('submit', function(e) {
        e.preventDefault();
        $("#ajaxloading-pago_participante").html(text__loading_dos);
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            type: 'POST',
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.pago_participante.php',
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
    });
</script>