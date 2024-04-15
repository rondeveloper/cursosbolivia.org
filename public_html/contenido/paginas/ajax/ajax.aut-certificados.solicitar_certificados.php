<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
if (num_rows($rqdp1) == 0) {
    echo 'Error';
    exit;
} 
$participante = fetch($rqdp1);
$id_curso = $participante['id_curso'];
$id_proceso_registro = $participante['id_proceso_registro'];

/* curso */
$rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo_identificador'];

/* registro */
$rqdr1 = query("SELECT p.celular,c.costo,c.sw_fecha2,c.fecha2,c.costo2,c.sw_fecha3,c.fecha3,c.costo3 FROM cursos_proceso_registro r INNER JOIN cursos_participantes p ON r.id=p.id_proceso_registro INNER JOIN cursos c ON r.id_curso=c.id WHERE r.id='$id_proceso_registro' ORDER BY r.id DESC limit 1");
$rqdr2 = fetch($rqdr1);
$celular_participante = $rqdr2['celular'];


/* total */
$total_costo = 50;
?>
<style>
    .myinput{
        background: #d9faff;
        padding: 10px 20px;
        height: auto;
        border-radius: 10px;
    }
    .btn-infopago {
        background: #fdfdca;
        color: #de9000;
        border: 1px solid orange;
        padding: 2px 10px;
        cursor: pointer;
        border-radius: 3px;
    }
</style>
<div>
    <p>Debe reportar el pago realizado por el costo de los certificados, y una vez se valide este pago, nuestros operadores le enviarán los certificados en formato digital o también puede solicitar los certificados en formato físico y recogerlos en nuestras oficinas.</p>
    <div style="text-align: center;padding: 20px 0 35px 0px;font-size: 20pt;font-weight: bold;color: #2b9da9;font-family: 'Open Sans';">
        <b>Total: <?= $total_costo ?> BS</b>
    </div>
    <p>Puede visualizar los datos de pago presionando el siguiente bot&oacute;n: &nbsp; <b class="btn-infopago" onclick="ver_metodos_pago()"><i class="fa fa-info"></i> VER METODOS DE PAGO</b></p>
    <div class="row">
        <form action="registro-curso-p___.html" method="post" enctype="multipart/form-data">
            <div class="panel panel-default" style="border: 1px solid #9ecc93;">
                <div class="panel-body">
                    <br>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
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
                                        <input class="form-control myinput" type='number' name='monto_deposito' required="required" value="<?= $total_costo ?>" />
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
                    </div>
                    <br />
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center" style="padding: 20px;">
                            <input type="submit" class="btn btn-lg btn-success" value='SUBIR COMPROBANTE' style="border-radius: 7px;" name='subir-comprobante' />
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id_proceso_registro" value="<?php echo $id_proceso_registro; ?>" />
        </form>
    </div>
</div>

<!-- ver metodos de pago -->
<script>
    function ver_metodos_pago() {
        $("#title-MODAL-general").html('METODOS DE PAGO');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso-p5c.ver_metodos_pago.php',
            data: {},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#body-MODAL-general").html(data);
            }
        });
    }
</script>