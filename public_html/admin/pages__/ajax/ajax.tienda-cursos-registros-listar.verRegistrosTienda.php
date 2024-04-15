<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_registro = post('id_registro');

$rq_tienda1 = query("SELECT *, mdp.titulo, b.nombre, cdb.numero_cuenta FROM tienda_registros AS tr LEFT JOIN modos_de_pago AS mdp ON mdp.id = tr.id_modo_pago LEFT JOIN bancos AS b ON b.id = tr.id_banco LEFT JOIN cuentas_de_banco AS cdb ON cdb.id = tr.id_cuenta_banco WHERE tr.id='$id_registro' ORDER BY tr.id DESC limit 1 ");
$rq_tienda2 = fetch($rq_tienda1);
?>


<table class="table table-striped table-bordered">
    <tr>
        <td style='padding:5px;'>Metodo de pago:</td>
        <td style='padding:5px;'><?php echo $rq_tienda2['titulo']; ?></td>
    </tr>
    <?php
    if ($rq_tienda2['id_transaccion'] != '') {
    ?>
        <tr>
            <td style='padding:5px;'>ID de transacción: (TIGO MONEY)</td>
            <td style='padding:5px;'><?php echo $rq_tienda2['id_transaccion']; ?></td>
        </tr>
    <?php
    } ?>
    <tr>
        <td style='padding:5px;'>Banco:</td>
        <td style='padding:5px;'><?php echo $rq_tienda2['nombre']; ?></td>
    </tr>
    <tr>
        <td style='padding:5px;'>Cuenta:</td>
        <td style='padding:5px;'><?php echo $rq_tienda2['numero_cuenta']; ?></td>
    </tr>
    <tr>
        <td style='padding:5px;'>Monto en Bolivianos del pago:</td>
        <td style='padding:5px;'><?php echo $rq_tienda2['monto_deposito']; ?></td>
    </tr>
    <tr>
        <td style='padding:5px;'>Foto del comprobante:</td>
        <td style='padding:5px;'><img src="<?= $dominio ?>contenido/imagenes/depositos/<?= $rq_tienda2['imagen_deposito'] ?>" alt="Foto Comprobante" width="130px" height="80px" /></td>
    </tr>
    <tr>
        <td style='padding:5px;'>Ciudad donde se hizo el pago:</td>
        <td style='padding:5px;'><?php
                                    switch ($rq_tienda2['ciudad']) {
                                        case '1':
                                            echo 'Cochabamba';
                                            break;
                                        case '2':
                                            echo 'Potosi';
                                            break;
                                        case '3':
                                            echo 'La Paz';
                                            break;
                                        case '4':
                                            echo 'Santa Cruz';
                                            break;
                                        case '5':
                                            echo 'Tarija';
                                            break;
                                        case '6':
                                            echo 'Chuquisaca';
                                            break;
                                        case '7':
                                            echo 'Pando';
                                            break;
                                        case '8':
                                            echo 'Oruro';
                                            break;
                                        case '9':
                                            echo 'Beni';
                                            break;
                                        default:
                                            echo '';
                                    }
                                    ?></td>
    </tr>
    <tr>
        <td style='padding:5px;'>Fecha cuando se hizo el pago:</td>
        <td style='padding:5px;'><?php echo $rq_tienda2['fecha']; ?></td>
    </tr>
    <tr>
        <td style='padding:5px;'>Hora cuando se hizo el pago:</td>
        <td style='padding:5px;'><?php echo $rq_tienda2['hora']; ?></td>
    </tr>
</table>

<div>
    <?php
    $costo = 200;
    ?>
    <h3 style="background: #DDD;color: #444;margin-top: 20px;padding: 7px 0px;text-align: center;border-radius: 7px;border: 1px solid #bfbfbf;">SUBIR COMPROBANTE DE PAGO</h3>
    <br>
    <div class="row" id="tienda-registro-datos-completado">
        <form id="tienda-registro-form" method="post" enctype="multipart/form-data">
            <div class="panel panel-default" style="border: 1px solid #9ecc93;">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2">
                                <b>Metodo de pago:</b>
                                <br />
                                <select class="form-control myinput" name='id_modo_pago' onchange="modPago(this.value);">
                                    <?php
                                    $rqccmtp1 = query("SELECT id,titulo,titulo_identificador FROM modos_de_pago WHERE sw_formreg='1' AND estado='1' ORDER BY FIELD(id,4,3,5,11) ");
                                    while ($rqccmtp2 = fetch($rqccmtp1)) {
                                    ?>
                                        <option value="<?php echo $rqccmtp2['id']; ?>"><?php echo $rqccmtp2['titulo'] . ($rqccmtp2['id'] == 5 ? ' &nbsp; 69714008' : '') . ($rqccmtp2['id'] == 11 ? ' &nbsp; info@nemabol.com' : ''); ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr id="TR-banco" style="display: none;">
                            <td>
                                <b>Banco:</b>
                                <br />
                                <select class="form-control myinput" name="id_banco" id="id_banco" onchange="modCB();">
                                    <?php
                                    $rqbn1 = query("SELECT id,nombre FROM bancos WHERE estado=1 ORDER BY id ASC ");
                                    while ($rqbn2 = fetch($rqbn1)) {
                                    ?>
                                        <option value="<?php echo $rqbn2['id']; ?>"><?php echo $rqbn2['nombre']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <b>Cuenta:</b>
                                <br />
                                <select class="form-control myinput" name="id_cuenta_banco" id="id_cuenta_banco">
                                </select>
                            </td>
                        </tr>
                        <tr id="TR-paypal" style="display: none;">
                            <td colspan="2" class="text-center" style="padding: 25px 15px 5px 15px;">
                                <p>Presione el siguiente botón para hacer el pago con paypal: <b><?php echo round($costo / 7, 2); ?> USD</b></p>
                                <a href="https://www.paypal.com/paypalme/nemabol/<?php echo round($costo / 7, 2); ?>USD" target="_blank" class="btn btn-info">PAGAR CON PAYPAL</a>
                                <br>
                                <br>
                                <p style="color:#00a4ca;">(Luego debe subir el comprobante de pago en este formulario)</p>
                            </td>
                        </tr>
                        <tr id="TR-idtransaccion">
                            <td colspan="2">
                                <b>ID de transacci&oacute;n:</b> <span style="color:gray;font-size:9pt;">(TIGO MONEY)</span>
                                <br />
                                <input class="form-control myinput" type='text' name='transaccion_id' placeholder="ID de transacci&oacute;n..." />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Monto en Bolivianos del pago:</b>
                                <br />
                                <input class="form-control myinput" type='number' name='monto_deposito' required="required" value="<?php echo $costo; ?>" />
                            </td>
                            <td>
                                <b>Imagen/foto/screenshot del comprobante:</b>
                                <br />
                                <input class="form-control myinput" type='file' accept="image/*" name='imagen_deposito' required="required" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <b>Ciudad donde se hizo el pago:</b>
                                <br />
                                <select class="form-control myinput" name="ciudad">
                                    <option value="3">La Paz</option>
                                    <option value="1">Cochabamba</option>
                                    <option value="4">Santa Cruz</option>
                                    <option value="6">Chuquisaca</option>
                                    <option value="2">Potosi</option>
                                    <option value="8">Oruro</option>
                                    <option value="7">Pando</option>
                                    <option value="9">Beni</option>
                                    <option value="5">Tarija</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Fecha cuando se hizo el pago:</b>
                                <br />
                                <input class="form-control myinput" type='date' name='fecha' required="required" value="<?php echo date("Y-m-d"); ?>" />
                            </td>
                            <td>
                                <b>Hora cuando se hizo el pago:</b>
                                <br />
                                <input class="form-control myinput" type='time' name='hora' required="required" value="<?php echo date("H:i"); ?>" />
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center" style="padding: 20px;">
                            <input type="submit" class="btn btn-lg btn-success" value='SUBIR COMPROBANTE' style="border-radius: 7px;" name='subir-comprobante' />
                            <input name="id_tienda_registro" type="hidden" value="<?= $id_registro ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- agregar tienda registro -- inicio -->
<script>
    $('#tienda-registro-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.tienda-registro-cursos.agregarPagoRegistro.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                verRegistrosTienda(formData.get('id_tienda_registro'));
            }
        });
    });
</script>
<!-- agregar tienda registro -- fin -->