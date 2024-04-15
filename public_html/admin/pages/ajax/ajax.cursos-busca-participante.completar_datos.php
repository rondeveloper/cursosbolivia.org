<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');
$nro_lista = 0;


/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* datos */
$participante = fetch($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT * FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_emision_factura = $data_registro['id_emision_factura'];
$id_cobro_khipu = $data_registro['id_cobro_khipu'];

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];
if ($imagen_de_deposito == '') {
    $url_imagen = "https://www.eternacadencia.com.ar/components/com_virtuemart/assets/images/vmgeneral/no-image.jpg";
} else {
    $url_imagen = $dominio_www. "contenido/imagenes/depositos/" . $imagen_de_deposito;
}

$observacion_participante = $participante['observacion'];
$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];

$sw_pago_enviado = $data_registro['sw_pago_enviado'];
$paydata_id_administrador = $data_registro['paydata_id_administrador'];
$paydata_fecha = $data_registro['paydata_fecha'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* turnos */
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
$sw_turnos = false;
if (num_rows($rqtc1) > 0) {
    $sw_turnos = true;
}
?>

<div class="row">
    <div class="col-md-12 text-left">
        <b style="font-size: 20pt;
           text-transform: uppercase;
           color: #00789f;">
           <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </b>
<!--        <b class="pull-right btn btn-info active">Nro. <?php echo $nro_lista; ?></b>-->
        <br/>
        <b style="font-size: 15pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
    <form id="form-completadatos-participante">
        <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="text-center">
                        Celular / Correo
                    </h5>
                    <div class="row">
                        <div class="col-md-6 text-left" style="padding: 0px 2px;">
                            <input type="number" class="form-control text-center" name="celular" value="<?php echo $participante['celular']; ?>" placeholder="Celular..."/>
                        </div>
                        <div class="col-md-6 text-left" style="padding: 0px 2px;">
                            <input type="text" class="form-control text-center" name="correo" value="<?php echo $participante['correo']; ?>" placeholder="Correo..."/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        
        <div style="background: whitesmoke;border: 1px solid #8cc2e6;padding-bottom: 20px;">
            <div class="row">
                <div class="col-md-7">
                    <h5 class="text-center">
                        Monto de pago
                    </h5>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Monto(BS):</span>
                        <input type="text" class="form-control text-center" name="monto_pago" value="<?php echo $monto_de_pago; ?>" placeholder="Monto..." />
                    </div>
                    <br/>
                    <h5 class="text-center">
                        Modo de pago
                    </h5>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Modalidad:</span>
                        <select name="id_modo_pago" class="form-control" >
                            <option value="">Sin pago</option>
                            <?php
                            $rqdmdp1 = query("SELECT * FROM modos_de_pago WHERE estado='1' ");
                            while ($rqdmdp2 = fetch($rqdmdp1)) {
                                $selected_aux1 = '';
                                if ($participante['id_modo_pago'] == $rqdmdp2['id']) {
                                    $selected_aux1 = ' selected="selected" ';
                                }
                                ?>
                                <option value="<?php echo $rqdmdp2['id']; ?>" <?php echo $selected_aux1; ?> >
                                    <?php echo $rqdmdp2['titulo']; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <br/>
                    <h5 class="text-center">
                        Comprobante de pago:
                    </h5>
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-money"></i> Imagen:</span>
                        <input type="file" class="form-control text-center" name="imagen_deposito" id="imagen_deposito" />
                    </div>
                </div>
                <div class="col-md-5">
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
            $rqdckv2 = fetch($rqdckv1);
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
            </table>
        </div>

        <br/>

        <!-- DIV CONTENT AJAX :: COMPLETAR DATOS DE PARTICIPANTE P2 -->
        <div id="ajaxloading-completa_datos"></div>
        <div id="ajaxbox-completa_datos">
            <div class="panel-footer">
                <br/>
                <input type="hidden" name="actual_imagen_deposito" value="<?php echo $imagen_de_deposito; ?>"/>
                <input type="hidden" name="update-proceso-2" value="1" />
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                <input type="hidden" name="id_participante" value="<?php echo $participante['id']; ?>" />
                <input type="submit" class="btn btn-success" value="ACTUALIZAR DATOS"/>
                &nbsp;&nbsp;&nbsp;
                <b class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</b>
                <br/>
                <br/>
            </div>
        </div>

    </form>
</div>


<script>
    $('#form-completadatos-participante').on('submit', function(e) {
        e.preventDefault();
        $("#ajaxloading-completa_datos").html('Procesando...');
        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-busca-participante.completar_datos_p2.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#ajaxloading-completa_datos").html("");
                $("#ajaxbox-completa_datos").html(data);
            }
        });
    });
</script>



<script>
    $('#form-pagoparticipante').on('submit', function(e) {
        e.preventDefault();
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
    });
</script>