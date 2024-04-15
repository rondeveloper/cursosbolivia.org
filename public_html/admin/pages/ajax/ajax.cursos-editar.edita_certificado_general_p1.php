<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_certificado = post('id_certificado');

$rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ");
$rqc2 = fetch($rqc1);
?>

<form id="FORM-edita_certificado_general_p2" action='' method='post'>
    <table class="table table-bordered">
        <tr>
            <td>
                <span class=""><b>CODIGO:</b></span>
            </td>
            <td>
                <input type="text" class="form-control" disabled="" value="<?php echo $rqc2['codigo']; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>CERTIFICADO (TEXTO QR):</b></span>
            </td>
            <td>
                <textarea class="form-control" name="texto_qr" rows="2"><?php echo $rqc2['texto_qr']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>FECHA INICIO (Fecha 1 QR):</b></span>
            </td>
            <td>
                <input type="date" class="form-control" name="fecha_qr" value="<?php echo $rqc2['fecha_qr']; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>FECHA FINAL (Fecha 2 QR):</b></span>
            </td>
            <td>
                <input type="date" class="form-control" name="fecha2_qr" value="<?php echo $rqc2['fecha2_qr'] == '0000-00-00' ? $rqc2['fecha_qr'] : $rqc2['fecha2_qr']; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>TITULO:</b></span>
            </td>
            <td>
                <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>CONT. 1:</b></span>
            </td>
            <td>
                <!--                                                            <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>-->
                <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo $rqc2['cont_uno']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>CONT. 2:</b></span>
            </td>
            <td>
                <!--                                                            <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>-->
                <textarea class="form-control" name="contenido_dos_certificado" rows="2"><?php echo $rqc2['cont_dos']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
            </td>
            <td>
                <!--                                                            <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>-->
                <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $rqc2['cont_tres']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="text-center" colspan="2" style="background: #f5f5f5;">
                <i>CARACTERISTICAS DE VISUALIZACI&Oacute;N DIGITAL</i>
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>FONDO DIGITAL :</b></span>
            </td>
            <td>
                <select type="text" class="form-control" name="id_fondo_digital">
                    <?php
                    $rqfc1 = query("SELECT * FROM certificados_imgfondo WHERE estado=1 AND modo='digital' ORDER BY id ASC ");
                    while ($rqfc2 = fetch($rqfc1)) {
                        $selected_f2 = "";
                        if ($rqc2['id_fondo_digital'] == $rqfc2['id']) {
                            $selected_f2 = " selected='selected' ";
                        }
                    ?>
                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f2; ?>><?php echo $rqfc2['descripcion']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="text-center" colspan="2" style="background: #f5f5f5;">
                <i>CARACTERISTICAS DE IMPRESION FISICO</i>
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b>PLANTILLA DE FIRMAS :</b></span>
            </td>
            <td>
                <select type="text" class="form-control" name="id_fondo_fisico">
                    <option value="0">Sin plantilla de firmas</option>
                    <?php
                    $rqfc1 = query("SELECT * FROM certificados_imgfondo WHERE estado=1 AND modo='fisico' ORDER BY id ASC ");
                    while ($rqfc2 = fetch($rqfc1)) {
                        $selected_f2 = "";
                        if ($rqc2['id_fondo_fisico'] == $rqfc2['id']) {
                            $selected_f2 = " selected='selected' ";
                        }
                    ?>
                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f2; ?>><?php echo $rqfc2['descripcion']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="text-center" colspan="2" style="background: #f5f5f5;">
                <i>OPCIONES DE CORRECCION DE CERTIFICADOS</i>
            </td>
        </tr>
        <tr>
            <td>
                <span class=""><b style="color: red;">Actualizar emitidos?</b></span>
            </td>
            <td class="text-center">
                <input type="radio" name="sw_update_emitidos" value="1" />
                Si
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="sw_update_emitidos" value="0" checked="" />
                No
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" id="AJAXCONTENT-edita_certificado_general_p2">
                <input type='hidden' name='id_certificado' value='<?php echo $id_certificado; ?>' />
                <br>
                <b class="btn btn-success" onclick="edita_certificado_general_p2();">ACTUALIZAR CERTIFICADO</b>
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
    <br />
    <p>
        En la opci&oacute;n impresion SIN FIRMA, solamente se generara un certificado con unicamente
        el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i>
    </p>
</form>

<script>
    function edita_certificado_general_p2() {
        var form = $("#FORM-edita_certificado_general_p2").serialize();
        $("#AJAXCONTENT-edita_certificado_general_p2").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.edita_certificado_general_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-edita_certificado_general_p2").html(data);
            }
        });
    }
</script>

