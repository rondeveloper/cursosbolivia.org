<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */

$id_banco = post('data');
$id_curso = post('cod');
$id_proceso_registro = post('id_proceso_registro');

/* curso */
$rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo_identificador'];

/* registro */
$rqdr1 = query("SELECT p.celular,c.costo,c.sw_fecha2,c.fecha2,c.costo2,c.sw_fecha3,c.fecha3,c.costo3 FROM cursos_proceso_registro r INNER JOIN cursos_participantes p ON r.id=p.id_proceso_registro INNER JOIN cursos c ON r.id_curso=c.id WHERE r.id='$id_proceso_registro' ORDER BY r.id DESC limit 1");
$rqdr2 = fetch($rqdr1);
$celular_participante = $rqdr2['celular'];

$monto_a_pagar = $rqdr2['costo'];
if ($rqdr2['sw_fecha2'] == '1' && (date("Y-m-d") <= $rqdr2['fecha2'])) {
    $monto_a_pagar = $rqdr2['costo2'];
}
if ($rqdr2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rqdr2['fecha3'])) {
    $monto_a_pagar = $rqdr2['costo3'];
}

/* banco */
if ($id_banco == 'tigomoney') {
    $txt_forma_pago = 'TIGO MONEY';
} elseif ($id_banco == 'paypal') {
    $txt_forma_pago = 'PAYPAL';
} else {
    $rqdb1 = query("SELECT nombre FROM bancos WHERE id='$id_banco' AND estado=1 LIMIT 1 ");
    $rqdb2 = fetch($rqdb1);
    $txt_forma_pago = $rqdb2['nombre'];
}
?>
<style>
    .myinput{
        background: #d9faff;
        padding: 10px 20px;
        height: auto;
        border-radius: 10px;
    }
</style>
<form action="registro-curso-p6.html" method="post" enctype="multipart/form-data">
    <div class="panel panel-default" style="border: 1px solid #9ecc93;">
        <div class="panel-body">
            <p>
                Usted selecciono la modalidad de pago por <b><?php echo $txt_forma_pago; ?></b>, es necesario que nos envie una <b>imagen del comprobante</b> para que el proceso de compra se complete correctamente.
                <br/>
                Puedes subir la imagen/foto/screenshot del pago en el siguiente formulario:
            </p>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <table class="table table-bordered">
                        <?php
                        if ($id_banco == 'tigomoney') {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <b>ID de transacci&oacute;n:</b> <span style="color:gray;font-size:9pt;">(TIGO MONEY)</span>
                                    <br/>
                                    <input class="form-control myinput" type='text' name='transaccion_id' placeholder="ID de transacci&oacute;n..."/>
                                </td>
                            </tr>
                        <?php } elseif ($id_banco != 'paypal') {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <b>Cuenta:</b> <span style="color:gray;font-size:9pt;">(<?php echo $txt_forma_pago; ?>)</span>
                                    <br/>
                                    <select class="form-control myinput" name='id_cuenta_banco'>
                                        <?php
                                        $rqccb1 = query("SELECT id,numero_cuenta,titular FROM cuentas_de_banco WHERE id_banco='$id_banco' AND estado=1 ");
                                        while ($rqccb2 = fetch($rqccb1)) {
                                            ?>
                                            <option value="<?php echo $rqccb2['id']; ?>"><?php echo $rqccb2['numero_cuenta'] . ' | ' . $rqccb2['titular']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                        if ($id_banco == 'paypal') {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <b>Monto en dolares del pago:</b>
                                    <br/>
                                    <input class="form-control myinput" type='number' step="0.1" id='monto_deposito_aux'  value="<?php echo round($monto_a_pagar / $__valor_dolar, 1); ?>"/>
                                    <input type='hidden' name='monto_deposito' value="<?php echo $monto_a_pagar; ?>"/>
                                </td>
                            </tr>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <b>Monto en Bolivianos del pago:</b>
                                    <br/>
                                    <input class="form-control myinput" type='number' name='monto_deposito' required="required" value="<?php echo $monto_a_pagar; ?>"/>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2">
                                <b>Imagen/foto/screenshot del comprobante:</b>
                                <br/>
                                <input class="form-control myinput" type='file' accept="image/*" name='imagen_deposito' required="required"/>
                            </td>
                        </tr>
                        <?php
                        if ($id_banco == 'paypal') {
                            ?>
                            <input type='hidden' name='ciudad' value="0"/>
                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <b>Ciudad donde se hizo el pago:</b>
                                    <br/>
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
                            <?php
                        }
                        ?>
                        <tr>
                            <td>
                                <b>Fecha cuando se hizo el pago:</b>
                                <br/>
                                <input class="form-control myinput" type='date' name='fecha' required="required" value="<?php echo date("Y-m-d"); ?>"/>
                            </td>
                            <td>
                                <b>Hora cuando se hizo el pago:</b>
                                <br/>
                                <input class="form-control myinput" type='time' name='hora' required="required" value="<?php echo date("H:i"); ?>"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br/>
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-12 text-center" style="padding: 20px;">
                    <input type="submit" class="btn btn-lg btn-success" value='SUBIR COMPROBANTE' style="border-radius: 7px;" name='subir-comprobante'/>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_banco" value="<?php echo $id_banco; ?>"/>
    <input type="hidden" name="id_proceso_registro" value="<?php echo $id_proceso_registro; ?>"/>
</form>