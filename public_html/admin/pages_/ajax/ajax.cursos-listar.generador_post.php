<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

?>
<table class="table table-striped table-bordered">
    <tr>
        <td style="padding: 20px;">
            <form id="FORM-generador-post">
                <label><input type="checkbox" name="data_detalle" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Detalle</label>
                <br>
                <label><input type="checkbox" name="data_forma_pago" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Formas de pago Bancos/ TigoMoney</label>
                <br>
                <label><input type="checkbox" name="data_reporte_pago" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Reportar Pago</label>
                <br>
                <label><input type="checkbox" name="data_whatsapp" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; WhatsApp</label>
                <br>
                <label><input type="checkbox" name="data_direccion" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Direcci&oacute;n</label>
                <br>
                <label><input type="checkbox" name="data_link_curso" value="1" style="width: 17px;height:17px;" checked="" /> &nbsp; Link Detalle curso</label>
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
            </form>
        </td>
    </tr>
    <tr>
        <td style="padding: 20px; text-align: center;">
            <b class="btn btn-success" onclick="generar_post();">GENERAR POST</b>
        </td>
    </tr>
</table>

<!-- generar_post -->
<script>
    function generar_post() {
        var form = $("#FORM-generador-post").serialize();

        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-facebook-post.generar_post.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>