<p>
    Puede visualizar los datos de pago presionando el siguiente bot&oacute;n: &nbsp; <b class="btn-infopago" onclick="ver_metodos_pago()"><i class="fa fa-info"></i> VER METODOS DE PAGO</b>
</p>
<div>
    <h3 style="background: #DDD;color: #444;margin-top: 20px;padding: 7px 0px;text-align: center;border-radius: 7px;border: 1px solid #bfbfbf;">SUBIR COMPROBANTE DE PAGO</h3>
    <br>
    <div class="row" id="AJAXRESULT-subir-pago">
        <form id="FORM-subir-pago" method="post" enctype="multipart/form-data">
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
                    </div>
                    <br />
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center" style="padding: 20px;">
                            <input type="submit" class="btn btn-lg btn-success" value='SUBIR COMPROBANTE' style="border-radius: 7px;" name='subir-comprobante' />
                            <input name="id_tienda_registro" type="hidden" value="<?= $id_tienda_registro ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- ver metodos de pago -->
<script>
    function ver_metodos_pago() {
        $("#title-MODAL-general").html('METODOS DE PAGO');
        $("#body-MODAL-general").html('Cargando...');
        $("#MODAL-general").modal('show');
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

<!-- agregar pago -->
<script>
    $('#FORM-subir-pago').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('_token', $('input[name=_token]').val());

        $("#AJAXRESULT-subir-pago").html('<div style="text-align: center;padding: 150px 0;"><img src="contenido/imagenes/images/loading.gif" style="height: 70px;"></div>');

        $.ajax({
            type: 'POST',
            url: 'contenido/paginas/ajax/ajax.registro-cursos-tienda-completado.agregarTiendaRegistro.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXRESULT-subir-pago").html(data);
            }
        });
    });
</script>