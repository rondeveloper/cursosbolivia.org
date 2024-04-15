<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_emision_certificado = post('id_emision_certificado');

$rqdc1 = query("SELECT *,(select nombre from administradores where id=c.id_administrador_emisor)administrador FROM cursos_emisiones_certificados c WHERE id='$id_emision_certificado' ");
$rqdc2 = fetch($rqdc1);
$cont_titulo = $rqdc2['cont_titulo'];
$cont_uno = $rqdc2['cont_uno'];
$cont_dos = $rqdc2['cont_dos'];
$cont_tres = $rqdc2['cont_tres'];
$texto_qr = $rqdc2['texto_qr'];
$fecha_qr = $rqdc2['fecha_qr'];
$fecha2_qr = $rqdc2['fecha2_qr'];
?>
<table class="table table-hover table-striped table-bordered">
    <tr>
        <td>ID de certificado</td>
        <td><?php echo $rqdc2['certificado_id']; ?></td>
    </tr>
    <tr>
        <td>Receptor de certificado</td>
        <td><?php echo $rqdc2['receptor_de_certificado']; ?></td>
    </tr>
    <tr>
        <td>Fecha de emision</td>
        <td><?php echo $rqdc2['fecha_emision']; ?></td>
    </tr>
    <tr>
        <td>Emitido por</td>
        <td><?php echo $rqdc2['administrador']; ?></td>
    </tr>
</table>
<hr/>
<div class="text-center" id="AJAXCONTENT-edita_certificado_individual_p2">
    <form id="FORM-edita_certificado_individual_p2" action="" method="POST">
        <table class="table table-hover table-striped table-bordered">
            <tr>
                <td>T&iacute;tulo</td>
                <td><input type="text" class="form-control" name="cont_titulo" value='<?php echo $cont_titulo; ?>'/></td>
            </tr>
            <tr>
                <td>Texto uno</td>
                <td><input type="text" class="form-control" name="cont_uno" value='<?php echo $cont_uno; ?>'/></td>
            </tr>
            <tr>
                <td>Texto dos</td>
                <td><input type="text" class="form-control" name="cont_dos" value='<?php echo $cont_dos; ?>'/></td>
            </tr>
            <tr>
                <td>Texto tres</td>
                <td><input type="text" class="form-control" name="cont_tres" value='<?php echo $cont_tres; ?>'/></td>
            </tr>
            <tr>
                <td>Fondo digital</td>
                <td>
                <select type="text" class="form-control" name="id_fondo_digital">
                    <?php
                    $rqfc1 = query("SELECT * FROM certificados_imgfondo WHERE estado='1' ORDER BY id ASC ");
                    while ($rqfc2 = fetch($rqfc1)) {
                        $selected_f2 = "";
                        if ($rqdc2['id_fondo_digital'] == $rqfc2['id']) {
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
                <td>Texto qr</td>
                <td><input type="text" class="form-control" name="texto_qr" value='<?php echo $texto_qr; ?>'/></td>
            </tr>
            <tr>
                <td>Fecha INICIO (Fecha 1 QR)</td>
                <td><input type="date" class="form-control" name="fecha_qr" value='<?php echo $fecha_qr; ?>'/></td>
            </tr>
            <tr>
                <td>Fecha FINAL (Fecha 2 QR)</td>
                <td><input type="date" class="form-control" name="fecha2_qr" value='<?php echo $fecha2_qr=='0000-00-00'?$fecha_qr:$fecha2_qr; ?>'/></td>
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
                            if ($rqdc2['id_fondo_digital'] == $rqfc2['id']) {
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
                            if ($rqdc2['id_fondo_fisico'] == $rqfc2['id']) {
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
        </table>
        <div>
            <br/>
            <input type="hidden" name="id_emision_certificado" value="<?php echo $id_emision_certificado; ?>"/>
            <b class="btn btn-success" onclick="edita_certificado_individual_p2();">ACTUALIZAR CERTIFICADO</b>
            <br/>
        </div>
    </form>
</div>

<hr/>

<script>
    function edita_certificado_individual_p2() {
        var form = $("#FORM-edita_certificado_individual_p2").serialize();
        $("#AJAXCONTENT-edita_certificado_individual_p2").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.edita_certificado_individual_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-edita_certificado_individual_p2").html(data);
            }
        });
    }
</script>
