<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

$rqc1 = query("SELECT estado,costo,costo2,costo3,costo_e,costo_e2,sw_fecha2,fecha2,sw_fecha3,fecha3,sw_tienda FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$costo_curso = $rqc2['costo'];
$costo2_curso = $rqc2['costo2'];
$costo3_curso = $rqc2['costo3'];
$costoe_curso = $rqc2['costo_e'];
$costoe2_curso = $rqc2['costo_e2'];

/* costo base */
$costo_base = $rqc2['costo'];
if ($rqc2['sw_fecha2'] == '1' && (date("Y-m-d") <= $rqc2['fecha2'])) {
    $costo_base = $rqc2['costo2'];
}
if ($rqc2['sw_fecha3'] == '1' && (date("Y-m-d") <= $rqc2['fecha3'])) {
    $costo_base = $rqc2['costo3'];
}

if($rqc2['estado']=='0' && $rqc2['sw_tienda']=='0'){
    echo '<div class="alert alert-warning">
    <strong>NO PERMITIDO</strong> el curso se encuentra desactivado.
  </div>';
    exit;
}
?>
<form id="FORM-registra-participante" name="form_add_participante" enctype="multipart/form-data">
    <div style="background: #f1f1f1;border: 1px solid #bdd69d;border-radius: 5px;margin-bottom: 7px;">
        <table class="table table-striped table-bordered">
            <tr>
                <td><b>C.I.:</b></td>
                <td><input type="text" name="ci" class="form-control" placeholder="C.I." id="fdata-ci" autocomplete="off" onkeyup="autocompletadoParticipante(this.value);" /></td>
                <td>
                    <select class="form-control" name="ci_expedido" id="fdata-exp">
                        <option value="">...</option>
                        <option value="Cod. QR">Cod. QR</option>
                        <option value="LP">LP</option>
                        <option value="CB">CB</option>
                        <option value="SC">SC</option>
                        <option value="OR">OR</option>
                        <option value="PT">PT</option>
                        <option value="CH">CH</option>
                        <option value="PD">PD</option>
                        <option value="BN">BN</option>
                        <option value="TJ">TJ</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Nombres:</b></td>
                <td colspan="2"><input type="text" name="nombres" class="form-control" placeholder="Nombres" id="fdata-nom" required="" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><b>Apellidos:</b></td>
                <td colspan="2"><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" id="fdata-ape" required="" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><b>Prefijo:</b></td>
                <td colspan="2"><input type="text" name="prefijo" id="fdata-pref" class="form-control" placeholder="Sr. / Dr. / Arq. / Ing. " /></td>
            </tr>
            <tr>
                <td><b>Departamento:</b></td>
                <td colspan="2">
                    <select class="form-control" name="id_departamento">
                        <?php
                        $rqddp1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ASC ");
                        while ($rqddp2 = fetch($rqddp1)) {
                        ?>
                            <option value="<?php echo $rqddp2['id']; ?>"><?php echo strtoupper($rqddp2['nombre']); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b>Celular:</b></td>
                <td colspan="2"><input type="text" name="celular" id="fdata-celular" class="form-control" placeholder="Celular" autocomplete="off" /></td>
            </tr>
            <tr>
                <td><b>Correo electr&oacute;nico:</b></td>
                <td colspan="2"><input type="text" name="correo" id="fdata-email" class="form-control" placeholder="Correo electr&oacute;nico..." autocomplete="off" onkeyup="this.value = this.value.toLowerCase()" required="" /></td>
            </tr>
            <?php
            if ($sw_turnos) {
            ?>
                <tr>
                    <td><b>Turno:</b></td>
                    <td colspan="2">
                        <select name="id_turno" class="form-control">
                            <?php
                            $rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ");
                            while ($rqtc2 = fetch($rqtc1)) {
                            ?>
                                <option value="<?php echo $rqtc2['id']; ?>">Turno: <?php echo $rqtc2['titulo']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            <?php
            } else {
                echo '<input type="hidden" name="id_turno" value="0"/>';
            }
            ?>
            <tr class="aux-datos-f">
                <td colspan="3" class="text-right">
                    <b onclick="datos_facturacion();" class="btn btn-xs btn-default">Datos-F</b>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="checkbox" name="F-impficha" value="1" id="F-impficha" /> Ficha
                    </label>
                </td>
            </tr>
            <tr class="datos-f" style="display: none;">
                <td colspan="3" class="text-center">
                    <b onclick="formatNombreFact(1);" class="btn btn-xs btn-primary">NF1</b>
                    &nbsp;
                    <b onclick="formatNombreFact(2);" class="btn btn-xs btn-primary">NF2</b>
                    &nbsp;
                    <b onclick="formatNombreFact(3);" class="btn btn-xs btn-primary">NF3</b>
                    &nbsp;
                    <b onclick="formatNnitFact(3);" class="btn btn-xs btn-primary">CiNit</b>
                </td>
            </tr>
            <tr class="datos-f" style="display: none;">
                <td><b>Factura a nombre:</b></td>
                <td colspan="2"><input type="text" name="razon_social" class="form-control" placeholder="Razon Social" id="fdata-raz" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr class="datos-f" style="display: none;">
                <td><b>NIT:</b></td>
                <td colspan="2"><input type="text" name="nit" class="form-control" placeholder="NIT" id="fdata-nit" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><b>Observaciones:</b></td>
                <td colspan="2"><input type="text" name="observaciones" class="form-control" placeholder="Observaciones..." id="id-observaciones"/></td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">&nbsp;</td>
            </tr>
            <tr>
                <td><b>Forma de pago:</b></td>
                <td colspan="2">
                    <select name="id_modo_pago" class="form-control" id="FRP-data_fpago" required="" onchange="selecciona_modo_pago(this.value);">
                        <?php
                        $rqdmp1 = query("SELECT id,titulo,titulo_identificador FROM modos_de_pago WHERE estado='1' ");
                        while ($rqdmp2 = fetch($rqdmp1)) {
                        ?>
                            <option value="<?php echo $rqdmp2['id']; ?>"><?php echo $rqdmp2['titulo']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="tr-montopago">
                <td><b>Monto de pago:</b></td>
                <td colspan="2">
                    <input type="number" name="monto_pago" class="form-control" id="FRP-data_mpago" required="" value="<?php echo $costo_curso; ?>"/>
                </td>
            </tr>
            <tr id="tr-comprobantepago" style="display: none;">
                <td><b>Comprobante de pago:</b></td>
                <td colspan="2"><input type="file" name="comprobante_pago" class="form-control" id="id-comprobantepago"/></td>
            </tr>
            <tr id="tr-banco" style="display: none;">
                <td><b>Banco:</b></td>
                <td colspan="2">
                    <select name="id_banco" class="form-control" id="id-banco">
                        <?php
                        $rqdmdp1 = query("SELECT c.*,(b.nombre)nombre_banco FROM cuentas_de_banco c LEFT JOIN bancos b ON c.id_banco=b.id WHERE c.estado='1' ORDER BY b.nombre DESC ");
                        while ($rqdmdp2 = fetch($rqdmdp1)) {
                            ?>
                            <option value="<?php echo $rqdmdp2['id']; ?>">
                                <?php echo $rqdmdp2['nombre_banco'].' &nbsp; | &nbsp; '.$rqdmdp2['numero_cuenta']; ?>
                            </option>
                            <?php
                        }
                        ?>
                        <option value="0">NO APLICA</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" id="RESPONSE-cont">
                    <button type="submit" class="btn btn-sm btn-success btn-block"><i class="fa fa-plus"></i> REGISTRAR PARTICIPANTE</b>
                </td>
            </tr>
        </table>
    </div>
    <input type='hidden' name='id_curso' value='<?php echo $id_curso; ?>' />
    <input type="hidden" name="numeracion" value="<?php echo $inicio_numeracion; ?>" />
</form>

<script>
    function autocompletadoParticipante(dat) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.checkParticipante.php',
            data: {
                dat: dat
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    if ($("#fdata-nom").val() === '') {
                        $("#fdata-nom").val((data_json_parsed['nombres']).toUpperCase());
                    }
                    if ($("#fdata-ape").val() === '') {
                        $("#fdata-ape").val((data_json_parsed['apellidos']).toUpperCase());
                    }
                    if ($("#fdata-email").val() === '') {
                        $("#fdata-email").val((data_json_parsed['correo']).toLowerCase());
                    }
                    if ($("#fdata-celular").val() === '') {
                        $("#fdata-celular").val((data_json_parsed['celular']).toLowerCase());
                    }
                    if ($("#fdata-pref").val() === '') {
                        $("#fdata-pref").val((data_json_parsed['prefijo']).toUpperCase());
                    }
                    if ($("#fdata-exp").val() === '') {
                        $("#fdata-exp").val(data_json_parsed['ci_expedido']).change();
                    }
                    if ($("#fdata-raz").val() === '') {
                        $("#fdata-raz").val((data_json_parsed['razon_social']).toUpperCase());
                    }
                    if ($("#fdata-nit").val() === '') {
                        $("#fdata-nit").val((data_json_parsed['nit']).toUpperCase());
                    }
                } else {
                    $("#fdata-nom").val('');
                    $("#fdata-ape").val('');
                    $("#fdata-email").val('');
                    $("#fdata-celular").val('');
                    $("#fdata-pref").val('');
                    $("#fdata-exp").val('').change();
                    $("#fdata-raz").val('');
                    $("#fdata-nit").val('');
                }
            }
        });
    }
</script>

<script>
    function datos_facturacion() {
        $(".aux-datos-f").css('display', 'none');
        $(".datos-f").css('display', 'table-row');
    }
</script>

<script>
    function formatNombreFact(dat) {
        var nom = document.getElementById("fdata-nom").value;
        var ape = document.getElementById("fdata-ape").value;
        var nfact = "";

        if (dat === 3) {
            nfact = nom + " " + ape;
        } else if (dat === 2) {
            var araux1 = nom.split(' ');
            var araux2 = ape.split(' ');
            nfact = araux1[0] + " " + araux2[0];
        } else {
            var araux2 = ape.split(' ');
            nfact = araux2[0];
        }

        document.getElementById("fdata-raz").value = nfact.toUpperCase();
    }

    function formatNnitFact() {
        var ci = document.getElementById("fdata-ci").value;
        document.getElementById("fdata-nit").value = ci;
    }
</script>
<script>
    function formatNombreFact2(dat) {

        var nom = document.getElementById("fdata-nom-p").value;
        var ape = document.getElementById("fdata-ape-p").value;
        var nfact = "";

        if (dat === 3) {
            nfact = nom + " " + ape;
        } else if (dat === 2) {
            var araux1 = nom.split(' ');
            var araux2 = ape.split(' ');
            nfact = araux1[0] + " " + araux2[0];
        } else {
            var araux2 = ape.split(' ');
            nfact = araux2[0];
        }

        document.getElementById("fdata-raz-p").value = nfact.toUpperCase();
    }

    function formatNnitFact2() {
        var ci = document.getElementById("fdata-ci-p").value;
        document.getElementById("fdata-nit-p").value = ci;
    }
</script>

<script>
    $('#FORM-registra-participante').on('submit', function(e) {
        e.preventDefault();
        if(validacion_costo_base()){
            $("#RESPONSE-cont").html('Procesando...');
            var formData = new FormData(this);
            formData.append('registrar-participante', 1);

            $.ajax({
                type: 'POST',
                url: 'pages/ajax/ajax.cursos-participantes.registra_participante_p2.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    var data_json_parsed = JSON.parse(data);
                    $("#RESPONSE-cont").html(data_json_parsed['mensaje']);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                    if (data_json_parsed['estado'] === 1) {
                        if (document.getElementById('F-impficha').checked === true) {
                            window.open(data_json_parsed['url_ficha'], 'popup', 'width=700,height=500');
                        }
                        var F_data_mpago = $("#FRP-data_mpago").val();
                        var F_data_fpago = $("#FRP-data_fpago").val();
                        document.getElementById("FORM-registra-participante").reset();
                        $("#FRP-data_mpago").val(F_data_mpago);
                        $("#FRP-data_fpago").val(F_data_fpago);
                    }else{
                        //let newdiv = document.createElement('div');
                        //newdiv.innerHTML = data_json_parsed['mensaje'];
                        //document.getElementById("AJAXCONTENT-modgeneral").appendChild(newdiv);
                    }
                }
            });
        }
    });
</script>
<script>
    function selecciona_modo_pago(id_modo_pago){
        if(id_modo_pago==1){
            $("#tr-montopago").css('display','table-row');
            $("#tr-comprobantepago").css('display','none');
            $("#tr-banco").css('display','none');
            document.getElementById("id-comprobantepago").required = false;
            document.getElementById("id-observaciones").required = false;
            $("#id-banco").val(0);
        }else if(id_modo_pago==10){
            $("#tr-montopago").css('display','none');
            $("#tr-comprobantepago").css('display','none');
            $("#tr-banco").css('display','none');
            document.getElementById("id-comprobantepago").required = false;
            document.getElementById("id-observaciones").required = true;
            $("#id-banco").val(0);
        }else if(id_modo_pago==5 || id_modo_pago==11){
            $("#tr-comprobantepago").css('display','table-row');
            $("#tr-montopago").css('display','table-row');
            $("#tr-banco").css('display','none');
            document.getElementById("id-comprobantepago").required = true;
            document.getElementById("id-observaciones").required = false;
            $("#id-banco").val(0);
        }else{
            $("#tr-comprobantepago").css('display','table-row');
            $("#tr-montopago").css('display','table-row');
            $("#tr-banco").css('display','table-row');
            document.getElementById("id-comprobantepago").required = true;
            document.getElementById("id-observaciones").required = false;
        }
    }
</script>

<script>
    let sw_aux_notif_costobase_v2 = false;
    function validacion_costo_base(){
        const costo_base = parseInt('<?php echo $costo_base; ?>');
        const costo_ingresado = parseInt(document.getElementById('FRP-data_mpago').value);
        alert(costo_ingresado+'<'+costo_base);
        if((costo_ingresado<costo_base) && (!sw_aux_notif_costobase_v2)){
            document.getElementById('id-observaciones').required = true;
            sw_aux_notif_costobase_v2 = true;
            alert('Esta ingresando un monto inferior al costo del curso,\ndebe escribir el motivo en Observaciones.');
            return false;
        }else if((costo_ingresado<costo_base) && (document.getElementById('id-observaciones').value=='')){
            alert('Esta ingresando un monto inferior al costo del curso,\ndebe escribir el motivo en Observaciones.');
            return false;
        }else{
            return true;
        }
    }
</script>
