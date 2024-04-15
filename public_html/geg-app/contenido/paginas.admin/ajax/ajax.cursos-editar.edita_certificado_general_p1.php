<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_certificado = post('id_certificado');

$rqc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado' ");
$rqc2 = mysql_fetch_array($rqc1);
?>

<form id="FORM-edita_certificado_general_p2" action='' method='post'>
    <table class="table table-striped table-bordered">
        <tr>
            <td>
                <span class="input-group-addon"><b>CODIGO:</b></span>
            </td>
            <td>
                <input type="text" class="form-control" disabled="" value="<?php echo $rqc2['codigo']; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>TITULO:</b></span>
            </td>
            <td>
                <input type="text" class="form-control" name="titulo_certificado" value="<?php echo $rqc2['cont_titulo']; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>CONT. 1:</b></span>
            </td>
            <td>
<!--                                                            <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo $rqc2['cont_uno']; ?>'/>-->
                <textarea class="form-control" name="contenido_uno_certificado" rows="2"><?php echo $rqc2['cont_uno']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>CONT. 2:</b></span>
            </td>
            <td>
<!--                                                            <input type="text" class="form-control" name="contenido_dos_certificado" value='<?php echo $rqc2['cont_dos']; ?>'/>-->
                <textarea class="form-control" name="contenido_dos_certificado" rows="2"><?php echo $rqc2['cont_dos']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>CONT. 3:</b> <i style="color:red !important;">(*)</i></span>
            </td>
            <td>
<!--                                                            <input type="text" class="form-control" name="contenido_tres_certificado" value='<?php echo $rqc2['cont_tres']; ?>'/>-->
                <textarea class="form-control" name="contenido_tres_certificado" rows="2"><?php echo $rqc2['cont_tres']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>TEXTO QR:</b></span>
            </td>
            <td>
                <textarea class="form-control" name="texto_qr" rows="2"><?php echo $rqc2['texto_qr']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>FECHA QR:</b></span>
            </td>
            <td>
                <input type="date" class="form-control" name="fecha_qr" value="<?php echo $rqc2['fecha_qr']; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>FIRMA 1 :</b></span>
            </td>
            <td>
                <select type="text" class="form-control" name="firma1">
                    <?php
                    $rqfc1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");
                    while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                        $text_img = "Sin imagen";
                        $url_img = "../../imagenes/firmas/" . $rqfc2['imagen'];
                        if (file_exists($url_img)) {
                            $text_img = "Imagen disponible";
                        }
                        $selected_f1 = "";
                        if ($rqc2['id_firma1'] == $rqfc2['id']) {
                            $selected_f1 = " selected='selected' ";
                        }
                        ?>
                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f1; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>FIRMA 2 :</b></span>
            </td>
            <td>
                <select type="text" class="form-control" name="firma2">
                    <?php
                    $rqfc1 = query("SELECT * FROM cursos_certificados_firmas ORDER BY id DESC");
                    while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                        $text_img = "Sin imagen";
                        $url_img = "../../imagenes/firmas/" . $rqfc2['imagen'];
                        if (file_exists($url_img)) {
                            $text_img = "Imagen disponible";
                        }
                        $selected_f2 = "";
                        if ($rqc2['id_firma2'] == $rqfc2['id']) {
                            $selected_f2 = " selected='selected' ";
                        }
                        ?>
                        <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected_f2; ?> ><?php echo $rqfc2['nombre']; ?> | <?php echo $rqfc2['cargo']; ?> | <?php echo $text_img; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>Impresi&oacute;n:</b></span>
            </td>
            <td>
                <br/>
                <?php
                $checked_uno = ' checked="" ';
                $checked_dos = '';
                if ($rqc2['sw_solo_nombre'] == '1') {
                    $checked_uno = '';
                    $checked_dos = ' checked="" ';
                }
                ?>
                <input type="radio" name="sw_solo_nombre" value="0" <?php echo $checked_uno; ?> /> 
                Completa
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="sw_solo_nombre" value="1" <?php echo $checked_dos; ?> />
                Solo Nombre-Fecha
                <br/>
            </td>
        </tr>
        <tr>
            <td>
                <span class="input-group-addon"><b>Formato:</b></span>
            </td>
            <td class="text-center">
                <select name="formato" class="form-control">
                    <?php
                    $selected_f = '';
                    if ($rqc2['formato'] == '2') {
                        $selected_f = ' selected="selected" ';
                    }
                    ?>
                    <option value="2" <?php echo $selected_f; ?> >CERTIFICADO ANTIGUO | QR en la parte lateral derecha</option>
                    <?php
                    $selected_f = '';
                    if ($rqc2['formato'] == '3') {
                        $selected_f = ' selected="selected" ';
                    }
                    ?>
                    <option value="3" <?php echo $selected_f; ?> >NUEVO CERTIFICADO | QR en la parte lateral derecha</option>
                    <?php
                    $selected_f = '';
                    if ($rqc2['formato'] == '5') {
                        $selected_f = ' selected="selected" ';
                    }
                    ?>
                    <option value="5" <?php echo $selected_f; ?> >Formato 5 | QR en la parte superior</option>
                </select> 
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center" id="AJAXCONTENT-edita_certificado_general_p2">
                <input type='hidden' name='id_certificado' value='<?php echo $id_certificado; ?>'/>
                <b class="btn btn-success" onclick="edita_certificado_general_p2();">ACTUALIZAR CERTIFICADO</b>
            </td>
        </tr>
    </table>
    <br/>
    <p>
        En la opci&oacute;n impresion solo Nombre-Fecha, solamente se generara un certificado con unicamente 
        el nombre del participante mas su prefijo correspondiente y la fecha/ubicaci&oacute;n  el cual es el campo editable con un asterisco rojo. <i style="color:red !important;">(*)</i>
    </p>
</form> 

<script>
    function edita_certificado_general_p2() {
        var form = $("#FORM-edita_certificado_general_p2").serialize();
        $("#AJAXCONTENT-edita_certificado_general_p2").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.edita_certificado_general_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-edita_certificado_general_p2").html(data);
            }
        });
    }
</script>
